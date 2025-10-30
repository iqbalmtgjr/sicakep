<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'kode_bidang',
        'deskripsi',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function pegawai()
    {
        return $this->hasMany(User::class)->where('role', 'pegawai');
    }

    public function userBidangs()
    {
        return $this->belongsToMany(User::class, 'user_bidang');
    }
}
