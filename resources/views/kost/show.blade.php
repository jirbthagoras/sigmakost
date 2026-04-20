@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('kost.index') }}"
                            class="text-gray-600 hover:text-[#1593E6] flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
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
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                    Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href="{{ route('rentals.index') }}"
                                    class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                    Lihat Pemesanan
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-[#1593E6] text-white hover:bg-[#0F7CC8] px-4 py-2 rounded-lg text-sm font-medium">
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
                                <img id="mainImage" src="{{ $kost->primary_image->image_url }}" alt="{{ $kost->name }}"
                                    class="w-full h-96 object-cover">

                            </div>
                            @if($kost->images->count() > 1)
                                <!-- Image Navigation -->
                                <div class="p-4 border-t">
                                    <div class="flex space-x-2 overflow-x-auto">
                                        @foreach($kost->images as $image)
                                            <button onclick="changeImage('{{ $image->image_url }}')"
                                                class="shrink-0 w-20 h-20 rounded-lg overflow-hidden border hover:ring-2 ring-[#1593E6] transition">
                                                <img src="{{ $image->image_url }}" alt="Thumbnail" class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="h-96 bg-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                            <svg class="h-5 w-5 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
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
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('app.facilities') }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_filter(array_map('trim', preg_split('/[,\n]+/', $kost->facilities))) as $facility)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ $facility }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Rules -->
                        @if($kost->rules)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('app.rules') }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_filter(array_map('trim', preg_split('/[,\n]+/', $kost->rules))) as $rule)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                            {{ $rule }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Section -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Ulasan</h3>
                            @if($kost->reviews->count() > 0)
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= round($kost->average_rating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $kost->average_rating }} / 5</span>
                                    <span class="text-sm text-gray-500">({{ $kost->reviews->count() }} ulasan)</span>
                                </div>
                            @endif
                        </div>

                        @if($kost->reviews->count() > 0)
                            <div class="space-y-4">
                                @foreach($kost->reviews->sortByDesc('created_at') as $review)
                                    <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-[#1593E6] rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $review->user->name }}</span>
                                                    <span class="text-xs text-gray-500 ml-2">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Belum ada ulasan untuk kost ini.</p>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ __('app.call_now') }}</span>
                                </a>

                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kost->contact_number) }}?text=Hi, I'm interested in {{ $kost->name }}"
                                    target="_blank"
                                    class="w-full bg-green-500 text-white text-center py-3 px-4 rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                    </svg>
                                    <span>{{ __('app.whatsapp') }}</span>
                                </a>
                            @endif

                            <!-- Booking Button -->
                            @auth
                                @if(auth()->user()->role !== 'admin')
                                    @if($existingRental && $existingRental->status === 'pending')
                                        <div class="mt-3 w-full bg-yellow-50 border border-yellow-200 text-yellow-800 text-center py-3 px-4 rounded-lg">
                                            <div class="flex items-center justify-center space-x-2 font-medium">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Pengajuan Sewa Menunggu Persetujuan</span>
                                            </div>
                                            <a href="{{ route('rentals.index') }}" class="text-sm text-yellow-600 underline hover:text-yellow-800 mt-1 inline-block">
                                                Lihat Pemesanan →
                                            </a>
                                        </div>
                                    @elseif($existingRental && $existingRental->status === 'approved')
                                        <div class="mt-3 w-full bg-green-50 border border-green-200 text-green-800 text-center py-3 px-4 rounded-lg">
                                            <div class="flex items-center justify-center space-x-2 font-medium">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Anda Sudah Menyewa Kost Ini</span>
                                            </div>
                                            <a href="{{ route('rentals.index') }}" class="text-sm text-green-600 underline hover:text-green-800 mt-1 inline-block">
                                                Lihat Pemesanan →
                                            </a>
                                        </div>
                                    @else
                                        <button onclick="openBookingModal()"
                                            class="w-full bg-[#1593E6] text-white text-center py-3 px-4 rounded-lg hover:bg-[#0F7CC8] transition-colors font-medium flex items-center justify-center space-x-2 mt-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ __('app.book_now') }}</span>
                                        </button>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full bg-[#1593E6] text-white text-center py-3 px-4 rounded-lg hover:bg-[#0F7CC8] transition-colors font-medium flex items-center justify-center space-x-2 mt-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    <span>{{ __('app.login') }} untuk Sewa</span>
                                </a>
                            @endauth
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
                            <div id="kostMap" class="h-64 rounded-lg overflow-hidden" data-lat="{{ $kost->latitude }}"
                                data-lng="{{ $kost->longitude }}" data-name="{{ $kost->name }}"></div>
                            <div class="mt-2 text-xs text-gray-500 text-center">
                                {{ __('app.coordinates') }}: {{ $kost->latitude }}, {{ $kost->longitude }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="relative z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeBookingModal()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form action="{{ route('kost.book', $kost) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        {{ __('app.book_now') }} - {{ $kost->name }}
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="start_date"
                                                class="block text-sm font-medium text-gray-700">{{ __('app.start_date') }}</label>
                                            <input type="date" name="start_date" id="start_date" required
                                                min="{{ date('Y-m-d') }}"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#1593E6] focus:border-[#1593E6] sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="duration_months"
                                                class="block text-sm font-medium text-gray-700">{{ __('app.duration') }}</label>
                                            <select name="duration_months" id="duration_months" required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#1593E6] focus:border-[#1593E6] sm:text-sm">
                                                @foreach([1, 3, 6, 12] as $month)
                                                    <option value="{{ $month }}">{{ $month }} Bulan</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="bg-blue-50 p-3 rounded-md">
                                            <p class="text-sm text-blue-700">
                                                Total: <span class="font-bold" id="totalPrice">Rp 0</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#1593E6] text-base font-medium text-white hover:bg-[#0F7CC8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1593E6] sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('app.book_now') }}
                            </button>
                            <button type="button" onclick="closeBookingModal()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        function openBookingModal() {
            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }

        function updateTotalPrice() {
            const pricePerMonth = {{ $kost->price_per_month }};
            const duration = document.getElementById('duration_months').value;
            const total = pricePerMonth * duration;
            document.getElementById('totalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        document.getElementById('duration_months').addEventListener('change', updateTotalPrice);
        // Initialize total price
        document.addEventListener('DOMContentLoaded', updateTotalPrice);

        function changeImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
        }
        document.addEventListener('DOMContentLoaded', function () {
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
                    setTimeout(function () { map.invalidateSize(); }, 200);
                }
            }
            updateTotalPrice(); // Ensure it runs on load
        });
    </script>
@endsection