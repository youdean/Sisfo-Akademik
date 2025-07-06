@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
<h1>Tambah Guru</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('guru.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" required>
        <x-input-error :messages="$errors->get('nip')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" required>
        <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-1" />
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
