@extends('layouts.app')

@section('title', 'Nilai Saya')

@section('content')
<h1 class="mb-3">Nilai Saya</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mata Pelajaran</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nilai as $n)
        <tr>
            <td>{{ $n->mapel->nama }}</td>
            <td>{{ $n->nilai }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('student.rapor') }}" class="btn btn-primary mt-3">Download Rapor</a>
@endsection
