@extends('layouts.app')

@section('title', 'Data Penilaian')

@section('content')
<h1>Data Penilaian</h1>
<a href="{{ route('penilaian.create') }}" class="btn btn-primary mb-3">Tambah</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Semester</th>
            <th>Nilai Absensi</th>
            <th>Nilai Tugas</th>
            <th>Nilai Harian</th>
            <th>PTS</th>
            <th>PAT</th>
            <th>Nilai Raport</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($penilaian as $p)
        <tr>
            <td>{{ $p->siswa->nama }}</td>
            <td>{{ $p->semester }}</td>
            <td>{{ number_format($p->nilai_absensi, 2) }}</td>
            <td>{{ number_format($p->nilai_tugas, 2) }}</td>
            <td>{{ number_format($p->nilai_harian, 2) }}</td>
            <td>{{ $p->pts }}</td>
            <td>{{ $p->pat }}</td>
            <td>{{ number_format($p->nilai_raport, 2) }}</td>
            <td>
                <form action="{{ route('penilaian.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $penilaian->links() }}
@endsection
