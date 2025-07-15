<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsensiSiswaController extends Controller
{
    /**
     * Menampilkan daftar absensi untuk siswa yang sedang login.
     * Siswa hanya dapat melihat status absensi mereka sendiri.
     */
    public function index()
    {
        // Mendapatkan objek siswa yang sedang login
        $siswa = auth()->user()->siswa;

        // Jika siswa tidak ditemukan, mungkin belum terhubung atau error
        if (!$siswa) {
            // Bisa redirect ke halaman error atau menampilkan pesan
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Mengambil semua jadwal yang diikuti oleh siswa ini
        // Asumsi: Ada cara untuk mengetahui jadwal siswa (misalnya melalui kelas)
        // Jika jadwal terkait langsung dengan siswa, sesuaikan query ini.
        // Contoh: Jika siswa tergabung dalam kelas, dan jadwal terhubung ke kelas.
        $jadwalSiswa = Jadwal::with(['mapel', 'kelas'])
                               ->where('id_kelas', $siswa->id_kelas) // Asumsi siswa punya id_kelas
                                ->get();

        $absensiData = [];

        // Mengambil data absensi untuk setiap jadwal yang diikuti siswa
        foreach ($jadwalSiswa as $jadwal) {
            $absensiRaw = Absensi::where('id_siswa', $siswa->id)
                                ->where('id_jadwal', $jadwal->id)
                                 ->orderBy('pertemuan_ke', 'asc') // Urutkan berdasarkan pertemuan
                                 ->orderBy('tanggal', 'desc') // Ambil yang terbaru jika ada duplikasi
                                ->get();

            // Memformat data absensi per jadwal
            $formattedAbsensi = [];
            foreach ($absensiRaw as $a) {
                // Pastikan hanya satu record per pertemuan_ke (yang terbaru)
                if (!isset($formattedAbsensi[$a->pertemuan_ke]) || $a->tanggal > $formattedAbsensi[$a->pertemuan_ke]->tanggal) {
                    $formattedAbsensi[$a->pertemuan_ke] = $a;
                }
            }
            $absensiData[$jadwal->id] = $formattedAbsensi;
        }

        return view('siswa.absensi.index', compact('siswa', 'jadwalSiswa', 'absensiData'));
    }
}
