<?php

namespace App\Http\Controllers;

use App\Models\JenisKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JenisKeuanganController extends Controller
{
    public function index()
    {
        $jenis = JenisKeuangan::orderBy('nama')->get();
        return view('jenis_keuangan.index', compact('jenis'));
    }

    public function create()
    {
        return view('jenis_keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_keuangan,nama',
        ]);

        try {
            DB::transaction(function () use ($request) {
                JenisKeuangan::create([
                    'nama' => $request->nama,
                ]);
            });

            return redirect()->route('jenis_keuangan.index')->with('success', 'Jenis keuangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan jenis keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit($id)
    {
        try {
            $jenis = JenisKeuangan::findOrFail($id);
            return view('jenis_keuangan.edit', compact('jenis'));
        } catch (\Exception $e) {
            return redirect()->route('jenis_keuangan.index')->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_keuangan,nama,' . $id,
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jenis = JenisKeuangan::findOrFail($id);
                $jenis->update([
                    'nama' => $request->nama,
                ]);
            });

            return redirect()->route('jenis_keuangan.index')->with('success', 'Jenis keuangan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update jenis keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $jenis = JenisKeuangan::findOrFail($id);
                $jenis->delete();
            });

            return redirect()->route('jenis_keuangan.index')->with('success', 'Jenis keuangan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus jenis keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
