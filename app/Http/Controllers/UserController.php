<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $guru = Guru::whereNull('user_id')->get();
        $siswa = Siswa::whereNull('user_id')->get();
        return view('users.create', compact('guru', 'siswa'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,guru,siswa',
            'guru_id' => 'nullable|exists:guru,id',
            'siswa_id' => 'nullable|exists:siswa,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'guru' && $data['guru_id']) {
            Guru::where('id', $data['guru_id'])->update(['user_id' => $user->id]);
        }
        if ($data['role'] === 'siswa' && $data['siswa_id']) {
            Siswa::where('id', $data['siswa_id'])->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $guru = Guru::where(function ($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->get();
        $siswa = Siswa::where(function ($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->get();
        return view('users.edit', compact('user', 'guru', 'siswa'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'role' => 'required|in:admin,guru,siswa',
            'guru_id' => 'nullable|exists:guru,id',
            'siswa_id' => 'nullable|exists:siswa,id',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        if ($data['role'] === 'guru') {
            Guru::where('user_id', $user->id)->update(['user_id' => null]);
            if ($data['guru_id']) {
                Guru::where('id', $data['guru_id'])->update(['user_id' => $user->id]);
            }
        } elseif ($data['role'] === 'siswa') {
            Siswa::where('user_id', $user->id)->update(['user_id' => null]);
            if ($data['siswa_id']) {
                Siswa::where('id', $data['siswa_id'])->update(['user_id' => $user->id]);
            }
        } else {
            Guru::where('user_id', $user->id)->update(['user_id' => null]);
            Siswa::where('user_id', $user->id)->update(['user_id' => null]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        Guru::where('user_id', $user->id)->update(['user_id' => null]);
        Siswa::where('user_id', $user->id)->update(['user_id' => null]);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
