@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<h1 class="mb-3">Jadwal Hari {{ $hari }}</h1>

@if(Auth::user()->role === 'admin')
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="form-control">
    </div>
    <div class="col-auto">
        <input type="text" id="hari" class="form-control" value="{{ $hari }}" disabled>
    </div>
    <div class="col-auto">
        <select name="kelas_id" class="form-control">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasOptions as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="mapel_id" class="form-control">
            <option value="">-- Semua Mapel --</option>
            @foreach($mapelOptions as $m)
                <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Filter</button>
    </div>
</form>
@endif

<ul class="list-group">
    @forelse($jadwal as $j)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $j->kelas->nama }} - {{ $j->mapel->nama }} ({{ $j->jam_mulai }} - {{ $j->jam_selesai }})</span>
            <a href="{{ route('absensi.pelajaran.form', [$j->id, 'tanggal' => $tanggal]) }}" class="btn btn-sm btn-primary">Isi Absen</a>
        </li>
    @empty
        <li class="list-group-item text-center">Tidak ada jadwal{{ Auth::user()->role === 'guru' ? ' hari ini' : '' }}</li>
    @endforelse
</ul>
@endsection

@section('scripts')
<script>
document.getElementById('tanggal')?.addEventListener('change', function () {
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const d = new Date(this.value);
    document.getElementById('hari').value = isNaN(d.getTime()) ? '' : days[d.getDay()];
});
</script>
@endsection
