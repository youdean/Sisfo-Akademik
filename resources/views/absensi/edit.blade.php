@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<h1>Edit Absensi</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
        <x-input-error :messages="$errors->get('siswa_id')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="{{ $absensi->tanggal }}" required>
        <x-input-error :messages="$errors->get('tanggal')" class="mt-1" />
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
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
