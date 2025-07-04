@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<h1>Edit Absensi</h1>
<form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama Siswa</label>
        <select name="siswa_id" class="form-control" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}" @if($absensi->siswa_id == $s->id) selected @endif>{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="{{ $absensi->tanggal }}" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="">-- Pilih Status --</option>
            <option value="Hadir" @if($absensi->status=='Hadir') selected @endif>Hadir</option>
            <option value="Izin" @if($absensi->status=='Izin') selected @endif>Izin</option>
            <option value="Sakit" @if($absensi->status=='Sakit') selected @endif>Sakit</option>
            <option value="Alpha" @if($absensi->status=='Alpha') selected @endif>Alpha</option>
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
