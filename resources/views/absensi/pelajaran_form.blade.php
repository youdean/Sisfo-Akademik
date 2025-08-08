@extends('layouts.app')

@section('title', 'Absensi ' . $jadwal->mapel->nama)

@section('content')
<h1 class="mb-3">Absensi {{ $jadwal->mapel->nama }} - {{ $jadwal->kelas->nama }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
    </div>
    <noscript class="col-auto"><button class="btn btn-primary">Tampilkan</button></noscript>
</form>
<form action="{{ route('absensi.pelajaran.store', $jadwal->id) }}" method="POST">
    @csrf
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
    <div class="table-responsive">
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td><input type="radio" name="status[{{ $s->id }}]" value="Hadir" @checked(($absen[$s->id] ?? '')=='Hadir')></td>
                <td><input type="radio" name="status[{{ $s->id }}]" value="Izin" @checked(($absen[$s->id] ?? '')=='Izin')></td>
                <td><input type="radio" name="status[{{ $s->id }}]" value="Sakit" @checked(($absen[$s->id] ?? '')=='Sakit')></td>
                <td><input type="radio" name="status[{{ $s->id }}]" value="Alpha" @checked(($absen[$s->id] ?? '')=='Alpha')></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <button class="btn btn-success">Simpan</button>
</form>
@endsection
