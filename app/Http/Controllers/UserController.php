<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('users.index', compact('users', 'search'));
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
            'name' => 'nullable',
            'email' => 'nullable|email|unique:users',
            'password' => 'nullable|confirmed|min:6',
            'role' => 'required|in:admin,guru,siswa',
            'guru_id' => 'nullable|exists:guru,id',
            'siswa_id' => 'nullable|exists:siswa,id',
        ]);

        if ($data['role'] === 'guru') {
            $guru = Guru::findOrFail($data['guru_id']);
            $name = $guru->nuptk.' - '.$guru->nama;
            $email = $guru->nuptk.'@muhammadiyah.ac.id';
            $password = Carbon::parse($guru->tanggal_lahir)->format('ymd');
        } elseif ($data['role'] === 'siswa') {
            $siswa = Siswa::findOrFail($data['siswa_id']);
            $name = $siswa->nisn.' - '.$siswa->nama;
            $email = $siswa->nisn.'@muhammadiyah.ac.id';
            $password = Carbon::parse($siswa->tanggal_lahir)->format('ymd');
        } else {
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['password'];
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'guru') {
            Guru::where('id', $data['guru_id'])->update(['user_id' => $user->id]);
        }
        if ($data['role'] === 'siswa') {
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
