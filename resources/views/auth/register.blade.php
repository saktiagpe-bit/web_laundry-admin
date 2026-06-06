@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-pink-100 text-pink-500 mb-4">
                <i data-feather="user-plus" class="w-8 h-8"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-600">Bergabunglah dengan YURE Laundry hari ini.</p>
        </div>
        <form class="space-y-4" action="{{ route('register') }}" method="POST">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}" class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
                </div>
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select id="gender" name="gender" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                </select>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3" required class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">{{ old('address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-pink-500 focus:border-pink-500 bg-gray-50">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white btn-pink focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-300">
                    Daftar Sekarang
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-pink-600 hover:text-pink-500">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
