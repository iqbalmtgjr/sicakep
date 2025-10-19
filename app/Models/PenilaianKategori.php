<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianKategori extends Model
{

    protected $table = 'penilaian_kategori';
    protected $fillable = [
        'kategori',
        'badge',
        'icon',
        'pesan',
        'min_nilai',
        'max_nilai',
    ];
}
