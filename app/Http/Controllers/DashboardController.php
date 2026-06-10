<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $active_orders = Order::whereNotIn('status', ['Selesai'])->count();
            $total_orders = Order::count();
            $recent_orders = Order::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $active_orders = Order::where('user_id', $user->id)
                ->whereNotIn('status', ['Selesai'])
                ->count();
            $total_orders = Order::where('user_id', $user->id)->count();
            $recent_orders = Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard.index', compact('active_orders', 'total_orders', 'recent_orders'));
    }

    public function orders()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $orders = Order::orderBy('created_at', 'desc')->get();
        } else {
            $orders = Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('dashboard.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $order->load(['items', 'payment', 'statuses', 'deliverySchedule']);

        return view('dashboard.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        // Update Order Status
        $order->update([
            'status' => $request->status
        ]);

        // Add to OrderStatus History
        \App\Models\OrderStatus::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'description' => $request->description ?? 'Status diperbarui oleh Admin',
        ]);

        // Update Payment Status
        if ($order->payment) {
            $payment_data = ['payment_status' => $request->payment_status];
            if ($request->payment_status === 'paid' && !$order->payment->paid_at) {
                $payment_data['paid_at'] = now();
            } elseif ($request->payment_status === 'unpaid') {
                $payment_data['paid_at'] = null;
            }
            $order->payment->update($payment_data);
        }

        return back()->with('success', 'Status pesanan dan pembayaran berhasil diperbarui!');
    }

    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|max:10240',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            if ($order->payment) {
                $order->payment->update([
                    'payment_proof' => $path,
                    'payment_status' => 'pending_validation'
                ]);
            }
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu validasi admin.');
    }
}
