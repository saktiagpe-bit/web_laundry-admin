@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row gap-8">
    
    <!-- Sidebar Menu -->
    <div class="md:w-1/4">
        <!-- Reusing sidebar code from index -->
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden sticky top-24">
            <div class="p-6 bg-pink-50 border-b border-pink-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-pink-200 rounded-full flex items-center justify-center text-pink-700 font-bold text-xl">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-gray-500">{{ Auth::user()->phone }}</p>
                </div>
            </div>
            <div class="p-4 flex flex-col gap-2">
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('dashboard.profile') ? 'bg-pink-500 text-white' : 'hover:bg-pink-50 text-gray-700' }} transition font-medium">
                    <i data-feather="user" class="w-5 h-5"></i> Profil Saya
                </a>
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('dashboard.index') ? 'bg-pink-500 text-white' : 'hover:bg-pink-50 text-gray-700' }} transition font-medium">
                    <i data-feather="grid" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('dashboard.orders') ? 'bg-pink-500 text-white' : 'hover:bg-pink-50 text-gray-700' }} transition font-medium">
                    <i data-feather="list" class="w-5 h-5"></i> Riwayat Pesanan
                </a>
                
                @if(Auth::user()->role === 'admin')
                    <div class="border-t border-gray-100 my-2"></div>
                    <p class="text-[10px] font-semibold text-pink-500 px-3 uppercase tracking-wider mb-1">Panel Admin</p>
                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 text-gray-700 transition font-medium">
                        <i data-feather="trending-up" class="w-5 h-5"></i> Laporan Keuangan
                    </a>
                    <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 text-gray-700 transition font-medium">
                        <i data-feather="shopping-bag" class="w-5 h-5"></i> Manajemen Pesanan
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:w-3/4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h1>
        
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 p-8">
            <form action="{{ route('dashboard.update-profile') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email <span class="text-gray-400 text-xs">(Tidak dapat diubah)</span></label>
                        <input type="email" value="{{ $user->email }}" readonly disabled class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea name="address" rows="3" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-pink-500 focus:border-pink-500">{{ $user->address }}</textarea>
                </div>

                <hr class="my-8 border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ubah Password <span class="text-sm font-normal text-gray-500">(Kosongkan jika tidak ingin mengubah)</span></h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-pink px-8 py-3 rounded-xl font-bold shadow-md flex items-center gap-2">
                        <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
