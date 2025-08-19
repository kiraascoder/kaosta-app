<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // Biarkan ini sebagai default

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Define the user redirection path after authentication.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        // Periksa peran pengguna yang sedang login
        if (auth()->check()) {
            $userRole = auth()->user()->role;

            // Arahkan Admin dan Sales ke dashboard utama
            if (in_array($userRole, ['Admin', 'Sales'])) { // Sales sekarang diarahkan ke /dashboard
                return '/dashboard';
            } 
            // Arahkan peran Pekerja lainnya (selain Sales) ke dashboard pekerja
            elseif (in_array($userRole, ['Desain Grafis', 'Pemotong Kain', 'Penjahit', 'Sablon', 'Quality Control'])) {
                return '/pekerja/dashboard'; 
            } else {
                // Fallback untuk peran yang tidak dikenali atau tidak memiliki rute spesifik.
                // Jika user terautentikasi tapi perannya tidak cocok di atas, arahkan ke dashboard utama
                // Ini bisa disesuaikan jika ada peran lain yang butuh pengalihan berbeda
                return '/dashboard'; // Default ke dashboard utama jika peran tidak terdaftar di atas
            }
        }
        // Fallback jika tidak ada user terautentikasi (seharusnya tidak terjadi setelah berhasil login)
        return '/login'; 
    }
}
