<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['user', 'kost'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.rentals.index', compact('rentals'));
    }

    public function updateStatus(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);

        $rental->update([
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        // If approved, decrement available rooms
        if ($validated['status'] === 'approved') {
            $rental->kost->decrement('available_rooms');
        }

        return redirect()->back()->with('success', 'Status sewa berhasil diperbarui!');
    }
}
