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
                                        @else
                                            <span class="badge bg-danger">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'paid')
                                            <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Verifikasi pembayaran ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
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