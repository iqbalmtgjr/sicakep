<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKinerja extends Model
{
    use HasFactory;

    protected $table = 'penilaian_kinerja';

    protected $fillable = [
        'user_id',
        'periode_id',
        'total_realisasi',
        'total_target',
        'persentase_capaian',
        'nilai_kinerja',
        'predikat',
        'catatan',
        'dinilai_oleh',
        'tanggal_penilaian',
    ];

    protected $casts = [
        'total_realisasi' => 'decimal:2',
        'total_target' => 'decimal:2',
        'persentase_capaian' => 'decimal:2',
        'nilai_kinerja' => 'decimal:2',
        'tanggal_penilaian' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    // Helper Methods
    public function hitungPredikat()
    {
        $persentase = $this->persentase_capaian;

        if ($persentase >= 90) {
            return 'Sangat Baik';
        } elseif ($persentase >= 76) {
            return 'Baik';
        } elseif ($persentase >= 60) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }
}
