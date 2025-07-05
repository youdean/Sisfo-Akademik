<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapor Siswa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #222; padding: 6px; text-align: left; }
        .table th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">RAPOR SISWA</h2>
    <table>
        <tr>
            <td>Nama Siswa</td>
            <td>: {{ $siswa->nama }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>: {{ $siswa->nisn }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: {{ $siswa->kelas }}</td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td>: {{ $siswa->tanggal_lahir }}</td>
        </tr>
    </table>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $i => $n)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $n->mapel->nama }}</td>
                    <td>{{ $n->nilai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <div style="margin-top:40px;text-align:right;">
        <span>Mengetahui,</span><br>
        <span>Wali Kelas</span><br><br><br>
        <span>__________________</span>
    </div>
</body>
</html>
