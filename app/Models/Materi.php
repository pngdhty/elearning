<?php

namespace App\Models;

use App\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materi extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // Relathionship

    public function parentKelas() // untuk group kelas
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'kelas');
    }

    public function kelas_spec() // untuk specific kelas
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
