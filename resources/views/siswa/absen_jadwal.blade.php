@extends('layouts.app')

@section('title', 'Ambil Absen')

@section('content')
<h1>Ambil Absen {{ $jadwal->mapel->nama }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
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
@if($riwayat->count())
    <h4 class="mt-4">Riwayat Absen</h4>
    <div class="table-responsive">
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $r)
            <tr>
                <td>{{ $r->tanggal }}</td>
                <td>{{ $r->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
<form action="{{ route('student.jadwal.absen', $jadwal->id) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="">-- Pilih Status --</option>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alpha">Alpha</option>
        </select>
        @error('status')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label>Password Sesi</label>
        <input type="text" name="password" class="form-control" required>
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('student.jadwal') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
