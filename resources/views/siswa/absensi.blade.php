@extends('layouts.app')

@section('title', 'Absensi Saya')

@section('content')
<h1 class="mb-3">Absensi Saya</h1>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mapel</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($absensi as $a)
        <tr>
            <td>{{ $a->mapel->nama ?? '-' }}</td>
            <td>{{ $a->tanggal }}</td>
            <td>{{ $a->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
