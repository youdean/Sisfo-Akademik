<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Sekolah')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar di sini -->
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Sisfo Akademik</a>
            @auth
            <div class="dropdown ms-3">
                <button class="btn btn-outline-light dropdown-toggle btn-sm" type="button" id="menuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Menu
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="menuDropdown">
                    <li>
                        <a href="{{ route('dashboard') }}" class="dropdown-item">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    @if(Auth::user()->role === 'admin')
                        <li><a href="{{ route('guru.index') }}" class="dropdown-item"><i class="bi bi-person-badge me-2"></i>Manajemen Guru</a></li>
                        <li><a href="{{ route('siswa.index') }}" class="dropdown-item"><i class="bi bi-people me-2"></i>Manajemen Siswa</a></li>
                        <li><a href="{{ route('mapel.index') }}" class="dropdown-item"><i class="bi bi-book me-2"></i>Manajemen Mapel</a></li>
                        <li><a href="{{ route('kelas.index') }}" class="dropdown-item"><i class="bi bi-building me-2"></i>Manajemen Kelas</a></li>
                        <li><a href="{{ route('pengajaran.index') }}" class="dropdown-item"><i class="bi bi-journal-text me-2"></i>Pengajaran</a></li>
                        <li><a href="{{ route('jadwal.index') }}" class="dropdown-item"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</a></li>
                        <li><a href="{{ route('users.index') }}" class="dropdown-item"><i class="bi bi-people-fill me-2"></i>Manajemen User</a></li>
                    @endif
                    @if(in_array(Auth::user()->role, ['admin', 'guru']))
                        @if(Auth::user()->role === 'guru')
                            <li><a href="{{ route('input-nilai.index') }}" class="dropdown-item"><i class="bi bi-pencil-square me-2"></i>Input Nilai</a></li>
                        @endif
                        <li><a href="{{ route('penilaian.index') }}" class="dropdown-item"><i class="bi bi-list-check me-2"></i>Penilaian</a></li>
                        <li><a href="{{ route('absensi.index') }}" class="dropdown-item"><i class="bi bi-person-check me-2"></i>Absensi Siswa</a></li>
                    @endif
                    @if(Auth::user()->role === 'siswa')
                        <li><a href="{{ route('student.profile') }}" class="dropdown-item"><i class="bi bi-person me-2"></i>Data Diri</a></li>
                        <li><a href="{{ route('student.absensi') }}" class="dropdown-item"><i class="bi bi-person-check me-2"></i>Absensi Saya</a></li>
                        <li><a href="{{ route('student.absen.form') }}" class="dropdown-item"><i class="bi bi-pencil-square me-2"></i>Ambil Absen</a></li>
                        <li><a href="{{ route('student.jadwal') }}" class="dropdown-item"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</a></li>
                    @endif
                </ul>
            </div>
            @endauth
            <div class="d-flex ms-auto">
                @auth
                    <span class="navbar-text me-3">
                        {{ Auth::user()->name }} ({{ Auth::user()->role }})
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>
</html>
