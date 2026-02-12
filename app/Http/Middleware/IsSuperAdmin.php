<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        // 2. Cek apakah role user adalah 'super_admin'
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request); // Izinkan akses masuk
        }

        // Jika bukan super_admin, lempar error 403 (Forbidden)
        abort(403, 'Akses Ditolak. Halaman ini hanya untuk Super Admin.');
    }
}