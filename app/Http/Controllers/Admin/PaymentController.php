<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['rental.kost', 'user', 'verifier'])
            ->orderByRaw("CASE status WHEN 'paid' THEN 1 WHEN 'unpaid' THEN 2 WHEN 'overdue' THEN 3 WHEN 'verified' THEN 4 ELSE 5 END")
            ->orderBy('due_date')
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Payment $payment)
    {
        if ($payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Hanya pembayaran berstatus "paid" yang bisa diverifikasi.');
        }

        $payment->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }
}
