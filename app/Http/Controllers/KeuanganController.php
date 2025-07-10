<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Siswa;
use App\Models\JenisKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeuanganController extends Controller
{
    public function index()
    {
        $data = Keuangan::with('siswa', 'jenisKeuangan')->latest()->get();
        return view('keuangan.index', compact('data'));
    }

    public function create()
    {
        return view('keuangan.create', [
            'siswa' => Siswa::all(),
            'jenis' => JenisKeuangan::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'           => 'required|exists:siswa,id',
            'id_jeniskeuangan'   => 'required|exists:jenis_keuangan,id',
            'tanggal_bayar'      => 'required|date',
            'jumlah'             => 'required|numeric|min:0',
            'metode_pembayaran'  => 'required|in:tunai,transfer',
            'status'             => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Keuangan::create($request->all());
            });

            return redirect()->route('keuangan.index')->with('success', 'Pembayaran berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit($id)
    {
        try {
            $data = Keuangan::findOrFail($id);
            return view('keuangan.edit', [
                'data'  => $data,
                'siswa' => Siswa::all(),
                'jenis' => JenisKeuangan::all()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('keuangan.index')->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar'      => 'required|date',
            'jumlah'             => 'required|numeric|min:0',
            'metode_pembayaran'  => 'required|in:tunai,transfer',
            'status'             => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $keuangan = Keuangan::findOrFail($id);
                $keuangan->update($request->all());
            });

            return redirect()->route('keuangan.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Update gagal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $keuangan = Keuangan::findOrFail($id);
                $keuangan->delete();
            });

            return redirect()->route('keuangan.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
