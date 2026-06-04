@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row gap-8">
    
    <!-- Sidebar Menu -->
    <div class="md:w-1/4">
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
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('dashboard.index') ? 'bg-pink-500 text-white' : 'hover:bg-pink-50 text-gray-700' }} transition font-medium">
                    <i data-feather="grid" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('dashboard.orders') ? 'bg-pink-500 text-white' : 'hover:bg-pink-50 text-gray-700' }} transition font-medium">
                    <i data-feather="list" class="w-5 h-5"></i> Riwayat Pesanan
                </a>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('dashboard.profile') ? 'bg-pink-500 text-white' : 'hover:bg-pink-50 text-gray-700' }} transition font-medium">
                    <i data-feather="user" class="w-5 h-5"></i> Profil Saya
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:w-3/4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Dashboard</h1>
        
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-r from-pink-400 to-pink-500 rounded-3xl p-6 text-white shadow-lg flex items-center justify-between hover:scale-105 transition transform">
                <div>
                    <p class="text-pink-100 font-medium mb-1">Pesanan Aktif</p>
                    <h3 class="text-4xl font-extrabold">{{ $active_orders }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl">
                    <i data-feather="loader" class="w-8 h-8 text-white"></i>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-100 flex items-center justify-between hover:scale-105 transition transform">
                <div>
                    <p class="text-gray-500 font-medium mb-1">Total Pesanan Selesai</p>
                    <h3 class="text-4xl font-extrabold text-gray-800">{{ $total_orders - $active_orders }}</h3>
                </div>
                <div class="bg-green-50 p-4 rounded-2xl">
                    <i data-feather="check-circle" class="w-8 h-8 text-green-500"></i>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-800">Pesanan Terakhir</h2>
                <a href="{{ route('dashboard.orders') }}" class="text-sm font-medium text-pink-600 hover:text-pink-800">Lihat Semua</a>
            </div>
            
            @if($recent_orders->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                    <p>Belum ada riwayat pesanan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm">
                                <th class="p-4 font-medium">TRX ID</th>
                                <th class="p-4 font-medium">Tanggal</th>
                                <th class="p-4 font-medium">Total</th>
                                <th class="p-4 font-medium">Status</th>
                                <th class="p-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders as $order)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">{{ $order->transaction_number }}</td>
                                    <td class="p-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="p-4 font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                                            {{ $order->status == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('dashboard.order-detail', $order->id) }}" class="text-pink-500 hover:text-pink-700 p-2 bg-pink-50 rounded-lg inline-block" title="Detail">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
