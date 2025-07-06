@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<h1>Tambah Mata Pelajaran</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('mapel.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Mapel</label>
        <input type="text" name="nama" class="form-control" required>
        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
