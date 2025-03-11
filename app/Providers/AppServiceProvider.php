<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer(
            'components.navbar', // Nama tampilan yang memerlukan data
            function ($view) {
                // Ambil data tamu
                $guests = \App\Models\Guest::all(); // Mengambil semua data tamu dari model Guest
                
                // Kirim data tamu ke tampilan
                $view->with('guests', $guests); 
            }
        );
    }
}
