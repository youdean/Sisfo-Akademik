@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<h1>Edit User</h1>
<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" id="role-select" disabled required>
            <option value="admin" @selected($user->role=='admin')>admin</option>
            <option value="guru" @selected($user->role=='guru')>guru</option>
            <option value="siswa" @selected($user->role=='siswa')>siswa</option>
        </select>
        <input type="hidden" name="role" value="{{ $user->role }}">
    </div>
    <div class="mb-3" id="field-name">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" disabled>
        <input type="hidden" name="name" value="{{ $user->name }}">
    </div>
    <div class="mb-3" id="field-email">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
    </div>
    <div class="mb-3" id="field-password">
        <label>Password (isi jika ingin ganti)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3" id="field-password-confirm">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <input type="hidden" name="guru_id" id="guru-id" value="{{ optional($guru->firstWhere('user_id', $user->id))->id }}">
    <input type="hidden" name="siswa_id" id="siswa-id" value="{{ optional($siswa->firstWhere('user_id', $user->id))->id }}">
    <datalist id="guru-list">
        @foreach ($guru as $g)
            <option value="{{ $g->nip }} - {{ $g->nama }}" data-id="{{ $g->id }}" data-nip="{{ $g->nip }}" data-tanggallahir="{{ $g->tanggal_lahir }}"></option>
        @endforeach
    </datalist>
    <datalist id="siswa-list">
        @foreach ($siswa as $s)
            <option value="{{ $s->nisn }} - {{ $s->nama }}" data-id="{{ $s->id }}" data-nisn="{{ $s->nisn }}" data-tanggallahir="{{ $s->tanggal_lahir }}"></option>
        @endforeach
    </datalist>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role-select');
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passInput = document.querySelector('input[name="password"]');
    const passConfirm = document.querySelector('input[name="password_confirmation"]');
    const guruId = document.getElementById('guru-id');
    const siswaId = document.getElementById('siswa-id');

    function setFromOption(opt, type) {
        const id = type === 'guru' ? opt.dataset.nip : opt.dataset.nisn;
        const tgl = opt.dataset.tanggallahir || '';
        if (id) {
            emailInput.value = id + '@muhammadiyah.ac.id';
            if (tgl) {
                const parts = tgl.split('-');
                passInput.value = parts[0].slice(2) + parts[1] + parts[2];
                passConfirm.value = passInput.value;
            }
        }
    }

    function handleNameChange() {
        if (roleSelect.value === 'guru') {
            const opt = document.querySelector('#guru-list option[value="' + nameInput.value + '"]');
            if (opt) {
                guruId.value = opt.dataset.id;
                siswaId.value = '';
                setFromOption(opt, 'guru');
            }
        } else if (roleSelect.value === 'siswa') {
            const opt = document.querySelector('#siswa-list option[value="' + nameInput.value + '"]');
            if (opt) {
                siswaId.value = opt.dataset.id;
                guruId.value = '';
                setFromOption(opt, 'siswa');
            }
        } else {
            guruId.value = '';
            siswaId.value = '';
        }
    }

    function updateRole() {
        if (roleSelect.value === 'guru') {
            nameInput.setAttribute('list', 'guru-list');
        } else if (roleSelect.value === 'siswa') {
            nameInput.setAttribute('list', 'siswa-list');
        } else {
            nameInput.removeAttribute('list');
        }
    }

    roleSelect.addEventListener('change', function() {
        nameInput.value = '';
        emailInput.value = '';
        passInput.value = '';
        passConfirm.value = '';
        guruId.value = '';
        siswaId.value = '';
        updateRole();
    });
    nameInput.addEventListener('input', handleNameChange);

    updateRole();
});
</script>
@endsection
