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
            ->orderByRaw("FIELD(status, 'paid', 'unpaid', 'verified')")
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
