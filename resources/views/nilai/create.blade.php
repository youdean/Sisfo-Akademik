@extends('layouts.app')

@section('title', 'Tambah Nilai')

@section('content')
<h1>Tambah Nilai</h1>
<form action="{{ route('nilai.store') }}" method="POST">
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
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach ($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nilai</label>
        <input type="number" name="nilai" class="form-control" min="0" max="100" required>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
