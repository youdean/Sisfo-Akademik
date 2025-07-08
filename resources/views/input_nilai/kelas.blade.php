@extends('layouts.app')

@section('title', 'Pilih Kelas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Pilih Kelas</h1>
<ul class="list-group">
    @foreach($kelasList as $k)
        <li class="list-group-item">
            <a href="{{ route('input-nilai.opsi', [$mapel->id, $k]) }}">{{ $k }}</a>
        </li>
    @endforeach
</ul>
<a href="{{ route('input-nilai.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
