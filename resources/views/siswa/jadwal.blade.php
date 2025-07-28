@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<h1 class="mb-3">Jadwal Pelajaran Saya (Kelas {{ $siswa->kelas }})</h1>
@php $days = ['Senin','Selasa','Rabu','Kamis','Jumat']; @endphp
@foreach($days as $day)
    <h4 class="mt-4">{{ $day }}</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mapel</th>
                <th>Guru</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal->get($day, collect()) as $j)
            <tr>
                <td>{{ $j->mapel->nama }}</td>
                <td>{{ $j->guru->nama }}</td>
                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Tidak ada jadwal</td></tr>
            @endforelse
        </tbody>
    </table>
@endforeach
@endsection
