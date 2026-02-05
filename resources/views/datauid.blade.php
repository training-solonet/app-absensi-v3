<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data UID</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            overflow-x: hidden; 
        }

        /* Desktop: sidebar visible. Mobile (<992px) overrides below */
        .sidebar {
            height: 100vh;
            background-color: #3F63E0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            transition: transform 0.25s ease, visibility 0.25s ease;
            z-index: 2000;
            transform: none;
            visibility: visible;
        }

        .sidebar.open { transform: translateX(0); visibility: visible; }

        .sidebar.collapsed { width: 70px !important; overflow: hidden; }
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .badge,
        .sidebar.collapsed .text-center img[alt="Logo"] { display: none !important; }

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

        .sidebar .nav-link:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .sidebar .nav-link i { font-size: 18px; color: inherit; }
        .sidebar.collapsed .nav-link { justify-content: center; }

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
            margin-left: 260px;
            padding: 20px;
            padding-top: 80px;
            transition: all 0.3s ease;
        }
        .content.collapsed { margin-left: 80px !important; }


                /* Header sits to the right of desktop sidebar */
                header.navbar {
                        background-color: #3F63E0;
                        position: fixed;
                        top: 0;
                        left: 240px;
                        right: 0;
                        height: 60px;
                        z-index: 1000; 
                        transition: none;
                        padding-left: 12px !important;
                        padding-right: 24px !important;
                }

                @media (min-width: 992px) {
                    header.navbar.collapsed { left: 70px; }
                }

        .header-toggle {
            background: transparent;
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-right: 8px;
            padding: 0;
            cursor: pointer;
            margin-left: -12px; /* nudge closer to left edge */
        }

        #header .container-fluid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap !important;
        }

        #header h5 {
            font-size: 1.1rem;
            color: #fff;
            margin: 0;
            white-space: nowrap;
        }

        #live-clock {
            color: #fff;
            white-space: nowrap;
            font-size: 0.9rem;
        }

    @media (max-width: 575.98px) {
            #header .container-fluid {
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: center !important;
                gap: 5px !important;
            }
            .content { margin-left: 0 !important; padding-top: 70px !important; }
            header.navbar { left: 0; }
            .sidebar { width: 80%; max-width: 320px; transform: translateX(-100%); visibility: hidden; }
            .sidebar.open { transform: translateX(0); visibility: visible; }
            #header h5 { font-size: 1rem; }
            #live-clock { font-size: 0.8rem; }
            #profileDropdown img { width: 36px; height: 36px; }
            .header-toggle { margin-left: -6px; }
        }

        /* Mobile/tablet: hide sidebar by default and show with .open */
        @media (max-width: 991.98px) {
            .content { margin-left: 0 !important; padding-top: 70px !important; }
            header.navbar { left: 0; }
            .toggle-btn { display: none; }
            .sidebar { width: 80%; max-width: 320px; transform: translateX(-100%); visibility: hidden; }
            .sidebar.open { transform: translateX(0); visibility: visible; }
            .overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 1950; display: none; }
            .overlay.show { display: block; }
        }

        .dt-container .dt-length label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .dt-container .dt-length select {
            margin-right: 6px;
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar p-3" id="sidebar">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" width="120">
        </div>

        @auth
        <div class="d-flex flex-column align-items-center text-center mb-4">
            <a href="{{ route('profile') }}" class="text-decoration-none">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" width="60" class="mb-2 rounded-circle">
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
                <a href="{{ route('data-uid') }}" class="nav-link {{ request()->is('data-uid*') ? 'active' : '' }}">
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
        <!-- floating desktop chevron toggle -->
        <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
        @endauth

        </div>

    <!-- Content -->
    <div class="content" id="content">
        <!-- Header -->
        <header class="navbar shadow-sm px-4" id="header">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @auth
                    <button id="mobileMenuBtn" class="header-toggle d-lg-none" aria-label="Toggle sidebar">
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                    @else
                    <button class="header-toggle d-lg-none" style="visibility: hidden;">
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                    @endauth
                    <h5 class="fw-bold text-light">Data UID</h5>
                </div>

                <div class="d-flex align-items-center">
                    @auth
                        <span id="live-clock" class="me-3"></span>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center" id="profileDropdown" 
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" 
                                   alt="Profile" width="40" height="40" class="rounded-circle border-2 border-primary">
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

        <!-- Table -->
        <div class="table-responsive mt-4">
          <table class="table table-bordered table-striped" id="uidTable">
              <thead style="background-color: #8DD8FF;">
                  <tr>
                      <th>UID</th>
                      <th>Nama</th>
                      <th style="width: 150px;">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($uids as $u)
                  <tr data-row-id="{{ $u->id }}">
                      <td class="cell-uid">{{ $u->uid ?? $u->uid_code ?? '-' }}</td>
                      <td class="cell-nama">{{ ucwords(strtolower($u->siswa->name ?? $u->nama ?? $u->name ?? '-')) }}</td>
                      <td>
                          <button 
                              type="button" 
                              class="btn btn-sm btn-outline-primary btn-edit-uid" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editUidModal"
                              data-id="{{ $u->id }}"
                              data-uid="{{ $u->uid ?? $u->uid_code ?? '' }}"
                              data-nama="{{ $u->siswa->name ?? $u->nama ?? $u->name ?? '' }}"
                              data-siswa-id="{{ $u->siswa->id ?? '' }}">
                              <i class="bi bi-pencil-square"></i> Edit
                          </button>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
        </div>
    </div>

    <!-- Edit UID Modal -->
    <div class="modal fade" id="editUidModal" tabindex="-1" aria-labelledby="editUidModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUidModalLabel">Edit Data UID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUidForm" method="POST" action="{{ route('uid.update-student', ['id' => '__ID__']) }}" onsubmit="this.action = this.action.replace('__ID__', document.getElementById('editUidId').value)">
                    @csrf
                    <input type="hidden" name="id" id="editUidId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editUidSiswa" class="form-label">Pilih Siswa</label>
                            <select class="form-select" id="editUidSiswa" name="siswa_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" data-uid="{{ $siswa->uid ?? '' }}">
                                        {{ $siswa->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUid" class="form-label">UID</label>
                            <input type="text" class="form-control" id="editUid" name="uid" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#uidTable').DataTable({
            order: [[1, 'asc']],
        });

        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const header = document.getElementById('header');
        const toggleBtn = document.getElementById('toggleBtn'); // desktop chevron
        const toggleIcon = toggleBtn ? toggleBtn.querySelector('i') : null;
        const mobileMenuBtn = document.getElementById('mobileMenuBtn'); // header hamburger
        const mobileIcon = mobileMenuBtn ? mobileMenuBtn.querySelector('i') : null;
        const overlay = document.getElementById('overlay');

        // desktop chevron toggles collapse
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
                header.classList.toggle('collapsed');
                if (toggleIcon) {
                    if (sidebar.classList.contains('collapsed')) toggleIcon.classList.replace('bi-chevron-left','bi-chevron-right');
                    else toggleIcon.classList.replace('bi-chevron-right','bi-chevron-left');
                }
            });
        }

        // mobile hamburger toggles overlay
        function openMobileSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            if (mobileIcon) { mobileIcon.classList.remove('bi-list'); mobileIcon.classList.add('bi-x'); }
        }
        function closeMobileSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            if (mobileIcon) { mobileIcon.classList.remove('bi-x'); mobileIcon.classList.add('bi-list'); }
        }
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar.classList.contains('open')) closeMobileSidebar(); else openMobileSidebar();
            });
        }

        // close on outside click
        document.addEventListener('click', function(ev) {
            const target = ev.target;
            if (sidebar.classList.contains('open') && !sidebar.contains(target) && !(mobileMenuBtn && mobileMenuBtn.contains(target))) {
                closeMobileSidebar();
            }
        });

        if (overlay) overlay.addEventListener('click', closeMobileSidebar);

        // reset on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                if (sidebar.classList.contains('open')) sidebar.classList.remove('open');
                if (overlay.classList.contains('show')) overlay.classList.remove('show');
                if (mobileIcon && mobileIcon.classList.contains('bi-x')) mobileIcon.classList.replace('bi-x','bi-list');
            }
        });

        // Handle edit button click
        $('.btn-edit-uid').on('click', function() {
            const id = $(this).data('id');
            const uid = $(this).data('uid');
            const nama = $(this).data('nama');
            const siswaId = $(this).data('siswa-id');
            
            $('#editUidId').val(id);
            $('#editUid').val(uid);
            
            // Set selected student in dropdown
            if (siswaId) {
                $('#editUidSiswa').val(siswaId);
            }
            
            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editUidModal'));
            editModal.show();
        });

        // When student is selected, update UID field if available
        $('#editUidSiswa').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const uid = selectedOption.data('uid');
            
            if (uid) {
                $('#editUid').val(uid);
            } else {
                $('#editUid').val('');
            }
        });
      });

      // Live Date
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
    </script>
</body>
</html>
