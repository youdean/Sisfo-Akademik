# Sisfo Akademik (Laravel)

Aplikasi Sistem Informasi Akademik berbasis Laravel.

## Fitur
- Login Multi-Role (Admin, Guru, Siswa)
- Tambah Absensi Siswa
- Rekap dan Absensi per Pelajaran
- Input dan Cetak Rapor
- Manajemen Siswa & Kelas
- CRUD Master Data Kelas
- Manajemen Tahun Ajaran
- Nilai per semester/periode
- Hak akses berdasarkan role:
  - **Admin** dapat mengelola data guru, siswa, mata pelajaran, nilai dan absensi.
  - **Guru** dapat mengelola nilai dan absensi siswa.
- Sistem memastikan kombinasi guru, mata pelajaran dan kelas pada menu
  **Pengajaran** tidak bisa diduplikasi.

## Instalasi Lokal
```bash
git clone https://github.com/youdean/Sisfo-Akademik.git
cd Sisfo-Akademik
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Jika ada migrasi baru, jalankan:

```bash
php artisan migrate
```

Login demo:

- Admin: `admin@demo.com` / `password`
- Guru: `guru@demo.com` / `password`
- Siswa: `siswa@demo.com` / `password`

Setelah login sebagai admin, menu **Manajemen User** dapat digunakan untuk membuat akun baru dan memilih perannya.
