@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-xl border border-pink-100 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-pink-100 text-pink-500 mb-6">
            <i data-feather="smartphone" class="w-10 h-10"></i>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Verifikasi OTP</h2>
        <p class="text-sm text-gray-600 mb-8">Demi keamanan dan mengurangi pesanan palsu, silakan verifikasi nomor telepon Anda.</p>
        
        <!-- Simulasi Kirim OTP -->
        <form action="{{ route('otp.send') }}" method="POST" class="mb-6">
            @csrf
            <button type="submit" class="w-full btn-pink py-3 rounded-xl font-bold flex justify-center items-center gap-2">
                <i data-feather="send" class="w-4 h-4"></i> Kirim Kode OTP (Simulasi)
            </button>
        </form>

        <hr class="border-pink-100 mb-6">

        <!-- Form Verifikasi -->
        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 text-left">Masukkan Kode 6 Digit</label>
                <input id="code" name="code" type="text" maxlength="6" required class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-center text-2xl tracking-[0.5em] focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
            </div>
            
            <button type="submit" class="w-full bg-gray-800 text-white hover:bg-gray-700 py-3 rounded-xl font-bold transition duration-300">
                Verifikasi
            </button>
        </form>
    </div>
</div>
@endsection
