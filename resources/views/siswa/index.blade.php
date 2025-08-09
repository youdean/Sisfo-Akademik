@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Siswa</h1>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>
<form method="GET" class="mb-3 d-flex align-items-end" action="{{ route('siswa.index') }}">
    <div class="me-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="form-control">
    </div>
    <div class="me-2">
        <select name="tahun_ajaran_id" class="form-select">
            <option value="">-- Semua Tahun Ajaran --</option>
            @foreach($tahun_ajaran as $ta)
                <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-outline-secondary" type="submit">Filter</button>
</form>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<a href="{{ route('siswa.export') }}" class="btn btn-success mb-3">Export Semua Siswa (Excel)</a>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Nama Orang Tua</th>
            <th>Kelas</th>
            <th>Tahun Ajaran</th>
            <th>Tempat Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $s)
        <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->nama }}</td>
            <td>{{ $s->nisn }}</td>
            <td>{{ $s->nama_ortu }}</td>
            <td>{{ $s->kelas }}</td>
            <td>{{ $s->tahunAjaran?->nama }}</td>
            <td>{{ $s->tempat_lahir }}</td>
            <td>{{ $s->jenis_kelamin }}</td>
            <td>{{ $s->tanggal_lahir }}</td>
            <td>
                <a href="{{ route('rapor.cetak', $s->id) }}" class="btn btn-sm btn-info">Cetak Rapor</a>
                <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
{{ $siswa->links('pagination::bootstrap-5') }}
@endsection
