@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
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

    <!-- Header Section -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('app.find_perfect_kost') }}</h1>
                <p class="text-lg text-gray-600">{{ __('app.comfortable_affordable') }} {{ __('app.ideal_living_space') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white border-b shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="searchInput" 
                               placeholder="{{ __('app.search_placeholder') }}" 
                               value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-[#1593E6] focus:border-[#1593E6] sm:text-sm">
                    </div>

                    <!-- Category Filter -->
                    <div class="relative">
                        <select id="categoryFilter" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1593E6] focus:border-[#1593E6] sm:text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <button id="filterBtn" 
                            class="bg-[#1593E6] text-white px-6 py-2 rounded-lg hover:bg-[#0F7CC8] transition-colors font-medium">
                        Apply Filters
                    </button>

                    <!-- Clear Filters -->
                    @if(request('search') || request('category'))
                        <a href="{{ route('kost.index') }}" 
                           class="text-gray-600 hover:text-[#1593E6] px-3 py-2 text-sm font-medium underline">
                            Clear Filters
                        </a>
                    @endif
                </div>

                <!-- Results Count -->
                <div class="text-sm text-gray-600">
                    {{ $kosts->total() }} {{ Str::plural('result', $kosts->total()) }} found
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($kosts->count() > 0)
            <!-- Kost Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($kosts as $kost)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-200">
                        <!-- Image -->
                        <div class="relative h-48 bg-gray-200">
                            @if($kost->primary_image)
                                <img src="{{ $kost->primary_image->image_url }}" 
                                     alt="{{ $kost->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No Image</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Availability Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    {{ $kost->available_rooms }} {{ Str::plural('room', $kost->available_rooms) }} available
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <!-- Categories -->
                            @if($kost->categories->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($kost->categories as $category)
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Name -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $kost->name }}
                            </h3>

                            <!-- Address -->
                            <div class="flex items-start space-x-2 mb-3">
                                <svg class="h-4 w-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm text-gray-600 line-clamp-2">{{ $kost->address }}</span>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center justify-between">
                                <div class="text-xl font-bold text-[#1593E6]">
                                    Rp {{ number_format($kost->price_per_month, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-gray-500">/month</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-4">
                                <a href="{{ route('kost.show', $kost) }}" 
                                   class="w-full bg-[#1593E6] text-white text-center py-2 px-4 rounded-lg hover:bg-[#0F7CC8] transition-colors font-medium block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $kosts->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No kost found</h3>
                <p class="text-gray-600 mb-4">
                    @if(request('search') || request('category'))
                        No kost match your current filters. Try adjusting your search criteria.
                    @else
                        There are no available kost at the moment. Please check back later.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('kost.index') }}" 
                       class="bg-[#1593E6] text-white px-6 py-2 rounded-lg hover:bg-[#0F7CC8] transition-colors font-medium">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const filterBtn = document.getElementById('filterBtn');

    function applyFilters() {
        const searchValue = searchInput.value.trim();
        const categoryValue = categoryFilter.value;
        
        const url = new URL(window.location.href);
        url.search = ''; // Clear existing parameters
        
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        }
        
        if (categoryValue) {
            url.searchParams.set('category', categoryValue);
        }
        
        window.location.href = url.toString();
    }

    filterBtn.addEventListener('click', applyFilters);

    // Allow Enter key to trigger search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    // Auto-apply category filter on change
    categoryFilter.addEventListener('change', function() {
        applyFilters();
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
