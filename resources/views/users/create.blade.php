@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<h1>Tambah User</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" id="role-select" required>
            <option value="admin">admin</option>
            <option value="guru">guru</option>
            <option value="siswa">siswa</option>
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-1" />
    </div>
    <div class="mb-3" id="field-name">
        <label>Nama</label>
        <input type="text" name="name" class="form-control">
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>
    <div class="mb-3" id="field-email">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>
    <div class="mb-3" id="field-password">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>
    <div class="mb-3" id="field-password-confirm">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
    </div>
    <input type="hidden" name="guru_id" id="guru-id">
    <input type="hidden" name="siswa_id" id="siswa-id">
    <datalist id="guru-list">
        @foreach ($guru as $g)
            <option value="{{ $g->nuptk }} - {{ $g->nama }}" data-id="{{ $g->id }}" data-nuptk="{{ $g->nuptk }}" data-email="{{ $g->email }}" data-tanggallahir="{{ $g->tanggal_lahir }}"></option>
        @endforeach
    </datalist>
    <datalist id="siswa-list">
        @foreach ($siswa as $s)
            <option value="{{ $s->nisn }} - {{ $s->nama }}" data-id="{{ $s->id }}" data-nisn="{{ $s->nisn }}" data-tanggallahir="{{ $s->tanggal_lahir }}"></option>
        @endforeach
    </datalist>
    <button class="btn btn-success">Simpan</button>
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
        const id = type === 'guru' ? opt.dataset.nuptk : opt.dataset.nisn;
        const tgl = opt.dataset.tanggallahir || '';
        if (id) {
            if (type === 'guru') {
                emailInput.value = opt.dataset.email || '';
            } else {
                emailInput.value = id + '@muhammadiyah.ac.id';
            }
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
        nameInput.value = '';
        emailInput.value = '';
        passInput.value = '';
        passConfirm.value = '';
        guruId.value = '';
        siswaId.value = '';

        if (roleSelect.value === 'guru') {
            nameInput.setAttribute('list', 'guru-list');
        } else if (roleSelect.value === 'siswa') {
            nameInput.setAttribute('list', 'siswa-list');
        } else {
            nameInput.removeAttribute('list');
        }
    }

    roleSelect.addEventListener('change', function() {
        updateRole();
    });
    nameInput.addEventListener('input', handleNameChange);

    updateRole();
});
</script>
@endsection
