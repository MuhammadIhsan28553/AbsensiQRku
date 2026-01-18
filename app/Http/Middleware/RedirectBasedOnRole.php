<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika pengguna sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Lakukan redirect HANYA jika rolenya admin DAN belum berada di halaman admin
            if ($user->role === 'admin' && !$request->routeIs('admin.*')) {
                // Arahkan ke dashboard admin yang baru
                return redirect()->route('admin.dashboard');
            }
        }

        // Jika bukan admin atau belum login, lanjutkan ke tujuan awal
        return $next($request);
    }
}
