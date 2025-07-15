<?php
namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    public function index()
    {
        $kelas   = Kelas::with(['jurusan', 'wali'])->orderBy('nomor_kelas')->get();
        $guru    = Guru::whereDoesntHave('kelasWali')->orderBy('nama')->get();
        $jurusan = \App\Models\Jurusan::orderBy('nama_jurusan')->get();
        return view('admin.kelas.index', compact('kelas', 'guru', 'jurusan'));
    }

    public function create()
    {
        $guru    = Guru::whereDoesntHave('kelasWali')->orderBy('nama')->get();
        $jurusan = \App\Models\Jurusan::orderBy('nama_jurusan')->get();
        return view('admin.kelas.create', [
            'guru'    => $guru,
            'jurusan' => $jurusan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jurusan'  => 'required|exists:jurusan,id',
            'tingkat'     => 'required|in:X,XI,XII',
            'nomor_kelas' => 'required|string|max:20',
            'kapasitas'   => 'required|integer|min:1',
            'wali_kelas'  => 'required|exists:guru,id',
        ]);

        $waliSudahDipakai = Kelas::where('wali_kelas', $request->wali_kelas)->exists();
        if ($waliSudahDipakai) {
            return back()->withInput()->with('error', 'Guru ini sudah menjadi wali di kelas lain.');
        }

        try {
            DB::transaction(function () use ($request) {
                Kelas::create([
                    'id_jurusan'  => $request->id_jurusan,
                    'tingkat'     => $request->tingkat,
                    'nomor_kelas' => $request->nomor_kelas,
                    'kapasitas'   => $request->kapasitas,
                    'wali_kelas'  => $request->wali_kelas,
                ]);
            });

            return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan kelas: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan kelas.');
        }
    }

    public function edit($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            $guru  = Guru::whereDoesntHave('kelasWali')
                ->orWhere('id', $kelas->wali_kelas)
                ->orderBy('nama')->get();
            return view('admin.kelas.edit', [
                'kelas' => $kelas,
                'guru'  => $guru,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.kelas.index')->with('error', 'Data kelas tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jurusan'  => 'required|exists:jurusan,id',
            'tingkat'     => 'required|in:X,XI,XII',
            'nomor_kelas' => 'required|string|max:20',
            'kapasitas'   => 'required|integer|min:1',
            'wali_kelas'  => 'required|exists:guru,id',
        ]);

        $waliSudahDipakai = Kelas::where('wali_kelas', $request->wali_kelas)
            ->where('id', '!=', $id)
            ->exists();
        if ($waliSudahDipakai) {
            return back()->withInput()->with('error', 'Guru ini sudah menjadi wali di kelas lain.');
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $kelas = Kelas::findOrFail($id);
                $kelas->update([
                    'id_jurusan'  => $request->id_jurusan,
                    'tingkat'     => $request->tingkat,
                    'nomor_kelas' => $request->nomor_kelas,
                    'kapasitas'   => $request->kapasitas,
                    'wali_kelas'  => $request->wali_kelas,
                ]);
            });

            return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update kelas: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $kelas = Kelas::findOrFail($id);
                $kelas->delete();
            });

            return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus kelas: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data kelas.');
        }
    }
}
