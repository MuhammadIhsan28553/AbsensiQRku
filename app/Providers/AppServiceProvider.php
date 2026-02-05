<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL; // <-- WAJIB: Untuk perbaikan HTTPS
use App\Models\User;

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
        // 1. Definisi Hak Akses (Gate)
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        // 2. Perbaikan Style Berantakan di Vercel (Force HTTPS)
        // Kode ini memaksa Laravel menggunakan https:// saat di production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
