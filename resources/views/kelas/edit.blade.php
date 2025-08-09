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
    <div class="mb-3">
        <label>Wali Kelas</label>
        <select name="guru_id" class="form-control" required>
            <option value="">-- Pilih Guru --</option>
            @foreach($guru as $g)
                @continue($g->jabatan == 'Kepala Sekolah')
                <option value="{{ $g->id }}" {{ $kela->guru_id == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('guru_id')" class="mt-1" />
    </div>
    <div class="mb-3">
        <label>Tahun Ajaran</label>
        <select name="tahun_ajaran_id" class="form-control" required>
            <option value="">-- Pilih Tahun Ajaran --</option>
            @foreach($tahun_ajaran as $ta)
                <option value="{{ $ta->id }}" {{ $kela->tahun_ajaran_id == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tahun_ajaran_id')" class="mt-1" />
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
