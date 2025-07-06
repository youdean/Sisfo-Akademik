@extends('layouts.app')

@section('title', 'Jadwal Hari Ini')

@section('content')
<h1 class="mb-3">Jadwal Hari {{ $hari }}</h1>
<ul class="list-group">
    @forelse($jadwal as $j)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $j->kelas->nama }} - {{ $j->mapel->nama }} ({{ $j->jam_mulai }} - {{ $j->jam_selesai }})</span>
            <a href="{{ route('absensi.pelajaran.form', $j->id) }}" class="btn btn-sm btn-primary">Isi Absen</a>
        </li>
    @empty
        <li class="list-group-item text-center">Tidak ada jadwal hari ini</li>
    @endforelse
</ul>
@endsection
