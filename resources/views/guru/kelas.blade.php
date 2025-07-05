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
<div class="mb-3">
    <a href="{{ route('absensi.harian', ['kelas' => $selected]) }}" class="btn btn-success">Input Absensi Harian</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NISN</th>
            <th>Tanggal Lahir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $s)
        <tr>
            <td>{{ $s->nama }}</td>
            <td>{{ $s->nisn }}</td>
            <td>{{ $s->tanggal_lahir }}</td>
            <td>
                <a href="{{ route('rapor.cetak', $s->id) }}" class="btn btn-sm btn-info" target="_blank">Cetak Rapor</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
