<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user', 'mapel')->orderBy('nama')->get();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create', [
            'users' => User::where('role', 'guru')->get(),
            'mapel' => Mapel::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_users'     => 'required|exists:users,id',
            'nama'         => 'required|string|max:255',
            'no_telepon'   => 'nullable|string|max:15',
            'foto'         => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $data = $request->except('foto');

                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $path = $file->store('foto-guru', 'public');
                    $data['foto'] = $path;
                }

                Guru::create($data);
            });

            return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan guru: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit($id)
    {
        try {
            $guru = Guru::findOrFail($id);
            return view('admin.guru.edit', [
                'guru'  => $guru,
                'users' => User::where('role', 'guru')->get(),
                'mapel' => Mapel::all(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')->with('error', 'Data guru tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'no_telepon'  => 'nullable|string|max:15',
            'mapel_utama' => 'required|exists:mapel,id',
            'foto'        => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $guru = Guru::findOrFail($id);
                $data = $request->except('foto');

                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $path = $file->store('foto-guru', 'public');
                    $data['foto'] = $path;
                }

                $guru->update($data);
            });

            return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update guru: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $guru = Guru::findOrFail($id);
                $guru->delete();
            });

            return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus guru: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data guru.');
        }
    }
}
