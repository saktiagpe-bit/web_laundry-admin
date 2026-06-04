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
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pesanan Saya</h1>
        
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
            @if($orders->isEmpty())
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
                                <th class="p-4 font-medium">Penyerahan</th>
                                <th class="p-4 font-medium">Total</th>
                                <th class="p-4 font-medium">Status</th>
                                <th class="p-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="p-4 font-bold text-pink-dark">{{ $order->transaction_number }}</td>
                                    <td class="p-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="p-4 text-gray-600">{{ $order->pickup_type == 'driver' ? 'Kurir' : 'Outlet' }}</td>
                                    <td class="p-4 font-medium text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                                            {{ $order->status == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('dashboard.order-detail', $order->id) }}" class="text-pink-500 hover:text-pink-700 p-2 bg-pink-50 rounded-lg inline-block font-medium" title="Detail">
                                            Detail
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
