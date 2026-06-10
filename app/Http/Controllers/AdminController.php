<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function reports(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }
        $timeframe = $request->input('timeframe', 'daily'); // daily, weekly, monthly, yearly
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $isSqlite = DB::getDriverName() === 'sqlite';

        // Base query for completed orders
        $query = Order::where('status', 'Selesai');

        $chartLabels = [];
        $chartData = [];

        // DB driver-compatible queries
        if ($timeframe === 'daily') {
            // Filter by year and month
            if ($isSqlite) {
                $query->whereRaw("strftime('%Y', completed_at) = ?", [$year])
                      ->whereRaw("strftime('%m', completed_at) = ?", [$month]);

                $results = $query->selectRaw("strftime('%d', completed_at) as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            } else {
                $query->whereRaw("to_char(completed_at, 'YYYY') = ?", [$year])
                      ->whereRaw("to_char(completed_at, 'MM') = ?", [$month]);

                $results = $query->selectRaw("to_char(completed_at, 'DD') as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            }

            // Populate all days of the selected month with 0 as default
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayStr = str_pad($d, 2, '0', STR_PAD_LEFT);
                $chartLabels[] = "Tgl " . $dayStr;
                $chartData["Tgl " . $dayStr] = 0;
            }

            foreach ($results as $row) {
                $dayLabel = "Tgl " . $row->label;
                $chartData[$dayLabel] = (int)$row->total;
            }

            $chartLabels = array_keys($chartData);
            $chartData = array_values($chartData);

        } else if ($timeframe === 'weekly') {
            if ($isSqlite) {
                $query->whereRaw("strftime('%Y', completed_at) = ?", [$year]);

                $results = $query->selectRaw("strftime('%W', completed_at) as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            } else {
                $query->whereRaw("to_char(completed_at, 'YYYY') = ?", [$year]);

                $results = $query->selectRaw("to_char(completed_at, 'IW') as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            }

            // Populate all 52 weeks
            for ($w = 1; $w <= 52; $w++) {
                $weekStr = str_pad($w, 2, '0', STR_PAD_LEFT);
                $chartLabels[] = "Wk " . $weekStr;
                $chartData["Wk " . $weekStr] = 0;
            }

            foreach ($results as $row) {
                $weekLabel = "Wk " . $row->label;
                if (isset($chartData[$weekLabel])) {
                    $chartData[$weekLabel] = (int)$row->total;
                } else {
                    $chartData[$weekLabel] = (int)$row->total;
                }
            }

            $chartLabels = array_keys($chartData);
            $chartData = array_values($chartData);

        } else if ($timeframe === 'monthly') {
            if ($isSqlite) {
                $query->whereRaw("strftime('%Y', completed_at) = ?", [$year]);

                $results = $query->selectRaw("strftime('%m', completed_at) as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            } else {
                $query->whereRaw("to_char(completed_at, 'YYYY') = ?", [$year]);

                $results = $query->selectRaw("to_char(completed_at, 'MM') as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            }

            $monthNames = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];

            foreach ($monthNames as $key => $name) {
                $chartLabels[] = $name;
                $chartData[$name] = 0;
            }

            foreach ($results as $row) {
                $mName = $monthNames[$row->label] ?? $row->label;
                $chartData[$mName] = (int)$row->total;
            }

            $chartLabels = array_keys($chartData);
            $chartData = array_values($chartData);

        } else { // yearly
            if ($isSqlite) {
                $results = $query->selectRaw("strftime('%Y', completed_at) as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            } else {
                $results = $query->selectRaw("to_char(completed_at, 'YYYY') as label, SUM(total_price) as total")
                                 ->groupBy('label')
                                 ->orderBy('label', 'asc')
                                 ->get();
            }

            foreach ($results as $row) {
                $chartLabels[] = $row->label;
                $chartData[] = (int)$row->total;
            }
        }

        // Get detailed transactions list for full transparency
        $detailQuery = Order::where('status', 'Selesai')->orderBy('completed_at', 'desc');

        if ($timeframe === 'daily') {
            if ($isSqlite) {
                $detailQuery->whereRaw("strftime('%Y', completed_at) = ?", [$year])
                            ->whereRaw("strftime('%m', completed_at) = ?", [$month]);
            } else {
                $detailQuery->whereRaw("to_char(completed_at, 'YYYY') = ?", [$year])
                            ->whereRaw("to_char(completed_at, 'MM') = ?", [$month]);
            }
        } else if ($timeframe === 'weekly' || $timeframe === 'monthly') {
            if ($isSqlite) {
                $detailQuery->whereRaw("strftime('%Y', completed_at) = ?", [$year]);
            } else {
                $detailQuery->whereRaw("to_char(completed_at, 'YYYY') = ?", [$year]);
            }
        }

        $transactions = $detailQuery->get();
        $totalRevenue = $transactions->sum('total_price');

        return view('admin.reports', compact(
            'timeframe', 'year', 'month', 
            'chartLabels', 'chartData', 
            'transactions', 'totalRevenue'
        ));
    }

    public function orders(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }
        $status = $request->input('status', 'semua');
        
        $query = Order::orderBy('created_at', 'desc');
        if ($status !== 'semua') {
            $query->where('status', $status);
        }
        
        $orders = $query->get();
        return view('admin.orders', compact('orders', 'status'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }
        $request->validate([
            'status' => 'required|in:Antre,Diproses,Dicuci,Disetrika,Siap Diambil/Diantar,Selesai,Batal'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->status = $newStatus;

        // Side effect: if changing to 'Selesai'
        if ($newStatus === 'Selesai') {
            $order->completed_at = Carbon::now();
            
            // Mark payment as paid if it exists
            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'paid',
                    'paid_at' => Carbon::now()
                ]);
            }
        } else {
            // If status is changed back from 'Selesai', remove completed_at
            if ($oldStatus === 'Selesai') {
                $order->completed_at = null;
            }
        }

        $order->save();

        // Create log in OrderStatus
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'description' => 'Status pesanan diubah oleh Admin menjadi ' . $newStatus
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $newStatus . '!');
    }

    public function validatePayment(Request $request, Order $order)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        if ($order->payment) {
            $order->payment->update([
                'payment_status' => 'paid',
                'paid_at' => Carbon::now()
            ]);

            OrderStatus::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'description' => 'Pembayaran telah divalidasi oleh Admin'
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil divalidasi!');
    }
}
