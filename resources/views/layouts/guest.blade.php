<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Gunakan tema Minty dari Bootswatch agar konsisten dengan layout utama -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/minty/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    </head>
    <body class="bg-light" style="background: linear-gradient(to right, #f8f9fa, #e2e2f2);">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center mb-4">
                        <a href="/">
                            <img src="{{ asset('images/logo-smasmuhammadiyah.png') }}" alt="Logo" class="img-fluid" style="max-height: 80px;" />
                        </a>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
