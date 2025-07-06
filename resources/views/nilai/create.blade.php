@extends('layouts.app')

@section('title', 'Tambah Nilai')

@section('content')
<h1>Tambah Nilai</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('nilai.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Siswa</label>
        <select name="siswa_id" class="form-control" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('siswa_id')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach ($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('mapel_id')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Nilai</label>
        <input type="number" name="nilai" class="form-control" min="0" max="100" required>
        <x-input-error :messages="$errors->get('nilai')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Semester</label>
        <select name="semester" class="form-control" required>
            <option value="">-- Pilih Semester --</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
        <x-input-error :messages="$errors->get('semester')" class="mt-1" />
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
