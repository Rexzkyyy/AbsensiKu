<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MagangMiddleware
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
        if ($role === 'magang') {
            return $next($request);
        }

        if ($role !== 'mentor' && $role !== 'admin') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'error' => 'Role akun tidak valid. Hubungi admin untuk memperbaiki data user.',
            ]);
        }

        // Jika bukan magang, redirect ke dashboard admin
        return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
