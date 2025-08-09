<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        .no-border td { border: none; }
    </style>
</head>
<body>
    <h3 style="text-align:center">RAPOR PESERTA DIDIK</h3>

    <table class="no-border">
        <tr>
            <td>Nama Sekolah</td><td>: SMAS MUHAMMADIYAH</td>
            <td>Kelas</td><td>: {{ $siswa->kelas }}</td>
        </tr>
        <tr>
            <td>Nama</td><td>: {{ $siswa->nama }}</td>
            <td>Tahun Pelajaran</td><td>: {{ date('Y') }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>: {{ $siswa->nisn }}</td>
            <td>Semester</td><td>: {{ $semester }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai Rapor</th>
                <th>KKM</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penilaian as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->mapel->nama }}</td>
                <td>{{ number_format($p->nilai_raport, 2) }}</td>
                <td>{{ \App\Models\Penilaian::KKM }}</td>
                <td>{{ $p->predikat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2">Ketidakhadiran</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Izin</td>
                <td>{{ $ketidakhadiran['Izin'] ?? 0 }} hari</td>
            </tr>
            <tr>
                <td>Sakit</td>
                <td>{{ $ketidakhadiran['Sakit'] ?? 0 }} hari</td>
            </tr>
            <tr>
                <td>Alpha</td>
                <td>{{ $ketidakhadiran['Alpha'] ?? 0 }} hari</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
