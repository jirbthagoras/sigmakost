{{-- Reusable Confirmation Modal --}}
{{-- Usage: Include once in layout, then call window.showConfirm({title, message, confirmText, cancelText, onConfirm})
--}}
<div id="confirmModal" class="fixed inset-0 z-[9999] hidden">
    {{-- Backdrop --}}
    <div id="confirmBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

    {{-- Dialog --}}
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="confirmDialog"
            class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0">

            {{-- Icon --}}
            <div id="confirmIcon"
                class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-amber-100 mb-4">
                <svg class="h-7 w-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.07 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>

            {{-- Content --}}
            <h3 id="confirmTitle" class="text-lg font-bold text-gray-900 text-center mb-2">Konfirmasi</h3>
            <p id="confirmMessage" class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin?</p>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button id="confirmCancel"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button id="confirmOk"
                    class="flex-1 px-4 py-2.5 bg-[#1593E6] text-white rounded-xl font-semibold text-sm hover:bg-[#0F7CC8] transition-colors">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Reusable Toast Notification --}}
{{-- Usage: window.showToast({message, type: 'success'|'error'|'warning'|'info', duration}) --}}
<div id="toastContainer" class="fixed top-4 right-4 z-[9998] flex flex-col gap-2 pointer-events-none"></div>

<script>
    // ─── Confirmation Modal ──────────────────────────────────
    window.showConfirm = function ({ title, message, confirmText, cancelText, variant, onConfirm }) {
        const modal = document.getElementById('confirmModal');
        const dialog = document.getElementById('confirmDialog');
        const btn = document.getElementById('confirmOk');

        document.getElementById('confirmTitle').textContent = title || 'Konfirmasi';
        document.getElementById('confirmMessage').textContent = message || 'Apakah Anda yakin?';
        btn.textContent = confirmText || 'Konfirmasi';
        document.getElementById('confirmCancel').textContent = cancelText || 'Batal';

        // Variant colors
        const v = variant || 'warning';
        const colors = {
            warning: { bg: 'bg-amber-100', text: 'text-amber-600', btn: 'bg-amber-500 hover:bg-amber-600' },
            danger: { bg: 'bg-red-100', text: 'text-red-600', btn: 'bg-red-500 hover:bg-red-600' },
            info: { bg: 'bg-blue-100', text: 'text-blue-600', btn: 'bg-[#1593E6] hover:bg-[#0F7CC8]' },
            success: { bg: 'bg-green-100', text: 'text-green-600', btn: 'bg-green-500 hover:bg-green-600' },
        };
        const c = colors[v] || colors.warning;
        const icon = document.getElementById('confirmIcon');
        icon.className = `mx-auto flex items-center justify-center h-14 w-14 rounded-full ${c.bg} mb-4`;
        icon.querySelector('svg').className = `h-7 w-7 ${c.text}`;
        btn.className = `flex-1 px-4 py-2.5 ${c.btn} text-white rounded-xl font-semibold text-sm transition-colors`;

        // Show
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            dialog.classList.remove('scale-95', 'opacity-0');
            dialog.classList.add('scale-100', 'opacity-100');
        });

        // Handlers
        function close() {
            dialog.classList.remove('scale-100', 'opacity-100');
            dialog.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
            cleanup();
        }

        function confirm() {
            close();
            if (onConfirm) onConfirm();
        }

        function cleanup() {
            document.getElementById('confirmOk').removeEventListener('click', confirm);
            document.getElementById('confirmCancel').removeEventListener('click', close);
            document.getElementById('confirmBackdrop').removeEventListener('click', close);
        }

        document.getElementById('confirmOk').addEventListener('click', confirm);
        document.getElementById('confirmCancel').addEventListener('click', close);
        document.getElementById('confirmBackdrop').addEventListener('click', close);
    };

    // ─── Toast Notifications ─────────────────────────────────
    window.showToast = function ({ message, type, duration }) {
        const container = document.getElementById('toastContainer');
        const dur = duration || 4000;

        const icons = {
            success: `<svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`,
            error: `<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`,
            warning: `<svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.07 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`,
            info: `<svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
        };

        const bgColors = {
            success: 'bg-green-50 border-green-200',
            error: 'bg-red-50 border-red-200',
            warning: 'bg-amber-50 border-amber-200',
            info: 'bg-blue-50 border-blue-200',
        };

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg ${bgColors[type] || bgColors.info} transform translate-x-full transition-transform duration-300`;
        toast.innerHTML = `
            ${icons[type] || icons.info}
            <span class="text-sm font-medium text-gray-800 flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 ml-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-full'));

        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, dur);
    };

    // Auto-show toasts for Laravel flash messages
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            window.showToast({ message: @json(session('success')), type: 'success' });
        @endif
        @if(session('error'))
            window.showToast({ message: @json(session('error')), type: 'error' });
        @endif
    });
</script>