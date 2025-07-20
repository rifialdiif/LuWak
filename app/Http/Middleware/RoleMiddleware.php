<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role parameter diberikan, cek apakah user memiliki role yang sesuai
        if ($role && $user->role !== $role) {
            // Jika bukan admin yang mencoba akses halaman admin, redirect ke 403
            if ($role === 'admin' && $user->role !== 'admin') {
                abort(403);
            }

            // Jika role tidak sesuai, redirect ke dashboard
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Jika tidak ada parameter role, lanjutkan
        return $next($request);
    }
}
