@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<h1 class="mb-4">Dashboard Siswa</h1>

<div class="mb-4">
    <h4>Jadwal Hari Ini</h4>
    @if($jadwalHariIni->isEmpty())
        <p>Tidak ada jadwal hari ini.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jam</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                </tr>
            </thead>
            <tbody>
            @foreach($jadwalHariIni as $j)
                <tr>
                    <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td>{{ $j->mapel->nama }}</td>
                    <td>{{ $j->guru->nama }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="mb-4">
    <h4>Ringkasan Kehadiran</h4>
    <table class="table table-bordered w-50">
        <tr><th>Hadir</th><td>{{ $absensiSummary['Hadir'] ?? 0 }}</td></tr>
        <tr><th>Izin</th><td>{{ $absensiSummary['Izin'] ?? 0 }}</td></tr>
        <tr><th>Sakit</th><td>{{ $absensiSummary['Sakit'] ?? 0 }}</td></tr>
        <tr><th>Alpha</th><td>{{ $absensiSummary['Alpha'] ?? 0 }}</td></tr>
    </table>
</div>

<div class="mb-4">
    <h4>Nilai Terbaru</h4>
    @if($nilaiTerbaru->isEmpty())
        <p>Belum ada nilai.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Semester</th>
                    <th>Nilai Raport</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiTerbaru as $n)
                <tr>
                    <td>{{ $n->mapel->nama }}</td>
                    <td>{{ $n->semester }}</td>
                    <td>{{ number_format($n->nilai_raport, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="mb-4">
    <h4>Pengumuman</h4>
    @if(empty($pengumuman))
        <p>Belum ada pengumuman.</p>
    @else
        <ul>
            @foreach($pengumuman as $p)
            <li>{{ $p }}</li>
            @endforeach
        </ul>
    @endif
</div>

<div class="mb-4">
    <a href="{{ route('student.profile') }}" class="btn btn-primary me-2">Profil</a>
    <a href="{{ route('student.nilai') }}" class="btn btn-success me-2">Nilai</a>
    <a href="{{ route('student.jadwal') }}" class="btn btn-info">Jadwal</a>
</div>
@endsection
