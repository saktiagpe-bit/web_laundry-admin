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
        $active_orders = Order::where('user_id', $user->id)
            ->whereNotIn('status', ['Selesai'])
            ->count();
        
        $total_orders = Order::where('user_id', $user->id)->count();
        
        $recent_orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('active_orders', 'total_orders', 'recent_orders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if($order->user_id !== Auth::id()) {
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
}
