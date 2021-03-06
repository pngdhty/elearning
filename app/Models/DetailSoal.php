<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Soal;
use App\Models\Jawaban;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailSoal extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }

    public function jawabans()
    {
        return $this->hasMany(Jawaban::class, 'detail_soal_id');
    }
}
