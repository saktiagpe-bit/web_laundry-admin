<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;

class LandingController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('landing', compact('services'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'transaction_number' => 'required|string',
            'phone_last_5' => 'required|string|size:5',
        ]);

        $order = Order::with('statuses', 'deliverySchedule', 'payment')
            ->where('transaction_number', $request->transaction_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan dengan nomor transaksi tersebut tidak ditemukan.');
        }

        // Validate last 5 digits of phone number
        $customerPhone = $order->customer_phone;
        $last5 = substr($customerPhone, -5);

        if ($last5 !== $request->phone_last_5) {
            return back()->with('error', '5 digit nomor telepon tidak cocok.');
        }

        return view('track-result', compact('order'));
    }
}
