@extends('layouts.app')

@section('title', 'Tambah Pengajaran')

@section('content')
<h1>Tambah Pengajaran</h1>
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
<form action="{{ route('pengajaran.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Guru</label>
        <input list="guru_list" name="guru_nama" value="{{ old('guru_nama') }}" class="form-control" required>
        <datalist id="guru_list">
            @foreach($guru as $g)
                <option value="{{ $g->nama }}"></option>
            @endforeach
        </datalist>
        <x-input-error :messages="$errors->get('guru_nama')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('mapel_id')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas" class="form-control" required>
            @foreach($kelas as $k)
                <option value="{{ $k->nama }}">{{ $k->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('pengajaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
