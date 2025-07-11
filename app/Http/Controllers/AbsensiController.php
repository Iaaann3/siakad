<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
public function index()
{
    $absensi = Absensi::with('siswa', 'jadwal')->orderByDesc('tanggal')->get();
    $siswaList = Siswa::with('kelas')->get(); 
    $jadwalList = Jadwal::with('mapel', 'kelas')->get();

    return view('guru.absensi.index', compact('absensi', 'siswaList', 'jadwalList'));
}


    public function create()
    {
        return view('guru.absensi.create', [
            'siswa' => Siswa::all(),
            'jadwal' => Jadwal::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'  => 'required|exists:siswa,id',
            'id_jadwal' => 'required|exists:jadwal,id',
            'tanggal'   => 'required|date',
            'status'    => 'required|in:hadir,izin,sakit,alpha',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Cek apakah sudah pernah absen di tanggal & jadwal yang sama
                $existing = Absensi::where('id_siswa', $request->id_siswa)
                    ->where('id_jadwal', $request->id_jadwal)
                    ->whereDate('tanggal', $request->tanggal)
                    ->first();

                if ($existing) {
                    throw new \Exception('Siswa sudah absen pada jadwal dan tanggal ini.');
                }

                Absensi::create($request->all());
            });

            return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan absensi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $absen = Absensi::findOrFail($id);
            return view('guru.absensi.edit', [
                'absen' => $absen,
                'siswa' => Siswa::all(),
                'jadwal' => Jadwal::all(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('guru.absensi.index')->with('error', 'Data absensi tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $absen = Absensi::findOrFail($id);
                $absen->update([
                    'status' => $request->status,
                ]);
            });

            return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update absensi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $absen = Absensi::findOrFail($id);
                $absen->delete();
            });

            return redirect()->route('guru.absensi.index')->with('success', 'Data absensi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus absensi: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
