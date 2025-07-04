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

<div class="row mb-4">
    <div class="col-md-6">
        <canvas id="absensiChart"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="nilaiChart"></canvas>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const absensiLabels = @json($absensiPerHari->pluck('tanggal'));
    const absensiData = @json($absensiPerHari->pluck('total'));
    new Chart(document.getElementById('absensiChart'), {
        type: 'bar',
        data: {
            labels: absensiLabels,
            datasets: [{
                label: 'Hadir',
                data: absensiData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const nilaiLabels = @json($topNilai->pluck('siswa.nama'));
    const nilaiData = @json($topNilai->pluck('rata'));
    new Chart(document.getElementById('nilaiChart'), {
        type: 'bar',
        data: {
            labels: nilaiLabels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: nilaiData,
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
