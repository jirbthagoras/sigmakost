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

                    <!-- Map Location Selector -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-primary"></i> Lokasi Kost
                        </label>
                        <div class="map-container">
                            <div class="map-search mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address-search" 
                                           placeholder="Cari alamat atau klik pada peta untuk mengubah lokasi..."
                                           onkeypress="if(event.key==='Enter'){event.preventDefault();searchAddress();}">
                                    <button type="button" class="btn btn-outline-primary" onclick="searchAddress()">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                            <div id="map" class="map-display"></div>
                            <div class="map-info mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            Klik pada peta untuk mengubah lokasi atau gunakan pencarian alamat
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small class="text-muted" id="coordinates-display">
                                            @if($kost->latitude && $kost->longitude)
                                                Lat: {{ number_format($kost->latitude, 6) }}, Lng: {{ number_format($kost->longitude, 6) }}
                                            @else
                                                Koordinat akan muncul setelah memilih lokasi
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs for latitude and longitude -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $kost->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $kost->longitude) }}">
                        
                        @error('latitude')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('longitude')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Gambar Baru</h6>
                    <small class="text-muted">Drag & drop atau klik untuk memilih</small>
                </div>
                <div class="card-body">
                    <!-- Enhanced Image Upload Area -->
                    <div class="image-upload-area mb-4" id="image-upload-area">
                        <div class="upload-zone" id="upload-zone">
                            <div class="upload-content text-center">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h5 class="text-primary mb-2">Tambah Gambar Kost</h5>
                                <p class="text-muted mb-3">
                                    <strong>Drag & drop</strong> gambar ke sini atau <strong>klik untuk memilih</strong>
                                </p>
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('new_images').click()">
                                    <i class="fas fa-folder-open"></i> Pilih Gambar
                                </button>
                                <input type="file" class="d-none @error('new_images') is-invalid @enderror" 
                                       id="new_images" name="new_images[]" multiple accept="image/*">
                            </div>
                        </div>
                        @error('new_images')
                            <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-center mt-2">
                            <i class="fas fa-info-circle"></i> Format: JPG, PNG, GIF. Maksimal 2MB per gambar. Dapat memilih beberapa gambar sekaligus.
                        </div>
                    </div>

                    <!-- Image Preview with Management -->
                    <div id="image-preview-section" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-secondary">
                                <i class="fas fa-images"></i> Preview Gambar (<span id="image-count">0</span>)
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllPreviews()">
                                <i class="fas fa-trash"></i> Hapus Semua
                            </button>
                        </div>
                        <div id="image-preview" class="row"></div>
                    </div>
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

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>

<style>
.map-container {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fc;
}

.map-display {
    height: 400px;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #d1d3e2;
}

.map-search .input-group {
    max-width: 400px;
}

.image-upload-area {
    position: relative;
}

.upload-zone {
    border: 2px dashed #4e73df;
    border-radius: 10px;
    padding: 40px 20px;
    background-color: #f8f9fc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-zone:hover, .upload-zone.dragover {
    border-color: #2e59d9;
    background-color: #e3ebfe;
    transform: translateY(-2px);
}

.upload-zone .upload-content {
    pointer-events: none;
}

.image-preview-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.image-preview-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.image-preview-item .remove-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.image-preview-item .remove-btn:hover {
    background: rgba(220, 53, 69, 1);
}

.image-size-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 20px 8px 8px;
    font-size: 11px;
}
</style>
@endpush

@push('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
// Global variables for map functionality
let mapInstance = null;
let markerInstance = null;

// Map functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Indonesia (Jakarta) or existing coordinates
    const initialLat = document.getElementById('latitude').value || -6.2088;
    const initialLng = document.getElementById('longitude').value || 106.8456;
    
    mapInstance = L.map('map').setView([parseFloat(initialLat), parseFloat(initialLng)], initialLat && initialLng ? 15 : 10);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(mapInstance);
    
    // Handle map click
    mapInstance.on('click', function(e) {
        addMarker(e.latlng.lat, e.latlng.lng);
    });
    
    // Set initial marker if coordinates exist
    if (initialLat && initialLng && initialLat !== -6.2088) {
        addMarker(parseFloat(initialLat), parseFloat(initialLng));
    }
});

// Function to update coordinates
function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    document.getElementById('coordinates-display').textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
}

// Function to add/move marker
function addMarker(lat, lng) {
    if (markerInstance) {
        markerInstance.setLatLng([lat, lng]);
    } else {
        markerInstance = L.marker([lat, lng], {
            draggable: true
        }).addTo(mapInstance);
        
        // Handle marker drag
        markerInstance.on('dragend', function(e) {
            const position = e.target.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    }
    updateCoordinates(lat, lng);
}

// Address search function
function searchAddress() {
    const address = document.getElementById('address-search').value;
    if (!address.trim()) return;
    
    // Using Nominatim API for geocoding (free)
    const searchUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&countrycodes=id`;
    
    fetch(searchUrl)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                if (mapInstance) {
                    mapInstance.setView([lat, lng], 15);
                    addMarker(lat, lng);
                }
            } else {
                alert('Alamat tidak ditemukan. Silakan coba alamat lain atau klik langsung pada peta.');
            }
        })
        .catch(error => {
            console.error('Error searching address:', error);
            alert('Gagal mencari alamat. Silakan coba lagi atau klik langsung pada peta.');
        });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('new_images');
    const previewSection = document.getElementById('image-preview-section');
    const preview = document.getElementById('image-preview');
    const imageCount = document.getElementById('image-count');
    
    let selectedFiles = [];

    // File input change handler
    fileInput.addEventListener('change', handleFileSelect);

    // Drag and drop handlers
    uploadZone.addEventListener('click', () => fileInput.click());
    
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });
    
    uploadZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });
    
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files).filter(file => 
            file.type.startsWith('image/')
        );
        
        if (files.length > 0) {
            addFilesToSelection(files);
        }
    });

    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        addFilesToSelection(files);
    }

    function addFilesToSelection(files) {
        files.forEach(file => {
            if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
                selectedFiles.push(file);
            }
        });
        
        updateFileInput();
        renderPreviews();
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    function renderPreviews() {
        preview.innerHTML = '';
        
        if (selectedFiles.length === 0) {
            previewSection.classList.add('d-none');
            return;
        }
        
        previewSection.classList.remove('d-none');
        imageCount.textContent = selectedFiles.length;
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                
                const sizeInKB = (file.size / 1024).toFixed(1);
                
                col.innerHTML = `
                    <div class="image-preview-item">
                        <img src="${e.target.result}" class="img-fluid" 
                             style="height: 150px; width: 100%; object-fit: cover;">
                        <button type="button" class="remove-btn" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="image-size-info">
                            <div class="text-truncate"><strong>${file.name}</strong></div>
                            <div>${sizeInKB} KB</div>
                        </div>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    // Global function to remove files
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreviews();
    };

    // Global function to clear all previews
    window.clearAllPreviews = function() {
        selectedFiles = [];
        updateFileInput();
        renderPreviews();
    };
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
        () => {
            makeRequest(`/admin/kost-images/${imageId}/primary`, 'PATCH')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Gagal mengubah gambar utama!', 'error');
                    }
                })
                .catch(error => {
                    showToast(error.message || 'Gagal mengubah gambar utama!', 'error');
                });
        }
    );
}

// Delete image using reusable popup
function deleteImage(imageId) {
    showConfirm(
        'Konfirmasi Hapus Gambar',
        'Yakin ingin menghapus gambar ini? Tindakan ini tidak dapat dibatalkan.',
        () => {
            makeRequest(`/admin/kost-images/${imageId}`, 'DELETE')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Gagal menghapus gambar!', 'error');
                    }
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
