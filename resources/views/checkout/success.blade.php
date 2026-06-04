@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-pink-100">
        <div class="bg-green-500 py-10 text-white">
            <i data-feather="check-circle" class="w-20 h-20 mx-auto mb-4 animate-bounce"></i>
            <h1 class="text-4xl font-extrabold mb-2">Pesanan Berhasil!</h1>
            <p class="text-lg opacity-90">Terima kasih telah mempercayakan cucian Anda pada BubbleWash.</p>
        </div>
        
        <div class="p-8">
            <div class="bg-pink-50 p-6 rounded-2xl mb-8 border border-pink-100 inline-block text-left w-full max-w-lg mx-auto">
                <p class="text-sm text-gray-500 mb-1 font-bold">Nomor Transaksi Anda:</p>
                <div class="text-3xl font-extrabold text-pink-dark tracking-widest bg-white py-3 px-6 rounded-xl border border-pink-200 shadow-sm text-center select-all mb-4">
                    {{ $order->transaction_number }}
                </div>
                <div class="text-sm text-pink-600 bg-pink-100 p-3 rounded-lg flex items-start gap-2">
                    <i data-feather="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <p>Simpan nomor transaksi ini. Anda dapat menggunakannya bersama 5 digit terakhir nomor HP ({{ substr($order->customer_phone, -5) }}) untuk melacak status pesanan di halaman utama.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-left border-b border-gray-100 pb-8 mb-8">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Tanggal Pesan</p>
                    <p class="font-medium text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Total</p>
                    <p class="font-medium text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Pembayaran</p>
                    <p class="font-medium text-gray-800 uppercase">{{ $order->payment->payment_method }} ({{ $order->payment->payment_status }})</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Penyerahan</p>
                    <p class="font-medium text-gray-800">{{ $order->pickup_type == 'driver' ? 'Kurir Jemput' : 'Antar Langsung' }}</p>
                </div>
            </div>

            @if($order->payment->payment_method == 'qris' && $order->payment->payment_status == 'unpaid')
                <div class="bg-gray-50 p-6 rounded-2xl mb-8">
                    <h3 class="font-bold text-gray-800 mb-4">Silakan Pindai QRIS untuk Pembayaran</h3>
                    <!-- Mock QRIS Image -->
                    <div class="bg-white w-48 h-48 mx-auto rounded-xl shadow-sm border p-2 flex items-center justify-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->transaction_number }}" alt="QRIS" class="w-full h-full object-contain">
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('dashboard.order-detail', $order->id) }}" class="btn-pink px-8 py-3 rounded-full font-bold shadow-md">Lihat Detail Pesanan</a>
                <a href="{{ route('home') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-8 py-3 rounded-full font-bold transition">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
@endsection
