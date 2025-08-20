<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Sekolah')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @media (min-width: 992px) {
            .main-content { margin-left: 250px; }
        }
        .sidebar .nav-link {
            color: #fff !important;
            border-radius: 12px;
            padding-left: 18px;
            font-size: 1.13rem;
            margin-bottom: 8px;
            transition: background 0.13s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:active {
            background-color: rgba(0,0,0,0.14) !important; /* gelap sedikit, tetap transparan */
            color: #fff !important;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(0,0,0,0.18) !important;
            color: #fff !important;
        }
        .sidebar hr {
            opacity: 0.15;
        }
        .sidebar .btn-outline-light {
            color: #fff;
            border-color: #fff3;
        }
        .sidebar .btn-outline-light:hover {
            background: #fff2;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light" style="background: linear-gradient(to right, #f8f9fa, #e2e2f2);">
    @php
        $isWaliKelas = false;
        if (Auth::check() && Auth::user()->role === 'guru') {
            $guruId = \App\Models\Guru::where('user_id', Auth::id())->value('id');
            $isWaliKelas = \App\Models\Kelas::where('guru_id', $guruId)->exists();
        }
    @endphp

    <!-- Navbar Mobile Only -->
    <nav class="navbar navbar-dark bg-primary d-lg-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="{{ route('dashboard') }}" class="navbar-brand ms-2">Sisfo Akademik</a>
        </div>
    </nav>

    <!-- Sidebar Offcanvas (Mobile) -->
    <div class="offcanvas offcanvas-start d-lg-none text-bg-primary" tabindex="-1" id="sidebarMobile" style="width:250px;" data-bs-scroll="true" data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Sisfo Akademik</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="sidebar offcanvas-body p-3">
            {{-- Sidebar Content --}}
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <span class="fs-5 fw-semibold">Sisfo Akademik</span>
            </a>
            <hr>
            @auth
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('guru.index') }}" class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"><i class="bi bi-person-badge me-2"></i>Manajemen Guru</a></li>
                    <li><a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i>Manajemen Siswa</a></li>
                    <li><a href="{{ route('mapel.index') }}" class="nav-link {{ request()->routeIs('mapel.*') ? 'active' : '' }}"><i class="bi bi-book me-2"></i>Manajemen Mapel</a></li>
                    <li><a href="{{ route('kelas.index') }}" class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}"><i class="bi bi-building me-2"></i>Manajemen Kelas</a></li>
                    <li><a href="{{ route('pengajaran.index') }}" class="nav-link {{ request()->routeIs('pengajaran.*') ? 'active' : '' }}"><i class="bi bi-journal-text me-2"></i>Pengajaran</a></li>
                    <li><a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</a></li>
                    <li><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-people-fill me-2"></i>Manajemen User</a></li>
                @endif
                @if(in_array(Auth::user()->role, ['admin', 'guru']))
                    @if(Auth::user()->role === 'guru')
                        @if($isWaliKelas)
                            <li><a href="{{ route('guru.kelas') }}" class="nav-link {{ request()->routeIs('guru.kelas') ? 'active' : '' }}"><i class="bi bi-people-fill me-2"></i>Kelas Saya</a></li>
                            <!-- <li><a href="{{ route('guru.nilai-kelas') }}" class="nav-link {{ request()->routeIs('guru.nilai-kelas') ? 'active' : '' }}"><i class="bi bi-bar-chart me-2"></i>Nilai Kelas</a></li> -->
                        @endif
                        <li><a href="{{ route('input-nilai.index') }}" class="nav-link {{ request()->routeIs('input-nilai.*') ? 'active' : '' }}"><i class="bi bi-pencil-square me-2"></i>Input Nilai</a></li>
                    @endif
                    <li><a href="{{ route('penilaian.index') }}" class="nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}"><i class="bi bi-list-check me-2"></i>Penilaian</a></li>
                    <li><a href="{{ route('absensi.pelajaran') }}" class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}"><i class="bi bi-person-check me-2"></i>Absensi Siswa</a></li>
                @endif
                @if(Auth::user()->role === 'siswa')
                    <li><a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}"><i class="bi bi-person me-2"></i>Data Diri</a></li>
                    <li><a href="{{ route('student.nilai') }}" class="nav-link {{ request()->routeIs('student.nilai') ? 'active' : '' }}"><i class="bi bi-clipboard-data me-2"></i>Nilai Saya</a></li>
                    <li><a href="{{ route('student.jadwal') }}" class="nav-link {{ request()->routeIs('student.jadwal') ? 'active' : '' }}"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</a></li>
                    <li><a href="{{ route('rapor.cetak') }}" class="nav-link {{ request()->routeIs('rapor.cetak') ? 'active' : '' }}"><i class="bi bi-printer me-2"></i>Cetak Rapor</a></li>
                @endif
                @if(in_array(Auth::user()->role, ['siswa','guru','admin']))
                    <li><a href="{{ route('password.edit') }}" class="nav-link {{ request()->routeIs('password.edit') ? 'active' : '' }}"><i class="bi bi-key me-2"></i>Ubah Password</a></li>
                @endif
            </ul>
            <hr>
            <div>
                <span class="d-block mb-2">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">Logout</button>
                </form>
            </div>
            @else
            <div class="mt-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm w-100 mb-2">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm w-100">Register</a>
                @endif
            </div>
            @endauth
        </div>
    </div>

    <!-- Sidebar Static (Desktop) -->
    <div class="d-none d-lg-block bg-primary text-white position-fixed h-100" style="width:250px; left:0; top:0; z-index:1030;">
        <div class="sidebar p-3">
            {{-- Sidebar Content --}}
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <span class="fs-5 fw-semibold">Sisfo Akademik</span>
            </a>
            <hr>
            @auth
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('guru.index') }}" class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"><i class="bi bi-person-badge me-2"></i>Manajemen Guru</a></li>
                    <li><a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i>Manajemen Siswa</a></li>
                    <li><a href="{{ route('mapel.index') }}" class="nav-link {{ request()->routeIs('mapel.*') ? 'active' : '' }}"><i class="bi bi-book me-2"></i>Manajemen Mapel</a></li>
                    <li><a href="{{ route('kelas.index') }}" class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}"><i class="bi bi-building me-2"></i>Manajemen Kelas</a></li>
                    <li><a href="{{ route('pengajaran.index') }}" class="nav-link {{ request()->routeIs('pengajaran.*') ? 'active' : '' }}"><i class="bi bi-journal-text me-2"></i>Pengajaran</a></li>
                    <li><a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</a></li>
                    <li><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-people-fill me-2"></i>Manajemen User</a></li>
                @endif
                @if(in_array(Auth::user()->role, ['admin', 'guru']))
                    @if(Auth::user()->role === 'guru')
                        @if($isWaliKelas)
                            <li><a href="{{ route('guru.kelas') }}" class="nav-link {{ request()->routeIs('guru.kelas') ? 'active' : '' }}"><i class="bi bi-people-fill me-2"></i>Kelas Saya</a></li>
                            <!-- <li><a href="{{ route('guru.nilai-kelas') }}" class="nav-link {{ request()->routeIs('guru.nilai-kelas') ? 'active' : '' }}"><i class="bi bi-bar-chart me-2"></i>Nilai Kelas</a></li> -->
                        @endif
                        <li><a href="{{ route('input-nilai.index') }}" class="nav-link {{ request()->routeIs('input-nilai.*') ? 'active' : '' }}"><i class="bi bi-pencil-square me-2"></i>Input Nilai</a></li>
                    @endif
                    <li><a href="{{ route('penilaian.index') }}" class="nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}"><i class="bi bi-list-check me-2"></i>Penilaian</a></li>
                    <li><a href="{{ route('absensi.pelajaran') }}" class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}"><i class="bi bi-person-check me-2"></i>Absensi Siswa</a></li>
                @endif
                @if(Auth::user()->role === 'siswa')
                    <li><a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}"><i class="bi bi-person me-2"></i>Data Diri</a></li>
                    <li><a href="{{ route('student.nilai') }}" class="nav-link {{ request()->routeIs('student.nilai') ? 'active' : '' }}"><i class="bi bi-clipboard-data me-2"></i>Nilai Saya</a></li>
                    <li><a href="{{ route('student.jadwal') }}" class="nav-link {{ request()->routeIs('student.jadwal') ? 'active' : '' }}"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</a></li>
                    <li><a href="{{ route('rapor.cetak') }}" class="nav-link {{ request()->routeIs('rapor.cetak') ? 'active' : '' }}"><i class="bi bi-printer me-2"></i>Cetak Rapor</a></li>
                @endif
                @if(in_array(Auth::user()->role, ['siswa','guru','admin']))
                    <li><a href="{{ route('password.edit') }}" class="nav-link {{ request()->routeIs('password.edit') ? 'active' : '' }}"><i class="bi bi-key me-2"></i>Ubah Password</a></li>
                @endif
            </ul>
            <hr>
            <div>
                <span class="d-block mb-2">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">Logout</button>
                </form>
            </div>
            @else
            <div class="mt-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm w-100 mb-2">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm w-100">Register</a>
                @endif
            </div>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1 p-4" style="min-height:100vh;">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
