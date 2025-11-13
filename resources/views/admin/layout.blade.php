<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Panel SigmaKost</title>
    
    <!-- Preload the font for better performance -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" as="style">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"></noscript>
    <!-- Backup Font Awesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <style>
        * {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
        }
        
        body {
            font-weight: 400;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        .font-weight-bold, .fw-bold {
            font-weight: 700 !important;
        }
        
        .btn {
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        
        .card-header h6 {
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.025em;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
        }
        
        .page-title {
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        
        .table th {
            font-weight: 600;
        }
        
        .badge {
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .alert {
            font-weight: 500;
        }
        
        /* Ensure Font Awesome icons load properly */
        .fas, .far, .fab, .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 5 Free", "Font Awesome 5 Pro" !important;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
        }
        
        .fas {
            font-weight: 900;
        }
        
        .far {
            font-weight: 400;
        }
        
        /* Fallback for icon display issues */
        .fa-building::before { content: "\f1ad"; }
        .fa-check-circle::before { content: "\f058"; }
        .fa-bed::before { content: "\f236"; }
        .fa-door-open::before { content: "\f52b"; }
        .fa-tags::before { content: "\f02c"; }
        .fa-users::before { content: "\f0c0"; }
        .fa-plus::before { content: "\002b"; }
        .fa-list::before { content: "\f03a"; }
        .fa-eye::before { content: "\f06e"; }
        .fa-edit::before { content: "\f044"; }
        .fa-trash::before { content: "\f1f8"; }
        .fa-arrow-left::before { content: "\f060"; }
        .fa-save::before { content: "\f0c7"; }
        
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            border-radius: 0.25rem;
            margin: 0.1rem 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">SigmaKost</h4>
                        <small class="text-white">Admin Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                               href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-tags"></i>
                                Kategori
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kosts.*') ? 'active' : '' }}" 
                               href="{{ route('admin.kosts.index') }}">
                                <i class="fas fa-building"></i>
                                Kost
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="{{ route('home') }}">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title')</h1>
                    @yield('page-actions')
                </div>

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Reusable Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmModalBody">
                    <!-- Dynamic content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="confirmCancel">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmOK">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toastMessage" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
                <!-- Dynamic content -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global Admin Scripts -->
    <script>
        // Global CSRF token
        window.csrfToken = '{{ csrf_token() }}';
        
        // Reusable confirmation dialog
        function showConfirm(title, message, onConfirm, confirmText = 'OK', confirmClass = 'btn-primary') {
            document.getElementById('confirmModalLabel').textContent = title;
            document.getElementById('confirmModalBody').textContent = message;
            
            const confirmBtn = document.getElementById('confirmOK');
            confirmBtn.textContent = confirmText;
            confirmBtn.className = 'btn ' + confirmClass;
            
            // Remove any existing click listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            // Add new click listener
            newConfirmBtn.addEventListener('click', function() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                modal.hide();
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
            });
            
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }
        
        // Show toast notification
        function showToast(message, type = 'success', title = null) {
            const toastEl = document.getElementById('toastMessage');
            const titleEl = document.getElementById('toastTitle');
            const bodyEl = document.getElementById('toastBody');
            
            // Set content
            bodyEl.textContent = message;
            titleEl.textContent = title || (type === 'success' ? 'Berhasil' : 'Error');
            
            // Set color
            toastEl.className = `toast border-${type === 'success' ? 'success' : 'danger'}`;
            
            // Show toast
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
        
        // AJAX helper function
        function makeRequest(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            };
            
            if (data && (method === 'POST' || method === 'PATCH' || method === 'PUT')) {
                options.body = JSON.stringify(data);
            }
            
            return fetch(url, options)
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Respons server tidak valid. Silakan refresh halaman.');
                    }
                    
                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || 'Terjadi kesalahan pada server.');
                        }
                        return data;
                    });
                })
                .catch(error => {
                    console.error('Request error:', error);
                    throw error;
                });
        }
    </script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @stack('scripts')
</body>
</html>
