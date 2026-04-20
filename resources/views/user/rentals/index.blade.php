@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        @include('layouts.navigation')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Riwayat Pemesanan Saya</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($rentals as $rental)
                                <li>
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-16 w-16">
                                                    <img class="h-16 w-16 rounded-md object-cover"
                                                        src="{{ $rental->kost->primary_image->image_url ?? 'https://via.placeholder.com/150' }}"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-[#1593E6] truncate">
                                                        {{ $rental->kost->name }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <p>
                                                            Mulai: {{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}
                                                            ({{ $rental->duration_months }} Bulan)
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end space-y-2">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                            {{ $rental->status === 'approved' ? 'bg-green-100 text-green-800' :
                        ($rental->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($rental->status) }}
                                                </span>
                                                <p class="text-sm text-gray-900 font-bold">
                                                    Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Review Section -->
                                        @if($rental->status === 'approved')
                                            <div class="mt-4 border-t pt-4">
                                                @if($rental->review)
                                                    <div class="bg-gray-50 p-3 rounded-md">
                                                        <div class="flex items-center mb-1">
                                                            <span class="text-sm font-medium text-gray-700 mr-2">Ulasan Anda:</span>
                                                            <div class="flex text-yellow-400">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <svg class="w-4 h-4 {{ $i <= $rental->review->rating ? 'fill-current' : 'text-gray-300' }}"
                                                                        viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                    </svg>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <p class="text-sm text-gray-600">{{ $rental->review->comment }}</p>
                                                    </div>
                                                @else
                                                    <div x-data="{ open: false }">
                                                        <button @click="open = !open"
                                                            class="text-sm text-[#1593E6] hover:text-[#0F7CC8] font-medium focus:outline-none">
                                                            + Berikan Ulasan
                                                        </button>

                                                        <div x-show="open" class="mt-3">
                                                            <form action="{{ route('kost.review', $rental->kost) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="rental_id" value="{{ $rental->id }}">

                                                                <div class="mb-3">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                                                    <div class="flex space-x-2">
                                                                        @foreach([1, 2, 3, 4, 5] as $star)
                                                                            <label class="cursor-pointer">
                                                                                <input type="radio" name="rating" value="{{ $star }}"
                                                                                    class="sr-only peer" required>
                                                                                <svg class="w-6 h-6 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition-colors"
                                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                                    <path
                                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                                </svg>
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="comment"
                                                                        class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                                                                    <textarea name="comment" rows="3"
                                                                        class="shadow-sm focus:ring-[#1593E6] focus:border-[#1593E6] block w-full sm:text-sm border-gray-300 rounded-md"
                                                                        placeholder="Ceritakan pengalaman Anda..."></textarea>
                                                                </div>

                                                                <button type="submit"
                                                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#1593E6] hover:bg-[#0F7CC8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1593E6]">
                                                                    Kirim Ulasan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </li>
                    @empty
                        <li class="px-4 py-8 text-center text-gray-500">
                            Belum ada riwayat pemesanan.
                            <a href="{{ route('kost.index') }}" class="text-[#1593E6] hover:underline">Cari Kost Sekarang</a>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection