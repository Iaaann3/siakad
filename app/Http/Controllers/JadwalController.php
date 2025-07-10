<?php
namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with('mapel', 'guru', 'kelas')->orderBy('hari')->get();
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        return view('admin.jadwal.create', [
            'mapel' => Mapel::all(),
            'guru'  => Guru::all(),
            'kelas' => Kelas::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mapel'    => 'required|exists:mapel,id',
            'id_guru'     => 'required|exists:guru,id',
            'id_kelas'    => 'required|exists:kelas,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Cek bentrok di kelas yang sama dan hari yang sama
                $bentrok = Jadwal::where('id_kelas', $request->id_kelas)
                    ->where('hari', $request->hari)
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                            ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
                    })->exists();

                if ($bentrok) {
                    throw new \Exception('Jadwal bentrok dengan kelas lain di waktu yang sama.');
                }

                Jadwal::create($request->all());
            });

            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan jadwal: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            return view('admin.jadwal.edit', [
                'jadwal' => $jadwal,
                'mapel'  => Mapel::all(),
                'guru'   => Guru::all(),
                'kelas'  => Kelas::all(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal.index')->with('error', 'Data jadwal tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mapel'    => 'required|exists:mapel,id',
            'id_guru'     => 'required|exists:guru,id',
            'id_kelas'    => 'required|exists:kelas,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jadwal = Jadwal::findOrFail($id);

                // Cek bentrok di kelas yang sama dan hari yang sama kecuali jadwal ini sendiri
                $bentrok = Jadwal::where('id_kelas', $request->id_kelas)
                    ->where('hari', $request->hari)
                    ->where('id', '!=', $id)
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                            ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
                    })->exists();

                if ($bentrok) {
                    throw new \Exception('Waktu jadwal bentrok dengan jadwal lain.');
                }

                $jadwal->update($request->all());
            });

            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update jadwal: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $jadwal = Jadwal::findOrFail($id);
                $jadwal->delete();
            });

            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus jadwal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus jadwal.');
        }
    }
}
