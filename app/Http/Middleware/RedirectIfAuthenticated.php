<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Cegah Admin/CS yang sudah login mengakses kembali halaman Login,
     * dan arahkan ke dashboard sesuai guard yang sedang aktif — bukan
     * redirect generik ke "/", karena ada dua kemungkinan tujuan (Admin
     * vs CS) tergantung guard mana yang ter-autentikasi.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect($guard === 'admin' ? route('admin.dashboard') : route('cs.dashboard'));
            }
        }

        return $next($request);
    }
}