@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<h1>Edit Guru</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('guru.update', $guru->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>NUPTK</label>
        <input type="text" name="nuptk" class="form-control" value="{{ $guru->nuptk }}" required>
        <x-input-error :messages="$errors->get('nuptk')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $guru->nama }}" required>
        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $guru->email }}">
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ $guru->tempat_lahir }}" required>
        <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $guru->tanggal_lahir }}" required>
        <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-1" />
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
