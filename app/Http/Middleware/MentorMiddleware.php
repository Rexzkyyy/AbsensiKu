<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MentorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = strtolower(trim((string) Auth::user()->role));
        if ($role === 'mentor' || $role === 'admin') {
            return $next($request);
        }

        if ($role !== 'magang') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'error' => 'Role akun tidak valid. Hubungi admin untuk memperbaiki data user.',
            ]);
        }

        // Jika bukan mentor/admin, redirect ke dashboard magang
        return redirect()->route('magang.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
