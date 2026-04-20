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

    public function pay(Request $request, Payment $payment)
    {
        // Ensure the payment belongs to the authenticated user
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'unpaid') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah dibayar.');
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'payment_method' => 'required|in:transfer_bank,e_wallet,cash',
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.mimes' => 'File harus berformat JPG, PNG, atau PDF.',
            'payment_proof.max' => 'Ukuran file maksimal 5MB.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        // Store the proof file
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        $payment->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_method' => $request->payment_method,
            'payment_proof' => $proofPath,
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }
}
