@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-3">
        <i data-feather="shopping-bag" class="text-pink-500"></i> Keranjang Laundry
    </h1>

    @if(empty($cart))
        <div class="bg-white p-12 rounded-3xl shadow-sm border border-pink-100 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-pink-50 text-pink-300 mb-6">
                <i data-feather="shopping-cart" class="w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Anda Masih Kosong</h2>
            <p class="text-gray-500 mb-8">Yuk, tambahkan layanan laundry yang Anda butuhkan!</p>
            <a href="{{ route('services.index') }}" class="btn-pink px-8 py-3 rounded-full font-bold inline-block shadow-md">Lihat Layanan Kami</a>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-xl border border-pink-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-pink-50 text-gray-700">
                            <th class="p-4 font-bold border-b border-pink-100">Layanan</th>
                            <th class="p-4 font-bold border-b border-pink-100">Harga</th>
                            <th class="p-4 font-bold border-b border-pink-100 text-center">Jumlah</th>
                            <th class="p-4 font-bold border-b border-pink-100">Subtotal</th>
                            <th class="p-4 font-bold border-b border-pink-100 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $details)
                            <tr class="border-b border-pink-50 hover:bg-pink-50/30 transition">
                                <td class="p-4 font-medium text-gray-800">{{ $details['name'] }}</td>
                                <td class="p-4 text-gray-600">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 border-gray-300 rounded-lg px-2 py-1 text-center bg-gray-50 border">
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-50 rounded-lg"><i data-feather="refresh-cw" class="w-4 h-4"></i></button>
                                    </form>
                                </td>
                                <td class="p-4 font-bold text-pink-dark">Rp {{ number_format($details['subtotal'], 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2 bg-red-50 rounded-lg"><i data-feather="trash-2" class="w-5 h-5"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-gray-50 flex flex-col md:flex-row justify-between items-center border-t border-gray-100">
                <a href="{{ route('services.index') }}" class="text-pink-600 hover:text-pink-800 font-medium flex items-center gap-2 mb-4 md:mb-0"><i data-feather="arrow-left" class="w-4 h-4"></i> Tambah Layanan Lain</a>
                <div class="flex items-center gap-6">
                    <div class="text-lg">Total: <span class="text-2xl font-bold text-pink-dark">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                    <a href="{{ route('checkout.index') }}" class="btn-pink px-8 py-3 rounded-full font-bold shadow-lg text-lg flex items-center gap-2">Lanjut Checkout <i data-feather="arrow-right"></i></a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
