@extends('layouts.app')

@section('title', 'Pilih Mata Pelajaran')

@section('content')
<h1 class="mb-3">{{ $kelas }} - Pilih Mata Pelajaran</h1>
<ul class="list-group">
    @foreach($mapelList as $m)
        <li class="list-group-item">
            <a href="{{ route('input-nilai.opsi', [$m->id, $kelas]) }}">{{ $m->nama }}</a>
        </li>
    @endforeach
</ul>
<a href="{{ route('input-nilai.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
