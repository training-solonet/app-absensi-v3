<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Input Absensi - UID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            background-color: #3F63E0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .sidebar.collapsed {
            width: 70px !important;
            overflow: hidden;
        }
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .badge,
        .sidebar.collapsed .text-center img[alt="Connectis Logo"] {
            display: none !important;
        }
        .sidebar .nav-link {
            font-weight: 600;
            color: #EAF2FF;
            margin: 4px 8px 10px 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 12px;
            padding: 10px 12px;
            position: relative;
        }
        .sidebar .nav-link i {
            font-size: 18px;
            color: inherit;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #FFFFFF;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.18);
            color: #FFFFFF;
        }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #F4D03F;
            border-radius: 2px;
        }
        .toggle-btn {
            position: absolute;
            top: 50%;
            right: -15px;
            transform: translateY(-50%);
            background: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1100;
        }
        .content {
            margin-left: 20px;
            padding: 20px;
            padding-top: 50px;
            transition: all 0.3s ease;
        }

        @auth
        .content {
            margin-left: 260px;
        }
        .content.collapsed {
            margin-left: 80px !important;
        }
        @endauth
        .content.collapsed {
            margin-left: 20px !important;
        }

        header.navbar {
            background-color: #3F63E0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 900;
            transition: all 0.3s ease;
            padding-left: 10px !important;
            padding-right: 20px !important;
        }

        @auth
        header.navbar {
            left: 240px;
        }
        header.navbar.collapsed {
            left: 70px;
        }
        @endauth
        header.navbar.collapsed {
            left: 70px;
        }

        #header .container-fluid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap !important;
        }

        #header .d-flex.align-items-center:first-child {
            margin-left: -5px;
            gap: 6px;
        }

        #header .d-flex.align-items-center:last-child {
            gap: 10px;
            padding-right: 6px;
        }

        #live-clock {
            white-space: nowrap;
            font-size: 0.9rem;
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .content {
                margin-left: 0 !important;
                padding-top: 70px;
            }
            header.navbar {
                left: 0 !important;
            }
            .toggle-btn {
                display: none;
            }
            .sidebar {
                width: 80%;
                max-width: 260px;
                transform: translateX(-100%);
                visibility: hidden;
            }
            .sidebar.open {
                transform: translateX(0);
                visibility: visible;
            }
        }

        .card-custom {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .uid-form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .uid-input-group {
            margin-bottom: 20px;
        }

        .uid-input {
            font-size: 18px;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .uid-input:focus {
            border-color: #3F63E0;
            box-shadow: 0 0 0 3px rgba(63, 99, 224, 0.1);
            outline: none;
        }

        .btn-submit {
            background-color: #3F63E0;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #2d4fa8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(63, 99, 224, 0.3);
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            animation: slideIn 0.3s ease-in-out;
        }

        .success-message.show {
            display: block;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            animation: slideIn 0.3s ease-in-out;
        }

        .error-message.show {
            display: block;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .attendance-info {
            background-color: #f0f4ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            display: none;
        }

        .attendance-info.show {
            display: block;
            animation: slideIn 0.3s ease-in-out;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e4ff;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #333;
            font-weight: 600;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 950;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        @media (max-width: 575.98px) {
            .uid-form-container {
                padding: 20px;
                margin: 15px;
            }
            #live-clock {
                font-size: 0.8rem;
                white-space: nowrap;
            }
            #header h5 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @auth
    <div class="sidebar p-3" id="sidebar">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png')}}" alt="Connectis Logo" width="120">
        </div>

        <div class="d-flex flex-column align-items-center text-center mb-4">
            <a href="{{ route('profile') }}" class="text-decoration-none">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     alt="Admin" width="60" class="mb-2 rounded-circle">
            </a>
            <div>
                <span class="badge bg-white text-dark">Administrator</span>
            </div>
        </div>
    @endauth

        <nav class="nav flex-column">
            @auth
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
            <a class="nav-link {{ request()->is('siswa*') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                <i class="bi bi-people-fill"></i> <span>Data Siswa</span>
            </a>
            <a class="nav-link {{ request()->is('absensi') || request()->is('absensi/') ? 'active' : '' }}" href="{{ url('/absensi') }}">
                <i class="bi bi-clipboard-check"></i> <span>Laporan Absensi</span>
            </a>
            <a class="nav-link {{ request()->is('data-uid*') ? 'active' : '' }}" href="{{ route('data-uid') }}">
                <i class="bi bi-credit-card-2-front"></i>
                <span>Data UID</span>
            </a>
            <a class="nav-link {{ request()->is('absensi-uid*') ? 'active' : '' }}" href="{{ route('absensi-uid') }}">
                <i class="bi bi-input-cursor"></i>
                <span>Input Absensi</span>
            </a>
            @else
            <a class="nav-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i> <span>Login</span>
            </a>
            @endauth
        </nav>

        @auth
        <button class="toggle-btn" id="toggleBtn">
            <i class="bi bi-chevron-left"></i>
        </button>
        @endauth
    </div>
    <div class="overlay" id="overlay"></div>

    <div class="content" id="content">
        <!-- Header -->
        <header class="navbar shadow-sm px-4" id="header">
            <div class="container-fluid d-flex justify-content-between align-items-center h-100">
                <div class="d-flex align-items-center">
                    @auth
                    <button class="btn btn-link text-white d-lg-none p-0" id="mobileMenuBtn" aria-label="Menu" style="margin-left:-6px;">
                        <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                    </button>
                    @endauth
                    <h5 class="fw-bold mb-0 text-light">Input Absensi - UID</h5>
                </div>

                <div class="d-flex align-items-center">
                    @auth
                    <span class="text-white me-3" id="live-clock"></span>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center" id="profileDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                 alt="Profile" width="42" height="42" class="rounded-circle border-2 border-primary">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger border-0 bg-transparent">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container mt-5">
            <div class="uid-form-container">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark mb-2">Input Absensi</h2>
                    <p class="text-muted">Silakan scan UID kartu siswa</p>
                </div>

                <div id="successMessage" class="success-message">
                    <i class="bi bi-check-circle me-2"></i>
                    <span id="successText">Data absensi berhasil disimpan!</span>
                </div>

                <div id="errorMessage" class="error-message">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <span id="errorText">Terjadi kesalahan saat menyimpan data.</span>
                </div>

                <form id="uidForm">
                    @csrf
                    <div class="uid-input-group">
                        <label for="uid" class="form-label fw-600 mb-2">UID Kartu</label>
                        <input 
                            type="text" 
                            id="uid" 
                            name="uid" 
                            class="form-control uid-input" 
                            placeholder="Tempelkan kartu UID ke scanner..."
                            autocomplete="off"
                            autofocus
                            required>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg me-2"></i>Submit
                    </button>
                </form>

                <div id="attendanceInfo" class="attendance-info">
                    <div class="info-row">
                        <span class="info-label">Nama Siswa:</span>
                        <span class="info-value" id="studentName">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value" id="attendanceDate">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Waktu Masuk:</span>
                        <span class="info-value" id="entryTime">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Waktu Keluar:</span>
                        <span class="info-value" id="exitTime">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        const header = document.getElementById("header");
        const toggleBtn = document.getElementById("toggleBtn");
        const icon = toggleBtn?.querySelector("i");
        const overlay = document.getElementById('overlay');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');

        function openMobileSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            if (mobileMenuBtn) {
                const mi = mobileMenuBtn.querySelector('i');
                if (mi) { mi.classList.remove('bi-list'); mi.classList.add('bi-x'); }
            }
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            if (mobileMenuBtn) {
                const mi = mobileMenuBtn.querySelector('i');
                if (mi) { mi.classList.remove('bi-x'); mi.classList.add('bi-list'); }
            }
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (window.innerWidth < 992) {
                    if (sidebar.classList.contains('open')) closeMobileSidebar();
                    else openMobileSidebar();
                }
            });
        }

        document.addEventListener('click', function(ev) {
            const target = ev.target;
            if (sidebar.classList.contains('open') && !sidebar.contains(target) && !(mobileMenuBtn && mobileMenuBtn.contains(target))) {
                closeMobileSidebar();
            }
        });

        if (overlay) overlay.addEventListener('click', closeMobileSidebar);

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
                header.classList.toggle('collapsed');
                if (icon) {
                    if (sidebar.classList.contains('collapsed')) icon.classList.replace('bi-chevron-left', 'bi-chevron-right');
                    else icon.classList.replace('bi-chevron-right','bi-chevron-left');
                }
            });
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                if (sidebar.classList.contains('open')) sidebar.classList.remove('open');
                if (overlay.classList.contains('show')) overlay.classList.remove('show');
                const mi = mobileMenuBtn?.querySelector('i');
                if (mi && mi.classList.contains('bi-x')) mi.classList.replace('bi-x','bi-list');
            }
        });

        // Update clock
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'numeric',
                day: 'numeric'
            };
            const tanggal = now.toLocaleDateString('id-ID', options);
            document.getElementById('live-clock').textContent = tanggal;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Handle form submission
        const uidForm = document.getElementById('uidForm');
        const uidInput = document.getElementById('uid');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const successText = document.getElementById('successText');
        const errorText = document.getElementById('errorText');
        const attendanceInfo = document.getElementById('attendanceInfo');

        uidForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const uid = uidInput.value.trim();
            if (!uid) {
                showError('Silakan masukkan UID');
                return;
            }

            try {
                const response = await fetch('{{ route("absensi-uid.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ uid: uid })
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    displayAttendanceInfo(data.data);
                    uidInput.value = '';
                    uidInput.focus();
                } else {
                    showError(data.message || 'Terjadi kesalahan saat menyimpan data');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan: ' + error.message);
            }
        });

        function showSuccess(message) {
            successText.textContent = message;
            successMessage.classList.add('show');
            errorMessage.classList.remove('show');
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
        }

        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.add('show');
            successMessage.classList.remove('show');
            setTimeout(() => {
                errorMessage.classList.remove('show');
            }, 5000);
        }

        function displayAttendanceInfo(data) {
            document.getElementById('studentName').textContent = data.nama_siswa || '-';
            document.getElementById('attendanceDate').textContent = data.tanggal || '-';
            document.getElementById('entryTime').textContent = data.waktu_masuk || '-';
            document.getElementById('exitTime').textContent = data.waktu_keluar || '-';
            attendanceInfo.classList.add('show');
        }
    </script>
</body>
</html>
