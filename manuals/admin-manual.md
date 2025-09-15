# Manual Book - Role Admin

Panduan ini menjelaskan semua fitur yang tersedia untuk peran *admin* pada sistem informasi sekolah. Daftar menu dan langkah diambil sesuai routes/web.php sehingga label dan rute mencerminkan navigasi aplikasi.

Daftar isi
- Ringkasan fitur / Menu utama
- Panduan per menu (akses & langkah singkat)
- Catatan khusus (generate jadwal, ekspor, absensi session)
- Cara menyisipkan gambar
- Aturan penamaan gambar

---

Ringkasan fitur / Menu utama (sesuai routes/web.php)
- Dashboard (/dashboard)
- Guru (resource: /guru)
- Siswa (resource: /siswa)
- Mapel (Mata Pelajaran) (resource: /mapel)
- Tahun Ajaran (resource: /tahun-ajaran)
- Kelas (resource: /kelas)
- Users (manajemen akun) (resource: /users)
- Pengajaran (resource: /pengajaran)
- Jadwal (resource: /jadwal) & Generate Jadwal (POST /jadwal/generate)
- Ekspor Siswa (GET /siswa-export)
- Absensi (resource: /absensi) dan endpoint terkait (/absensi/pelajaran, /absensi/rekap, session)
- Penilaian (index/create/store/destroy) (resource: /penilaian)
- Profile (edit/update/destroy) (/profile)
- Rapor Cetak (GET /rapor/cetak/{siswa?})
- Halaman khusus Guru (Kelas Saya, Nilai Kelas, Input Nilai)
- Halaman khusus Siswa (/saya, /saya/absensi, /saya/nilai, /saya/jadwal dsb.)
- Catatan Wali Kelas (create/store)
- Ekstra Kurikuler (create/store)
- Prestasi Non Akademik (create/store)

Panduan per menu (akses & langkah singkat)

Dashboard (/dashboard)
- Akses: Menu utama -> Dashboard
- Langkah: login sebagai admin -> klik Dashboard -> cek widget ringkasan (siswa, guru, notifikasi)
- Contoh gambar: ./images/dashboard-overview.png

Guru (/guru)
- Akses: Admin -> Guru
- Fungsi: tambah/edit/hapus guru
- Langkah singkat: klik Guru -> Tambah -> isi nama, NIP, email, mapel -> Simpan

Siswa (/siswa)
- Akses: Admin -> Siswa
- Fungsi: tambah/edit/hapus siswa, impor/ekspor data
- Impor massal: unggah CSV/Excel -> mapping kolom -> proses
- Ekspor: gunakan menu Ekspor Siswa (GET /siswa-export)

Mapel (Mata Pelajaran) (/mapel)
- Akses: Admin -> Mapel
- Fungsi: tambah/edit mapel, sesuaikan jam pelajaran dan semester

Tahun Ajaran (/tahun-ajaran)
- Akses: Admin -> Tahun Ajaran
- Fungsi: atur periode tahun ajaran aktif

Kelas (/kelas)
- Akses: Admin -> Kelas
- Fungsi: buat kelas, alokasikan siswa dan wali kelas

Users (/users)
- Akses: Admin -> Users
- Fungsi: manajemen akun internal (staff, admin tambahan)

Pengajaran (/pengajaran)
- Akses: Admin -> Pengajaran
- Fungsi: mengaitkan guru dengan kelas/mapel (relasi pengajaran)

Jadwal (/jadwal)
- Akses: Admin -> Jadwal
- Fungsi: buat dan kelola jadwal pembelajaran
- Generate Jadwal: gunakan tombol generate yang memanggil POST /jadwal/generate untuk otomatisasi

Absensi (/absensi)
- Akses: Admin -> Absensi
- Fungsi: kelola absensi pelajaran, rekap, ekspor
- Endpoint penting:
  - /absensi/pelajaran : daftar pelajaran untuk absensi
  - /absensi/pelajaran/{jadwal} (form & store) : input absensi per jadwal
  - /absensi/rekap : rekap absensi
  - /absensi/rekap/export : ekspor rekap
  - /absensi/session/{jadwal} : kelola session absen (start/end)
- Catatan: fitur session memungkinkan check-in siswa via session specific URL

Penilaian (/penilaian)
- Akses: Admin/Guru -> Penilaian
- Fungsi: input penilaian melalui form (index, create, store), hapus jika perlu
- Untuk input nilai guru: gunakan menu Input Nilai (guru)

Halaman khusus Guru
- Kelas Saya (/kelas-saya) : lihat daftar kelas yang diampu guru
- Nilai Kelas (/nilai-kelas) : rekap nilai per kelas
- Input Nilai (/input-nilai) : alur input nilai per mapel/kelas/semester, termasuk tugas, pts/pat, absensi
- Konfirmasi Rapor: POST /siswa/{siswa}/konfirmasi-rapor

Profile (/profile)
- Akses: Semua user login -> Profile
- Fungsi: edit profil, update password, hapus akun (jika diizinkan)

Rapor (/rapor/cetak/{siswa?})
- Akses: Admin, Guru, Siswa
- Fungsi: cetak rapor siswa (opsional parameter siswa untuk admin/guru)

Halaman khusus Siswa (/saya)
- /saya : profil siswa
- /saya/absensi : lihat absensi pribadi
- /saya/nilai : lihat nilai dan rapor
- /saya/jadwal : lihat jadwal
- /saya/jadwal/{jadwal}/absen : form absen per jadwal
- /saya/absensi/check-in : endpoint check-in untuk student

Catatan Wali Kelas, Ekstra, Prestasi
- Catatan Wali Kelas: create/store (route name catatan-wali-kelas.create/store)
- Ekstra Kurikuler: create/store
- Prestasi Non Akademik: create/store

Catatan khusus teknis
- Perhatikan rute generate jadwal: gunakan tombol atau aksi yang memanggil POST /jadwal/generate.
- Untuk ekspor siswa gunakan /siswa-export yang menghasilkan file CSV/Excel.
- Absensi session: ada endpoint untuk memulai dan mengakhiri session (/absensi/session/{jadwal}/start dan /end). Gunakan dengan hati-hati.

Cara menyisipkan gambar ke manual
- Simpan file gambar di folder manuals/images/.
- Referensi dengan path relatif dalam Markdown, contoh:
  ![Alt teks](./images/dashboard-overview.png)
- Rekomendasi format: PNG/JPG untuk screenshot; SVG untuk diagram.

Aturan penamaan gambar
- Gunakan lowercase, pisahkan dengan dash, contoh:
  - dashboard-overview.png
  - siswa-list-export.png
  - guru-add-form.png
  - jadwal-generate-result.png

Versi dokumen: 1.2
Tanggal: 2025-09-09

