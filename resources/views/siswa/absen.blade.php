@extends('layouts.app')

@section('title', 'Ambil Absen')

@section('content')
<h1>Ambil Absen Hari Ini</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form action="{{ route('student.absen') }}" method="POST">
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
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('student.absensi') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
