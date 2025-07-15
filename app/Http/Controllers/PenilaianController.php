<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $kelasId     = $request->kelas;
        $semesterId  = $request->semester;
        $mapelId     = $request->mapel;

        $kelasList    = Kelas::all();
        $semesterList = Semester::all();

        $guru = Auth::user()->guru;

        $mapelList = [];
        if ($guru) {
            $mapelList = Mapel::whereHas('jadwal', function ($q) use ($guru) {
                $q->where('id_guru', $guru->id);
            })->get();
        }

        $siswaList    = collect();
        $penilaianMap = [];

        if ($kelasId && $semesterId && $mapelId) {
            $siswaList     = Siswa::where('id_kelas', $kelasId)->get();
            $penilaianList = Penilaian::where('id_kelas', $kelasId)
                ->where('id_semester', $semesterId)
                ->where('id_mapel', $mapelId)
                ->get();

            foreach ($penilaianList as $item) {
                $penilaianMap[$item->id_siswa] = $item;
            }
        }

        return view('guru.penilaian.index', compact(
            'kelasList', 'semesterList', 'mapelList', 'siswaList', 'penilaianMap'
        ));
    }

    public function store(Request $request)
    {
        if ($request->wantsJson()) {
            try {
                $idKelas = $request->id_kelas;
                if (empty($idKelas) && $request->id_siswa) {
                    $siswa   = Siswa::find($request->id_siswa);
                    $idKelas = $siswa ? $siswa->id_kelas : null;
                }
                if (empty($idKelas)) {
                    return response()->json(['success' => false, 'message' => 'id_kelas wajib diisi']);
                }

                $nh1 = (float) ($request->nh1 ?? 0);
                $nh2 = (float) ($request->nh2 ?? 0);
                $nh3 = (float) ($request->nh3 ?? 0);
                $uts = (float) ($request->uts ?? 0);
                $uas = (float) ($request->uas ?? 0);

                $rata_nh = ($nh1 + $nh2 + $nh3) / 3;
                $rapot   = round(($rata_nh * 0.5) + ($uts * 0.2) + ($uas * 0.3), 2);

                $data = [
                    'id_siswa'    => $request->id_siswa,
                    'id_kelas'    => $idKelas,
                    'id_semester' => $request->id_semester,
                    'id_mapel'    => $request->id_mapel,
                    'nh1'         => $nh1,
                    'nh2'         => $nh2,
                    'nh3'         => $nh3,
                    'uts'         => $uts,
                    'uas'         => $uas,
                    'rata_rata'   => round($rata_nh, 2),
                    'rapot'       => $rapot,
                ];

                $penilaian = Penilaian::create($data);

                return response()->json(['success' => true, 'id' => $penilaian->id]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        try {
            $penilaian = Penilaian::findOrFail($id);

            if ($request->isJson()) {
                $field = $request->keys()[0];
                $value = (float) $request->input($field);

                $allowed = ['nh1', 'nh2', 'nh3', 'uts', 'uas'];
                if (!in_array($field, $allowed)) {
                    return response()->json(['success' => false, 'message' => 'Field tidak valid'], 400);
                }

                $penilaian->$field = $value;

                if (in_array($field, ['nh1', 'nh2', 'nh3'])) {
                    $nh1 = $field === 'nh1' ? $value : $penilaian->nh1;
                    $nh2 = $field === 'nh2' ? $value : $penilaian->nh2;
                    $nh3 = $field === 'nh3' ? $value : $penilaian->nh3;
                    $penilaian->rata_rata = round(($nh1 + $nh2 + $nh3) / 3, 2);
                }

                $rata_nh = ($penilaian->nh1 + $penilaian->nh2 + $penilaian->nh3) / 3;
                $penilaian->rata_rata = round($rata_nh, 2);
                $penilaian->rapot = round(($rata_nh * 0.5) + ($penilaian->uts * 0.2) + ($penilaian->uas * 0.3), 2);

                $penilaian->save();

                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('error', 'Hanya menerima permintaan JSON');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $nilai = Penilaian::findOrFail($id);

        return response()->json([
            'nh1'       => $nilai->nh1,
            'nh2'       => $nilai->nh2,
            'nh3'       => $nilai->nh3,
            'uts'       => $nilai->uts,
            'uas'       => $nilai->uas,
            'rata_rata' => number_format($nilai->rata_rata, 2),
            'rapot'     => $nilai->rapot,
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $nilai = Penilaian::findOrFail($id);
                $nilai->delete();
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function siswaIndex()
    {
        $user = Auth::user();
        if (!$user || !$user->siswa) {
            abort(403, 'Tidak memiliki akses');
        }

        $siswa   = $user->siswa;
        $siswaId = $siswa->id;

        $mapelList = Mapel::all();
        $penilaianList = Penilaian::where('id_siswa', $siswaId)->get()->groupBy('id_mapel');

        return view('siswa.penilaian.index', compact('mapelList', 'penilaianList'));
    }

    public function siswaShowDetail($mapelId)
    {
        $user = Auth::user();
        if (!$user || !$user->siswa) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $nilai = Penilaian::where('id_siswa', $user->siswa->id)
            ->where('id_mapel', $mapelId)
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$nilai) {
            return response()->json([
                'nh1' => 0, 'nh2' => 0, 'nh3' => 0,
                'uts' => 0, 'uas' => 0,
                'rata_rata' => 0,
                'rapot' => 0,
            ]);
        }

        return response()->json([
            'nh1'       => $nilai->nh1,
            'nh2'       => $nilai->nh2,
            'nh3'       => $nilai->nh3,
            'uts'       => $nilai->uts,
            'uas'       => $nilai->uas,
            'rata_rata' => $nilai->rata_rata,
            'rapot'     => $nilai->rapot,
        ]);
    }
}
