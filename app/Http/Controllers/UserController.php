<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::orderBy('email')->get();
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data user: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat memuat data user.');
        }
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,guru,siswa',
        ]);

        try {
            DB::transaction(function () use ($request) {
                User::create([
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => $request->role,
                ]);
            });

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan user.');
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'User tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|in:admin,guru,siswa',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $user = User::findOrFail($id);

                $data = [
                    'email' => $request->email,
                    'role'  => $request->role,
                ];

                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                $user->update($data);
            });

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update data user.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $user = User::findOrFail($id);
                $user->delete();
            });

            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }
}
