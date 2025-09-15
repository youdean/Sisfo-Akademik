# MANUAL BOOK SISTEM INFORMASI AKADEMIK SEKOLAH
**Versi 1.0**

![Logo Sekolah](/images/logo-sekolah.png)

## Daftar Isi
1. Pendahuluan
2. Persyaratan Sistem
3. Panduan Per Role User
4. FAQ (Frequently Asked Questions)
5. Kontak Support

## 1. Pendahuluan
### 1.1 Tentang Aplikasi
Sistem Informasi Akademik Sekolah adalah platform berbasis web yang memudahkan pengelolaan data akademik sekolah.

### 1.2 Persyaratan Sistem
- Browser: Chrome/Firefox/Safari versi terbaru
- Koneksi internet stabil
- Resolusi layar minimal 1024x768

## 2. Panduan Login

### 2.1 Halaman Login
![Halaman Login](/images/login-page.png)
- Buka halaman utama web Sisfo-Akademik
- Masukkan username dan password pada form login
- Klik tombol "Login"
- Jika berhasil, Anda akan masuk ke dashboard sesuai peran

### 2.2 Tampilan Dashboard
![Dashboard Admin](/images/dashboard-admin.png)
*Contoh tampilan dashboard untuk role Admin*

## 3. Panduan Per Role User

### 3.1 Role Admin
#### Fitur-fitur:
1. **Manajemen User**
   ![Menu Manajemen User](/images/admin-user-management.png)
   - Cara membuat user baru:
     1. Klik menu "Manajemen User"
     ![Menu User](/images/admin-menu-user.png)
     2. Klik tombol "Tambah User"
     ![Form Tambah User](/images/admin-add-user.png)
     3. Isi form dengan data lengkap:
        - Username (minimal 5 karakter)
        - Password (minimal 8 karakter)
        - Role (Admin/Guru/Wali Kelas/Siswa)
        - Status (Aktif/Non-aktif)
        - Data pribadi (nama lengkap, email, dll)
     4. Klik "Simpan"
     ![Konfirmasi Tambah User](/images/admin-confirm-user.png)

   - Cara edit user:
     1. Klik icon "Edit" pada list user
     ![Edit User](/images/admin-edit-user.png)
     2. Update informasi yang diperlukan
     3. Klik "Simpan Perubahan"

   - Cara nonaktifkan user:
     1. Klik icon "Status" pada list user
     ![Status User](/images/admin-status-user.png)
     2. Konfirmasi perubahan status

2. **Manajemen Data Master**
   ![Menu Data Master](/images/admin-data-master.png)
   - Mengelola data kelas
   ![Manajemen Kelas](/images/admin-class-management.png)
   - Mengelola data mata pelajaran
   ![Manajemen Mata Pelajaran](/images/admin-subject-management.png)
   - Mengelola tahun akademik
   - Mengelola jadwal

3. **Manajemen Data Guru**
   - Cara input data guru baru
   - Cara edit data guru
   - Cara nonaktifkan guru

4. **Manajemen Data Siswa**
   - Cara input data siswa baru
   - Cara edit data siswa
   - Cara atur kenaikan kelas

### 3.2 Role Guru
#### Fitur-fitur:
1. **Dashboard Guru**
   ![Dashboard Guru](/images/teacher-dashboard.png)
   - Melihat jadwal mengajar hari ini
   ![Jadwal Mengajar](/images/teacher-schedule.png)
   - Melihat pengumuman
   ![Pengumuman Guru](/images/teacher-announcements.png)
   - Menu navigasi cepat
   ![Menu Navigasi](/images/teacher-quick-menu.png)

2. **Input Nilai**
   ![Menu Nilai](/images/teacher-grade-menu.png)
   - Cara input nilai per kelas:
     1. Pilih kelas dan mata pelajaran
     ![Pilih Kelas](/images/teacher-select-class.png)
     2. Input nilai untuk semua siswa
     ![Input Nilai Kelas](/images/teacher-class-grade.png)
     3. Klik "Simpan"
     ![Simpan Nilai](/images/teacher-save-grades.png)
   
   - Cara input nilai per siswa:
     1. Pilih siswa dari daftar
     ![Pilih Siswa](/images/teacher-select-student.png)
     2. Input nilai individual
     ![Input Nilai Siswa](/images/teacher-student-grade.png)
     3. Klik "Simpan"
   
   - Cara edit nilai:
     1. Pilih nilai yang akan diedit
     ![Edit Nilai](/images/teacher-edit-grade.png)
     2. Update nilai
     3. Klik "Simpan Perubahan"
   
   - Cara export nilai:
     1. Pilih format export (PDF/Excel)
     ![Export Nilai](/images/teacher-export-grades.png)
     2. Klik "Download"

3. **Absensi**
   - Cara input absensi kelas
   - Cara edit absensi
   - Cara cetak rekap absensi

### 3.3 Role Wali Kelas
#### Fitur-fitur:
1. **Dashboard Wali Kelas**
   ![Dashboard Wali Kelas](/images/homeroom-dashboard.png)
   - Informasi kelas yang diampu
   ![Info Kelas](/images/homeroom-class-info.png)
   - Statistik kehadiran
   ![Statistik Kehadiran](/images/homeroom-attendance-stats.png)
   - Rekap nilai kelas
   ![Rekap Nilai](/images/homeroom-grade-summary.png)

2. **Monitoring Kelas**
   - Data Siswa:
     1. Lihat daftar siswa kelas
     ![Daftar Siswa](/images/homeroom-student-list.png)
     2. Detail profil siswa
     ![Profil Siswa](/images/homeroom-student-profile.png)
     3. Riwayat akademik
     ![Riwayat Akademik](/images/homeroom-academic-history.png)
   
   - Rekap Nilai:
     1. Nilai per mata pelajaran
     ![Nilai Mapel](/images/homeroom-subject-grades.png)
     2. Grafik perkembangan
     ![Grafik Nilai](/images/homeroom-grade-chart.png)
   
   - Rekap Absensi:
     1. Absensi harian
     ![Absensi Harian](/images/homeroom-daily-attendance.png)
     2. Rekap bulanan
     ![Rekap Bulanan](/images/homeroom-monthly-attendance.png)

3. **Laporan**
   - Generate Rapor:
     1. Pilih semester
     ![Pilih Semester](/images/homeroom-select-semester.png)
     2. Input nilai sikap dan catatan
     ![Input Catatan](/images/homeroom-input-notes.png)
     3. Preview rapor
     ![Preview Rapor](/images/homeroom-report-preview.png)
   
   - Cetak Rapor:
     1. Pilih format cetak
     ![Format Cetak](/images/homeroom-print-format.png)
     2. Download atau cetak langsung
     ![Download Rapor](/images/homeroom-download-report.png)
   
   - Export Data Kelas:
     1. Pilih jenis data
     ![Pilih Data](/images/homeroom-select-data.png)
     2. Export ke Excel/PDF
     ![Export Data](/images/homeroom-export-data.png)

### 3.4 Role Siswa
#### Fitur-fitur:
1. **Dashboard Siswa**
   ![Dashboard Siswa](/images/student-dashboard.png)
   - Melihat jadwal pelajaran
   - Melihat pengumuman
   - Melihat tugas

2. **Akademik**
   - Cara lihat nilai
   - Cara lihat absensi
   - Cara download materi

## 4. Panduan Fitur Umum

### 4.1 Manajemen Profil
1. Cara ganti password:
   - Klik menu profil
   - Pilih "Ganti Password"
   - Masukkan password lama
   - Masukkan password baru
   - Konfirmasi password baru
   - Klik "Simpan"

2. Cara update profil:
   - Klik menu profil
   - Pilih "Edit Profil"
   - Update informasi yang dibutuhkan
   - Klik "Simpan"

### 4.2 Download dan Export
1. Format file yang didukung:
   - PDF untuk laporan
   - Excel untuk data rekap
   - Word untuk dokumen

2. Cara export data:
   - Pilih menu yang akan di-export
   - Klik tombol "Export"
   - Pilih format file
   - Klik "Download"

## 5. FAQ (Frequently Asked Questions)

1. **Q: Bagaimana jika lupa password?**
   A: Klik forgot passwrod di halaman login untuk reset password

2. **Q: Browser apa yang direkomendasikan?**
   A: Google Chrome versi terbaru

3. **Q: Bagaimana jika terjadi error?**
   A: Screenshot error dan laporkan ke admin

## 6. Kontak Support

Jika mengalami kendala, silakan hubungi:
- Email: support@smamuhammadiyah.com
- Telp: (021) xxx-xxxx
- Jam kerja: Senin-Jumat, 08.00-16.00

---

**Catatan Penting:**
- Pastikan selalu logout setelah selesai menggunakan sistem
- Jaga kerahasiaan username dan password
- Backup data penting secara berkala
- Update browser secara rutin

*Note: Pastikan untuk mengganti gambar placeholder dengan screenshot aktual dari aplikasi*
