@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<h1>Edit Guru</h1>
<form action="{{ route('guru.update', $guru->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" value="{{ $guru->nip }}" required>
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $guru->nama }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
