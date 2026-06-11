<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneVerification;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function index()
    {
        // Kalau user sudah terverifikasi nomor HP-nya, langsung arahin ke halaman utama layanan
        if (Auth::user()->phone_verified_at) {
            return redirect()->route('services.index');
        }
        return view('auth.otp');
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        if ($user->phone_verified_at) {
            return redirect()->route('services.index');
        }

        // Bikin kode OTP acak 6 digit
        $code = rand(100000, 999999);
        
        // Simpan/update kode verifikasi dengan waktu kedaluwarsa 10 menit ke depan
        PhoneVerification::updateOrCreate(
            ['phone' => $user->phone],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'verified_at' => null
            ]
        );

        // Simulasi SMS: kita simpan kodenya ke session flash biar bisa dirender di alert halaman web
        return back()->with('simulated_otp', "SIMULATION OTP SMS: Kode verifikasi Anda adalah {$code}");
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        
        // Cari kode OTP aktif yang cocok, belum kedaluwarsa, dan belum pernah diverifikasi
        $verification = PhoneVerification::where('phone', $user->phone)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->whereNull('verified_at')
            ->first();

        if (!$verification) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        }

        // Tandai kode verifikasi sudah sukses diverifikasi
        $verification->update(['verified_at' => now()]);
        
        // Update data user agar tidak ditanyai OTP lagi
        $user->update(['phone_verified_at' => now()]);

        return redirect()->route('services.index')->with('success', 'Nomor telepon berhasil diverifikasi!');
    }
}
