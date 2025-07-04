@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<h1>Tambah User</h1>
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="admin">admin</option>
            <option value="guru">guru</option>
            <option value="siswa">siswa</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Kaitkan Guru (opsional)</label>
        <select name="guru_id" class="form-control">
            <option value="">-</option>
            @foreach ($guru as $g)
                <option value="{{ $g->id }}">{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kaitkan Siswa (opsional)</label>
        <select name="siswa_id" class="form-control">
            <option value="">-</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
