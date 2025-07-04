@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<h1>Edit Kelas</h1>
<form action="{{ route('kelas.update', $kela->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $kela->nama }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
