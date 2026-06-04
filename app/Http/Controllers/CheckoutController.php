<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\OrderStatus;
use App\Models\DeliverySchedule;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('services.index')->with('warning', 'Keranjang Anda kosong.');
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['subtotal'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_gender' => 'required|in:male,female',
            'address' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'distance_km' => 'required|numeric|max:3.0',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|date_format:H:i',
            'pickup_type' => 'required|in:driver,self',
            'delivery_type' => 'required|in:driver,self',
            'driver_notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,qris',
        ], [
            'distance_km.max' => 'Maaf, area Anda berada di luar jangkauan layanan pickup dan delivery (maks 3 KM).'
        ]);

        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('services.index');
        }

        $total_price = 0;
        foreach($cart as $item) {
            $total_price += $item['subtotal'];
        }

        // Generate Transaction Number
        $transaction_number = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'transaction_number' => $transaction_number,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_gender' => $request->customer_gender,
            'pickup_type' => $request->pickup_type,
            'delivery_type' => $request->delivery_type,
            'distance_km' => $request->distance_km,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'driver_notes' => $request->driver_notes,
            'status' => 'Antre',
            'total_price' => $total_price,
        ]);

        // Create Order Items
        foreach ($cart as $service_id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service_id,
                'service_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Create Payment
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'amount' => $total_price,
        ]);

        // Create Status
        OrderStatus::create([
            'order_id' => $order->id,
            'status' => 'Antre',
            'description' => 'Pesanan baru masuk ke dalam antrean'
        ]);

        // Create Schedule
        DeliverySchedule::create([
            'order_id' => $order->id,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
        ]);

        // Clear Cart
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }

    public function success(Order $order)
    {
        // Ensure user owns this order
        if($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
