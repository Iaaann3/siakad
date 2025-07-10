<?php
namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa   = Siswa::with('user', 'kelas', 'jurusan')->orderBy('nama')->get();
        $users   = User::where('role', 'siswa')->get();
        $kelas   = Kelas::all();
        $jurusan = Jurusan::all();
        return view('admin.siswa.index', compact('siswa', 'users', 'kelas', 'jurusan'));
    }

    public function create()
    {
        return view('admin.siswa.create', [
            'users'   => User::where('role', 'siswa')->get(),
            'kelas'   => Kelas::all(),
            'jurusan' => Jurusan::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_users'      => 'required|exists:users,id',
            'id_kelas'      => 'required|exists:kelas,id',
            'id_jurusan'    => 'required|exists:jurusan,id',
            'nis'           => 'required|unique:siswa,nis',
            'nama'          => 'required|string|max:255',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'foto'          => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $data = $request->except('foto');

                if ($request->hasFile('foto')) {
                    $file         = $request->file('foto');
                    $path         = $file->store('foto-siswa', 'public');
                    $data['foto'] = $path;
                }

                Siswa::create($data);
            });

            return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan siswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data siswa.');
        }
    }

    public function edit($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return view('admin.siswa.edit', [
                'siswa'   => $siswa,
                'users'   => User::where('role', 'siswa')->get(),
                'kelas'   => Kelas::all(),
                'jurusan' => Jurusan::all(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')->with('error', 'Data siswa tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kelas'      => 'required|exists:kelas,id',
            'id_jurusan'    => 'required|exists:jurusan,id',
            'nis'           => 'required|unique:siswa,nis,' . $id,
            'nama'          => 'required|string|max:255',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'foto'          => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $siswa = Siswa::findOrFail($id);
                $data  = $request->except('foto');

                if ($request->hasFile('foto')) {
                    $file         = $request->file('foto');
                    $path         = $file->store('foto-siswa', 'public');
                    $data['foto'] = $path;
                }

                $siswa->update($data);
            });

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update siswa: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data siswa.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $siswa = Siswa::findOrFail($id);
                $siswa->delete();
            });

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus siswa: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data siswa.');
        }
    }
}
