@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<h1 class="mb-3">Jadwal Pelajaran Saya (Kelas {{ $siswa->kelas }})</h1>
@php
    use Carbon\Carbon;
    $days = ['Senin','Selasa','Rabu','Kamis','Jumat'];
    $dayMap = ['Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];
    $currentDayIndex = Carbon::now()->dayOfWeek;
    $currentTime = Carbon::now()->format('H:i');
@endphp
@foreach($days as $day)
    <h4 class="mt-4">{{ $day }}</h4>
    <div class="table-responsive">
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Mapel</th>
                <th>Guru</th>
                <th>Jam</th>
                <th>Absen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal->get($day, collect()) as $j)
            @php
                $jadwalIndex = $dayMap[$j->hari] ?? 7;
                $isActive = $jadwalIndex == $currentDayIndex &&
                           $currentTime >= $j->jam_mulai &&
                           $currentTime <= $j->jam_selesai;
                $sessionOpen = in_array($j->id, $openSessions);
            @endphp
            <tr>
                <td>{{ $j->mapel->nama }}</td>
                <td>{{ $j->guru->nama }}</td>
                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                <td>
                    @if($isActive && $sessionOpen)
                        <a href="{{ route('student.jadwal.absen.form', $j->id) }}" class="btn btn-sm btn-primary">Ambil Absen</a>
                    @else
                        <button class="btn btn-sm btn-primary" disabled>Ambil Absen</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada jadwal</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endforeach
@endsection
