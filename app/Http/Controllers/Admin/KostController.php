<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\KostImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KostController extends Controller
{
    public function index()
    {
        $kosts = Kost::with(['creator', 'images', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.kosts.index', compact('kosts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.kosts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'price_per_month' => 'required|numeric|min:0',
            'room_count' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'facilities' => 'nullable|string',
            'rules' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $validated['created_by'] = auth()->id();
            $kost = Kost::create($validated);

            // Attach categories
            $kost->categories()->attach($validated['categories']);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('kost-images', 'public');
                    
                    KostImage::create([
                        'kost_id' => $kost->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0, // First image as primary
                        'order' => $index,
                    ]);
                }
            }
        });

        return redirect()->route('admin.kosts.index')
            ->with('success', 'Kost berhasil ditambahkan!');
    }

    public function show(Kost $kost)
    {
        $kost->load(['creator', 'images', 'categories']);
        return view('admin.kosts.show', compact('kost'));
    }

    public function edit(Kost $kost)
    {
        $categories = Category::orderBy('name')->get();
        $kost->load(['images', 'categories']);
        return view('admin.kosts.edit', compact('kost', 'categories'));
    }

    public function update(Request $request, Kost $kost)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'price_per_month' => 'required|numeric|min:0',
            'room_count' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'facilities' => 'nullable|string',
            'rules' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request, $kost) {
            $kost->update($validated);

            // Update categories
            $kost->categories()->sync($validated['categories']);

            // Handle new image uploads
            if ($request->hasFile('new_images')) {
                $currentImageCount = $kost->images()->count();
                
                foreach ($request->file('new_images') as $index => $image) {
                    $path = $image->store('kost-images', 'public');
                    
                    KostImage::create([
                        'kost_id' => $kost->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'order' => $currentImageCount + $index,
                    ]);
                }
            }
        });

        return redirect()->route('admin.kosts.index')
            ->with('success', 'Kost berhasil diperbarui!');
    }

    public function destroy(Kost $kost)
    {
        DB::transaction(function () use ($kost) {
            // Delete images from storage
            foreach ($kost->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            $kost->delete();
        });

        return redirect()->route('admin.kosts.index')
            ->with('success', 'Kost berhasil dihapus!');
    }

    public function deleteImage(KostImage $image)
    {
        // Check if this is the last image
        if ($image->kost->images()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus gambar terakhir!'
            ], 400);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus!'
        ]);
    }

    public function setPrimaryImage(KostImage $image)
    {
        DB::transaction(function () use ($image) {
            // Remove primary status from all images of this kost
            $image->kost->images()->update(['is_primary' => false]);
            
            // Set this image as primary
            $image->update(['is_primary' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Gambar utama berhasil diubah!'
        ]);
    }

    public function updateStatus(Request $request, Kost $kost)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $kost->update([
            'status' => $request->status
        ]);

        $statusText = $request->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Kost berhasil {$statusText}!",
            'status' => $request->status
        ]);
    }
}
