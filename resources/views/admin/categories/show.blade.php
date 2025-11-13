@extends('admin.layout')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori: ' . $category->name)

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kategori</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Nama:</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Deskripsi:</th>
                        <td>{{ $category->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Kost:</th>
                        <td><span class="badge bg-primary">{{ $category->kosts->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Dibuat:</th>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diubah:</th>
                        <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Kost dalam Kategori Ini ({{ $category->kosts->count() }})</h6>
            </div>
            <div class="card-body">
                @if($category->kosts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="15%">Gambar</th>
                                    <th>Nama Kost</th>
                                    <th>Harga/Bulan</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->kosts as $kost)
                                    <tr>
                                        <td>
                                            @if($kost->images->first())
                                                <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" 
                                                     alt="{{ $kost->name }}" class="rounded" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $kost->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($kost->address, 40) }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $kost->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($kost->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.kosts.show', $kost) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.kosts.edit', $kost) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Kost</h5>
                        <p class="text-muted">Belum ada kost yang menggunakan kategori ini.</p>
                        <a href="{{ route('admin.kosts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Kost
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
