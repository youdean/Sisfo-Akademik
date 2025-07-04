@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')

@section('content')
<h1>Edit Tahun Ajaran</h1>
<form action="{{ route('tahunajaran.update', $tahunAjaran->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Tahun Ajaran</label>
        <input type="text" name="tahun" class="form-control" value="{{ $tahunAjaran->tahun }}" required>
    </div>
    <button class="btn btn-success">Update</button>
    <a href="{{ route('tahunajaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
