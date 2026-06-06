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
                    <p class="text-xs text-gray-500">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'User' }}</p>
                </div>
            </div>
            <div class="p-4 flex flex-col gap-2">
                <!-- Customer Menu -->
                <p class="text-xs font-semibold text-gray-400 px-3 uppercase tracking-wider mb-1">Menu Pelanggan</p>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 text-gray-700 transition font-medium">
                    <i data-feather="user" class="w-5 h-5"></i> Profil Saya
                </a>
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 text-gray-700 transition font-medium">
                    <i data-feather="grid" class="w-5 h-5"></i> Dashboard Saya
                </a>

                <div class="border-t border-gray-100 my-2"></div>

                <!-- Admin Menu -->
                <p class="text-xs font-semibold text-pink-500 px-3 uppercase tracking-wider mb-1">Panel Admin</p>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 text-gray-700 transition font-medium">
                    <i data-feather="trending-up" class="w-5 h-5"></i> Laporan Keuangan
                </a>
                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 p-3 rounded-xl bg-pink-500 text-white transition font-medium shadow-md shadow-pink-200">
                    <i data-feather="shopping-bag" class="w-5 h-5"></i> Manajemen Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:w-3/4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">Manajemen Status Pesanan</h1>
                <p class="text-gray-500 mt-1">Ubah pelacakan status pesanan secara langsung.</p>
            </div>
        </div>

        <!-- Filter Status tabs -->
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-pink-100 mb-8 flex flex-wrap gap-2">
            <a href="{{ route('admin.orders', ['status' => 'semua']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'semua' ? 'bg-pink-500 text-white shadow-sm' : 'bg-pink-50 text-gray-600 hover:bg-pink-100' }}">Semua</a>
            <a href="{{ route('admin.orders', ['status' => 'Antre']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'Antre' ? 'bg-pink-500 text-white shadow-sm' : 'bg-pink-50 text-gray-600 hover:bg-pink-100' }}">Antre</a>
            <a href="{{ route('admin.orders', ['status' => 'Diproses']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'Diproses' ? 'bg-pink-500 text-white shadow-sm' : 'bg-pink-50 text-gray-600 hover:bg-pink-100' }}">Diproses</a>
            <a href="{{ route('admin.orders', ['status' => 'Selesai']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'Selesai' ? 'bg-pink-500 text-white shadow-sm' : 'bg-pink-50 text-gray-600 hover:bg-pink-100' }}">Selesai</a>
            <a href="{{ route('admin.orders', ['status' => 'Batal']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'Batal' ? 'bg-pink-500 text-white shadow-sm' : 'bg-pink-50 text-gray-600 hover:bg-pink-100' }}">Batal</a>
        </div>

        <!-- Order List Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Daftar Transaksi Aktif</h2>
                <span class="text-xs bg-pink-100 text-pink-700 px-3 py-1 rounded-full font-bold">Total: {{ $orders->count() }} Pesanan</span>
            </div>

            @if($orders->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                    <p>Tidak ada pesanan dalam status ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm">
                                <th class="p-4 font-medium">TRX ID</th>
                                <th class="p-4 font-medium">Pelanggan</th>
                                <th class="p-4 font-medium">Tanggal Masuk</th>
                                <th class="p-4 font-medium">Total Harga</th>
                                <th class="p-4 font-medium">Status Saat Ini</th>
                                <th class="p-4 font-medium text-center">Ganti Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">
                                        <a href="{{ route('dashboard.order-detail', $order->id) }}" class="font-bold text-pink-600 hover:underline">{{ $order->transaction_number }}</a>
                                        <div class="text-[10px] text-gray-400">Tipe: {{ ucfirst($order->order_type ?? 'online') }}</div>
                                        @if($order->payment && $order->payment->payment_status === 'pending_validation')
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] rounded-full font-bold">Menunggu Validasi</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="p-4 text-gray-500 text-sm">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="p-4 font-bold text-pink-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-black inline-block
                                            @if($order->status === 'Antre') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'Diproses') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'Selesai') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="flex items-center gap-1 justify-center">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="px-2 py-1.5 border border-gray-300 rounded-xl text-xs bg-gray-50 text-gray-700 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                                <option value="Antre" {{ $order->status === 'Antre' ? 'selected' : '' }}>Antre</option>
                                                <option value="Dicuci" {{ $order->status === 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                                                <option value="Disetrika" {{ $order->status === 'Disetrika' ? 'selected' : '' }}>Disetrika</option>
                                                <option value="Siap Diambil/Diantar" {{ $order->status === 'Siap Diambil/Diantar' ? 'selected' : '' }}>Siap Diambil/Diantar</option>
                                                <option value="Diproses" {{ $order->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Selesai" {{ $order->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="Batal" {{ $order->status === 'Batal' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </form>
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
