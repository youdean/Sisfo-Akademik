@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<h1 class="mb-3">Pilih Mata Pelajaran</h1>
<ul class="list-group">
    @foreach($mapelList as $m)
        <li class="list-group-item">
            <a href="{{ route('input-nilai.kelas', $m->id) }}">{{ $m->nama }}</a>
        </li>
    @endforeach
</ul>
@endsection
