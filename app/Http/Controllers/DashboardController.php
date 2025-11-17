<?php

namespace App\Http\Controllers;

use App\Models\Kost;
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
            
        // Get user's bookings count (placeholder for future booking system)
        $userBookings = 0; // TODO: Implement booking system
        $pendingPayments = 0; // TODO: Implement payment system

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
