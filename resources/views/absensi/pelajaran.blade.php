@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<h1 class="mb-3">
    @if(Auth::user()->role === 'guru')
        Jadwal Minggu Ini
    @else
        Jadwal Hari {{ $hari }}
    @endif
</h1>

@if(Auth::user()->role === 'admin')
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control">
    </div>
    <div class="col-auto">
        <select name="kelas_id" class="form-control">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasOptions as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="mapel_id" class="form-control">
            <option value="">-- Semua Mapel --</option>
            @foreach($mapelOptions as $m)
                <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Filter</button>
    </div>
</form>
@endif

@if(Auth::user()->role === 'admin')
@php $isFuture = \Carbon\Carbon::parse($tanggal)->isAfter(\Carbon\Carbon::today()); @endphp
<ul class="list-group">
    @forelse($jadwal as $j)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $j->kelas->nama }} - {{ $j->mapel->nama }} ({{ $j->jam_mulai }} - {{ $j->jam_selesai }})</span>
            @if($isFuture)
                <button class="btn btn-sm btn-primary" disabled>Isi Absen</button>
            @else
                <a href="{{ route('absensi.pelajaran.form', ['jadwal' => $j->id, 'tanggal' => $tanggal]) }}" class="btn btn-sm btn-primary">Isi Absen</a>
            @endif
        </li>
    @empty
        <li class="list-group-item text-center">Tidak ada jadwal</li>
    @endforelse
</ul>
@else
    @php $now = \Carbon\Carbon::now(); @endphp
    @foreach($days as $day)
        <h4 class="mt-4">{{ $day }}</h4>
        <ul class="list-group mb-3">
            @forelse($jadwal->get($day, collect()) as $j)
                @php
                    $start = \Carbon\Carbon::parse($dates[$day] . ' ' . $j->jam_mulai);
                    $end = \Carbon\Carbon::parse($dates[$day] . ' ' . $j->jam_selesai);
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $j->kelas->nama }} - {{ $j->mapel->nama }} ({{ $j->jam_mulai }} - {{ $j->jam_selesai }})</span>
                    @if($now->between($start, $end))
                        <a href="{{ route('absensi.pelajaran.form', ['jadwal' => $j->id, 'tanggal' => $dates[$day]]) }}" class="btn btn-sm btn-primary">Isi Absen</a>
                    @else
                        <button class="btn btn-sm btn-primary" disabled>Isi Absen</button>
                    @endif
                </li>
            @empty
                <li class="list-group-item text-center">Tidak ada jadwal</li>
            @endforelse
        </ul>
    @endforeach
@endif
@endsection
