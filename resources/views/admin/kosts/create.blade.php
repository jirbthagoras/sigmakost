@extends('admin.layout')

@section('title', 'Tambah Kost')
@section('page-title', 'Tambah Kost')

@section('page-actions')
    <a href="{{ route('admin.kosts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<form action="{{ route('admin.kosts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
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
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Nomor Kontak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                   id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price_per_month" class="form-label">Harga per Bulan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price_per_month') is-invalid @enderror" 
                                       id="price_per_month" name="price_per_month" value="{{ old('price_per_month') }}" required>
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
                                   id="room_count" name="room_count" value="{{ old('room_count') }}" min="1" required>
                            @error('room_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="available_rooms" class="form-label">Kamar Tersedia <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('available_rooms') is-invalid @enderror" 
                                   id="available_rooms" name="available_rooms" value="{{ old('available_rooms') }}" min="0" required>
                            @error('available_rooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Map Location Selector -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-primary"></i> Pilih Lokasi Kost
                        </label>
                        <div class="map-container">
                            <div class="map-search mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address-search" 
                                           placeholder="Cari alamat atau klik pada peta..."
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
                                            Klik pada peta untuk memilih lokasi atau gunakan pencarian alamat
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small class="text-muted" id="coordinates-display">
                                            Koordinat akan muncul setelah memilih lokasi
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs for latitude and longitude -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                        
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
                                  id="facilities" name="facilities" rows="4">{{ old('facilities') }}</textarea>
                        @error('facilities')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pisahkan dengan enter atau koma</div>
                    </div>

                    <div class="mb-3">
                        <label for="rules" class="form-label">Aturan Kost</label>
                        <textarea class="form-control @error('rules') is-invalid @enderror" 
                                  id="rules" name="rules" rows="4">{{ old('rules') }}</textarea>
                        @error('rules')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pisahkan dengan enter atau koma</div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Gambar Kost <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="images" class="form-label">Pilih Gambar</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" 
                               id="images" name="images[]" multiple accept="image/*" required>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pilih minimal 1 gambar. Format: JPG, PNG, GIF. Maksimal 2MB per gambar.
                            Gambar pertama akan menjadi gambar utama.
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
                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                               value="active" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">
                            <span class="badge bg-success">Aktif</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="inactive" 
                               value="inactive" {{ old('status') === 'inactive' ? 'checked' : '' }}>
                        <label class="form-check-label" for="inactive">
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Kost
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

.coordinates-info {
    background: rgba(78, 115, 223, 0.1);
    padding: 8px 12px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}
</style>
@endpush

@push('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

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
                            ${index === 0 ? '<br><span class="badge bg-primary">Gambar Utama</span>' : ''}
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
    if (!availableRooms.value || parseInt(availableRooms.value) > parseInt(this.value)) {
        availableRooms.value = this.value;
    }
    availableRooms.max = this.value;
});

// Global variables for map functionality
let mapInstance = null;
let markerInstance = null;

// Map functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Indonesia (Jakarta)
    const defaultLat = -6.2088;
    const defaultLng = 106.8456;
    
    mapInstance = L.map('map').setView([defaultLat, defaultLng], 10);
    
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
    const initialLat = document.getElementById('latitude').value;
    const initialLng = document.getElementById('longitude').value;
    if (initialLat && initialLng) {
        addMarker(parseFloat(initialLat), parseFloat(initialLng));
        mapInstance.setView([initialLat, initialLng], 15);
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
@endpush
