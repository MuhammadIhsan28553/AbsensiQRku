<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        // 1. Definisi Hak Akses Admin
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        // 2. Konfigurasi Khusus Production (Vercel)
        if ($this->app->environment('production')) {
            // A. Paksa HTTPS agar CSS/JS tidak error
            URL::forceScheme('https');

            // B. PERBAIKAN DATABASE SSL (Solusi Error 2002)
            // Kita timpa konfigurasi database dengan path yang benar-benar ada saat runtime
            // '/var/task' adalah root standar di Vercel Runtime
            $caPath = '/var/task/storage/keys/ca.pem';
            
            // Cek jika file ada, lalu update config database secara dinamis
            if (file_exists($caPath)) {
                config(['database.connections.mysql.options.' . \PDO::MYSQL_ATTR_SSL_CA => $caPath]);
            }
        }
    }
}
