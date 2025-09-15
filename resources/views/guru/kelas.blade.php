@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')
<h1 class="mb-3">Siswa di Kelas {{ $selected }}</h1>
<a href="{{ route('guru.nilai-kelas') }}" class="btn btn-primary mb-3">Lihat Rekap Nilai</a>
<form method="GET" action="{{ route('guru.kelas') }}" class="mb-3 d-flex">
    <select name="kelas" class="form-select me-2" onchange="this.form.submit()">
        @foreach($kelasList as $k)
            <option value="{{ $k }}" {{ $k == $selected ? 'selected' : '' }}>{{ $k }}</option>
        @endforeach
    </select>
    <noscript><button class="btn btn-primary">Lihat</button></noscript>
</form>
<div class="table-responsive">
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
            <td>
                @if(!$s->rapor_konfirmasi)
                    <form method="POST" action="{{ route('siswa.konfirmasi-rapor', $s->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">Konfirmasi Rapor</button>
                    </form>
                    <button class="btn btn-sm btn-info" disabled>Cetak Rapor</button>
                @else
                    <span class="badge bg-success mb-1">Sudah Dikonfirmasi</span><br>
                    <a href="{{ route('rapor.cetak', $s->id) }}" class="btn btn-sm btn-info" target="_blank" rel="noopener">Cetak Rapor</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
