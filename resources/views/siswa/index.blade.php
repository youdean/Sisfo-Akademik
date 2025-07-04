@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Siswa</h1>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<a href="{{ route('siswa.export') }}" class="btn btn-success mb-3">Export Semua Siswa (Excel)</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Tanggal Lahir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $s)
        <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->nama }}</td>
            <td>{{ $s->kelas }}</td>
            <td>{{ $s->tanggal_lahir }}</td>
            <td>
                <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
                <a href="{{ route('rapor.cetak', $s->id) }}" class="btn btn-sm btn-info" target="_blank">Cetak Rapor</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
