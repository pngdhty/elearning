<?php

namespace App\Http\Controllers\Umum;

use App\Events\PengumumanEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengumumanRequest;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pengumuman;
use App\User;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{

    public function index()
    {
        if (Auth::user()->is_guru) {
            $layout = 'guru';
            $kelas = Mapel::where('guru_id', Auth::id())->with('kelas')->get();
            $pengumuman = Pengumuman::with(['kelas', 'author'])->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        } else {
            $layout = 'admin';
            $kelas = Kelas::all();
            $pengumuman = Pengumuman::with(['kelas', 'author'])->orderBy('created_at', 'desc')->get();
        }

        return view('pages.umum.pengumuman.index',[
            'pengumuman' => $pengumuman,
            'kelas' => $kelas,
            'layout' => $layout
        ]);
    }

    public function show(Pengumuman $pengumuman)
    {
        $layout = 'admin';
        if (Auth::user()->is_guru) {
            $layout = 'guru';
        } elseif (Auth::user()->role == 2) {
            $layout = 'murid';
        }

        return view('pages.umum.pengumuman.detail',[
            'pengumuman' => $pengumuman,
            'layout' => $layout
        ]);
    }

    public function create()
    {
        $layout = 'admin';
        if (Auth::user()->is_guru) {
            $kelas = Mapel::where('guru_id', Auth::id())->with('kelas')->get();
            $layout = 'guru';
            // return $kelas;
            return view('pages.umum.pengumuman.tambah',[
                'kelas' => $kelas,
                'layout' => $layout
            ]);
        }

        return view('pages.umum.pengumuman.tambah',[
            'layout' => $layout
        ]);
    }

    public function store(PengumumanRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman/', 'public');
        } else {
            $data['gambar'] = null;
        }
        // return $data['kelas'];
        
        $pengumuman = Pengumuman::create([
            'judul' => $data['judul'],
            'gambar' => $data['gambar'],
            'isi' => $data['isi'],
            'user_id' => Auth::id()
        ]);
            
        if (Auth::user()->is_guru) {
            $kelas = Kelas::find($data['kelas']);
            $pengumuman->kelas()->attach($kelas);
            $email = User::select('email')->whereHas('murid', function($q) use($data){
                $q->whereIn('kelas_id', $data['kelas']);
            })->get()->pluck('email');
        } else {
            $email = User::select('email')->has('murid')->get()->pluck('email');
        }
        event(new PengumumanEvent($pengumuman, $email));

        return redirect(route('pengumuman.index'))->with('success', 'Pengumuman Berhasil Dibuat');
    }

    public function edit(Pengumuman $pengumuman)
    {
        $layout = 'admin';
        if (Auth::user()->is_guru) {
            $pengumuman->load('kelas');
            $kelas = Mapel::where('guru_id', Auth::id())->with('kelas')->get();
            $layout = 'guru';

            return view('pages.umum.pengumuman.edit', [
                'pengumuman' => $pengumuman,
                'kelas' => $kelas,
                'layout' => $layout
            ]);
        }

        return view('pages.umum.pengumuman.edit',[
            'pengumuman' => $pengumuman,
            'layout' => $layout
        ]);
    }

    public function update(PengumumanRequest $request, Pengumuman $pengumuman)
    {
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman/', 'public');
            $pengumuman->update([
                'judul' => $data['judul'],
                'gambar' => $data['gambar'],
                'isi' => $data['isi'],
                'user_id' => Auth::id()
            ]);
        } else {
            $pengumuman->update([
                'judul' => $data['judul'],
                'isi' => $data['isi'],
                'user_id' => Auth::id()
            ]);
        }

        if (Auth::user()->is_guru) {
            $kelas = Kelas::find($data['kelas']);
            $pengumuman->kelas()->attach($kelas);
        }

        return redirect(route('pengumuman.index'))->with('success', 'Pengumuman Berhasil Diupdate');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if (Auth::user()->is_guru) {
            $k = [];

            foreach ($pengumuman->kelas as $kelas) {
                $k = $kelas->id;
            }

            $pengumuman->kelas()->detach($k);
        }
        
        $pengumuman->delete();
        return redirect(route('pengumuman.index'))->with('success', 'Pengumuman Berhasil Dihapus');
    }
}
