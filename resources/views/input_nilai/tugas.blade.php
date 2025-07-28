@extends('layouts.app')

@section('title', 'Input Nilai Tugas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }} Semester {{ $semester }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('input-nilai.tugas.store', [$mapel->id, $kelas, $semester]) }}">
    @csrf
    <div class="mb-3">
        <label>Nama Tugas</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa as $s)
                <tr>
                    <td>{{ $s->nama }}</td>
                    <td><input type="number" name="nilai[{{ $s->id }}]" class="form-control" min="0" max="100"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas, 'semester' => $semester]) }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
