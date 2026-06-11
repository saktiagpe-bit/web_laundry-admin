@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-pink-100">
        <div class="pink-gradient p-8 text-white text-center">
            <i data-feather="check-circle" class="w-16 h-16 mx-auto mb-4"></i>
            <h2 class="text-3xl font-bold">Hasil Pelacakan</h2>
            <p class="opacity-90">Nomor Transaksi: {{ $order->transaction_number }}</p>
        </div>
        
        <div class="p-8">
            <div class="flex flex-col md:flex-row justify-between border-b pb-6 mb-6">
                <div>
                    <h3 class="font-bold text-gray-500 text-sm uppercase tracking-wider mb-1">Status Saat Ini</h3>
                    <div class="text-2xl font-bold text-pink-dark">
                        {{ $order->status }}
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

            <div class="bg-pink-50 p-6 rounded-2xl">
                <h3 class="font-bold text-lg mb-4 text-gray-800 border-b border-pink-200 pb-2">Rincian Layanan</h3>
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <span class="font-medium text-gray-800">{{ $item->service_name }}</span>
                            <span class="text-gray-500 text-sm">x {{ $item->quantity }}</span>
                        </div>
                        <div class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-pink-200 font-bold text-lg">
                    <span>Total Keseluruhan</span>
                    <span class="text-pink-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="btn-pink px-8 py-3 rounded-full font-bold inline-block shadow-md">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Load Supabase JS SDK dari CDN untuk fitur Realtime Tracking -->
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderId = "{{ $order->id }}";
        const currentStatus = "{{ $order->status }}";
        
        // Ambil konfigurasi kredensial Supabase dari file .env Laravel
        const supabaseUrl = "{{ env('VITE_SUPABASE_URL') }}";
        const supabaseKey = "{{ env('VITE_SUPABASE_ANON_KEY') }}";
        
        let usingRealtime = false;
        
        if (supabaseUrl && supabaseKey) {
            try {
                // Inisialisasi client Supabase
                const supabaseClient = supabase.createClient(supabaseUrl, supabaseKey);
                
                // Dengarkan pembaruan status secara langsung (Realtime WebSocket)
                const channel = supabaseClient
                    .channel('order-status-updates')
                    .on('postgres_changes', {
                        event: 'INSERT', // Dengarkan penambahan baris status baru
                        schema: 'public',
                        table: 'order_statuses',
                        filter: `order_id=eq.${orderId}` // Hanya untuk order ini saja
                    }, (payload) => {
                        console.log('Ada update status via Supabase Realtime:', payload);
                        // Reload halaman agar UI terupdate otomatis tanpa perlu refresh manual
                        window.location.reload();
                    })
                    .subscribe((status) => {
                        if (status === 'SUBSCRIBED') {
                            usingRealtime = true;
                            console.log('Berhasil terhubung ke Supabase Realtime WebSocket!');
                        }
                    });
            } catch (err) {
                console.error('Koneksi Supabase Realtime bermasalah:', err);
            }
        }
        
        // Mekanisme Fallback: Kalau WebSocket gagal terhubung,
        // web otomatis ngecek status lewat HTTP Polling (Ajax request) setiap 4 detik ke server Laravel
        setInterval(function() {
            if (!usingRealtime) {
                fetch(`/orders/${orderId}/status`)
                    .then(response => response.json())
                    .then(data => {
                        // Jika status di server ternyata berbeda dengan status saat ini, reload halaman
                        if (data.status && data.status !== currentStatus) {
                            console.log('Update status terdeteksi via HTTP Polling');
                            window.location.reload();
                        }
                    })
                    .catch(err => console.error('Error saat HTTP Polling:', err));
            }
        }, 4000);
    });
</script>
@endpush
@endsection
