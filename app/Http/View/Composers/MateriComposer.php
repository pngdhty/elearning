<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Kelas;
use App\Models\Mapel;

class MateriComposer
{

    public function compose(View $view)
    {
        $view->with([
            'kelas' => Kelas::select('kelas')->distinct()->orderBy('kelas')->get(),
            'mapel' => Mapel::getParent()->orderBy('nama')->get()
        ]);
    }
}
