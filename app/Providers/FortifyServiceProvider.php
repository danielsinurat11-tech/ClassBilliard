<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // 1. Tentukan View Login - Redirect jika sudah login
        Fortify::loginView(function (Request $request) {
            // Jika user sudah login, redirect ke dashboard sesuai role
            if (Auth::check()) {
                $role = Auth::user()->role;
                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'kitchen') {
                    return redirect()->route('dapur');
                }
                return redirect()->route('home');
            }
            
            return view('auth.login');
        });

        // 2. Custom Login Response (Redirect berdasarkan Role)
        $this->app->instance(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request)
                {
                    $role = auth()->user()->role;
                    
                    // Check if there's an intended URL and it's an admin/kitchen route
                    $intended = session()->pull('url.intended');
                    
                    if ($role === 'admin') {
                        // If intended URL is admin route, use it; otherwise go to admin dashboard
                        if ($intended && (str_contains($intended, '/admin') || str_contains($intended, '/dapur'))) {
                            return redirect($intended);
                        }
                        // Always redirect admin to admin dashboard
                        return redirect()->route('admin.dashboard');
                    } elseif ($role === 'kitchen') {
                        // If intended URL is kitchen route, use it; otherwise go to kitchen dashboard
                        if ($intended && str_contains($intended, '/dapur')) {
                            return redirect($intended);
                        }
                        // Always redirect kitchen to dapur
                        return redirect()->route('dapur');
                    }

                    // Jika tidak ada role yang cocok, redirect ke home
                    return redirect('/');
                }
            }
        );

        // 3. Custom Logout Response (Redirect ke halaman login)
        $this->app->instance(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            new class implements \Laravel\Fortify\Contracts\LogoutResponse {
                public function toResponse($request)
                {
                    return redirect('/login')->with('success', 'Anda telah berhasil logout.');
                }
            }
        );

        // Rate Limiter bawaan tetap dipertahankan
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
