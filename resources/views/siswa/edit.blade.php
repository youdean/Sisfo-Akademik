@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<h1>Edit Siswa</h1>
<form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
    </div>
    <div class="mb-3">
        <label>Kelas</label>
        <input type="text" name="kelas" class="form-control" value="{{ $siswa->kelas }}" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
