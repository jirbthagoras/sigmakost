<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Rental;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalKosts = Kost::active()->count();
        $availableRooms = Kost::active()->sum('available_rooms');
        $categories = Category::orderBy('name')->get();

        // Get featured/recent kosts (latest 6 active kosts)
        $featuredKosts = Kost::with(['images', 'categories'])
            ->active()
            ->available()
            ->latest()
            ->limit(6)
            ->get();

        // Get user's real booking stats
        $userBookings = Rental::where('user_id', auth()->id())->count();
        $pendingPayments = Rental::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->sum('total_price');

        return view('dashboard', compact(
            'totalKosts',
            'availableRooms',
            'categories',
            'featuredKosts',
            'userBookings',
            'pendingPayments'
        ));
    }
}
