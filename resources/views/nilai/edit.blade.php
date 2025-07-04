@extends('layouts.app')

@section('title', 'Edit Nilai')

@section('content')
<h1>Edit Nilai</h1>
<form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama Siswa</label>
        <select name="siswa_id" class="form-control" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}" @if($nilai->siswa_id == $s->id) selected @endif>{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach ($mapel as $m)
                <option value="{{ $m->id }}" @if($nilai->mapel_id == $m->id) selected @endif>{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nilai</label>
        <input type="number" name="nilai" class="form-control" min="0" max="100" value="{{ $nilai->nilai }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
