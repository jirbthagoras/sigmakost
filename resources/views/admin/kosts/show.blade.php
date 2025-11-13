@extends('admin.layout')

@section('title', 'Detail Kost')
@section('page-title', 'Detail Kost: ' . $kost->name)

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('admin.kosts.edit', $kost) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-danger" onclick="deleteKost({{ $kost->id }})">
            <i class="fas fa-trash"></i> Hapus
        </button>
        <a href="{{ route('admin.kosts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kost</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Nama Kost:</strong></div>
                    <div class="col-md-9">{{ $kost->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Deskripsi:</strong></div>
                    <div class="col-md-9">{{ $kost->description }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Alamat:</strong></div>
                    <div class="col-md-9">{{ $kost->address }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Nomor Kontak:</strong></div>
                    <div class="col-md-9">{{ $kost->contact_number }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Harga per Bulan:</strong></div>
                    <div class="col-md-9">
                        <span class="h5 text-success mb-0">
                            Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Jumlah Kamar:</strong></div>
                    <div class="col-md-9">
                        <span class="badge bg-primary">{{ $kost->room_count }}</span> total kamar
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Kamar Tersedia:</strong></div>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $kost->available_rooms > 0 ? 'success' : 'danger' }}">
                            {{ $kost->available_rooms }}
                        </span> 
                        kamar tersedia
                    </div>
                </div>
                @if($kost->latitude && $kost->longitude)
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Koordinat:</strong></div>
                        <div class="col-md-9">
                            {{ $kost->latitude }}, {{ $kost->longitude }}
                            <a href="https://maps.google.com/?q={{ $kost->latitude }},{{ $kost->longitude }}" 
                               target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="fas fa-map-marker-alt"></i> Lihat di Maps
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Information -->
        @if($kost->facilities || $kost->rules)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Tambahan</h6>
                </div>
                <div class="card-body">
                    @if($kost->facilities)
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Fasilitas:</strong></div>
                            <div class="col-md-9">
                                <div class="facilities-list">
                                    @foreach(explode("\n", $kost->facilities) as $facility)
                                        @if(trim($facility))
                                            <span class="badge bg-light text-dark me-1 mb-1">
                                                <i class="fas fa-check text-success"></i> {{ trim($facility) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($kost->rules)
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Aturan Kost:</strong></div>
                            <div class="col-md-9">
                                <div class="rules-list">
                                    @foreach(explode("\n", $kost->rules) as $rule)
                                        @if(trim($rule))
                                            <div class="mb-1">
                                                <i class="fas fa-info-circle text-warning"></i> {{ trim($rule) }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Images Gallery -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Galeri Gambar ({{ $kost->images->count() }})</h6>
            </div>
            <div class="card-body">
                @if($kost->images->count() > 0)
                    <div class="row">
                        @foreach($kost->images->sortBy('order') as $image)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <img src="{{ $image->image_url }}" class="card-img-top" 
                                         style="height: 200px; object-fit: cover; cursor: pointer;" 
                                         alt="Kost Image" 
                                         onclick="showImageModal('{{ $image->image_url }}', '{{ $kost->name }}')">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($image->is_primary)
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-star"></i> Gambar Utama
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Gambar #{{ $image->order + 1 }}</span>
                                            @endif
                                            <small class="text-muted">
                                                {{ $image->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada gambar untuk kost ini.</p>
                        <a href="{{ route('admin.kosts.edit', $kost) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Gambar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Status & Categories -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Status & Kategori</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-5"><strong>Status:</strong></div>
                    <div class="col-7">
                        <span class="badge bg-{{ $kost->status === 'active' ? 'success' : 'secondary' }} fs-6">
                            {{ $kost->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-5"><strong>Kategori:</strong></div>
                    <div class="col-7">
                        @if($kost->categories->count() > 0)
                            @foreach($kost->categories as $category)
                                <span class="badge bg-info me-1 mb-1">{{ $category->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Tidak ada kategori</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Meta</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width="40%">ID:</th>
                        <td>#{{ $kost->id }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat:</th>
                        <td>
                            {{ $kost->created_at->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $kost->created_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Diubah:</th>
                        <td>
                            {{ $kost->updated_at->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $kost->updated_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Pembuat:</th>
                        <td>{{ $kost->creator->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>Total Gambar:</th>
                        <td>{{ $kost->images->count() }} gambar</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.kosts.edit', $kost) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Kost
                    </a>
                    @if($kost->status === 'active')
                        <button class="btn btn-secondary" onclick="changeStatus('{{ $kost->id }}', 'inactive')">
                            <i class="fas fa-pause"></i> Nonaktifkan
                        </button>
                    @else
                        <button class="btn btn-success" onclick="changeStatus('{{ $kost->id }}', 'active')">
                            <i class="fas fa-play"></i> Aktifkan
                        </button>
                    @endif
                    <button class="btn btn-danger" onclick="deleteKost({{ $kost->id }})">
                        <i class="fas fa-trash"></i> Hapus Kost
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Gambar Kost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Kost Image">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Show image in modal
function showImageModal(imageSrc, kostName) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModalTitle').textContent = 'Gambar: ' + kostName;
    
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

// Delete kost using reusable popup
function deleteKost(kostId) {
    showConfirm(
        'Konfirmasi Hapus',
        'Apakah Anda yakin ingin menghapus kost ini? Semua gambar dan data terkait akan ikut terhapus. Aksi ini tidak dapat dibatalkan.',
        function() {
            // Perform delete
            makeRequest(`/admin/kosts/${kostId}`, 'DELETE')
                .then(data => {
                    showToast(data.message || 'Kost berhasil dihapus!', 'success');
                    setTimeout(() => {
                        window.location.href = '/admin/kosts';
                    }, 1500);
                })
                .catch(error => {
                    showToast(error.message || 'Gagal menghapus kost!', 'error');
                });
        },
        'Hapus',
        'btn-danger'
    );
}

// Change status using reusable popup
function changeStatus(kostId, status) {
    const statusText = status === 'active' ? 'aktifkan' : 'nonaktifkan';
    const statusAction = status === 'active' ? 'Aktifkan' : 'Nonaktifkan';
    
    showConfirm(
        'Konfirmasi Perubahan Status',
        `Apakah Anda yakin ingin ${statusText} kost ini?`,
        function() {
            // Perform status change
            makeRequest(`/admin/kosts/${kostId}/status`, 'PATCH', { status: status })
                .then(data => {
                    showToast(data.message || `Kost berhasil ${statusText}!`, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    showToast(error.message || 'Gagal mengubah status!', 'error');
                });
        },
        statusAction,
        status === 'active' ? 'btn-success' : 'btn-secondary'
    );
}
</script>
@endpush
