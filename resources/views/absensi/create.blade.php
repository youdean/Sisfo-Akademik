@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
<h1>Tambah Absensi</h1>
<form action="{{ route('absensi.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Siswa</label>
        <select name="siswa_id" class="form-control" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>
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
    <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
