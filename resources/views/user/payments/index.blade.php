@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        @include('layouts.navigation')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Pembayaran Saya</h1>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @forelse($rentals as $rental)
                <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                    {{-- Rental Header --}}
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

                    {{-- Payment List --}}
                    <div class="divide-y divide-gray-100">
                        @foreach($rental->payments as $payment)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
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
                                        @if($payment->status === 'unpaid' || $payment->isOverdue())
                                            <button type="button"
                                                onclick="openPayModal({{ $payment->id }}, '{{ $payment->period_label }}', 'Rp {{ number_format($payment->amount, 0, ',', '.') }}')"
                                                class="inline-flex items-center px-4 py-2 bg-[#1593E6] text-white text-sm font-medium rounded-lg hover:bg-[#0F7CC8] transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                Upload Bukti
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- Show uploaded proof info for paid status --}}
                                @if($payment->status === 'paid' && $payment->payment_proof)
                                    <div class="mt-3 ml-14 flex items-center gap-2 text-xs text-gray-500">
                                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Bukti telah diunggah
                                        <span class="text-gray-400">•</span>
                                        <span class="capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                                        @if($payment->paid_date)
                                            <span class="text-gray-400">•</span>
                                            {{ $payment->paid_date->format('d M Y H:i') }}
                                        @endif
                                    </div>
                                @endif
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

    {{-- Payment Upload Modal --}}
    <div id="payModal" class="fixed inset-0 z-[9990] hidden">
        <div id="payBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="payDialog" class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 transform transition-all scale-95 opacity-0">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900">Upload Bukti Pembayaran</h3>
                    <button onclick="closePayModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Payment Info --}}
                <div class="bg-blue-50 rounded-xl p-4 mb-5">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold" id="payPeriodLabel"></span>
                        <br>
                        <span class="text-lg font-extrabold" id="payAmountLabel"></span>
                    </p>
                </div>

                <form id="payForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Payment Method --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer_bank" class="sr-only peer" required>
                                <div class="flex flex-col items-center gap-1 p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#1593E6] peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                    <svg class="w-6 h-6 text-gray-400 peer-checked:text-[#1593E6]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-600">Transfer Bank</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="e_wallet" class="sr-only peer">
                                <div class="flex flex-col items-center gap-1 p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#1593E6] peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-600">E-Wallet</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="cash" class="sr-only peer">
                                <div class="flex flex-col items-center gap-1 p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#1593E6] peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-600">Cash</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- File Upload --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bukti Pembayaran</label>
                        <div id="dropZone"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#1593E6] transition-colors cursor-pointer">
                            <input type="file" name="payment_proof" id="proofFile" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required>
                            <div id="dropZoneContent">
                                <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm text-gray-600 font-medium">Klik atau seret file ke sini</p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau PDF (maks. 5MB)</p>
                            </div>
                            <div id="filePreview" class="hidden">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span id="fileName" class="text-sm font-medium text-gray-700"></span>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-xs text-red-500 hover:text-red-700 mt-1">Hapus file</button>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3">
                        <button type="button" onclick="closePayModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="button" onclick="submitPayment()"
                            class="flex-1 px-4 py-2.5 bg-[#1593E6] text-white rounded-xl font-semibold text-sm hover:bg-[#0F7CC8] transition-colors">
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPayModal(paymentId, period, amount) {
            const modal = document.getElementById('payModal');
            const dialog = document.getElementById('payDialog');
            const form = document.getElementById('payForm');

            form.action = '/payments/' + paymentId + '/pay';
            document.getElementById('payPeriodLabel').textContent = period;
            document.getElementById('payAmountLabel').textContent = amount;

            // Reset form
            form.reset();
            clearFile();

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                dialog.classList.remove('scale-95', 'opacity-0');
                dialog.classList.add('scale-100', 'opacity-100');
            });
        }

        function closePayModal() {
            const dialog = document.getElementById('payDialog');
            dialog.classList.remove('scale-100', 'opacity-100');
            dialog.classList.add('scale-95', 'opacity-0');
            setTimeout(() => document.getElementById('payModal').classList.add('hidden'), 200);
        }

        function submitPayment() {
            const form = document.getElementById('payForm');
            const file = document.getElementById('proofFile');
            const method = form.querySelector('input[name="payment_method"]:checked');

            if (!method) {
                window.showToast({ message: 'Pilih metode pembayaran terlebih dahulu.', type: 'warning' });
                return;
            }
            if (!file.files.length) {
                window.showToast({ message: 'Upload bukti pembayaran terlebih dahulu.', type: 'warning' });
                return;
            }

            const amount = document.getElementById('payAmountLabel').textContent;
            const period = document.getElementById('payPeriodLabel').textContent;

            window.showConfirm({
                title: 'Konfirmasi Pembayaran',
                message: `Kirim bukti pembayaran ${amount} untuk ${period}?`,
                confirmText: 'Ya, Kirim',
                variant: 'info',
                onConfirm: () => form.submit()
            });
        }

        // File drop zone
        const dropZone = document.getElementById('dropZone');
        const proofFile = document.getElementById('proofFile');

        dropZone.addEventListener('click', () => proofFile.click());
        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-[#1593E6]', 'bg-blue-50'); });
        dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-[#1593E6]', 'bg-blue-50'); });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-[#1593E6]', 'bg-blue-50');
            if (e.dataTransfer.files.length) {
                proofFile.files = e.dataTransfer.files;
                showFilePreview(e.dataTransfer.files[0]);
            }
        });

        proofFile.addEventListener('change', (e) => {
            if (e.target.files.length) showFilePreview(e.target.files[0]);
        });

        function showFilePreview(file) {
            document.getElementById('dropZoneContent').classList.add('hidden');
            document.getElementById('filePreview').classList.remove('hidden');
            document.getElementById('fileName').textContent = file.name;
        }

        function clearFile() {
            proofFile.value = '';
            document.getElementById('dropZoneContent').classList.remove('hidden');
            document.getElementById('filePreview').classList.add('hidden');
        }

        // Close modal on backdrop click
        document.getElementById('payBackdrop').addEventListener('click', closePayModal);
    </script>
@endsection
