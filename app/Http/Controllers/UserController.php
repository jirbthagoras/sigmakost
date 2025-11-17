<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Category;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Kost::with(['images', 'categories'])
            ->active()
            ->available();

        // Filter by search term (name)
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $kosts = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('kost.index', compact('kosts', 'categories'));
    }

    public function show(Kost $kost)
    {
        $kost->load(['images', 'categories', 'creator']);
        
        return view('kost.show', compact('kost'));
    }
}
