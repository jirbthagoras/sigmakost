<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;

class ReviewController extends Controller
{
    public function store(Request $request, Kost $kost)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'rental_id' => 'required|exists:rentals,id',
        ]);

        // Verify rental belongs to user and kost
        $rental = \App\Models\Rental::where('id', $validated['rental_id'])
            ->where('user_id', auth()->id())
            ->where('kost_id', $kost->id)
            ->where('status', 'approved') // Only approved rentals can be reviewed
            ->firstOrFail();

        // Check if already reviewed
        if (\App\Models\Review::where('rental_id', $rental->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk penyewaan ini.');
        }

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'kost_id' => $kost->id,
            'rental_id' => $rental->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
