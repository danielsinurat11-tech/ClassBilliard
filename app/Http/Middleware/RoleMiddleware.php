<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login (middleware auth sudah handle ini, tapi double check)
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika role tidak sesuai, tendang ke halaman home
        if (Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}