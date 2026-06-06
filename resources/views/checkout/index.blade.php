@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Form Checkout -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-3xl shadow-xl border border-pink-100 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-4"><i data-feather="file-text" class="text-pink-500"></i> Informasi Pemesanan</h2>
                
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    <!-- Data Pelanggan -->
                    <h3 class="text-lg font-bold text-gray-700 mt-6 mb-4">1. Data Pelanggan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="customer_name" value="{{ Auth::user()->name }}" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="customer_email" value="{{ Auth::user()->email }}" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="customer_phone" value="{{ Auth::user()->phone }}" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="customer_gender" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                                <option value="male" {{ Auth::user()->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ Auth::user()->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <h3 class="text-lg font-bold text-gray-700 mt-8 mb-4">2. Lokasi Penjemputan / Pengantaran</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Titik Lokasi di Peta (Wajib jika menggunakan layanan kurir kami)</label>
                        <div class="bg-yellow-50 p-3 rounded-lg text-sm text-yellow-700 mb-2">
                            <i data-feather="info" class="w-4 h-4 inline"></i> Layanan antar-jemput hanya tersedia maksimal 3 KM dari outlet kami.
                        </div>
                        <div id="map" class="w-full h-64 rounded-xl border border-gray-300 mb-2 z-0"></div>
                        <div class="flex justify-between items-center text-sm">
                            <span id="distanceText" class="font-bold text-gray-600">Jarak: - KM</span>
                            <span id="distanceWarning" class="font-bold text-red-500 hidden">Di luar jangkauan (> 3 KM)!</span>
                        </div>
                        
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <input type="hidden" name="distance_km" id="distance_km" value="0">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="2" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">{{ Auth::user()->address }}</textarea>
                    </div>

                    <!-- Layanan Pengambilan/Pengantaran -->
                    <h3 class="text-lg font-bold text-gray-700 mt-8 mb-4">3. Metode Pengambilan & Pengantaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Opsi Penyerahan Cucian kotor</label>
                            <select name="pickup_type" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                                <option value="driver">Pickup oleh Driver (Kurir Jemput)</option>
                                <option value="self">Antar Langsung ke Outlet</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Opsi Pengambilan Hasil</label>
                            <select name="delivery_type" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                                <option value="driver">Delivery (Kurir Antar ke Rumah)</option>
                                <option value="self">Ambil Sendiri ke Outlet</option>
                            </select>
                        </div>
                    </div>

                    <!-- Jadwal & Catatan -->
                    <h3 class="text-lg font-bold text-gray-700 mt-8 mb-4">4. Jadwal & Catatan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Penyerahan/Pickup</label>
                            <input type="date" name="pickup_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Penyerahan/Pickup</label>
                            <input type="time" name="pickup_time" required value="09:00" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Instruksi / Catatan (Opsional)</label>
                            <textarea name="driver_notes" rows="2" placeholder="Contoh: Titip ke satpam, rumah pagar biru..." class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50"></textarea>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <h3 class="text-lg font-bold text-gray-700 mt-8 mb-4">5. Pembayaran</h3>
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Metode Pembayaran</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 p-4 border rounded-xl cursor-pointer hover:bg-pink-50 transition">
                                <input type="radio" name="payment_method" value="cash" checked class="text-pink-500 focus:ring-pink-500">
                                <span class="font-bold"><i data-feather="dollar-sign" class="w-4 h-4 inline"></i> Tunai</span>
                            </label>
                            <label class="flex items-center gap-2 p-4 border rounded-xl cursor-pointer hover:bg-pink-50 transition">
                                <input type="radio" name="payment_method" value="qris" class="text-pink-500 focus:ring-pink-500">
                                <span class="font-bold"><i data-feather="smartphone" class="w-4 h-4 inline"></i> QRIS</span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" id="submitBtn" class="w-full btn-pink py-4 rounded-xl font-bold text-lg shadow-lg">Konfirmasi Pesanan</button>
                </form>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-3xl shadow-xl border border-pink-100 p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-4 mb-6">
                    @foreach($cart as $item)
                        <div class="flex justify-between items-center text-sm">
                            <div class="text-gray-600">{{ $item['name'] }} <span class="text-gray-400">x{{ $item['quantity'] }}</span></div>
                            <div class="font-medium">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center text-lg font-bold text-gray-800">
                        <div>Total Pembayaran</div>
                        <div class="text-pink-dark">Rp {{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl text-sm text-blue-800 flex items-start gap-3">
                    <i data-feather="shield" class="mt-1 flex-shrink-0"></i>
                    <p>Pesanan Anda aman bersama YURE Laundry. Kami menjamin kebersihan dan ketepatan waktu.</p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Outlet coordinate (Jalan Pandanwangi Raya No.99, Cibiru Wetan, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40625)
    const OUTLET_LAT = -6.942611;
    const OUTLET_LNG = 107.725861;
    
    // Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the earth in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2); 
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        return R * c; 
    }

    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([OUTLET_LAT, OUTLET_LNG], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Outlet Marker
        L.marker([OUTLET_LAT, OUTLET_LNG]).addTo(map)
            .bindPopup('<b>YURE Laundry Outlet</b>').openPopup();

        // 3KM Circle
        L.circle([OUTLET_LAT, OUTLET_LNG], {
            color: '#FF69B4',
            fillColor: '#FFC0CB',
            fillOpacity: 0.2,
            radius: 3000 // meters
        }).addTo(map);

        var userMarker = null;

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            if (userMarker) {
                userMarker.setLatLng(e.latlng);
            } else {
                userMarker = L.marker(e.latlng).addTo(map);
            }

            var dist = calculateDistance(OUTLET_LAT, OUTLET_LNG, lat, lng);
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('distance_km').value = dist.toFixed(2);
            document.getElementById('distanceText').innerText = 'Jarak: ' + dist.toFixed(2) + ' KM';

            var submitBtn = document.getElementById('submitBtn');
            var warning = document.getElementById('distanceWarning');

            if (dist > 3.0) {
                warning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                warning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Auto-fill address from clicked location
            var addressTextarea = document.querySelector('textarea[name="address"]');
            addressTextarea.value = "Mencari alamat...";

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        addressTextarea.value = data.display_name;
                    } else {
                        addressTextarea.value = "";
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    addressTextarea.value = "";
                });
        });
    });
</script>
@endpush
@endsection
