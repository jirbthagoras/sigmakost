<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $rentals = Rental::with([
            'kost.images',
            'payments' => function ($q) {
                $q->orderBy('due_date');
            }
        ])
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('user.payments.index', compact('rentals'));
    }

    public function pay(Payment $payment)
    {
        // Ensure the payment belongs to the authenticated user
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'unpaid') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah dibayar.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_method' => 'dummy',
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil! Menunggu verifikasi admin.');
    }
}
