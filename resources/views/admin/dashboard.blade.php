@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kost
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_kosts'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kost Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_kosts'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Kamar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_rooms'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bed fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kamar Tersedia
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['available_rooms'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-door-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Categories and Users -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistik Lainnya</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 text-center">
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['total_categories'] }}</div>
                        <div class="text-xs font-weight-bold text-uppercase">Kategori</div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        <div class="text-xs font-weight-bold text-uppercase">Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Kosts -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Kost Terbaru</h6>
                <a href="{{ route('admin.kosts.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recent_kosts->count() > 0)
                    @foreach($recent_kosts as $kost)
                        <div class="d-flex align-items-center mb-3">
                            @if($kost->images->first())
                                <img src="{{ $kost->images->first()->image_url }}" 
                                     alt="{{ $kost->name }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-white"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $kost->name }}</div>
                                <small class="text-muted">
                                    Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}/bulan
                                </small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada kost yang ditambahkan.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.kosts.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Tambah Kost
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-info btn-block">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.kosts.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-list"></i> Kelola Kost
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-tags"></i> Kelola Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .btn-block {
        width: 100%;
    }
</style>
@endpush
