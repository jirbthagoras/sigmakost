@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <h1 class="text-2xl font-extrabold text-[#1593E6] tracking-tight">{{ __('app.app_name') }}</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">{{ __('app.welcome_user', ['name' => Auth::user()->name]) }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-[#DDDDDD] hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            {{ __('app.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ __('app.welcome_user', ['name' => Auth::user()->name]) }}</h2>
                <p class="mt-2 text-gray-600 font-medium">{{ __('app.find_manage_bookings') }}</p>
            </div>

            <!-- Enhanced Search Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-[#1593E6]/10 rounded-full mb-4">
                        <svg class="w-8 h-8 text-[#1593E6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('app.find_perfect_kost') }}</h3>
                    <p class="text-gray-600">Cari dari <span class="font-semibold text-[#1593E6]">{{ $totalKosts }} kost</span> dengan <span class="font-semibold text-[#1593E6]">{{ $availableRooms }} kamar</span> tersedia</p>
                </div>
                
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-6">
                        <!-- Search Input -->
                        <div class="lg:col-span-6 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="dashboardSearch" 
                                   placeholder="Cari berdasarkan nama atau lokasi kost..." 
                                   class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1593E6] focus:border-[#1593E6] text-gray-900 font-medium transition-all">
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="lg:col-span-4 relative">
                            <select id="dashboardCategory" 
                                    class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1593E6] focus:border-[#1593E6] bg-white text-gray-900 font-medium transition-all">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Search Button -->
                        <div class="lg:col-span-2">
                            <button onclick="searchKost()" 
                                    class="w-full bg-[#1593E6] text-white px-6 py-4 rounded-xl font-bold hover:bg-[#0F7CC8] transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mx-auto lg:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden lg:block">Cari</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Filters -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <span class="text-sm text-gray-500 mr-2">Filter cepat:</span>
                        <button onclick="quickFilter('Pria')" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-medium hover:bg-blue-100 transition-colors">
                            Kost Pria
                        </button>
                        <button onclick="quickFilter('Wanita')" class="px-3 py-1 bg-pink-50 text-pink-600 rounded-full text-sm font-medium hover:bg-pink-100 transition-colors">
                            Kost Wanita
                        </button>
                        <button onclick="quickFilter('Campur')" class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-sm font-medium hover:bg-purple-100 transition-colors">
                            Kost Campur
                        </button>
                        <button onclick="browseAll()" class="px-3 py-1 bg-gray-50 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-100 transition-colors">
                            Lihat Semua
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-[#1593E6]/10 rounded-xl">
                            <svg class="w-6 h-6 text-[#1593E6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">{{ __('app.available_kosts') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalKosts }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-xl">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1H21l-1 6H4l-1-6h7.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Kamar Tersedia</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $availableRooms }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">{{ __('app.your_bookings') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $userBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">{{ __('app.pending_payments') }}</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 tracking-tight">{{ __('app.quick_actions') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('kost.index') }}" class="flex items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-[#1593E6] hover:bg-[#1593E6]/5 transition-colors group">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-[#1593E6] mx-auto mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600 group-hover:text-[#1593E6] transition-colors">Jelajahi Semua Kost</span>
                        </div>
                    </a>

                    <button onclick="alert('Sistem pemesanan segera hadir!')" class="flex items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-[#1593E6] hover:bg-[#1593E6]/5 transition-colors group">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-[#1593E6] mx-auto mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600 group-hover:text-[#1593E6] transition-colors">{{ __('app.view_bookings') }}</span>
                        </div>
                    </button>

                    <button onclick="alert('Sistem pembayaran segera hadir!')" class="flex items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-[#1593E6] hover:bg-[#1593E6]/5 transition-colors group">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-[#1593E6] mx-auto mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600 group-hover:text-[#1593E6] transition-colors">{{ __('app.payments') }}</span>
                        </div>
                    </button>

                    <button onclick="alert('Pengaturan profil segera hadir!')" class="flex items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-[#1593E6] hover:bg-[#1593E6]/5 transition-colors group">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-[#1593E6] mx-auto mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600 group-hover:text-[#1593E6] transition-colors">Pengaturan {{ __('app.profile') }}</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Featured Kost Section -->
            @if($featuredKosts->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Kost Terbaru</h3>
                        <a href="{{ route('kost.index') }}" class="text-[#1593E6] hover:text-[#0F7CC8] font-medium text-sm transition-colors">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredKosts as $kost)
                            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                <!-- Image -->
                                <div class="relative h-40 bg-gray-200">
                                    @if($kost->primary_image)
                                        <img src="{{ $kost->primary_image->image_url }}" 
                                             alt="{{ $kost->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Availability Badge -->
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                            {{ $kost->available_rooms }} rooms
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <!-- Categories -->
                                    @if($kost->categories->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @foreach($kost->categories->take(2) as $category)
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Name -->
                                    <h4 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                                        {{ $kost->name }}
                                    </h4>

                                    <!-- Address -->
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-1">
                                        {{ $kost->address }}
                                    </p>

                                    <!-- Price and Action -->
                                    <div class="flex items-center justify-between">
                                        <div class="text-lg font-bold text-[#1593E6]">
                                            Rp {{ number_format($kost->price_per_month / 1000000, 1) }}M
                                            <span class="text-xs font-normal text-gray-500">/mo</span>
                                        </div>
                                        <a href="{{ route('kost.show', $kost) }}" 
                                           class="bg-[#1593E6] text-white text-xs px-3 py-1.5 rounded-lg hover:bg-[#0F7CC8] transition-colors">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function searchKost() {
    const searchValue = document.getElementById('dashboardSearch').value.trim();
    const categoryValue = document.getElementById('dashboardCategory').value;
    
    const url = new URL('{{ route("kost.index") }}', window.location.origin);
    
    if (searchValue) {
        url.searchParams.set('search', searchValue);
    }
    
    if (categoryValue) {
        url.searchParams.set('category', categoryValue);
    }
    
    window.location.href = url.toString();
}

function quickFilter(categoryName) {
    const url = new URL('{{ route("kost.index") }}', window.location.origin);
    url.searchParams.set('search', categoryName);
    window.location.href = url.toString();
}

function browseAll() {
    window.location.href = '{{ route("kost.index") }}';
}

// Allow Enter key to trigger search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('dashboardSearch');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchKost();
            }
        });
    }
});
</script>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
