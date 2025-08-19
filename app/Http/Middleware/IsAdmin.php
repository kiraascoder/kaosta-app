<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->role !== 'Admin' && auth()->user()->role !== 'Sales') {
            abort(403, 'Akses tidak diizinkan. Hanya untuk admin dan sales.');
        }

        return $next($request);
    }
}