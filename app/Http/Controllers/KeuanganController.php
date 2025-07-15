<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Siswa;
use App\Models\JenisKeuangan;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Keuangan::with('siswa', 'jenisKeuangan', 'semester')->latest();

        if ($request->has('status') && in_array($request->status, ['lunas', 'belum_lunas'])) {
            $query->where('status', $request->status);
        }

        return view('admin.keuangan.index', [
            'data'    => $query->get(),
            'siswa'   => Siswa::all(),
            'jenis'   => JenisKeuangan::all(),
            'semester'=> Semester::all(),
            'filter'  => $request->status,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'           => 'required|exists:siswa,id',
            'id_jenis_keuangan'  => 'required|exists:jenis_keuangan,id',
            'id_semester'        => 'required|exists:semester,id',
            'tanggal_bayar'      => 'required|date',
            'jumlah_bayar'       => 'required|numeric|min:0',
            'metode_pembayaran'  => 'required|in:tunai,transfer',
            'status'             => 'required|in:lunas,belum_lunas',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Keuangan::create([
                    'id_siswa'           => $request->id_siswa,
                    'id_jenis_keuangan'  => $request->id_jenis_keuangan,
                    'id_semester'        => $request->id_semester,
                    'tanggal_bayar'      => $request->tanggal_bayar,
                    'jumlah_bayar'       => $request->jumlah_bayar,
                    'metode_pembayaran'  => $request->metode_pembayaran,
                    'status'             => $request->status,
                ]);
            });

            return redirect()->route('admin.keuangan.index')->with('success', 'Pembayaran berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit($id)
    {
        $data = Keuangan::findOrFail($id);
        return view('admin.keuangan.edit', [
            'data'     => $data,
            'siswa'    => Siswa::all(),
            'jenis'    => JenisKeuangan::all(),
            'semester' => Semester::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar'      => 'required|date',
            'jumlah_bayar'       => 'required|numeric|min:0',
            'metode_pembayaran'  => 'required|in:tunai,transfer',
            'status'             => 'required|in:lunas,belum_lunas',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $keuangan = Keuangan::findOrFail($id);
                $keuangan->update([
                    'tanggal_bayar'      => $request->tanggal_bayar,
                    'jumlah_bayar'       => $request->jumlah_bayar,
                    'metode_pembayaran'  => $request->metode_pembayaran,
                    'status'             => $request->status,
                ]);
            });

            return redirect()->route('admin.keuangan.index')->with('success', 'Data berhasil diperbarui.');
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

            return redirect()->route('admin.keuangan.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus keuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
