@extends('admin.layout')

@section('title', 'Pembayaran')
@section('page-title', 'Manajemen Pembayaran')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Penyewa</th>
                                <th>Kost</th>
                                <th>Periode</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $payment->user->name }}</div>
                                        <div class="text-muted small">{{ $payment->user->email }}</div>
                                    </td>
                                    <td>{{ $payment->rental->kost->name ?? '-' }}</td>
                                    <td>{{ $payment->period_label }}</td>
                                    <td>
                                        {{ $payment->due_date->format('d M Y') }}
                                        @if($payment->isOverdue())
                                            <br><span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @if($payment->status === 'verified')
                                            <span class="badge bg-success">Terverifikasi</span>
                                            @if($payment->verifier)
                                                <br><small class="text-muted">oleh {{ $payment->verifier->name }}</small>
                                            @endif
                                        @elseif($payment->status === 'paid')
                                            <span class="badge bg-warning text-dark">Dibayar</span>
                                            @if($payment->paid_date)
                                                <br><small class="text-muted">{{ $payment->paid_date->format('d M Y H:i') }}</small>
                                            @endif
                                            @if($payment->payment_method)
                                                <br><small class="text-muted">via
                                                    {{ str_replace('_', ' ', ucwords($payment->payment_method, '_')) }}</small>
                                            @endif
                                        @elseif($payment->status === 'overdue')
                                            <span class="badge bg-danger">Terlambat</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($payment->payment_proof)
                                            @php
                                                $ext = pathinfo($payment->payment_proof, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                            @endphp
                                            @if($isImage)
                                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary" title="Lihat Bukti">
                                                    <i class="fas fa-image"></i> Lihat
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary" title="Download Bukti">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'paid')
                                            <form id="verifyForm-{{ $payment->id }}"
                                                action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-success" onclick="window.showConfirm({
                                                                        title: 'Verifikasi Pembayaran',
                                                                        message: 'Verifikasi pembayaran Rp {{ number_format($payment->amount, 0, ',', '.') }} dari {{ $payment->user->name }}?',
                                                                        confirmText: 'Ya, Verifikasi',
                                                                        variant: 'success',
                                                                        onConfirm: () => document.getElementById('verifyForm-{{ $payment->id }}').submit()
                                                                    })">
                                                    <i class="fas fa-check"></i> Verifikasi
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data pembayaran.</h5>
                </div>
            @endif
        </div>
    </div>
@endsection