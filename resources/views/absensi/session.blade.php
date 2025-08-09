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
    $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
    $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
    $canStart = $currentDay === $jadwal->hari && $now->between($start, $end);
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
