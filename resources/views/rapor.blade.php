<h3>RAPOR SISWA</h3>
<table>
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
        <td>No. Induk/NISN</td><td>: {{ $rapor['identitas']['nis'] }} / {{ $rapor['identitas']['nisn'] }}</td>
    </tr>
</table>

<h4>A. Sikap</h4>
<ul>
    <li>Spiritual: {{ $rapor['sikap']['spiritual'] }}</li>
    <li>Sosial: {{ $rapor['sikap']['sosial'] }}</li>
</ul>

<h4>B. Pengetahuan</h4>
<table border="1">
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
        @foreach ($rapor['pengetahuan'] as $row)
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

<!-- Keterampilan, Ekstrakurikuler, dst tinggal diulang format tabelnya seperti di atas -->

<h4>Ranking</h4>
<p>Ranking ke-{{ $rapor['ranking']['ke'] }} dari {{ $rapor['ranking']['dari'] }} siswa</p>

