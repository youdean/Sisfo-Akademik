@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<h1 class="mb-4">Dashboard Guru</h1>

<div class="mb-4">
    <h4>Jadwal Hari Ini</h4>
    @if($jadwalHariIni->isEmpty())
        <p>Tidak ada jadwal mengajar hari ini.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jam</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalHariIni as $j)
                <tr>
                    <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td>{{ $j->kelas->nama }}</td>
                    <td>{{ $j->mapel->nama }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="mb-4">
    <h4>Kelas &amp; Mapel Diampu</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Mapel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelasMapel as $p)
            <tr>
                <td>{{ $p->kelas }}</td>
                <td>{{ $p->mapel->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mb-4">
    <a href="{{ route('input-nilai.index') }}" class="btn btn-primary me-2">Input Nilai</a>
    <a href="{{ route('absensi.pelajaran') }}" class="btn btn-secondary">Absensi</a>
</div>
@endsection
