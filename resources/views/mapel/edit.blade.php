@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<h1>Edit Mata Pelajaran</h1>
<form action="{{ route('mapel.update', $mapel->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama Mapel</label>
        <input type="text" name="nama" class="form-control" value="{{ $mapel->nama }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
