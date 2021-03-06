<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Tugas;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DataAdminController extends Controller
{

    public function profile()
    {
        $admin = User::findOrFail(Auth::id());
        return view('pages.admin.profile',[
            'admin' => $admin
        ]);
    }

    public function kelas()
    {
        $kelas = Kelas::with('wali_kelas.guru')->withCount('murids')->get();

        return view('pages.admin.nilai.kelas',[
            'kelas' => $kelas
        ]);
    }

    public function detailkelas(Kelas $kelas, $kel)
    {
        $set = array_pad(explode('-', $kel), 2, null);

        if ( count($set) > 2 || $kelas->kelas !== intval($set[0]) || $kelas->kode_kelas !== $set[1]) {
            abort(404);
        }

        $mapel =  $kelas->load('mapels.guru.guru');
        // return $mapel;
        return view('pages.admin.nilai.mapel',[
            'mapel' => $mapel
        ]);
    }

    public function detailmapel(Kelas $kelas, $kel, $map, Mapel $mapel)
    {
        $set = array_pad(explode('-', $kel), 2, null);
        $rules = $map !== Str::slug($mapel->nama) || count($set) > 2 || $kelas->kelas !== intval($set[0]) || $kelas->kode_kelas !== $set[1];
        if ($rules) {
            abort(404);
        }

        $map = function ($q) use ($mapel, $kelas) {
            $q->withCount(['nilais' => function ($q) use ($kelas) {
                $q->where('percobaan', 1)->whereHas('murid.murid', function ($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id)->with('murid');
                });;
            }])->where('mapel_id', $mapel->parent_id);
        };

        $data = $kelas->load(['soal.mapel', 'soals.mapel', 'soal' => $map, 'soals' => $map]);
        // return $data;
        return view('pages.admin.nilai.soal', [
            'data' => $data
        ]);
    }

    public function detailsoal(Kelas $kelas, $kel, $map, Mapel $mapel, $so, Soal $soal)
    {
        $set = array_pad(explode('-', $kel), 2, null);
        $rules = $so !== Str::slug($soal->judul) || $map !== Str::slug($mapel->nama) || count($set) > 2 || $kelas->kelas !== intval($set[0]) || $kelas->kode_kelas !== $set[1];
        if ($rules) {
            abort(404);
        }

        $nilai =  $soal->load(['speckelas', 'nilais'  => function ($q) use ($kelas) {
            $q->whereHas('murid.murid', function ($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id)->with('murid');
            });
        }]);

        return view('pages.admin.nilai.nilai', [
            'nilai' => $nilai
        ]);
    }

    public function alltugas()
    {
        $tugas = Tugas::with([
            'kelas', 'guru', 'mapel'
        ])->get();

        return view('pages.admin.tugas.tugas',[
            'tugas' => $tugas
        ]);
    }

    public function tugasshow(Tugas $tugas)
    {
        $tugas->load(['kelas', 'kumpultugas.murid.nilais' => function ($q) use ($tugas) {
            $q->where([
                'nilaiable_id' => $tugas->id,
                'nilaiable_type' => 'App\Models\Tugas'
            ])->get();
        }]);
        // return $tugas;
        return view('pages.admin.tugas.detail', [
            'tugas' => $tugas
        ]);
    }

    public function tugasdelete(Tugas $tugas)
    {
        $tugas->kumpultugas()->delete();
        $tugas->delete();

        return redirect(route('tugas.admin'))->with('info', 'Tugas Berhasil Dihapus');
    }
}
