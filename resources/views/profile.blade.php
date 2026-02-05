<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f7fb;
    }

    .page-title {
      font-weight: 700;
      margin: 24px 0;
      color: #2b2f38;
    }

    .card-soft {
      background: #fff;
      border: none;
      border-radius: 14px;
      box-shadow: 0 4px 16px rgba(20, 22, 36, .06);
    }

    .card-section {
      margin-bottom: 18px;
    }

    .summary {
      padding: 20px;
    }

    .summary .avatar {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #e9eef8;
    }

    .summary .name {
      font-size: 1.05rem;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .summary .meta {
      color: #6b7280;
      font-size: .9rem;
    }

    .section-header {
      padding: 14px 16px;
      border-bottom: 1px solid #eef2f7;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .section-title {
      margin: 0;
      font-weight: 600;
      color: #334155;
    }

    .edit-btn {
      background: #ff8a3d;
      color: #fff;
      border: none;
      font-weight: 600;
      padding: 6px 10px;
      border-radius: 8px;
    }

    .info-grid {
      padding: 16px;
    }

    .info-label {
      color: #6b7280;
      font-size: .85rem;
      margin-bottom: 4px;
    }

    .info-value {
      color: #0f172a;
      font-weight: 600;
    }

    .sidebar {
      height: 100vh;
      background-color: #3F63E0;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
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

    .sidebar .nav-link:hover {
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
    }

    .sidebar .nav-link i {
      font-size: 18px;
      color: inherit;
    }

    .sidebar .nav-link.active {
      background: rgba(255, 255, 255, 0.18);
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
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1100;
    }

    .content {
      margin-left: 260px;
      padding: 20px;
      padding-top: 80px;
      transition: all 0.3s ease;
    }

    .content.collapsed {
      margin-left: 80px !important;
    }

    header.navbar {
      background-color: #3F63E0;
      position: fixed;
      top: 0;
      left: 240px;
      right: 0;
      height: 60px;
      z-index: 900;
      transition: all 0.3s ease;
      padding-right: 16px;
      display: flex;
      align-items: center;
    }

    header.navbar.collapsed {
      left: 70px;
    }

    #header .container-fluid {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: nowrap !important;
    }

    #live-clock {
      white-space: nowrap;
      font-size: 0.9rem;
      color: #fff;
      opacity: 0.95;
    }

    .dropdown img {
      border: 2px solid #fff;
      width: 40px;
      height: 40px;
      object-fit: cover;
      margin-left: 8px;
    }

    @media (max-width: 575.98px) {
      #header .container-fluid {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }

      #live-clock {
        font-size: 0.8rem;
        margin-right: 6px;
      }

      .dropdown img {
        width: 36px;
        height: 36px;
        margin-left: 10px;
      }
    }

    @media (max-width: 991.98px) {
      .content {
        margin-left: 0;
        padding-top: 70px;
      }

      header.navbar {
        left: 0;
      }

      .toggle-btn {
        display: none;
      }

      .sidebar {
        width: 80%;
        max-width: 260px;
        transform: translateX(-100%);
      }

      .sidebar.open {
        transform: translateX(0);
      }
      .overlay {
        display: none;
      }
    }
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.35);
      z-index: 1050;
      display: none;
      transition: opacity 0.2s ease;
    }

    .overlay.show {
      display: block;
      opacity: 1;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar p-3" id="sidebar">
    <div class="text-center mb-4">
      <img src="{{ asset('img/logo.png')}}" alt="Connectis Logo" width="120">
    </div>
    <div class="d-flex flex-column align-items-center text-center mb-4">
      <a href="{{ route('profile') }}" class="text-decoration-none">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" width="60"
          class="mb-2 rounded-circle">
      </a>
      <div>
        <span class="badge bg-white text-dark">Administrator</span>
      </div>
    </div>
    <nav class="nav flex-column">
  <a class="nav-link" href="/dashboard">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
      </a>
  <a class="nav-link requires-auth" href="{{ route('siswa.index') }}">
        <i class="bi bi-people-fill"></i> <span>Data Siswa</span>
      </a>
  <a class="nav-link requires-auth" href="{{ url('/absensi') }}">
        <i class="bi bi-clipboard-check"></i> <span>Laporan Absensi</span>
      </a>
  <a class="nav-link requires-auth" href="{{ route('data-uid') }}">
        <i class="bi bi-credit-card-2-front"></i> <span>Data UID</span>
      </a>
    </nav>
    <button class="toggle-btn" id="toggleBtn">
      <i class="bi bi-chevron-left"></i>
    </button>
  </div>

  <div id="overlay" class="overlay"></div>

  <!-- Content -->
  <div class="content" id="content">
    <!-- Header -->
    <header class="navbar shadow-sm px-4" id="header">
      <div class="container-fluid d-flex justify-content-between align-items-center h-100">
        <div class="d-flex align-items-center">
          <button class="btn btn-link text-white d-lg-none me-2 p-0" id="mobileMenuBtn" aria-label="Menu">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
          </button>
          <h5 class="fw-bold mb-0 text-light">Profil</h5>
        </div>
        <div class="d-flex align-items-center">
          <span class="text-white me-3" id="live-clock"></span>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" width="40" height="40"
                class="rounded-circle border-2 border-primary">
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
              @auth
              <li>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit"
                    class="dropdown-item d-flex align-items-center text-danger border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                  </button>
                </form>
              </li>
              @endauth
              @guest
              <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('login') }}">
                  <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </a>
              </li>
              @endguest
            </ul>
          </div>
        </div>
      </div>
    </header>

    <!-- Profile Content -->
    <div class="card-soft summary card-section">
      <div class="d-flex align-items-center gap-3">
        <img class="avatar" src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar">
        <div class="flex-grow-1">
          <div class="name">Administrator</div>
          <div class="meta">Admin • Solo, Indonesia</div>
          <div class="meta">{{ session('user_email', 'admin@gmail.com') }}</div>
        </div>
      </div>
    </div>

    <!-- Personal Information -->
    <div class="card-soft card-section">
      <div class="section-header">
        <h6 class="section-title mb-0">Personal Information</h6>
        <button class="edit-btn"><i class="bi bi-pencil-square"></i> Edit</button>
      </div>
      <div class="info-grid">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="info-label">First Name</div>
            <div class="info-value">Admin</div>
          </div>
          <div class="col-md-4">
            <div class="info-label">Last Name</div>
            <div class="info-value">User</div>
          </div>
          <div class="col-md-4">
            <div class="info-label">Email Address</div>
            <div class="info-value">{{ session('user_email', 'admin@gmail.com') }}</div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      const sidebar = document.getElementById("sidebar");
      const content = document.getElementById("content");
      const header = document.getElementById("header");
      const toggleBtn = document.getElementById("toggleBtn");
      const icon = toggleBtn ? toggleBtn.querySelector("i") : null;
      const mobileMenuBtn = document.getElementById("mobileMenuBtn");
  const overlay = document.getElementById('overlay');
  const mobileIcon = mobileMenuBtn ? mobileMenuBtn.querySelector('i') : null;

      if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
          sidebar.classList.toggle("collapsed");
          content.classList.toggle("collapsed");
          header.classList.toggle("collapsed");
          if (icon) {
            if (sidebar.classList.contains("collapsed")) {
              icon.classList.replace("bi-chevron-left", "bi-chevron-right");
            } else {
              icon.classList.replace("bi-chevron-right", "bi-chevron-left");
            }
          }
        });
      }

      if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          const isOpen = sidebar.classList.contains('open');
          if (isOpen) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            if (mobileIcon) mobileIcon.classList.replace('bi-x', 'bi-list');
          } else {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            if (mobileIcon) mobileIcon.classList.replace('bi-list', 'bi-x');
          }
        });
      }

      if (overlay) {
        overlay.addEventListener('click', () => {
          sidebar.classList.remove('open');
          overlay.classList.remove('show');
          if (mobileIcon) mobileIcon.classList.replace('bi-x', 'bi-list');
        });
      }

      document.addEventListener('click', (e) => {
        const target = e.target;
        if (window.innerWidth < 992) {
          if (!sidebar.contains(target) && !mobileMenuBtn.contains(target)) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            if (mobileIcon) mobileIcon.classList.replace('bi-x', 'bi-list');
          }
        }
      });

      window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
          sidebar.classList.remove('open');
          overlay.classList.remove('show');
          if (mobileIcon) mobileIcon.classList.replace('bi-x', 'bi-list');
        }
      });

      function updateClock() {
        const now = new Date();
        const tanggal = now.toLocaleDateString('id-ID', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
        const jam = now.toLocaleTimeString('id-ID', {
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit'
        });
        const el = document.getElementById('live-clock');
        if (el) el.textContent = `${tanggal} | ${jam}`;
      }
      setInterval(updateClock, 1000);
      updateClock();
    </script>
    <!-- Login confirm modal -->
    <div class="modal fade" id="loginConfirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Perlu Login</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Anda harus login untuk mengakses halaman ini. Ingin ke halaman login sekarang?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
            <button type="button" id="confirmLoginBtn" class="btn btn-primary">Ya, ke Login</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
      const requiresAuthLinks = document.querySelectorAll('.requires-auth');
      const loginModalEl = document.getElementById('loginConfirmModal');
      const loginModal = loginModalEl ? new bootstrap.Modal(loginModalEl) : null;
      const confirmLoginBtn = document.getElementById('confirmLoginBtn');

      requiresAuthLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          if (!isAuthenticated) {
            e.preventDefault();
            if (loginModal) loginModal.show();
            loginModalEl.dataset.targetHref = this.href;
          }
        });
      });

      if (confirmLoginBtn) {
        confirmLoginBtn.addEventListener('click', function() {
          window.location.href = '{{ route("login") }}';
        });
      }
    </script>
</body>
</html>
