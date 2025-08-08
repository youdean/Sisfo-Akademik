@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<h1 class="mb-3">Ubah Password</h1>

@if (session('status') === 'password-updated')
    <div class="alert alert-success">Password berhasil diubah.</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="w-50">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="current_password" class="form-label">Password Saat Ini</label>
        <input type="password" name="current_password" id="current_password" class="form-control">
        @error('current_password', 'updatePassword')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password Baru</label>
        <input type="password" name="password" id="password" class="form-control">
        @error('password', 'updatePassword')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection

