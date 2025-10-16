<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiKinerja extends Model
{
    use HasFactory;

    protected $table = 'realisasi_kinerja';

    protected $fillable = [
        'target_kinerja_id',
        'user_id',
        'tanggal_realisasi',
        'realisasi',
        'keterangan',
        'bukti_file',
        'status',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tanggal_realisasi' => 'date',
        'realisasi' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function targetKinerja()
    {
        return $this->belongsTo(TargetKinerja::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPeriode($query, $periodeId)
    {
        return $query->whereHas('targetKinerja', function ($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        });
    }
}
