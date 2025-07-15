<?php

namespace App\Http\Controllers;
use App\Models\Jadwal;

use Illuminate\Http\Request;

class jadwalSiswaController extends Controller
{
    public function index()
{
    // Cek apakah siswa boleh lihat jadwal
    $siswa = auth()->user()->siswa;

    // Misalnya, ambil semua jadwal berdasarkan kelas siswa
    $jadwal = Jadwal::where('id_kelas', $siswa->id_kelas)->with(['mapel', 'guru'])->get();

    return view('siswa.jadwal.index', compact('jadwal'));
}

}
