@extends('layouts.app')

@section('title', 'Ambil Absen')

@section('content')
<h1>Ambil Absen Hari Ini</h1>
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
<form action="{{ route('student.absen') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('mapel_id')" class="mt-1" />
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
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('student.absensi') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
