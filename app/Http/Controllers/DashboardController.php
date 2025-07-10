<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Penilaian;
use App\Models\Absensi;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUser'      => User::count(),
            'totalSiswa'     => Siswa::count(),
            'totalGuru'      => Guru::count(),
            'totalKelas'     => Kelas::count(),
            'totalMapel'     => Mapel::count(),
        ];

        return view('admin.index', compact('data'));
    }
}
