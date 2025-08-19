<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsPekerja
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Daftar peran yang diizinkan untuk mengakses dashboard pekerja
        $allowedRoles = [
            'Desain Grafis',
            'Pemotong Kain',
            'Penjahit',
            'Sablon',
            'Quality Control',
        ];

        // --- Perbaikan: Bersihkan (trim) peran pengguna sebelum perbandingan ---
        $userRole = $user ? trim($user->role) : null; // Pastikan peran dibersihkan dari spasi

        // Jika user tidak login, atau perannya tidak ada dalam daftar yang diizinkan
        if (!$user || !in_array($userRole, $allowedRoles)) { // Gunakan $userRole yang sudah di-trim
            // Jika user login tapi perannya tidak diizinkan untuk rute pekerja ini,
            // dan dia adalah Admin atau Sales, arahkan ke dashboard utama mereka.
            if (Auth::check() && in_array(Auth::user()->role, ['Admin', 'Sales'])) {
                Log::warning('Unauthorized access attempt to pekerja dashboard by Admin/Sales: ' . Auth::user()->email);
                return redirect()->route('dashboard'); // Arahkan Admin/Sales kembali ke dashboard admin
            }
            // Jika bukan Admin/Sales dan bukan pekerja yang diizinkan, blokir akses
            Log::error('Unauthorized access to pekerja dashboard: ' . ($user ? $user->email . ' with role ' . $user->role : 'Guest'));
            abort(403, 'Akses tidak sah. Hanya untuk peran pekerja yang valid.');
        }

        return $next($request);
    }
}
