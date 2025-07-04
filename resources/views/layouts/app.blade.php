<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Sekolah')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar di sini -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            @auth
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            @endauth
            <a class="navbar-brand" href="{{ url('/') }}">Sisfo Akademik</a>
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
    @auth
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="{{ route('guru.index') }}" class="list-group-item list-group-item-action">Manajemen Guru</a>
                <a href="{{ route('siswa.index') }}" class="list-group-item list-group-item-action">Manajemen Siswa</a>
                <a href="{{ route('mapel.index') }}" class="list-group-item list-group-item-action">Manajemen Mapel</a>
                <a href="{{ route('nilai.index') }}" class="list-group-item list-group-item-action">Nilai Siswa</a>
                <a href="{{ route('absensi.index') }}" class="list-group-item list-group-item-action">Absensi Siswa</a>
            </div>
        </div>
    </div>
    @endauth
    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>
</html>
