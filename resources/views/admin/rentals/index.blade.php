@extends('admin.layout')

@section('title', __('app.booking_request'))
@section('page-title', __('app.booking_request'))

@section('content')
    <div class="card shadow">
        <div class="card-body">

            @if($rentals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('app.applicant') }}</th>
                                <th>{{ __('app.kost_name') }}</th>
                                <th>{{ __('app.start_date') }} / {{ __('app.duration') }}</th>
                                <th>{{ __('app.total_price') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $rental)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $rental->user->name }}</div>
                                        <div class="text-muted small">{{ $rental->user->email }}</div>
                                    </td>
                                    <td>{{ $rental->kost->name }}</td>
                                    <td>
                                        <div>{{ $rental->start_date->format('d M Y') }}</div>
                                        <div class="text-muted small">{{ $rental->duration_months }} Bulan</div>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match ($rental->status) {
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'warning',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ __('app.' . $rental->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($rental->status === 'pending')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.rentals.status', $rental) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('{{ __('app.confirm_approve') }}')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        title="{{ __('app.approve') }}">
                                                        <i class="fas fa-check"></i> {{ __('app.approve') }}
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="openRejectModal('{{ route('admin.rentals.status', $rental) }}')"
                                                    title="{{ __('app.reject') }}">
                                                    <i class="fas fa-times"></i> {{ __('app.reject') }}
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $rentals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada permintaan sewa.</h5>
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.reject') }} {{ __('app.booking_request') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">{{ __('app.rejection_reason') }}</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">{{ __('app.reject') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openRejectModal(actionUrl) {
            const rejectForm = document.getElementById('rejectForm');
            rejectForm.action = actionUrl;

            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            rejectModal.show();
        }
    </script>
@endpush