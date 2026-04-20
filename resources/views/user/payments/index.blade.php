@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        @include('layouts.navigation')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Pembayaran Saya</h1>

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

            @forelse($rentals as $rental)
                <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                    <!-- Rental Header -->
                    <div class="bg-gradient-to-r from-[#1593E6] to-[#0F7CC8] px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden">
                                    <img class="h-12 w-12 object-cover"
                                        src="{{ $rental->kost->primary_image->image_url ?? 'https://via.placeholder.com/150' }}"
                                        alt="">
                                </div>
                                <div class="text-white">
                                    <h2 class="text-lg font-bold">{{ $rental->kost->name }}</h2>
                                    <p class="text-blue-100 text-sm">
                                        {{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }} — {{ $rental->duration_months }} Bulan
                                    </p>
                                </div>
                            </div>
                            <div class="text-right text-white">
                                <p class="text-sm text-blue-100">Total</p>
                                <p class="font-bold text-lg">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment List -->
                    <div class="divide-y divide-gray-100">
                        @foreach($rental->payments as $payment)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        {{ $payment->status === 'verified' ? 'bg-green-100' : ($payment->status === 'paid' ? 'bg-yellow-100' : ($payment->isOverdue() ? 'bg-red-100' : 'bg-gray-100')) }}">
                                        @if($payment->status === 'verified')
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @elseif($payment->status === 'paid')
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 {{ $payment->isOverdue() ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $payment->period_label }}</p>
                                        <p class="text-xs text-gray-500">
                                            Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                            @if($payment->isOverdue())
                                                <span class="text-red-600 font-medium"> — Terlambat</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        @if($payment->status === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Terverifikasi
                                            </span>
                                        @elseif($payment->status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($payment->isOverdue())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                    @if($payment->status === 'unpaid')
                                        <form action="{{ route('payments.pay', $payment) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Konfirmasi pembayaran Rp {{ number_format($payment->amount, 0, ',', '.') }} untuk {{ $payment->period_label }}?')"
                                                class="inline-flex items-center px-4 py-2 bg-[#1593E6] text-white text-sm font-medium rounded-lg hover:bg-[#0F7CC8] transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Bayar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Pembayaran</h3>
                    <p class="mt-1 text-sm text-gray-500">Pembayaran akan muncul setelah pengajuan sewa Anda disetujui.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
