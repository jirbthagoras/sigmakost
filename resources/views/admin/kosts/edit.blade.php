@extends('admin.layout')

@section('title', 'Edit Kost')
@section('page-title', 'Edit Kost: ' . $kost->name)

@section('page-actions')
    <a href="{{ route('admin.kosts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<form action="{{ route('admin.kosts.update', $kost) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Dasar</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kost <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $kost->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $kost->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address', $kost->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Nomor Kontak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                   id="contact_number" name="contact_number" value="{{ old('contact_number', $kost->contact_number) }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price_per_month" class="form-label">Harga per Bulan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price_per_month') is-invalid @enderror" 
                                       id="price_per_month" name="price_per_month" value="{{ old('price_per_month', $kost->price_per_month) }}" required>
                            </div>
                            @error('price_per_month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_count" class="form-label">Total Kamar <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('room_count') is-invalid @enderror" 
                                   id="room_count" name="room_count" value="{{ old('room_count', $kost->room_count) }}" min="1" required>
                            @error('room_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="available_rooms" class="form-label">Kamar Tersedia <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('available_rooms') is-invalid @enderror" 
                                   id="available_rooms" name="available_rooms" value="{{ old('available_rooms', $kost->available_rooms) }}" min="0" required>
                            @error('available_rooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude', $kost->latitude) }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude', $kost->longitude) }}">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Tambahan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="facilities" class="form-label">Fasilitas</label>
                        <textarea class="form-control @error('facilities') is-invalid @enderror" 
                                  id="facilities" name="facilities" rows="4">{{ old('facilities', $kost->facilities) }}</textarea>
                        @error('facilities')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pisahkan dengan enter atau koma</div>
                    </div>

                    <div class="mb-3">
                        <label for="rules" class="form-label">Aturan Kost</label>
                        <textarea class="form-control @error('rules') is-invalid @enderror" 
                                  id="rules" name="rules" rows="4">{{ old('rules', $kost->rules) }}</textarea>
                        @error('rules')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pisahkan dengan enter atau koma</div>
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Gambar Saat Ini</h6>
                </div>
                <div class="card-body">
                    @if($kost->images->count() > 0)
                        <div class="row">
                            @foreach($kost->images as $image)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="{{ $image->image_url }}" class="card-img-top" 
                                             style="height: 150px; object-fit: cover;" alt="Kost Image">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                @if($image->is_primary)
                                                    <span class="badge bg-primary">Gambar Utama</span>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="setPrimary({{ $image->id }})">
                                                        Set Utama
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteImage({{ $image->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada gambar untuk kost ini.</p>
                    @endif
                </div>
            </div>

            <!-- Add New Images -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Gambar Baru</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="images" class="form-label">Pilih Gambar</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" 
                               id="images" name="images[]" multiple accept="image/*">
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pilih gambar untuk ditambahkan. Format: JPG, PNG, GIF. Maksimal 2MB per gambar.
                        </div>
                    </div>
                    <div id="image-preview" class="row"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input @error('categories') is-invalid @enderror" 
                                       type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                       id="category_{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', $kost->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                        @error('categories')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    @else
                        <p class="text-muted">Belum ada kategori. <a href="{{ route('admin.categories.create') }}">Buat kategori</a> terlebih dahulu.</p>
                    @endif
                </div>
            </div>

            <!-- Status -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Status</h6>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="active" 
                               value="active" {{ old('status', $kost->status) === 'active' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">
                            <span class="badge bg-success">Aktif</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="inactive" 
                               value="inactive" {{ old('status', $kost->status) === 'inactive' ? 'checked' : '' }}>
                        <label class="form-check-label" for="inactive">
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Info Kost</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Dibuat:</th>
                            <td>{{ $kost->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diubah:</th>
                            <td>{{ $kost->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Pembuat:</th>
                            <td>{{ $kost->creator->name ?? 'Unknown' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Kost
                        </button>
                        <a href="{{ route('admin.kosts.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small>
                        </div>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Auto-update available rooms when room count changes
document.getElementById('room_count').addEventListener('input', function() {
    const availableRooms = document.getElementById('available_rooms');
    if (parseInt(availableRooms.value) > parseInt(this.value)) {
        availableRooms.value = this.value;
    }
    availableRooms.max = this.value;
});

// Set image as primary using reusable popup
function setPrimary(imageId) {
    showConfirm(
        'Konfirmasi Gambar Utama',
        'Set gambar ini sebagai gambar utama?',
        function() {
            makeRequest(`/admin/kost-images/${imageId}/primary`, 'PATCH')
                .then(data => {
                    showToast(data.message || 'Gambar utama berhasil diubah!', 'success');
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    showToast(error.message || 'Gagal mengubah gambar utama!', 'error');
                });
        },
        'Set Utama',
        'btn-primary'
    );
}

// Delete image using reusable popup
function deleteImage(imageId) {
    showConfirm(
        'Konfirmasi Hapus Gambar',
        'Hapus gambar ini? Aksi ini tidak dapat dibatalkan.',
        function() {
            makeRequest(`/admin/kost-images/${imageId}`, 'DELETE')
                .then(data => {
                    showToast(data.message || 'Gambar berhasil dihapus!', 'success');
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    showToast(error.message || 'Gagal menghapus gambar!', 'error');
                });
        },
        'Hapus',
        'btn-danger'
    );
}
</script>
@endpush
