<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsMentor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek User Mentor
        if (Auth::check() && Auth::user()->role !== 'mentor') {
            abort(403, 'Akses Ditolak. Khusus Mentor.');
        }

        return $next($request);
    }
}