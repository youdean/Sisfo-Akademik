@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')
<h1>Rekap Absensi Bulanan</h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="bulan" class="form-control">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="col-auto">
        <select name="tahun" class="form-control">
            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div class="col-auto">
        <select name="kelas" class="form-control">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Tampilkan</button>
    </div>
    <div class="col-auto">
        <a class="btn btn-success" href="{{ route('absensi.rekap.export', request()->query()) }}">Download Excel</a>
    </div>
</form>
<div class="table-responsive">
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
        </tr>
    </thead>
    <tbody>
        @foreach ($rekap as $r)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->nama }}</td>
            <td>{{ $r->kelas }}</td>
            <td>{{ $r->hadir }}</td>
            <td>{{ $r->izin }}</td>
            <td>{{ $r->sakit }}</td>
            <td>{{ $r->alpha }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
