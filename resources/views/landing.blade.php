@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="pink-gradient py-20 overflow-hidden relative">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center justify-between">
        <div class="md:w-1/2 text-white">
            <h1 class="text-5xl font-extrabold mb-4 leading-tight">Cucian Bersih,<br>Harum & Rapi!</h1>
            <p class="text-xl mb-8 opacity-90">YURE Laundry hadir untuk menyelesaikan masalah cucian kotor Anda dengan layanan antar-jemput yang cepat dan profesional.</p>
            <div class="flex gap-4">
                <a href="{{ route('register') }}" class="bg-white text-pink-dark px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-100 transition duration-300">Mulai Sekarang</a>
                <a href="#track" class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-pink-dark transition duration-300">Cek Status</a>
            </div>
        </div>
        <div class="md:w-1/2 mt-10 md:mt-0 flex justify-center">
            <!-- Cute Washing Machine SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 250" class="w-64 h-auto drop-shadow-2xl animate-pulse">
                <rect x="20" y="20" width="160" height="210" rx="20" fill="#ffffff" />
                <rect x="20" y="20" width="160" height="50" rx="20" fill="#f8f8f8" />
                <circle cx="150" cy="45" r="8" fill="#FF69B4" />
                <circle cx="120" cy="45" r="8" fill="#FFB6C1" />
                <rect x="40" y="35" width="40" height="20" rx="5" fill="#e0e0e0" />
                <circle cx="100" cy="140" r="50" fill="#e0e0e0" />
                <circle cx="100" cy="140" r="40" fill="#87CEEB" />
                <!-- Bubbles -->
                <circle cx="85" cy="130" r="8" fill="#ffffff" opacity="0.8" />
                <circle cx="115" cy="150" r="5" fill="#ffffff" opacity="0.6" />
                <circle cx="100" cy="120" r="4" fill="#ffffff" opacity="0.7" />
            </svg>
        </div>
    </div>
</div>

<!-- Layanan Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Layanan Unggulan Kami</h2>
            <p class="text-gray-500 mt-2">Pilih layanan yang paling sesuai dengan kebutuhan Anda</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach($services->take(4) as $service)
            <div class="bg-pink-soft rounded-2xl p-6 text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-2 border border-pink-100">
                <div class="bg-white w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 shadow-sm text-pink-500">
                    <i data-feather="star"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $service->name }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 50) }}</p>
                <div class="text-pink-dark font-bold text-lg mb-4">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('services.index') }}" class="btn-pink px-8 py-3 rounded-full font-bold inline-flex items-center gap-2">Lihat Semua Layanan <i data-feather="arrow-right" class="w-4 h-4"></i></a>
        </div>
    </div>
</div>

<!-- Cara Kerja -->
<div class="py-16 bg-pink-soft/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Cara Kerja Mudah</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center relative">
            <div class="relative z-10">
                <div class="bg-white w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 shadow-md text-pink-500 font-bold text-2xl border-4 border-pink-200">1</div>
                <h3 class="text-xl font-bold mb-2">Pilih Layanan</h3>
                <p class="text-gray-600">Pilih layanan laundry yang Anda butuhkan dan masukkan ke keranjang.</p>
            </div>
            <div class="relative z-10">
                <div class="bg-white w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 shadow-md text-pink-500 font-bold text-2xl border-4 border-pink-200">2</div>
                <h3 class="text-xl font-bold mb-2">Pilih Jadwal & Lokasi</h3>
                <p class="text-gray-600">Tentukan lokasi jemput menggunakan peta interaktif dan jadwal pengambilan.</p>
            </div>
            <div class="relative z-10">
                <div class="bg-white w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 shadow-md text-pink-500 font-bold text-2xl border-4 border-pink-200">3</div>
                <h3 class="text-xl font-bold mb-2">Tunggu Selesai</h3>
                <p class="text-gray-600">Kurir kami akan menjemput, dan Anda tinggal memantau status cucian.</p>
            </div>
        </div>
    </div>
</div>

<!-- Cek Status -->
<div id="track" class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-pink-gradient rounded-3xl p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 text-white opacity-10">
                <i data-feather="search" class="w-64 h-64"></i>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold text-white mb-6 text-center">Cek Status Laundry</h2>
                <form action="{{ route('track') }}" method="POST" class="bg-white p-6 rounded-2xl shadow-md">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nomor Transaksi</label>
                            <input type="text" name="transaction_number" placeholder="TRX-..." class="w-full border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 px-4 py-2 bg-gray-50 border" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">5 Digit Terakhir No. HP</label>
                            <input type="text" name="phone_last_5" placeholder="Contoh: 56789" class="w-full border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 px-4 py-2 bg-gray-50 border" maxlength="5" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full btn-pink py-3 rounded-xl font-bold text-lg flex justify-center items-center gap-2">
                        <i data-feather="search"></i> Lacak Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FAQ -->
<div class="py-16 bg-pink-soft">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-10">Pertanyaan yang Sering Diajukan</h2>
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Berapa jarak maksimal layanan antar-jemput?</h3>
                <p class="text-gray-600">Kami melayani antar-jemput gratis untuk area dengan jarak maksimal 3 KM dari outlet kami.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Apakah saya harus mendaftar untuk memesan?</h3>
                <p class="text-gray-600">Ya, untuk menjamin keamanan dan kenyamanan, Anda wajib mendaftar dan memverifikasi nomor telepon sebelum memesan.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-bold text-lg text-gray-800 mb-2">Metode pembayaran apa saja yang tersedia?</h3>
                <p class="text-gray-600">Saat ini kami menerima pembayaran secara Tunai (saat kurir datang atau di outlet) dan QRIS.</p>
            </div>
        </div>
    </div>
</div>
@endsection
