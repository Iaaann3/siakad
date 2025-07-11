<?php
namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    public function index()
{
    $penilaian = Penilaian::with(['siswa.kelas', 'mapel'])->get();
    $siswaList = Siswa::with('kelas')->get();
    $mapelList = Mapel::all();
    $semesterList = Semester::all();

    return view('guru.penilaian.index', compact('penilaian', 'siswaList', 'mapelList', 'semesterList'));
}

    public function create()
    {
        return view('guru.penilaian.create', [
            'siswa'    => Siswa::all(),
            'semester' => Semester::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'        => 'required|exists:siswa,id',
            'id_semester'     => 'required|exists:semester,id',
            'jenis_penilaian' => 'required|in:harian,uts,uas',
            'nilai'           => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Cek apakah sudah ada nilai untuk siswa, semester, dan jenis penilaian ini
                $duplikat = Penilaian::where('id_siswa', $request->id_siswa)
                    ->where('id_semester', $request->id_semester)
                    ->where('jenis_penilaian', $request->jenis_penilaian)
                    ->exists();

                if ($duplikat) {
                    throw new \Exception('Nilai untuk kombinasi ini sudah ada.');
                }

                Penilaian::create($request->all());
            });

            return redirect()->route('guru.penilaian.index')->with('success', 'Nilai berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan nilai: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $nilai = Penilaian::findOrFail($id);
            return view('guru.penilaian.edit', [
                'nilai'    => $nilai,
                'siswa'    => Siswa::all(),
                'semester' => Semester::all(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('guru.penilaian.index')->with('error', 'Data penilaian tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $nilai = Penilaian::findOrFail($id);
                $nilai->update([
                    'nilai' => $request->nilai,
                ]);
            });

            return redirect()->route('guru.penilaian.index')->with('success', 'Nilai berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update nilai: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui nilai.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $nilai = Penilaian::findOrFail($id);
                $nilai->delete();
            });

            return redirect()->route('guru.penilaian.index')->with('success', 'Nilai berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus nilai: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus nilai.');
        }
    }
}
