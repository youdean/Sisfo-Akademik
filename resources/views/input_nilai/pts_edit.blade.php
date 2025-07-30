@extends('layouts.app')

@section('title', 'Edit Nilai PTS')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }} Semester {{ $semester }}</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('input-nilai.pts.update', [$mapel->id, $kelas, $semester]) }}">
    @csrf
    @method('PUT')
    <div class="table-responsive">
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>PTS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa as $s)
                <tr>
                    <td>{{ $s->nama }}</td>
                    <td><input type="number" name="pts[{{ $s->id }}]" class="form-control" min="0" max="100" value="{{ $nilai[$s->id] }}"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('input-nilai.pts.list', [$mapel->id, $kelas, $semester]) }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
