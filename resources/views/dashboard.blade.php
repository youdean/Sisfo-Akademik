@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $totalSiswa }}</h4>
                <p class="card-text">Total Siswa</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $totalGuru }}</h4>
                <p class="card-text">Total Guru</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $totalMapel }}</h4>
                <p class="card-text">Total Mapel</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $absensiHariIni }}</h4>
                <p class="card-text">Siswa Hadir Hari Ini</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $totalKelas }}</h4>
                <p class="card-text">Total Kelas</p>
            </div>
        </div>
    </div>
</div>

<div class="list-group">
    <a href="{{ route('guru.index') }}" class="list-group-item list-group-item-action">Manajemen Guru</a>
    <a href="{{ route('siswa.index') }}" class="list-group-item list-group-item-action">Manajemen Siswa</a>
    <a href="{{ route('mapel.index') }}" class="list-group-item list-group-item-action">Manajemen Mapel</a>
    <a href="{{ route('nilai.index') }}" class="list-group-item list-group-item-action">Nilai Siswa</a>
    <a href="{{ route('absensi.index') }}" class="list-group-item list-group-item-action">Absensi Siswa</a>
</div>
@endsection
