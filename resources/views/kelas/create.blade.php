@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<h1>Tambah Kelas</h1>
<form action="{{ route('kelas.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Kelas</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
