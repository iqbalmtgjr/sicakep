<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'email',
        'password',
        'role',
        'bidang_id',
        'pangkat_golongan',
        'jabatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // Relationships
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function indikatorKinerja()
    {
        return $this->hasMany(IndikatorKinerja::class);
    }

    public function targetKinerja()
    {
        return $this->hasMany(TargetKinerja::class);
    }

    public function realisasiKinerja()
    {
        return $this->hasMany(RealisasiKinerja::class);
    }

    public function penilaianKinerja()
    {
        return $this->hasMany(PenilaianKinerja::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    // Scopes
    public function scopePegawai($query)
    {
        return $query->where('role', 'pegawai');
    }

    public function scopeAtasan($query)
    {
        return $query->where('role', 'atasan');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeByBidang($query, $bidangId)
    {
        return $query->where('bidang_id', $bidangId);
    }

    // Helper Methods
    public function isPegawai()
    {
        return $this->role === 'pegawai';
    }

    public function isAtasan()
    {
        return $this->role === 'atasan';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
