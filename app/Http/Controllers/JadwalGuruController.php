<?php
namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalGuruController extends Controller
{
    public function index()
    {
        $guruId = auth()->user()->guru->id ?? null;
        $jadwal = Jadwal::with('kelas', 'mapel')
            ->where('id_guru', $guruId)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
        $kelasList = Kelas::all();
        $mapelList = Mapel::all();
        return view('guru.jadwal.index', compact('jadwal', 'kelasList', 'mapelList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas'    => 'required|exists:kelas,id',
            'id_mapel'    => 'required|exists:mapel,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);
        try {
            DB::transaction(function () use ($request) {
                Jadwal::create([
                    'id_kelas'    => $request->id_kelas,
                    'id_guru'     => auth()->user()->guru->id,
                    'id_mapel'    => $request->id_mapel,
                    'hari'        => $request->hari,
                    'jam_mulai'   => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                ]);
            });
            return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal tambah jadwal guru: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat tambah jadwal.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kelas'    => 'required|exists:kelas,id',
            'id_mapel'    => 'required|exists:mapel,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);
        try {
            DB::transaction(function () use ($request, $id) {
                $jadwal = Jadwal::findOrFail($id);
                $jadwal->update([
                    'id_kelas'    => $request->id_kelas,
                    'id_mapel'    => $request->id_mapel,
                    'hari'        => $request->hari,
                    'jam_mulai'   => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                ]);
            });
            return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error('Gagal update jadwal guru: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update jadwal.');
        }
    }
}
