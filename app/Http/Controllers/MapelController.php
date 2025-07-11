<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Mapel::create([
                    'nama_mapel' => $request->nama_mapel,
                ]);
            });

            return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan mapel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data mapel.');
        }
    }

    public function edit($id)
    {
        try {
            $mapel = Mapel::findOrFail($id);
            return view('admin.mapel.edit', compact('mapel'));
        } catch (\Exception $e) {
            return redirect()->route('admin.mapel.index')->with('error', 'Data mapel tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel,' . $id,
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $mapel = Mapel::findOrFail($id);
                $mapel->update([
                    'nama_mapel' => $request->nama_mapel,
                ]);
            });

            return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update mapel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $mapel = Mapel::findOrFail($id);
                $mapel->delete();
            });

            return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus mapel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
