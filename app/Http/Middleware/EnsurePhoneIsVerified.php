<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ==========================================
        // UNCOMMENT BLOK DI BAWAH INI UNTUK MENGAKTIFKAN REDIRECT OTP JIKA BELUM VERIFIKASI:
        // if ($request->user() && !$request->user()->phone_verified_at) {
        //     return redirect()->route('otp.index');
        // }
        // ==========================================

        return $next($request);
    }
}
