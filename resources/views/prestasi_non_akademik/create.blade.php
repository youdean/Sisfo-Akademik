@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Input Prestasi Non Akademik</h2>
    <form action="{{ route('prestasi-non-akademik.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="siswa_id" class="form-label">Siswa</label>
            <select name="siswa_id" id="siswa_id" class="form-control" required>
                @foreach($siswa as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control" required>
                @foreach($tahunAjaran as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="nama_prestasi" class="form-label">Nama Prestasi</label>
            <input type="text" name="nama_prestasi" id="nama_prestasi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tingkat" class="form-label">Tingkat</label>
            <input type="text" name="tingkat" id="tingkat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
