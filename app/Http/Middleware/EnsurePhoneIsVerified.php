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
        if ($request->user() && ! $request->user()->phone_verified_at) {
            return redirect()->route('otp.index')->with('warning', 'Silakan verifikasi nomor telepon Anda terlebih dahulu sebelum memesan layanan.');
        }

        return $next($request);
    }
}
