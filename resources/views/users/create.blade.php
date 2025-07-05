@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<h1>Tambah User</h1>
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" id="role-select" required>
            <option value="admin">admin</option>
            <option value="guru">guru</option>
            <option value="siswa">siswa</option>
        </select>
    </div>
    <div class="mb-3" id="field-name">
        <label>Nama</label>
        <input type="text" name="name" class="form-control">
    </div>
    <div class="mb-3" id="field-email">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3" id="field-password">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3" id="field-password-confirm">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <div class="mb-3" id="field-guru" style="display:none;">
        <label>Pilih Guru</label>
        <select name="guru_id" class="form-control" id="guru-select">
            <option value="">-</option>
            @foreach ($guru as $g)
                <option value="{{ $g->id }}" data-nip="{{ $g->nip }}" data-tanggallahir="{{ $g->tanggal_lahir }}">{{ $g->nip }} - {{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3" id="field-siswa" style="display:none;">
        <label>Pilih Siswa</label>
        <select name="siswa_id" class="form-control" id="siswa-select">
            <option value="">-</option>
            @foreach ($siswa as $s)
                <option value="{{ $s->id }}" data-nisn="{{ $s->nisn }}" data-tanggallahir="{{ $s->tanggal_lahir }}">{{ $s->nisn }} - {{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role-select');
    const guruSelect = document.getElementById('guru-select');
    const siswaSelect = document.getElementById('siswa-select');
    const fieldGuru = document.getElementById('field-guru');
    const fieldSiswa = document.getElementById('field-siswa');
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passInput = document.querySelector('input[name="password"]');
    const passConfirm = document.querySelector('input[name="password_confirmation"]');

    function setFromOption(opt, type) {
        const id = type === 'guru' ? opt.dataset.nip : opt.dataset.nisn;
        const tgl = opt.dataset.tanggallahir || '';
        if (id) {
            nameInput.value = id + ' - ' + opt.textContent.trim().split(' - ').slice(1).join(' - ');
            emailInput.value = id + '@muhammadiyah.co.id';
            if (tgl) {
                const parts = tgl.split('-');
                passInput.value = parts[0].slice(2) + parts[1] + parts[2];
                passConfirm.value = passInput.value;
            }
        }
    }

    function updateVisibility() {
        const role = roleSelect.value;
        if (role === 'guru') {
            fieldGuru.style.display = '';
            fieldSiswa.style.display = 'none';
            if (guruSelect.selectedIndex > 0) {
                setFromOption(guruSelect.options[guruSelect.selectedIndex], 'guru');
            }
        } else if (role === 'siswa') {
            fieldGuru.style.display = 'none';
            fieldSiswa.style.display = '';
            if (siswaSelect.selectedIndex > 0) {
                setFromOption(siswaSelect.options[siswaSelect.selectedIndex], 'siswa');
            }
        } else {
            fieldGuru.style.display = 'none';
            fieldSiswa.style.display = 'none';
            nameInput.value = '';
            emailInput.value = '';
            passInput.value = '';
            passConfirm.value = '';
        }
    }

    roleSelect.addEventListener('change', updateVisibility);
    guruSelect.addEventListener('change', function() { setFromOption(this.options[this.selectedIndex], 'guru'); });
    siswaSelect.addEventListener('change', function() { setFromOption(this.options[this.selectedIndex], 'siswa'); });

    updateVisibility();
});
</script>
@endsection
