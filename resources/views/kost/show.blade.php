@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('kost.index') }}" class="text-gray-600 hover:text-[#1593E6] flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>{{ __('app.back_to_all') }}</span>
                    </a>
                    <div class="shrink-0">
                        <a href="{{ route('home') }}" class="text-2xl font-extrabold text-[#1593E6] tracking-tight">
                            {{ __('app.app_name') }}
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-[#1593E6] text-white hover:bg-[#0F7CC8] px-4 py-2 rounded-lg text-sm font-medium">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    @if($kost->images->count() > 0)
                        <div class="relative">
                            <img id="mainImage" 
                                 src="{{ $kost->primary_image->image_url }}" 
                                 alt="{{ $kost->name }}"
                                 class="w-full h-96 object-cover">
                            
                        </div>
                        @if($kost->images->count() > 1)
                            <!-- Image Navigation -->
                            <div class="p-4 border-t">
                                <div class="flex space-x-2 overflow-x-auto">
                                    @foreach($kost->images as $image)
                                        <button onclick="changeImage('{{ $image->image_url }}')" 
                                                class="shrink-0 w-20 h-20 rounded-lg overflow-hidden border hover:ring-2 ring-[#1593E6] transition">
                                            <img src="{{ $image->image_url }}" 
                                                 alt="Thumbnail"
                                                 class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="h-96 bg-gray-200 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">{{ __('app.no_image') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Kost Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <!-- Categories -->
                    @if($kost->categories->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($kost->categories as $category)
                                <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Title and Availability -->
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $kost->name }}</h1>
                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full font-medium">
                            {{ $kost->available_rooms }} {{ __('app.available_rooms') }}
                        </span>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start space-x-2 mb-6">
                        <svg class="h-5 w-5 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-gray-600">{{ $kost->address }}</span>
                    </div>

                    <!-- Description -->
                    @if($kost->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.description') }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $kost->description }}</p>
                        </div>
                    @endif

                    <!-- Facilities -->
                    @if($kost->facilities)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.facilities') }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $kost->facilities }}</p>
                        </div>
                    @endif

                    <!-- Rules -->
                    @if($kost->rules)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.rules') }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $kost->rules }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Booking Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <!-- Price -->
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-[#1593E6]">
                            Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}
                        </div>
                        <div class="text-gray-500">{{ __('app.per_month') }}</div>
                    </div>

                    <!-- Property Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.total_rooms') }}:</span>
                            <span class="font-medium">{{ $kost->room_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.available') }}:</span>
                            <span class="font-medium text-green-600">{{ $kost->available_rooms }}</span>
                        </div>
                        @if($kost->contact_number)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('app.contact') }}:</span>
                                <span class="font-medium">{{ $kost->contact_number }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contact Actions -->
                    <div class="space-y-3">
                        @if($kost->contact_number)
                            <a href="tel:{{ $kost->contact_number }}" 
                               class="w-full bg-[#1593E6] text-white text-center py-3 px-4 rounded-lg hover:bg-[#0F7CC8] transition-colors font-medium flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>{{ __('app.call_now') }}</span>
                            </a>
                            
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kost->contact_number) }}?text=Hi, I'm interested in {{ $kost->name }}" 
                               target="_blank"
                               class="w-full bg-green-500 text-white text-center py-3 px-4 rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                <span>{{ __('app.whatsapp') }}</span>
                            </a>
                        @endif
                    </div>

                    <!-- Owner Info -->
                    @if($kost->creator)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-600">{{ __('app.listed_by') }}</div>
                            <div class="font-medium">{{ $kost->creator->name }}</div>
                        </div>
                    @endif
                </div>

                <!-- Map Section (Placeholder) -->
                @if($kost->latitude && $kost->longitude)
                    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.location') }}</h3>
                        <div id="kostMap" class="h-64 rounded-lg overflow-hidden" data-lat="{{ $kost->latitude }}" data-lng="{{ $kost->longitude }}" data-name="{{ $kost->name }}"></div>
                        <div class="mt-2 text-xs text-gray-500 text-center">
                            {{ __('app.coordinates') }}: {{ $kost->latitude }}, {{ $kost->longitude }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function changeImage(imageUrl) {
    document.getElementById('mainImage').src = imageUrl;
}
document.addEventListener('DOMContentLoaded', function() {
    var mapEl = document.getElementById('kostMap');
    if (mapEl) {
        var lat = parseFloat(mapEl.dataset.lat);
        var lng = parseFloat(mapEl.dataset.lng);
        var name = mapEl.dataset.name || '';
        if (!isNaN(lat) && !isNaN(lng)) {
            var map = L.map('kostMap', { scrollWheelZoom: false }).setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map).bindPopup(name);
            setTimeout(function(){ map.invalidateSize(); }, 200);
        }
    }
});
</script>
@endsection
