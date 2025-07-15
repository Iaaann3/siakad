<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mapel;
use App\Models\Absensi;
use App\Models\Keuangan;
use App\Models\Penilaian;
use App\Models\Jadwal;

class SiswaDashboardController extends Controller
{
    public function index()
{
    $siswa = auth()->user()->siswa;

    $id_kelas = $siswa->id_kelas;

    // Total mapel (mapel yang dijadwalkan di kelas siswa)
    $totalMapel = Jadwal::where('id_kelas', $id_kelas)
                    ->distinct('id_mapel')
                    ->count('id_mapel');

    // Total absensi siswa itu sendiri
    $totalAbsensi = Absensi::where('id_siswa', $siswa->id)->count();

    // Total tagihan siswa
    $totalTagihan = Keuangan::where('id_siswa', $siswa->id)->sum('jumlah_bayar');

    // Total jadwal hari ini
    $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
    $jadwalHariIni = Jadwal::where('id_kelas', $id_kelas)
                        ->where('hari', $hariIni)
                        ->count();

    return view('siswa.index', compact(
        'totalMapel',
        'totalAbsensi',
        'totalTagihan',
        'jadwalHariIni'
    ));
}
}
