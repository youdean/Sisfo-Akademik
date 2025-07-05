@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Jadwal Pelajaran</h1>
    <a href="{{ route('jadwal.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
</div>
<form method="GET" action="{{ route('jadwal.index') }}" class="mb-3 d-flex">
    <select name="kelas" class="form-select me-2" onchange="this.form.submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $id => $nama)
            <option value="{{ $id }}" {{ $id == $selectedKelas ? 'selected' : '' }}>{{ $nama }}</option>
        @endforeach
    </select>
    <noscript><button class="btn btn-primary">Lihat</button></noscript>
</form>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@php $days = ['Senin','Selasa','Rabu','Kamis','Jumat']; @endphp
@foreach($days as $day)
    <h4 class="mt-4">{{ $day }}</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Guru</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal->get($day, collect()) as $j)
            <tr>
                <td>{{ $j->kelas->nama }}</td>
                <td>{{ $j->mapel->nama }}</td>
                <td>{{ $j->guru->nama }}</td>
                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                <td>
                    <a href="{{ route('jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Tidak ada jadwal</td></tr>
            @endforelse
        </tbody>
    </table>
@endforeach
@endsection
