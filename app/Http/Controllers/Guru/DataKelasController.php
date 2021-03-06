<?php

namespace App\Http\Controllers\Guru;

use App\Exports\NilaiSoalExport;
use App\Exports\NilaiTugasExport;
use App\Exports\NilaiMapelExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Tugas;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DataKelasController extends Controller
{
    public function index($kelas)
    {
        $set = array_pad(explode('-', $kelas), 2, null);
        if (count($set) > 2 || $set[1] == null) {
            abort(404);
        }
        $kelas = Kelas::select('id')->where(['kelas' => $set[0], 'kode_kelas' => $set[1]])->first();

        if ($kelas) {

            $data = Mapel::with(['kelas' => function ($q) use ($set) {
                $q->where(['kelas' => $set[0], 'kode_kelas' => $set[1]])->with(['murids.user:id,nama', 'wali_kelas.guru:user_id,pendidikan'])->first();
            }])->where(['guru_id' => Auth::id(), 'kelas_id' => $kelas->id])->first();
            // return $data;
            return view('pages.guru.kelas.kelas', [
                'data' => $data
            ]);
        }

        abort(404);
    }

    public function soal()
    {

        $kelas = Kelas::where('guru_id', Auth::id());
        $pluck = $kelas->pluck('kelas', 'id')->toArray();
        $soals = $kelas->with(['mapels' => function($q) use ($pluck) {
            $q->with('kelas.wali_kelas.guru')->where('guru_id', Auth::id())->withCount(['soals as soal_umum' => function ($q) use ($pluck) {
                $q->whereIn('kelas', array_values($pluck));
            }, 'soals as soal_kelas' => function ($q) use ($pluck) {
                $q->whereIn('kelas_id', array_keys($pluck));
            }]);
        }])->get();
        // return $soals;
        return view('pages.guru.detail.soal',[
            'soals' => $soals
        ]);
    }

    public function detailsoal($kelas, $m, Mapel $mapel)
    {
        $kel = array_pad(explode('-', $kelas), 2, null);
        if ($m !== Str::slug($mapel->nama) || $mapel->parent_id == null || count($kel) > 2) {
            abort(404);
        }

        $kelas = Kelas::where(['kelas' => $kel[0], 'kode_kelas' => $kel[1]])->first();

        if ($kelas) {

            $map = function($q) use ($mapel, $kelas) {
                $q->withCount(['nilais' => function($q) use ($kelas) {
                    $q->where('percobaan', 1)->orWhere('percobaan', null)->whereHas('murid.murid', function ($q) use ($kelas) {
                        $q->where('kelas_id', $kelas->id)->with('murid');
                    });
                }])->where('mapel_id', $mapel->parent_id);
            };

            // $data = $kelas->load(['soal.mapel', 'soals.mapel', 'soal' => $map, 'soals' => $map]);
            $data = $kelas->load([
                'mapels' => function ($q) use ($kelas, $mapel) {
                    $q->where([
                        'guru_id' => Auth::id(),
                        'parent_id' => $mapel->parent_id
                    ])->with([
                        'soals' => function ($q) use ($kelas) {
                            $q->where(function ($r) use ($kelas) {
                                $r->where([
                                    'kelas_id' => $kelas->id,
                                    'guru_id' => Auth::id()
                                ]);
                            })->orWhere('kelas', $kelas->kelas)->withCount(['nilais' => function ($q) use($kelas) {
                                $q->where('percobaan', null)->Orwhere('percobaan', 1);
                            }]);
                        }
                    ]);
                }
            ]);
            // return $data;
            return view('pages.guru.detail.list',[
                'data' => $data
            ]);
        }

        abort(404);
    }

    public function detailmurid(Kelas $kelas, Soal $soal, $judul)
    {
        if ($judul !== Str::slug($soal->judul)) {
            abort(404);
        }

        $nilai =  $soal->load(['speckelas', 'nilais'  => function ($q) use ($kelas) {
            $q->with('murid.murid')->whereHas('murid.murid', function($q) use ($kelas) {
                $q->where('kelas_id', $kelas->id);
            })->selectRaw('nilaiable_type, nilaiable_id, max(nilai) as nilai, user_id')->groupBy('user_id', 'nilaiable_id', 'nilaiable_type');
        }]);
        // return $nilai;
         return view('pages.guru.detail.nilai',[
             'nilai' => $nilai
         ]);
    }

    public function exportNilai($id, $kelas)
    {
        switch (Route::currentRouteName()) {
            case 'soal.nilai.export':
                $s = Soal::findOrFail($id);
                return (new NilaiSoalExport)->soalKelas($id, $kelas)->download('Soal - ' . $s->judul . '.xlsx');
            break;
            case 'tugas.nilai.export':
                $t = Tugas::findOrfail($id);
                return (new NilaiTugasExport)->soalKelas($id, $kelas)->download('Tugas - ' . $t->judul_tugas . '.xlsx');
            break;
            default:
                $m = Mapel::where([
                    'parent_id' => $id,
                    'guru_id' => Auth::id(),
                    'kelas_id' => $kelas
                    ])->firstOrFail();
                return (new NilaiMapelExport)->soalKelas($id, $kelas)->download('Nilai - '. $m->nama .'.xlsx');
            break;
        }
    }
}
