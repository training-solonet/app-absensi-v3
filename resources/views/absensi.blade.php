<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Absensi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" rel="stylesheet">

  <style>
.toast-center {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1100;
  min-width: 300px;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
}
.toast-center.show { opacity: 1; }

body {
  font-family: 'Poppins', sans-serif;
  background-color: #f8f9fa;
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
.sidebar.collapsed { width: 70px !important; overflow: hidden; }
.sidebar.collapsed .nav-link span,
.sidebar.collapsed .badge,
.sidebar.collapsed .text-center img[alt="Connectis Logo"] { display: none !important; }

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
  background: rgba(255,255,255,0.12);
  color: #fff;
}
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

header.navbar {
  background-color: #3F63E0;
  position: fixed;
  top: 0;
  left: 240px;
  right: 0;
  height: 60px;
  z-index: 900;
  transition: all 0.3s ease;
  padding-left: 12px !important;
  padding-right: 24px !important; 
}
header.navbar.collapsed { left: 70px; }

#header .container-fluid {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap !important;
  gap: 10px;
  padding-left: 0 !important;
  padding-right: 0 !important;
}

#header h5 {
  color: #fff;
  line-height: 1.1;
  margin-bottom: 0;
}
#header h5 span {
  display: block;
  font-weight: 700;
  font-size: 1.1rem;
}
#header h5 small {
  display: block;
  font-size: 0.95rem;
  font-weight: 600;
}

#profileDropdown img {
  width: 42px;
  height: 42px;
  object-fit: cover;
  border: 2px solid #fff;
  border-radius: 50%;
}
#live-clock { margin-right: 12px !important; }

@media (max-width: 575.98px) {
  header.navbar .dropdown { margin-right: 15px !important; }
  #profileDropdown img { width: 36px; height: 36px; }
  #header h5 span { font-size: 1rem; }
  #header h5 small { font-size: 0.9rem; }
  #live-clock { font-size: 0.8rem; }
}

.card-custom {
  border-radius: 12px;
  border: none;
  box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.35);
  z-index: 950;
  display: none;
}
.overlay.show { display: block; }

@media (max-width: 991.98px) {
  .content { margin-left: 0; padding-top: 70px; }
  header.navbar { left: 0; }
  .toggle-btn { display: none; }
  .sidebar {
    width: 80%;
    max-width: 260px;
    transform: translateX(-100%);
  }
  .sidebar.open { transform: translateX(0); }
}

.dt-container .dt-length label {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.dt-container .dt-length select { margin-right: 6px; }
</style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar p-3" id="sidebar">
    <div class="text-center mb-4">
      <img src="{{ asset('img/logo.png') }}" alt="Connectis Logo" width="120">
    </div>
    <div class="d-flex flex-column align-items-center text-center mb-4">
      <a href="{{ route('profile') }}" class="text-decoration-none">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" width="60" class="mb-2 rounded-circle">
      </a>
      <div><span class="badge bg-white text-dark">Administrator</span></div>
    </div>
    <nav class="nav flex-column">
      <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
      </a>
      <a class="nav-link {{ request()->is('siswa') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
        <i class="bi bi-people-fill"></i> <span>Data Siswa</span>
      </a>
      <a class="nav-link {{ request()->is('absensi') ? 'active' : '' }}" href="{{ url('/absensi') }}">
        <i class="bi bi-clipboard-check"></i> <span>Laporan Absensi</span>
      </a>
      <a href="{{ route('data-uid') }}" class="nav-link">
        <i class="bi bi-credit-card-2-front"></i> <span>Data UID</span>
      </a>
      <a class="nav-link {{ request()->is('absensi-uid*') ? 'active' : '' }}" href="{{ route('absensi-uid') }}">
        <i class="bi bi-input-cursor"></i>
        <span>Input Absensi</span>
      </a>
    </nav>
    <button class="toggle-btn" id="toggleBtn"><i class="bi bi-chevron-left"></i></button>
  </div>
  <div class="overlay" id="overlay"></div>

  <!-- Content -->
  <div class="content" id="content">
    <header class="navbar shadow-sm" id="header">
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <button class="btn btn-link text-white d-lg-none me-2 p-0" id="mobileMenuBtn">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
          </button>
          <h5 class="fw-bold mb-0 text-light">
            <span>Laporan</span>
            <small>Absensi</small>
          </h5>
        </div>

        <div class="d-flex align-items-center">
          <span class="text-white me-3" id="live-clock"></span>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown">
              <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" class="rounded-circle">
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item d-flex align-items-center text-danger border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <!-- Card Laporan Absensi -->
    <div class="card card-custom mt-4 p-3">
      <div class="card-body">
        <!-- Filter: Tanggal Awal & Tanggal Akhir -->
        <form id="filterForm" method="GET" action="{{ route('absensi') }}" class="row g-3 mb-4">
          <div class="col-md-3">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ $tanggalAwal ?? now()->toDateString() }}">
          </div>
          <div class="col-md-3">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir ?? now()->toDateString() }}">
          </div>
          <div class="col-md-4">
            <label for="nama_siswa" class="form-label">Pilih nama siswa</label>
            <select id="nama_siswa" name="nama_siswa" class="form-select" onchange="this.form.submit()">
              <option value="">Semua Siswa</option>
              @foreach($siswas ?? [] as $siswa)
                @php
                  $sname = is_object($siswa) ? ($siswa->name ?? '') : (is_array($siswa) ? ($siswa['name'] ?? '') : (string)$siswa);
                  $selected = request('nama_siswa') == $sname ? 'selected' : '';
                @endphp
                @if(!empty($sname))
                  <option value="{{ $sname }}" {{ $selected }}>
                    {{ ucwords(strtolower($sname)) }}
                  </option>
                @endif
              @endforeach
            </select>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="absensiTable">
            <thead style="background-color: #8DD8FF;">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Keterangan</th>
                <th class="text-center">Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($absen as $absensi)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $absensi->siswa ? ucwords(strtolower($absensi->siswa->name)) : 'Siswa tidak ditemukan' }}</td>
                <td>{{ date('d/m/Y', strtotime($absensi->tanggal)) }}</td>
                <td>{{ date('H:i:s', strtotime($absensi->waktu_masuk)) }}</td>
                <td>{{ $absensi->waktu_keluar ? date('H:i:s', strtotime($absensi->waktu_keluar)) : '00:00:00' }}</td>
                <td>{{ $absensi->keterangan }}</td>
                <td class="text-center">
                    @if($absensi->catatan)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-start flex-grow-1">{{ $absensi->catatan }}</span>
                            <button type="button" class="btn btn-sm btn-outline-primary ms-2 d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#catatanModal{{ $loop->iteration }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </div>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#catatanModal{{ $loop->iteration }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>tambah catatan</span>
                        </button>
                    @endif
                    
                    <!-- Modal -->
                    <div class="modal fade" id="catatanModal{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Catatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="catatan" class="form-label">Catatan untuk {{ $absensi->siswa ? $absensi->siswa->name : 'Siswa' }}</label>
                                            <textarea class="form-control" id="catatan" name="catatan" rows="3" maxlength="255">{{ old('catatan', $absensi->catatan) }}</textarea>
                                            <div class="form-text">0/50</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton{{ $loop->iteration }}" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $loop->iteration }}">
                      <li>
                        <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="keterangan" value="Hadir">
                          <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi Hadir?')">
                            <i class="bi bi-check-circle text-success me-2"></i>Hadir
                          </button> 
                        </form>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="keterangan" value="Terlambat">
                          <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi Terlambat?')">
                            <i class="bi bi-clock-history text-warning me-2"></i>Terlambat
                          </button> 
                        </form>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="keterangan" value="Izin">
                          <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi Izin?')">
                            <i class="bi bi-info-circle text-primary me-2"></i>Izin
                          </button> 
                        </form>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="keterangan" value="Sakit">
                          <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi Sakit?')">
                            <i class="bi bi-thermometer-sun text-warning me-2"></i>Sakit
                          </button>
                        </form>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="keterangan" value="Alpha">
                          <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi Alpha?')">
                            <i class="bi bi-x-circle text-danger me-2"></i>Alpha
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center">Belum ada data absensi</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      // Submit form when date changes
      $('#tanggal_awal, #tanggal_akhir').on('change', function() {
        $('#filterForm').submit();
      });

      // Initialize DataTable
      const table = $('#absensiTable').DataTable({
        order: [[2, 'desc']], // Default sort by date column (3rd column, 0-indexed)
        pageLength: 50,
        lengthMenu: [10, 25, 50, 100],
        language: {
          search: 'Search:',
          lengthMenu: 'Show _MENU_ entries',
          info: 'Showing _START_ to _END_ of _TOTAL_ entries',
          infoEmpty: 'No data available',
          infoFiltered: '(filtered from _MAX_ total entries)',
          paginate: {
            first: '«',
            last: '»',
            next: '›',
            previous: '‹'
          }
        }
      });
      
      // Auto-submit form when filter changes
      $('input[type="date"], select').on('change', function() {
        $('#filterForm').submit();
      });

      $('form[action*="absensi/"]').on('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin mengubah status absensi?')) {
          e.preventDefault();
        }
      });

      // Sidebar toggle
      const sidebar = document.getElementById("sidebar");
      const content = document.getElementById("content");
      const header = document.getElementById("header");
      const toggleBtn = document.getElementById("toggleBtn");
      const icon = toggleBtn.querySelector("i");
      const overlay = document.getElementById("overlay");
      const mobileMenuBtn = document.getElementById("mobileMenuBtn");

      toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
        content.classList.toggle("collapsed");
        header.classList.toggle("collapsed");
        icon.classList.toggle("bi-chevron-left");
        icon.classList.toggle("bi-chevron-right");
      });

      // Mobile menu
      function openSidebarMobile() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
      }

      function closeSidebarMobile() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
      }

      if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openSidebarMobile);
      if (overlay) overlay.addEventListener('click', closeSidebarMobile);

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
    });
  </script>
    <!-- Toast Notification -->
    @if(session('success'))
    <div class="toast-center position-fixed top-50 start-50 translate-middle">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    @endif

    <script>
      // Show toast notification
      document.addEventListener('DOMContentLoaded', function() {
        const toast = document.querySelector('.toast-center');
        if (toast) {
          // Show the toast
          setTimeout(() => {
            toast.classList.add('show');
            toast.classList.add('fade-in');
            
            // Hide after 3 seconds
            setTimeout(() => {
              toast.classList.remove('show');
              toast.classList.remove('fade-in');
            }, 3000);
          }, 100);
        }
      });
    </script>
  </body>
</html>