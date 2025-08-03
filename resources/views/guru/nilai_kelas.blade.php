@extends('layouts.app')

@section('title', 'Nilai Kelas')

@section('content')
<h1 class="mb-3">Rekap Nilai Kelas {{ $kelas->nama }}</h1>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Siswa</th>
            @foreach($mapel as $m)
                <th>{{ $m->nama }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $s)
        <tr>
            <td>{{ $s->nama }}</td>
            @foreach($mapel as $m)
                @php
                    $nilai = $penilaian[$s->id][$m->id][0]->nilai_raport ?? null;
                @endphp
                <td>{{ $nilai !== null ? number_format($nilai, 2) : '-' }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

