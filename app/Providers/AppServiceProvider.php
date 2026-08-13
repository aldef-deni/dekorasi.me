<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Vuexy & halaman depan memakai Bootstrap 5, bukan Tailwind bawaan Laravel.
        Paginator::useBootstrapFive();

        // Di hosting berbasis proxy/SSL, paksa skema HTTPS agar aset tidak diblokir.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
