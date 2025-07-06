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
        @auth
            <button class="btn btn-outline-light me-2" id="toggle-sidebar">
                <i class="bi bi-list"></i>
            </button>
        @endauth
            <a class="navbar-brand" href="{{ route('dashboard') }}">Sisfo Akademik</a>
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
    <div class="d-flex">
    @auth
        <nav id="sidebar" class="bg-light border-end" style="width:260px; min-height:100vh;">
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-light fw-semibold">Menu</div>
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('guru.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-badge me-2"></i>Manajemen Guru
                    </a>
                    <a href="{{ route('siswa.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-people me-2"></i>Manajemen Siswa
                    </a>
                    <a href="{{ route('mapel.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-book me-2"></i>Manajemen Mapel
                    </a>
                    <a href="{{ route('kelas.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-building me-2"></i>Manajemen Kelas
                    </a>
                    <a href="{{ route('pengajaran.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-journal-text me-2"></i>Pengajaran
                    </a>
                    <a href="{{ route('jadwal.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran
                    </a>
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-people-fill me-2"></i>Manajemen User
                    </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin', 'guru']))
                    <a href="{{ route('nilai.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-card-checklist me-2"></i>Nilai Siswa
                    </a>
                    <a href="{{ route('absensi.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-check me-2"></i>Absensi Siswa
                    </a>
                @endif

                @if(Auth::user()->role === 'siswa')
                    <a href="{{ route('student.profile') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person me-2"></i>Data Diri
                    </a>
                    <a href="{{ route('student.absensi') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-check me-2"></i>Absensi Saya
                    </a>
                    <a href="{{ route('student.absen.form') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-pencil-square me-2"></i>Ambil Absen
                    </a>
                    <a href="{{ route('student.nilai') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-card-checklist me-2"></i>Nilai Saya
                    </a>
                @endif
            </div>
        </nav>
    @endauth
        <div class="flex-grow-1 p-3">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const toggleButton = document.getElementById('toggle-sidebar');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('d-none');
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
