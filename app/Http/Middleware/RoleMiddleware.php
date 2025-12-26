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
            session()->put('url.intended', $request->fullUrl());
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika role tidak sesuai, tendang ke halaman home dengan pesan error
        if (Auth::user()->role !== $role) {
            $userRole = Auth::user()->role ?? 'tidak ada';
            $requiredRole = $role === 'admin' ? 'Admin' : ($role === 'kitchen' ? 'Dapur' : ucfirst($role));
            
            return redirect('/')->with('error', "Anda tidak memiliki akses. Halaman ini hanya untuk role: {$requiredRole}. Role Anda: " . ($userRole === 'admin' ? 'Admin' : ($userRole === 'kitchen' ? 'Dapur' : ucfirst($userRole))));
        }

        return $next($request);
    }
}