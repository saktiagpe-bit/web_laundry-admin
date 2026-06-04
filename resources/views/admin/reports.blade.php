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
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-3 rounded-xl bg-pink-500 text-white transition font-medium shadow-md shadow-pink-200">
                    <i data-feather="trending-up" class="w-5 h-5"></i> Laporan Keuangan
                </a>
                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-pink-50 text-gray-700 transition font-medium">
                    <i data-feather="shopping-bag" class="w-5 h-5"></i> Manajemen Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:w-3/4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">Laporan Keuangan</h1>
                <p class="text-gray-500 mt-1">Transparansi dan analisis pemasukan bisnis laundry Anda.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reports', ['timeframe' => 'daily', 'month' => $month, 'year' => $year]) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $timeframe === 'daily' ? 'bg-pink-500 text-white shadow-md' : 'bg-white border border-pink-100 text-gray-600 hover:bg-pink-50' }}">Harian</a>
                <a href="{{ route('admin.reports', ['timeframe' => 'weekly', 'year' => $year]) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $timeframe === 'weekly' ? 'bg-pink-500 text-white shadow-md' : 'bg-white border border-pink-100 text-gray-600 hover:bg-pink-50' }}">Mingguan</a>
                <a href="{{ route('admin.reports', ['timeframe' => 'monthly', 'year' => $year]) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $timeframe === 'monthly' ? 'bg-pink-500 text-white shadow-md' : 'bg-white border border-pink-100 text-gray-600 hover:bg-pink-50' }}">Bulanan</a>
                <a href="{{ route('admin.reports', ['timeframe' => 'yearly']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $timeframe === 'yearly' ? 'bg-pink-500 text-white shadow-md' : 'bg-white border border-pink-100 text-gray-600 hover:bg-pink-50' }}">Tahunan</a>
            </div>
        </div>

        <!-- Filter Parameters -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i data-feather="filter" class="text-pink-500 w-5 h-5"></i>
                <span class="font-bold text-gray-800">Filter Parameter:</span>
            </div>
            
            <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-end">
                <input type="hidden" name="timeframe" value="{{ $timeframe }}">
                
                @if($timeframe === 'daily')
                    <div class="flex gap-2">
                        <select name="month" class="px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            @foreach([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ] as $mVal => $mName)
                                <option value="{{ $mVal }}" {{ $month === $mVal ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                        
                        <select name="year" class="px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            @for($y = date('Y') - 4; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                @elseif($timeframe === 'weekly' || $timeframe === 'monthly')
                    <div>
                        <select name="year" class="px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            @for($y = date('Y') - 4; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                @endif
                
                @if($timeframe !== 'yearly')
                    <button type="submit" class="btn-pink px-4 py-2 rounded-xl text-sm font-bold shadow-sm">
                        Terapkan
                    </button>
                @endif
            </form>
        </div>

        <!-- Highlight Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-pink-400 to-pink-500 rounded-3xl p-6 text-white shadow-lg flex items-center justify-between">
                <div>
                    <p class="text-pink-100 font-medium mb-1">Total Pemasukan</p>
                    <h3 class="text-3xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl">
                    <i data-feather="dollar-sign" class="w-8 h-8 text-white"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 font-medium mb-1">Kuantitas Pesanan Selesai</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $transactions->count() }}</h3>
                </div>
                <div class="bg-pink-50 p-4 rounded-2xl">
                    <i data-feather="check-circle" class="w-8 h-8 text-pink-500"></i>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 font-medium mb-1">Status Aturan Keuangan</p>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 inline-block mt-2">Otomatis Aktif</span>
                    <p class="text-[10px] text-gray-400 mt-2">Hanya status "Selesai" yang dihitung.</p>
                </div>
                <div class="bg-green-50 p-4 rounded-2xl">
                    <i data-feather="shield" class="w-8 h-8 text-green-500"></i>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-feather="bar-chart-2" class="text-pink-500 w-5 h-5"></i>
                Grafik Tren Pemasukan - {{ ucfirst($timeframe) }}
            </h2>
            <div class="relative w-full h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Transparency Ledger Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Transparansi Rincian Transaksi</h2>
                    <p class="text-xs text-gray-400">Daftar transaksi yang membentuk laporan keuangan di atas.</p>
                </div>
                <i data-feather="file-text" class="text-pink-500 w-5 h-5"></i>
            </div>
            
            @if($transactions->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                    <p>Tidak ada transaksi terhitung ("Selesai") untuk periode ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm">
                                <th class="p-4 font-medium">No. Transaksi</th>
                                <th class="p-4 font-medium">Tanggal Selesai</th>
                                <th class="p-4 font-medium">Pelanggan</th>
                                <th class="p-4 font-medium">Metode</th>
                                <th class="p-4 font-medium">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $order)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-gray-800">{{ $order->transaction_number }}</td>
                                    <td class="p-4 text-gray-500">{{ $order->completed_at ? $order->completed_at->format('d M Y H:i') : '-' }}</td>
                                    <td class="p-4">
                                        <div class="font-medium text-gray-800">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="p-4 text-gray-500">
                                        <span class="px-2 py-0.5 rounded text-xs uppercase font-semibold bg-gray-100 text-gray-600">
                                            {{ $order->payment ? $order->payment->payment_method : 'cash' }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(255, 105, 180, 0.4)');
        gradient.addColorStop(1, 'rgba(255, 105, 180, 0.0)');

        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan Bersih (Rp)',
                    data: data,
                    borderColor: '#FF69B4',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#FF1493',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#FF1493',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 192, 203, 0.15)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
                            },
                            color: '#888',
                            font: {
                                family: 'Fredoka'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#888',
                            font: {
                                family: 'Fredoka'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
