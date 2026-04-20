<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Category;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Kost::with(['images', 'categories', 'reviews'])
            ->active()
            ->available();

        // Filter by search term (name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('address', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price_per_month', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_month', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        $kosts = $query->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('kost.index', compact('kosts', 'categories'));
    }

    public function show(Kost $kost)
    {
        $kost->load(['images', 'categories', 'creator', 'reviews.user']);

        $existingRental = null;
        if (auth()->check()) {
            $existingRental = \App\Models\Rental::where('user_id', auth()->id())
                ->where('kost_id', $kost->id)
                ->whereIn('status', ['pending', 'approved'])
                ->latest()
                ->first();
        }

        return view('kost.show', compact('kost', 'existingRental'));
    }
}
