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
        // Jika belum login atau role tidak sesuai, tendang ke halaman login/welcome
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}