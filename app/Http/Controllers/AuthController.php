<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Proses pencocokan kredensial login ke database
        if (Auth::attempt($credentials)) {
            // Regenerasi session ID untuk mencegah session hijacking
            $request->session()->regenerate();

            // Jika OTP aktif (route otp.index ada) dan user belum verifikasi
            // $user = Auth::user();
            // if (\Route::has('otp.index') && !$user->phone_verified_at) {
            //     return redirect()->route('otp.index')->with('warning', 'Silakan lakukan verifikasi OTP terlebih dahulu.');
            // }

            return redirect()->intended('services');
        }

        // Kembalikan error jika data login tidak cocok
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi data masukan dari form register
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:users',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
        ], [
            'email.unique' => 'Akun sudah terdaftar, silakan login.',
            'phone.unique' => 'Nomor HP sudah terdaftar, silakan login.',
        ]);

        // Simpan data user baru dengan password terenkripsi bcrypt
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => 'user'
        ]);

        // Otomatis login setelah berhasil membuat akun
        Auth::login($user);

        // Jika OTP aktif (route otp.index ada) dan user belum verifikasi, arahkan ke OTP
        // if (\Route::has('otp.index') && !$user->phone_verified_at) {
        //     return redirect()->route('otp.index')->with('success', 'Registrasi berhasil! Silakan verifikasi nomor HP Anda.');
        // }

        return redirect()->route('services.index')->with('success', 'Registrasi berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
