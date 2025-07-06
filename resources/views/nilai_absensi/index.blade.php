@extends('layouts.app')

@section('title', 'Nilai Absensi')

@section('content')
<h1>Nilai Absensi</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="kelas" class="form-control">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $k == $kelas ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Tampilkan</button>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alpha</th>
            <th>Nilai Absensi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $r)
        @php
            $total = $r->hadir + $r->izin + $r->sakit + $r->alpha;
            $nilai = $total ? ($r->hadir / $total) * 100 : 0;
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->nama }}</td>
            <td>{{ $r->kelas }}</td>
            <td>{{ $r->hadir }}</td>
            <td>{{ $r->izin }}</td>
            <td>{{ $r->sakit }}</td>
            <td>{{ $r->alpha }}</td>
            <td>{{ number_format($nilai, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
