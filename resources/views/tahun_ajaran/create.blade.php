@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<h1>Tambah Tahun Ajaran</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('tahun-ajaran.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
        <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>End Date</label>
        <input type="date" name="end_date" class="form-control" required>
        <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
