@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('dashboard.orders') }}" class="text-pink-500 hover:text-pink-700 bg-pink-50 p-2 rounded-full transition">
            <i data-feather="arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan <span class="text-pink-dark">{{ $order->transaction_number }}</span></h1>
    </div>

    <!-- Similar to Track Result but customized for Dashboard -->
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        
        <div class="p-8">
            <div class="flex flex-col md:flex-row justify-between border-b border-gray-100 pb-6 mb-6">
                <div>
                    <h3 class="font-bold text-gray-500 text-sm uppercase tracking-wider mb-1">Status Saat Ini</h3>
                    <div class="text-2xl font-bold text-pink-dark flex items-center gap-2">
                        {{ $order->status }}
                        @if($order->status == 'Selesai')
                            <i data-feather="check-circle" class="text-green-500"></i>
                        @endif
                    </div>
                </div>
                <div class="mt-4 md:mt-0 text-left md:text-right">
                    <h3 class="font-bold text-gray-500 text-sm uppercase tracking-wider mb-1">Tanggal Pesanan</h3>
                    <div class="text-lg font-medium text-gray-800">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- Stepper Status -->
            <div class="mb-10 relative">
                <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-1 bg-pink-100 md:-translate-x-1/2"></div>
                
                @foreach($order->statuses as $status)
                <div class="relative flex flex-col md:flex-row items-center mb-8">
                    <div class="md:w-1/2 md:text-right md:pr-12 text-left pl-12 md:pl-0 w-full mb-2 md:mb-0">
                        <h4 class="font-bold text-gray-800 text-lg">{{ $status->status }}</h4>
                        <p class="text-gray-500 text-sm">{{ $status->description }}</p>
                    </div>
                    <div class="absolute left-0 md:left-1/2 w-8 h-8 rounded-full bg-pink-500 border-4 border-white shadow-md transform md:-translate-x-1/2 flex items-center justify-center text-white z-10">
                        <i data-feather="check" class="w-4 h-4"></i>
                    </div>
                    <div class="md:w-1/2 md:pl-12 text-left pl-12 w-full">
                        <p class="text-sm text-gray-400 font-medium">{{ $status->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Data Pelanggan & Lokasi -->
                <div>
                    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Informasi Pengiriman</h3>
                    <div class="space-y-3 text-sm">
                        <p><span class="text-gray-500 block">Nama:</span> <span class="font-medium text-gray-800">{{ $order->customer_name }}</span></p>
                        <p><span class="text-gray-500 block">Metode Penyerahan:</span> <span class="font-medium text-gray-800">{{ $order->pickup_type == 'driver' ? 'Pickup Kurir' : 'Antar ke Outlet' }}</span></p>
                        <p><span class="text-gray-500 block">Metode Pengambilan:</span> <span class="font-medium text-gray-800">{{ $order->delivery_type == 'driver' ? 'Delivery Kurir' : 'Ambil di Outlet' }}</span></p>
                        <p><span class="text-gray-500 block">Alamat:</span> <span class="font-medium text-gray-800">{{ $order->address }}</span></p>
                        <p><span class="text-gray-500 block">Jarak:</span> <span class="font-medium text-gray-800">{{ $order->distance_km ?? 0 }} KM</span></p>
                        @if($order->driver_notes)
                        <p><span class="text-gray-500 block">Catatan Driver:</span> <span class="font-medium text-gray-800">{{ $order->driver_notes }}</span></p>
                        @endif
                    </div>
                </div>
                
                <!-- Pembayaran -->
                <div>
                    @if(Auth::user()->role === 'admin')
                    <div class="bg-pink-50 p-6 rounded-3xl border border-pink-100 mb-8 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-pink-dark flex items-center gap-2">
                            <i data-feather="settings" class="w-5 h-5"></i> Aksi Admin: Update Status
                        </h3>
                        <form action="{{ route('dashboard.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-pink-700 uppercase tracking-wider mb-1">Status Pesanan</label>
                                <select name="status" class="w-full rounded-xl border border-pink-200 p-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-pink-400">
                                    <option value="Antre" {{ $order->status == 'Antre' ? 'selected' : '' }}>Antre</option>
                                    <option value="Dicuci" {{ $order->status == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                                    <option value="Disetrika" {{ $order->status == 'Disetrika' ? 'selected' : '' }}>Disetrika</option>
                                    <option value="Siap Diambil/Diantar" {{ $order->status == 'Siap Diambil/Diantar' ? 'selected' : '' }}>Siap Diambil/Diantar</option>
                                    <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Batal" {{ $order->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-pink-700 uppercase tracking-wider mb-1">Status Pembayaran</label>
                                <select name="payment_status" class="w-full rounded-xl border border-pink-200 p-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-pink-400">
                                    <option value="unpaid" {{ $order->payment->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                                    <option value="paid" {{ $order->payment->payment_status == 'paid' ? 'selected' : '' }}>Sudah Bayar (Paid)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-pink-700 uppercase tracking-wider mb-1">Keterangan / Catatan Status</label>
                                <input type="text" name="description" placeholder="Contoh: Pakaian sedang proses dicuci" class="w-full rounded-xl border border-pink-200 p-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-pink-400">
                            </div>
                            
                            <button type="submit" class="w-full btn-pink py-2.5 rounded-xl font-bold text-sm shadow-md">
                                Update Status
                            </button>
                        </form>
                    </div>
                    @endif

                    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Informasi Pembayaran</h3>
                    <div class="space-y-3 text-sm mb-6">
                        <p><span class="text-gray-500 block">Metode:</span> <span class="font-medium text-gray-800 uppercase">{{ $order->payment->payment_method }}</span></p>
                        <p><span class="text-gray-500 block">Status:</span> 
                            <span class="font-bold {{ $order->payment->payment_status == 'paid' ? 'text-green-500' : 'text-red-500' }} uppercase">
                                {{ $order->payment->payment_status }}
                            </span>
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h4 class="font-bold text-gray-800 mb-3">Rincian Layanan</h4>
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-center mb-2 text-sm">
                                <div>
                                    <span class="text-gray-700">{{ $item->service_name }}</span>
                                    <span class="text-gray-400">x {{ $item->quantity }}</span>
                                </div>
                                <div class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200 font-bold">
                            <span>Total</span>
                            <span class="text-pink-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center border-t border-gray-100 pt-8">
                <button onclick="window.print()" class="btn-pink px-6 py-2 rounded-lg font-bold shadow-md inline-flex items-center gap-2">
                    <i data-feather="printer" class="w-4 h-4"></i> Cetak Invoice
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderId = "{{ $order->id }}";
        const currentStatus = "{{ $order->status }}";
        
        setInterval(function() {
            fetch(`/orders/${orderId}/status`)
                .then(response => response.json())
                .then(data => {
                    if (data.status && data.status !== currentStatus) {
                        window.location.reload();
                    }
                })
                .catch(err => console.error('Error checking order status:', err));
        }, 4000);
    });
</script>
@endpush
@endsection
