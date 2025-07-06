@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<h1>Edit Kelas</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('kelas.update', $kela->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $kela->nama }}" required>
        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
