@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')
<h1 class="mb-3">Siswa di Kelas {{ $selected }}</h1>
<form method="GET" action="{{ route('guru.kelas') }}" class="mb-3 d-flex">
    <select name="kelas" class="form-select me-2" onchange="this.form.submit()">
        @foreach($kelasList as $k)
            <option value="{{ $k }}" {{ $k == $selected ? 'selected' : '' }}>{{ $k }}</option>
        @endforeach
    </select>
    <noscript><button class="btn btn-primary">Lihat</button></noscript>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NISN</th>
            <th>Tempat Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $s)
        <tr>
            <td>{{ $s->nama }}</td>
            <td>{{ $s->nisn }}</td>
            <td>{{ $s->tempat_lahir }}</td>
            <td>{{ $s->jenis_kelamin }}</td>
            <td>{{ $s->tanggal_lahir }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
