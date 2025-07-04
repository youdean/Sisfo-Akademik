@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<h1>Tambah Mata Pelajaran</h1>
<form action="{{ route('mapel.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Mapel</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
