<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PenilaianKinerja;
use App\Models\RealisasiKinerja;
use App\Observers\PenilaianKinerjaObserver;
use App\Observers\RealisasiKinerjaObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register PenilaianKinerja Observer
        PenilaianKinerja::observe(PenilaianKinerjaObserver::class);

        // Register RealisasiKinerja Observer
        RealisasiKinerja::observe(RealisasiKinerjaObserver::class);
    }
}
