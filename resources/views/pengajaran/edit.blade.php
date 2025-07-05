@extends('layouts.app')

@section('title', 'Edit Pengajaran')

@section('content')
<h1>Edit Pengajaran</h1>
<form action="{{ route('pengajaran.update', $pengajaran->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Guru</label>
        <select name="guru_id" class="form-control" required>
            @foreach($guru as $g)
                <option value="{{ $g->id }}" @selected($pengajaran->guru_id == $g->id)>{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}" @selected($pengajaran->mapel_id == $m->id)>{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas" class="form-control" required>
            @foreach($kelas as $k)
                <option value="{{ $k->nama }}" @selected($pengajaran->kelas == $k->nama)>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('pengajaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
