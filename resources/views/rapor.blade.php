<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        .no-border td { border: none; }
        .section-title { font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <h3 style="text-align:center">RAPOR PESERTA DIDIK</h3>

    <table class="no-border">
        <tr>
            <td>Nama Sekolah</td><td>: {{ $rapor['identitas']['nama_sekolah'] }}</td>
            <td>Kelas</td><td>: {{ $rapor['identitas']['kelas'] }}</td>
        </tr>
        <tr>
            <td>Alamat</td><td>: {{ $rapor['identitas']['alamat'] }}</td>
            <td>Semester</td><td>: {{ $rapor['identitas']['semester'] }}</td>
        </tr>
        <tr>
            <td>Nama</td><td>: {{ $rapor['identitas']['nama'] }}</td>
            <td>Tahun Pelajaran</td><td>: {{ $rapor['identitas']['tahun_ajaran'] }}</td>
        </tr>
        <tr>
            <td>No. Induk / NISN</td><td>: {{ $rapor['identitas']['nis'] }} / {{ $rapor['identitas']['nisn'] }}</td>
        </tr>
    </table>

    <div class="section-title">A. SIKAP</div>
    <table>
        <thead>
            <tr>
                <th>Aspek</th>
                <th>Predikat</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($rapor['sikap'] as $row)
            <tr>
                <td>{{ $row['jenis'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="section-title">B. PENGETAHUAN</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>KKM</th>
                <th>Nilai</th>
                <th>Predikat</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok A (Wajib)</strong></td></tr>
            @foreach ($rapor['pengetahuan']['kelompok_a'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok B (Wajib)</strong></td></tr>
            @foreach ($rapor['pengetahuan']['kelompok_b'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok Peminatan</strong></td></tr>
            @foreach ($rapor['pengetahuan']['peminatan'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total Nilai Pengetahuan: {{ $rapor['total_pengetahuan'] }}</p>

    <div class="section-title">C. KETERAMPILAN</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>KKM</th>
                <th>Nilai</th>
                <th>Predikat</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok A (Wajib)</strong></td></tr>
            @foreach ($rapor['keterampilan']['kelompok_a'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok B (Wajib)</strong></td></tr>
            @foreach ($rapor['keterampilan']['kelompok_b'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok Peminatan</strong></td></tr>
            @foreach ($rapor['keterampilan']['peminatan'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
            <tr><td colspan="6" style="text-align:center"><strong>Kelompok Mapel Tambahan</strong></td></tr>
            @foreach ($rapor['keterampilan']['tambahan'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kkm'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['predikat'] }}</td>
                <td>{{ $row['deskripsi'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total Nilai Keterampilan: {{ $rapor['total_keterampilan'] }}</p>

    <div class="section-title">D. EKSTRAKURIKULER</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rapor['ekstrakurikuler'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['kegiatan'] }}</td>
                <td>{{ $row['keterangan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">E. PRESTASI</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Prestasi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rapor['prestasi'] as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['jenis'] }}</td>
                <td>{{ $row['keterangan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">F. KETIDAKHADIRAN</div>
    <table>
        <tr><td>Sakit</td><td>: {{ $rapor['ketidakhadiran']['sakit'] }} hari</td></tr>
        <tr><td>Izin</td><td>: {{ $rapor['ketidakhadiran']['izin'] }} hari</td></tr>
        <tr><td>Tanpa Keterangan</td><td>: {{ $rapor['ketidakhadiran']['tanpa_keterangan'] }} hari</td></tr>
    </table>

    <div class="section-title">F. CATATAN WALI KELAS</div>
    <p>{{ $rapor['catatan'] }}</p>

    <div class="section-title">G. TANGGAPAN ORANG TUA/WALI</div>
    <p>{{ $rapor['tanggapan'] }}</p>

    <p>Rangking ke-{{ $rapor['ranking']['ke'] }} dari {{ $rapor['ranking']['dari'] }} siswa</p>

    <table class="no-border" style="margin-top:20px;">
        <tr>
            <td style="text-align:center">Mengetahui<br>Orang Tua/Wali<br><br><br><br><strong>{{ $rapor['ttd']['orang_tua'] }}</strong></td>
            <td style="text-align:center">{{ $rapor['ttd']['tanggal'] }}<br>Wali Kelas<br><br><br><br><strong>{{ $rapor['ttd']['wali_kelas'] }}</strong></td>
            <td style="text-align:center">Mengetahui<br>Kepala Sekolah<br><br><br><br><strong>{{ $rapor['ttd']['kepala_sekolah'] }}</strong></td>
        </tr>
    </table>
</body>
</html>
