@extends('layouts.app')

@section('title', 'Absensi Harian')

@section('content')
<h1 class="mb-3">Absensi Harian</h1>
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
        <select name="kelas" class="form-select" onchange="this.form.submit()">
            @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $k == $selected ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    @if(auth()->user()->role !== 'guru')
    <div class="col-auto">
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" onchange="this.form.submit()">
    </div>
    @else
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <div class="col-auto align-self-center">{{ $tanggal }}</div>
    @endif
    <noscript class="col-auto"><button class="btn btn-primary">Tampilkan</button></noscript>
</form>
@if($selected)
<form action="{{ route('absensi.harian.store') }}" method="POST">
    @csrf
    <input type="hidden" name="kelas" value="{{ $selected }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
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
    <button class="btn btn-success">Simpan</button>
</form>
@endif
@endsection
