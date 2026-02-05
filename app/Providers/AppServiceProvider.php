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
        // 1. Definisi Hak Akses Admin (Gate)
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        // 2. Konfigurasi Khusus Production (Vercel)
        if ($this->app->environment('production')) {
            // A. Paksa HTTPS agar tampilan (CSS/JS) tidak berantakan karena Mixed Content
            URL::forceScheme('https');

            // B. PERBAIKAN DATABASE SSL (Wajib untuk Aiven/Vercel)
            // Kita cari lokasi file sertifikat secara dinamis saat aplikasi berjalan menggunakan base_path()
            // Ini akan menghasilkan path absolut yang benar (misal: /var/task/user/storage/keys/ca.pem)
            $caPath = base_path('storage/keys/ca.pem');
            
            // Cek apakah file benar-benar ada
            if (file_exists($caPath)) {
                // Timpa konfigurasi database secara runtime agar menggunakan path yang valid
                config(['database.connections.mysql.options.' . \PDO::MYSQL_ATTR_SSL_CA => $caPath]);
            }
        }
    }
}
