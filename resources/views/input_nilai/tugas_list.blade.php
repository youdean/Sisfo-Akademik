@extends('layouts.app')

@section('title', 'Daftar Nilai Tugas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<a href="{{ route('input-nilai.tugas.form', [$mapel->id, $kelas]) }}" class="btn btn-success mb-3">Input Nilai Tugas</a>

@if($tugas->isEmpty())
    <p>Tidak ada nilai tugas.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor Tugas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tugas as $nomor)
                <tr>
                    <td>Tugas Nomor {{ $nomor }}</td>
                    <td>
                        <a href="{{ route('input-nilai.tugas.edit', [$mapel->id, $kelas, $nomor]) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
