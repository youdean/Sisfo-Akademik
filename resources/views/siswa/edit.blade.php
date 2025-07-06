@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<h1>Edit Siswa</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>NISN</label>
        <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}" required>
        <x-input-error :messages="$errors->get('nisn')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->nama }}" {{ $siswa->kelas == $k->nama ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ $siswa->tempat_lahir }}" required>
        <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir }}" required>
        <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-1" />
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
