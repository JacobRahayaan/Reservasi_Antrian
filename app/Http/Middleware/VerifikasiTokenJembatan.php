<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifikasiTokenJembatan
{
    /**
     * Melindungi endpoint API laptop jembatan dari akses tak sah. Karena
     * endpoint ini menerima request dari internet (bukan dari sesi login
     * Admin/CS), proteksinya memakai token statis sederhana yang dikirim
     * lewat header, dibandingkan dengan nilai di .env — bukan sistem
     * autentikasi penuh, cukup untuk skala satu perangkat jembatan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenDikirim = $request->header('X-Bridge-Token');
        $tokenValid = config('services.jembatan_antrean.token');

        if (! $tokenValid || $tokenDikirim !== $tokenValid) {
            abort(401, 'Token jembatan tidak valid.');
        }

        return $next($request);
    }
}