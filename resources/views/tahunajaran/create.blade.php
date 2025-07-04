@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<h1>Tambah Tahun Ajaran</h1>
<form action="{{ route('tahunajaran.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Tahun Ajaran</label>
        <input type="text" name="tahun" class="form-control" placeholder="2024/2025" required>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('tahunajaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
