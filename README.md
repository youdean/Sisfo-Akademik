# Sisfo Akademik (Laravel)

Aplikasi Sistem Informasi Akademik berbasis Laravel.

## Fitur
- Login Multi-Role (Admin, Guru, Siswa)
- Absensi Harian Siswa
- Input dan Cetak Rapor
- Manajemen Siswa & Kelas

## Instalasi Lokal
```bash
git clone https://github.com/youdean/Sisfo-Akademik.git
cd Sisfo-Akademik
composer install
cp .env.example .env
php artisan key:generate
