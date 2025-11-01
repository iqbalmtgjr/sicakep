<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetKinerja extends Model
{
    use HasFactory;

    protected $table = 'target_kinerja';

    protected $fillable = [
        'user_id',
        'indikator_kinerja_id',
        'periode_id',
        'target',
        'satuan',
        'keterangan',
    ];

    protected $casts = [
        'target' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function indikatorKinerja()
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function realisasiKinerja()
    {
        return $this->hasMany(RealisasiKinerja::class);
    }

    // Helper Methods
    public function getTotalRealisasi()
    {
        return $this->realisasiKinerja()
            ->where('status', 'verified')
            ->sum('realisasi');
    }

    public function getPersentaseCapaian()
    {
        $totalRealisasi = $this->getTotalRealisasi();
        if ($this->target == 0) return 0;
        return ($totalRealisasi / $this->target) * 100;
    }
}
