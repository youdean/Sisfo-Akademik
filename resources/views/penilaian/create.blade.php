@extends('layouts.app')

@section('title', 'Tambah Penilaian')

@section('content')
<h1>Tambah Penilaian</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('penilaian.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Siswa</label>
        <select name="siswa_id" class="form-control" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Semester</label>
        <select name="semester" class="form-control" required>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <div class="row">
        <div class="col">
            <label>Hadir</label>
            <input type="number" name="hadir" class="form-control" min="0" required>
        </div>
        <div class="col">
            <label>Sakit</label>
            <input type="number" name="sakit" class="form-control" min="0" required>
        </div>
        <div class="col">
            <label>Izin</label>
            <input type="number" name="izin" class="form-control" min="0" required>
        </div>
        <div class="col">
            <label>Alpha</label>
            <input type="number" name="alpha" class="form-control" min="0" required>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <label>Tugas 1</label>
            <input type="number" name="tugas1" class="form-control" min="0" max="100">
        </div>
        <div class="col">
            <label>Tugas 2</label>
            <input type="number" name="tugas2" class="form-control" min="0" max="100">
        </div>
        <div class="col">
            <label>Tugas 3</label>
            <input type="number" name="tugas3" class="form-control" min="0" max="100">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <label>PTS</label>
            <input type="number" name="pts" class="form-control" min="0" max="100">
        </div>
        <div class="col">
            <label>PAT</label>
            <input type="number" name="pat" class="form-control" min="0" max="100">
        </div>
    </div>
    <button class="btn btn-success mt-3">Simpan</button>
    <a href="{{ route('penilaian.index') }}" class="btn btn-secondary mt-3">Batal</a>
</form>
@endsection
