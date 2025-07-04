@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<h1>Edit User</h1>
<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
    </div>
    <div class="mb-3">
        <label>Password (isi jika ingin ganti)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="admin" @selected($user->role=='admin')>admin</option>
            <option value="guru" @selected($user->role=='guru')>guru</option>
            <option value="siswa" @selected($user->role=='siswa')>siswa</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Kaitkan Guru (opsional)</label>
        <select name="guru_id" class="form-control">
            <option value="">-</option>
            @foreach ($guru as $g)
                <option value="{{ $g->id }}" @selected($g->user_id===$user->id)>{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kaitkan Siswa (opsional)</label>
        <select name="siswa_id" class="form-control">
            <option value="">-</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}" @selected($s->user_id===$user->id)>{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
