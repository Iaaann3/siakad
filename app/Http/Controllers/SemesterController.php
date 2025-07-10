<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemesterController extends Controller
{
    public function index()
    {
        $semester = Semester::orderByDesc('tanggal_mulai')->get();
        return view('semester.index', compact('semester'));
    }

    public function create()
    {
        return view('semester.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran'   => 'required|string|max:20|unique:semester,tahun_ajaran',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Semester::create([
                    'tahun_ajaran'    => $request->tahun_ajaran,
                    'tanggal_mulai'   => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                ]);
            });

            return redirect()->route('semester.index')->with('success', 'Semester berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan semester: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan semester.');
        }
    }

    public function edit($id)
    {
        try {
            $semester = Semester::findOrFail($id);
            return view('semester.edit', compact('semester'));
        } catch (\Exception $e) {
            return redirect()->route('semester.index')->with('error', 'Data semester tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_ajaran'   => 'required|string|max:20|unique:semester,tahun_ajaran,' . $id,
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $semester = Semester::findOrFail($id);

                $semester->update([
                    'tahun_ajaran'    => $request->tahun_ajaran,
                    'tanggal_mulai'   => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                ]);
            });

            return redirect()->route('semester.index')->with('success', 'Semester berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update semester: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui semester.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $semester = Semester::findOrFail($id);
                $semester->delete();
            });

            return redirect()->route('semester.index')->with('success', 'Semester berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus semester: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus semester.');
        }
    }
}
