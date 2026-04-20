@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Hero Header -->
        <div class="relative bg-gradient-to-br from-[#0F7CC8] via-[#1593E6] to-[#38BDF8] overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)" />
                </svg>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">
                    {{ __('app.find_perfect_kost') }}
                </h1>
                <p class="text-blue-100 text-lg max-w-2xl">
                    {{ __('app.comfortable_affordable') }} {{ __('app.ideal_living_space') }}
                </p>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white border-b shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <form id="filterForm" method="GET" action="{{ route('kost.index') }}"
                    class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">

                    <!-- Search -->
                    <div class="relative flex-1 min-w-0">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Cari nama kost atau alamat..."
                            value="{{ request('search') }}"
                            class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1593E6]/30 focus:border-[#1593E6] transition-all">
                    </div>

                    <!-- Category -->
                    <div class="relative md:w-48">
                        <select name="category"
                            class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1593E6]/30 focus:border-[#1593E6] transition-all appearance-none cursor-pointer"
                            onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="relative md:w-48">
                        <select name="sort"
                            class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1593E6]/30 focus:border-[#1593E6] transition-all appearance-none cursor-pointer"
                            onchange="this.form.submit()">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah → Tinggi</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi → Rendah</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit"
                        class="bg-[#1593E6] text-white px-5 py-2.5 rounded-xl hover:bg-[#0F7CC8] transition-all font-semibold text-sm shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>

                    <!-- Active Filters Info -->
                    @if(request('search') || request('category') || (request('sort') && request('sort') !== 'newest'))
                        <a href="{{ route('kost.index') }}"
                            class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1 whitespace-nowrap transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Results Info -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-900">{{ $kosts->total() }}</span> kost ditemukan
                    @if(request('search'))
                        untuk "<span class="font-medium text-[#1593E6]">{{ request('search') }}</span>"
                    @endif
                </p>
                @if(request('category'))
                    @php
                        $activeCategory = $categories->firstWhere('id', request('category'));
                    @endphp
                    @if($activeCategory)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $activeCategory->name }}
                        </span>
                    @endif
                @endif
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 pt-2">
            @if($kosts->count() > 0)
                <!-- Kost Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($kosts as $kost)
                        <a href="{{ route('kost.show', $kost) }}"
                            class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#1593E6]/20 hover:-translate-y-1">
                            <!-- Image -->
                            <div class="relative h-44 bg-gray-100 overflow-hidden">
                                @if($kost->primary_image)
                                    <img src="{{ $kost->primary_image->image_url }}" alt="{{ $kost->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Top badges -->
                                <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5">
                                    @foreach($kost->categories->take(2) as $category)
                                        <span class="bg-white/90 backdrop-blur-sm text-gray-700 text-[10px] px-2 py-0.5 rounded-md font-semibold shadow-sm">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Availability -->
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="bg-emerald-500 text-white text-[10px] px-2 py-0.5 rounded-md font-semibold shadow-sm">
                                        {{ $kost->available_rooms }} kamar
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="text-[15px] font-bold text-gray-900 mb-1 line-clamp-1 group-hover:text-[#1593E6] transition-colors">
                                    {{ $kost->name }}
                                </h3>

                                <div class="flex items-center gap-1 mb-3">
                                    <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xs text-gray-500 line-clamp-1">{{ $kost->address }}</span>
                                </div>

                                @if($kost->average_rating)
                                    <div class="flex items-center gap-1 mb-3">
                                        <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-700">{{ $kost->average_rating }}</span>
                                        <span class="text-xs text-gray-400">({{ $kost->reviews->count() }})</span>
                                    </div>
                                @endif

                                <div class="flex items-end justify-between pt-2 border-t border-gray-50">
                                    <div>
                                        <span class="text-lg font-extrabold text-[#1593E6]">
                                            Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium">/bulan</span>
                                    </div>
                                    <span class="text-xs text-[#1593E6] font-semibold group-hover:translate-x-0.5 transition-transform">
                                        Detail →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $kosts->withQueryString()->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto h-20 w-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak ada kost ditemukan</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        @if(request('search') || request('category'))
                            Coba ubah kata kunci pencarian atau filter kategori Anda.
                        @else
                            Belum ada kost yang tersedia saat ini.
                        @endif
                    </p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('kost.index') }}"
                            class="inline-flex items-center gap-2 bg-[#1593E6] text-white px-6 py-2.5 rounded-xl hover:bg-[#0F7CC8] transition-colors font-semibold text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

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