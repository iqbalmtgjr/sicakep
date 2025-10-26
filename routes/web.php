<?php

use Livewire\Volt\Volt;
use Laravel\Fortify\Features;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Bidang\Index as BidangIndex;
use App\Livewire\RealisasiKinerja\Verifikasi;
use App\Livewire\Periode\Index as PeriodeIndex;
use App\Livewire\Pengguna\Index as PenggunaIndex;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\TargetKinerja\Index as TargetIndex;
use App\Livewire\IndikatorKinerja\Index as IndikatorIndex;
use App\Livewire\RealisasiKinerja\Index as RealisasiIndex;
use App\Livewire\Notifikasi\Penilaian\Index as NotifikasiPenilaianIndex;
use App\Livewire\HasilKinerja\Index as HasilKinerjaIndex;
use App\Livewire\Laporan\HierarkiKinerja;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // Volt::route('settings/two-factor', 'settings.two-factor')
    //     ->middleware(
    //         when(
    //             Features::canManageTwoFactorAuthentication()
    //                 && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
    //             ['password.confirm'],
    //             [],
    //         ),
    //     )
    //     ->name('two-factor.show');

    // dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        // Pengguna
        Route::get('pengguna', PenggunaIndex::class)->name('pengguna.index');

        // Bidang
        Route::get('bidang', BidangIndex::class)->name('bidang.index');

        // Periode
        Route::get('periode', PeriodeIndex::class)->name('periode.index');

        // Indikator Kinerja
        Route::get('indikator-kinerja', IndikatorIndex::class)->name('indikator.index');

        // Target Kinerja
        Route::get('target-kinerja', TargetIndex::class)->name('target.index');
    });

    // Menu untuk Pegawai & Atasan
    Route::middleware(['role:pegawai,atasan,admin'])->group(function () {
        // Realisasi Kinerja
        Route::get('realisasi-kinerja', RealisasiIndex::class)->name('realisasi.index');

        // Notifikasi Penilaian
        Route::get('notifikasi-penilaian', NotifikasiPenilaianIndex::class)->name('notifikasi.penilaian.index');

        Route::get('hasil-kinerja', HasilKinerjaIndex::class)->name('hasil.index');
    });

    // Menu untuk Atasan & Admin (Verifikasi)
    Route::middleware(['role:atasan,admin'])->group(function () {
        // Verifikasi Realisasi
        Route::get('verifikasi-realisasi', Verifikasi::class)->name('verifikasi.index');
    });

    Route::middleware(['auth', 'role:admin,atasan'])->group(function () {
        // Laporan Hierarki
        Route::get('laporan/hierarki-kinerja', HierarkiKinerja::class)->name('laporan.hierarki');
    });
});

require __DIR__ . '/auth.php';
