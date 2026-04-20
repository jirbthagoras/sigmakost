{{-- Admin Popup Components (Bootstrap-compatible) --}}

{{-- Confirmation Modal using Bootstrap --}}
<div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div id="adminConfirmIcon" class="mx-auto mb-3"
                    style="width:56px;height:56px;border-radius:50%;background:#FEF3C7;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-exclamation-triangle fa-lg text-warning"></i>
                </div>
                <h5 id="adminConfirmTitle" class="fw-bold mb-2">Konfirmasi</h5>
                <p id="adminConfirmMessage" class="text-muted mb-0">Apakah Anda yakin?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                    id="adminConfirmCancel">Batal</button>
                <button type="button" class="btn btn-primary px-4" id="adminConfirmOk">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div id="adminToastContainer" class="position-fixed top-0 end-0 p-3" style="z-index:9999"></div>

<script>
    // ─── Admin Confirm Modal ─────────────────────────────────
    window.showConfirm = function ({ title, message, confirmText, cancelText, variant, onConfirm }) {
        const modalEl = document.getElementById('globalConfirmModal');
        const modal = new bootstrap.Modal(modalEl);
        const btn = document.getElementById('adminConfirmOk');
        const icon = document.getElementById('adminConfirmIcon');

        document.getElementById('adminConfirmTitle').textContent = title || 'Konfirmasi';
        document.getElementById('adminConfirmMessage').textContent = message || 'Apakah Anda yakin?';
        btn.textContent = confirmText || 'Konfirmasi';
        document.getElementById('adminConfirmCancel').textContent = cancelText || 'Batal';

        // Variant
        const v = variant || 'warning';
        const variants = {
            warning: { bg: '#FEF3C7', icon: 'fa-exclamation-triangle text-warning', btn: 'btn-warning' },
            danger: { bg: '#FEE2E2', icon: 'fa-times-circle text-danger', btn: 'btn-danger' },
            info: { bg: '#DBEAFE', icon: 'fa-info-circle text-primary', btn: 'btn-primary' },
            success: { bg: '#D1FAE5', icon: 'fa-check-circle text-success', btn: 'btn-success' },
        };
        const c = variants[v] || variants.warning;
        icon.style.background = c.bg;
        icon.innerHTML = `<i class="fas ${c.icon} fa-lg"></i>`;
        btn.className = `btn ${c.btn} px-4`;

        // Clean up previous listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        newBtn.id = 'adminConfirmOk';

        newBtn.addEventListener('click', function () {
            modal.hide();
            if (onConfirm) onConfirm();
        });

        modal.show();
    };

    // ─── Admin Toast Notifications ───────────────────────────
    window.showToast = function ({ message, type, duration }) {
        const container = document.getElementById('adminToastContainer');
        const dur = duration || 4000;

        const colors = {
            success: { bg: 'bg-success', icon: 'fa-check-circle' },
            error: { bg: 'bg-danger', icon: 'fa-times-circle' },
            warning: { bg: 'bg-warning', icon: 'fa-exclamation-triangle' },
            info: { bg: 'bg-primary', icon: 'fa-info-circle' },
        };
        const c = colors[type] || colors.info;

        const toast = document.createElement('div');
        toast.className = `toast show align-items-center text-white ${c.bg} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="fas ${c.icon}"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.toast').remove()"></button>
            </div>
        `;

        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.style.transition = 'opacity 0.3s';
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