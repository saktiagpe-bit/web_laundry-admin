<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneVerification;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function index()
    {
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

        // Generate 6 digit OTP
        $code = rand(100000, 999999);
        
        PhoneVerification::updateOrCreate(
            ['phone' => $user->phone],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'verified_at' => null
            ]
        );

        // Simulated SMS - we flash the OTP code to session so we can display it on the UI
        return back()->with('simulated_otp', "SIMULATION OTP SMS: Kode verifikasi Anda adalah {$code}");
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        
        $verification = PhoneVerification::where('phone', $user->phone)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->whereNull('verified_at')
            ->first();

        if (!$verification) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        }

        // Mark as verified
        $verification->update(['verified_at' => now()]);
        
        // Update user
        $user->update(['phone_verified_at' => now()]);

        return redirect()->route('services.index')->with('success', 'Nomor telepon berhasil diverifikasi!');
    }
}
