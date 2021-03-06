<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\KumpulTugas;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\Nilai;
use App\Models\Tugas;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MengerjakanController extends Controller
{
    public function soal($category, $mapel, Soal $soal)
    {
        // URL
        if ($category !== strtolower($soal->kategori) || $mapel !== Str::slug($soal->mapel->nama)) {
            abort(404);
        }

        if ($soal->mulai > now() || $soal->selesai < now()) {
            $soal->load(['mapel' => function($q){
                $q->with(['child' => function($r){
                    $r->where('kelas_id', Auth::user()->murid->kelas_id);
                }]);
            }]);
            return redirect(route('detail.soal',[$soal->mapel->child[0]->id, Str::slug($mapel)]))->with('errors','Soal Belum Mulai Atau Sudah Selesai');
        }

        $percobaan = Nilai::where([
            'user_id' => Auth::user()->id,
            'nilaiable_type' => 'App\Models\Soal',
            'nilaiable_id' => $soal->id
        ])->get();

        if ($soal->kategori == 'UAS' || $soal->kategori == 'UTS') {
            if (count($percobaan) >= 1) {
                return view('pages.siswa.nilai', [
                    'checker' => $percobaan,
                    'soal' => $soal
                ]);
            }
        }

        if (count($percobaan) >= 2) {

            return view('pages.siswa.nilai',[
                'checker' => $percobaan,
                'soal' => $soal
            ]);
        }

        $soal->load('mapel:id,nama');

        // SESSION ORDERBY SOAL
        if (!Session::exists(['soal', 'jawaban'])) {
            session()->push('soal', 0);
            session()->push('jawaban', 0);

            $by = array('randomize', 'id', 'isi');
            $b = array_rand($by);
            session()->push('by', $by[$b]);

            $sort = array('asc', 'desc');
            $s = array_rand($sort);
            session()->push('sort', $sort[$s]);
        }
        $order = Session::get('by');
        $as = Session::get('sort');

        $detail = $soal->detail_soal()->orderBy($order[0], $as[0])->with(['jawabans' => function ($q) {
            $q->inRandomOrder()->get();
        }])->paginate(1);

        // return $soal;
        return view('pages.siswa.soal', [
            'soal' => $soal,
            'detail' => $detail,
            'toggle' => 'null', // untuk hidden toggle navbar
        ]);
    }

    public function slide(Request $request, $category, $mapel, Soal $soal)
    {
        if ($category !== strtolower($soal->kategori) || $mapel !== Str::slug($soal->mapel->nama)) {
            abort(404);
        }

        $data = $request->all();

        $soal = Session::get('soal');
        $jawaban = Session::get('jawaban');

        if (isset($data['jawaban'])) {
            $search_soal = array_search($data['soal'], $soal);
            $search_jwb = array_search($data['jawaban'], $jawaban);
            if ($search_soal == false && $search_jwb == false) {
                session()->push('jawaban', $data['jawaban']);
                session()->push('soal', $data['soal']);
            } elseif ($search_soal == true && $search_jwb == false) {
                session()->pull('soal.'. $search_soal);
                session()->pull('jawaban.'. $search_soal);
                session()->push('jawaban', $data['jawaban']);
                session()->push('soal', $data['soal']);
            }
        }

        return redirect($request->fullUrl());
    }

    public function tugas(Tugas $tugas, $judul)
    {
        if ($tugas->mulai > now() || $tugas->selesai < now()) {
            abort(403);
        }

        if ($judul !== Str::slug($tugas->judul_tugas) || $tugas->kelas_id !== Auth::user()->murid->kelas_id) {
            abort(404);
        }

        $tugas->load(['mapel', 'nilais' => function($q){
            $q->where('user_id', Auth::id());
        }]);

        return view('pages.siswa.tugas.tugas',[
            'tugas' => $tugas
        ]);
    }
    
    public function kumpultugas(Request $request, Tugas $tugas, $judul)
    {
        $this->validate($request,[
            'file' => 'required|max:2048|file|mimes:doc,pdf,docx,zip,rar'
        ]);

        if ($tugas->mulai > now() || $tugas->selesai < now()) {
            abort(403);
        }

        if ($judul !== Str::slug($tugas->judul_tugas) || $tugas->kelas_id !== Auth::user()->murid->kelas_id) {
            abort(404);
        }

        $data =  $request->all();

        $data['murid_id'] = Auth::id();
        $data['file'] = $request->file('file')->store('tugas/kumpul/' . $tugas->kelas_id, 'public');
        $kumpul = KumpulTugas::where([
            'murid_id' => $data['murid_id'],
            'tugas_id' => $tugas->id
        ])->first();

        if ($kumpul !== NULL) {
            Storage::delete('public/'. $kumpul->file);
            $tugas->kumpultugas()->update([
                'file' => $data['file']
            ]);
        } else {
            $tugas->kumpultugas()->create($data);
        }

        return redirect(route('murid.mapel', Str::slug($tugas->mapel->nama)))->with('success', 'Tugas Berhasil Dikumpulkan');
    }
}
