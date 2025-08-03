@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<h1 class="mb-3">Pilih Kelas</h1>
<ul class="list-group">
    @foreach($kelasList as $k)
        <li class="list-group-item">
            <a href="{{ route('input-nilai.mapel', $k) }}">{{ $k }}</a>
        </li>
    @endforeach
</ul>
@endsection
