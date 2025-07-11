<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        $totalSiswa  = 0;
        $totalKelas  = 0;
        $totalJadwal = 0;

        if ($guru) {
            $kelasIds    = $guru->kelas()->pluck('id');
            $totalKelas  = $kelasIds->count();
            $totalSiswa  = Siswa::whereIn('id_kelas', $kelasIds)->count();
            $totalJadwal = Jadwal::where('id_guru', $guru->id)->count();
        }

        return view('guru.index', compact(
            'totalSiswa',
            'totalKelas',
            'totalJadwal'
        ));
    }
}
