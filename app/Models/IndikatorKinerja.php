<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorKinerja extends Model
{
    use HasFactory;

    protected $table = 'indikator_kinerja';

    protected $fillable = [
        'user_id',
        'kode_indikator',
        'sasaran_strategis',
        'nama_indikator',
        'sasaran_program',
        'indikator_program',
        'satuan',
        'target',
        // 'bobot',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'target' => 'decimal:2',
        // 'bobot' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetKinerja()
    {
        return $this->hasMany(TargetKinerja::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
