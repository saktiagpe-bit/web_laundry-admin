@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Katalog Layanan Laundry</h1>
        <p class="text-xl text-gray-600">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($services as $service)
        <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-pink-100 hover:shadow-2xl transition duration-300 flex flex-col">
            <div class="h-40 bg-pink-100 flex items-center justify-center text-pink-300">
                <i data-feather="image" class="w-16 h-16 opacity-50"></i>
            </div>
            <div class="p-6 flex-grow flex flex-col">
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $service->name }}</h3>
                <p class="text-gray-600 text-sm mb-4 flex-grow">{{ $service->description }}</p>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="text-pink-dark font-bold text-xl">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                    <div class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><i data-feather="clock" class="w-3 h-3 inline"></i> {{ $service->estimate_hours }} Jam</div>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <div class="flex gap-2">
                        <input type="number" name="quantity" value="1" min="1" class="w-20 border-gray-300 rounded-xl px-3 py-2 text-center bg-gray-50 border">
                        <button type="submit" class="flex-1 btn-pink py-2 rounded-xl font-bold text-sm flex justify-center items-center gap-1">
                            <i data-feather="plus-circle" class="w-4 h-4"></i> Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
