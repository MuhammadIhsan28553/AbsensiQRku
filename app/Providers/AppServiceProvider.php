<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Gate; // <-- TAMBAHKAN INI

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
        // TAMBAHKAN KODE GATE DI SINI
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
