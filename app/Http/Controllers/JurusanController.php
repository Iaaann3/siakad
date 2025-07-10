<?php
namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        return view('admin.jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan',
        ]);
        try {
            Jurusan::create([
                'nama_jurusan' => $request->nama_jurusan,
            ]);
            return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambah jurusan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambah jurusan.');
        }
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan,' . $id,
        ]);
        try {
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->update([
                'nama_jurusan' => $request->nama_jurusan,
            ]);
            return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update jurusan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update jurusan.');
        }
    }

    public function destroy($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->delete();
            return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus jurusan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus jurusan.');
        }
    }
}
