@extends('layouts.app')

@section('title', 'Ambil Absen')

@section('content')
<h1>Ambil Absen {{ $jadwal->mapel->nama }}</h1>
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
@if($riwayat->count())
    <h4 class="mt-4">Riwayat Absen</h4>
    <div class="table-responsive">
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Check-in</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $r)
            <tr>
                <td>{{ $r->tanggal }}</td>
                <td>{{ $r->status }}</td>
                <td>{{ $r->check_in_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@if($today && $today->check_in_at)
    <div class="alert alert-info">Check-in pada: {{ $today->check_in_at }}</div>
@else
<form action="{{ route('student.absensi.checkin') }}" method="POST">
    @csrf
    <button class="btn btn-success">Check In</button>
    <a href="{{ route('student.jadwal') }}" class="btn btn-secondary">Kembali</a>
</form>
@endif
@endsection
