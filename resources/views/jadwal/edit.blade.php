@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<h1>Edit Jadwal</h1>
<form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Kelas</label>
        <select name="kelas_id" class="form-control" required>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}" @selected($k->id==$jadwal->kelas_id)>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Mata Pelajaran</label>
        <select name="mapel_id" class="form-control" required>
            @foreach($mapel as $m)
                <option value="{{ $m->id }}" @selected($m->id==$jadwal->mapel_id)>{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Guru</label>
        <select name="guru_id" class="form-control" required>
            @foreach($guru as $g)
                <option value="{{ $g->id }}" @selected($g->id==$jadwal->guru_id)>{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Hari</label>
        <select name="hari" class="form-control" required>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)
                <option value="{{ $h }}" @selected($jadwal->hari==$h)>{{ $h }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" value="{{ $jadwal->jam_mulai }}" required>
    </div>
    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" value="{{ $jadwal->jam_selesai }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
