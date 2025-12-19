<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user login DAN role-nya adalah admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika Admin mencoba akses halaman user, lempar ke Dashboard Admin
            // Tapi ijinkan jika requestnya adalah logout (penting!)
            if (!$request->is('logout')) {
                 return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
