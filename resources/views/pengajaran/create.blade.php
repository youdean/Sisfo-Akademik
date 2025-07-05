@extends('layouts.app')

@section('title', 'Tambah Pengajaran')

@section('content')
<h1>Tambah Pengajaran</h1>
<form action="{{ route('pengajaran.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Guru</label>
        <select name="guru_id" class="form-control" required>
            @foreach($guru as $g)
                <option value="{{ $g->id }}">{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas" class="form-control" required>
            @foreach($kelas as $k)
                <option value="{{ $k->nama }}">{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('pengajaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
