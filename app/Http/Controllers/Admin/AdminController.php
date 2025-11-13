<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_kosts' => Kost::count(),
            'active_kosts' => Kost::where('status', 'active')->count(),
            'total_categories' => Category::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_rooms' => Kost::sum('room_count'),
            'available_rooms' => Kost::sum('available_rooms'),
        ];

        $recent_kosts = Kost::with(['creator', 'categories'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_kosts'));
    }
}
