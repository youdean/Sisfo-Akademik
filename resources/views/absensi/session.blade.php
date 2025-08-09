@extends('layouts.app')

@section('title', 'Sesi Absensi')

@section('content')
<h1>Sesi Absensi {{ $jadwal->mapel->nama }} - {{ $jadwal->kelas->nama }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($session && $session->status_sesi === 'open')
    @if(session('password'))
        <div class="alert alert-info">Password Sesi: <strong>{{ session('password') }}</strong></div>
    @endif
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
