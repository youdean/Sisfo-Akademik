@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<h1>Tambah Jadwal</h1>
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form action="{{ route('jadwal.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas_id" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Guru</label>
        <select name="guru_id" class="form-control" required>
            <option value="">-- Pilih Guru --</option>
            @foreach($guru as $g)
                <option value="{{ $g->id }}">{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Hari</label>
        <select name="hari" class="form-control" required>
            <option value="">-- Pilih Hari --</option>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)
                <option value="{{ $h }}">{{ $h }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" required>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
