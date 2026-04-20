<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['kost', 'kost.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.rentals.index', compact('rentals'));
    }

    public function store(Request $request, Kost $kost)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        DB::transaction(function () use ($validated, $kost) {
            $totalPrice = $kost->price_per_month * $validated['duration_months'];
            $endDate = \Carbon\Carbon::parse($validated['start_date'])->addMonths((int) $validated['duration_months']);

            Rental::create([
                'kost_id' => $kost->id,
                'user_id' => auth()->id(),
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
                'duration_months' => $validated['duration_months'],
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
        });

        return redirect()->back()->with('success', __('app.booking_success'));
    }
}
