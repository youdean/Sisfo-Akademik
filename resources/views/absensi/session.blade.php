@extends('layouts.app')

@section('title', 'Sesi Absensi')

@section('content')
<h1>Sesi Absensi {{ $jadwal->mapel->nama }} - {{ $jadwal->kelas->nama }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@php
    $dayMap = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu',
    ];
    $now = \Carbon\Carbon::now();
    $currentDay = $dayMap[$now->format('l')] ?? '';
    $currentTime = $now->format('H:i');
    $canStart = $currentDay === $jadwal->hari && $currentTime >= $jadwal->jam_mulai && $currentTime <= $jadwal->jam_selesai;
@endphp

@if($session && $session->status_sesi === 'open')
    <form action="{{ route('absensi.session.end', $jadwal->id) }}" method="POST">
        @csrf
        <button class="btn btn-danger">Tutup Sesi</button>
    </form>
@else
    <form action="{{ route('absensi.session.start', $jadwal->id) }}" method="POST">
        @csrf
        <button class="btn btn-primary" {{ $canStart ? '' : 'disabled' }}>Mulai Sesi</button>
    </form>
@endif
@endsection
