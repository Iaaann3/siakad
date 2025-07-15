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
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;

        $jadwalList = Jadwal::with('mapel', 'kelas')
            ->where('id_guru', $guru->id)
            ->get();

        $jadwalId = $request->jadwal;
        $selectedJadwal = null;
        $siswaList = collect();
        $absensi = [];

        if ($jadwalId) {
            $selectedJadwal = Jadwal::with('kelas.siswa')->find($jadwalId);
            if ($selectedJadwal && $selectedJadwal->kelas) {
                $siswaList = $selectedJadwal->kelas->siswa;

                // Ambil semua absensi terkait jadwal ini
                // Jika ada kemungkinan multiple records for same siswa_id, pertemuan_ke (e.g., different dates)
                // maka perlu logika untuk memilih yang paling relevan (misal: yang terbaru)
                $absensiRaw = Absensi::where('id_jadwal', $jadwalId)
                                    ->orderBy('tanggal', 'desc') // Urutkan berdasarkan tanggal terbaru
                                    ->get();

                // Susun absensi ke dalam array, pastikan yang terbaru yang disimpan
                foreach ($absensiRaw as $a) {
                    // Hanya simpan jika belum ada atau jika record ini lebih baru
                    if (!isset($absensi[$a->id_siswa][$a->pertemuan_ke]) || $a->tanggal > $absensi[$a->id_siswa][$a->pertemuan_ke]->tanggal) {
                         $absensi[$a->id_siswa][$a->pertemuan_ke] = $a;
                    }
                }
            }
        }

        return view('guru.absensi.index', compact('jadwalList', 'selectedJadwal', 'siswaList', 'absensi'));
    }

    public function ajaxUpdate(Request $request)
    {
        Log::info('AJAX Update Request Received:', $request->all());

        try {
            $validated = $request->validate([
                'jadwal_id'    => 'required|exists:jadwal,id',
                'siswa_id'     => 'required|exists:siswa,id',
                'pertemuan_ke' => 'required|integer|min:1|max:8',
                'status'       => 'required|in:hadir,izin,sakit,alpha',
            ]);

            Log::info('AJAX Update Validation Successful:', $validated);

            $currentDate = now()->toDateString(); // Format YYYY-MM-DD

            // Perubahan PENTING di sini: Hapus 'tanggal' dari kriteria pencarian updateOrCreate
            // Ini akan memastikan hanya ada satu record per id_jadwal, id_siswa, pertemuan_ke
            $absen = Absensi::updateOrCreate(
                [
                    'id_jadwal'    => $validated['jadwal_id'],
                    'id_siswa'     => $validated['siswa_id'],
                    'pertemuan_ke' => $validated['pertemuan_ke'],
                ],
                [
                    'status'  => $validated['status'],
                    'tanggal' => $currentDate, // Tetap set tanggal sebagai kolom yang diupdate/dibuat
                ]
            );

            Log::info('Absensi updated/created successfully:', ['absensi_id' => $absen->id]);

            return response()->json(['success' => true, 'data' => $absen]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Failed for AJAX Update:', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . json_encode($e->errors())], 422);
        } catch (\Exception $e) {
            Log::error('AJAX Update Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()], 500);
        }
    }

    // Metode create, store, edit, update, destroy lainnya tetap sama
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
