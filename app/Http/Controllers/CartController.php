<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach($cart as $item) {
            $total += $item['subtotal'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $service = Service::find($request->service_id);
        $cart = session()->get('cart', []);

        if(isset($cart[$service->id])) {
            $cart[$service->id]['quantity'] += $request->quantity;
            $cart[$service->id]['subtotal'] = $cart[$service->id]['quantity'] * $service->price;
        } else {
            $cart[$service->id] = [
                'name' => $service->name,
                'price' => $service->price,
                'quantity' => $request->quantity,
                'subtotal' => $service->price * $request->quantity,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Layanan ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if(isset($cart[$request->service_id])) {
            $cart[$request->service_id]['quantity'] = $request->quantity;
            $cart[$request->service_id]['subtotal'] = $cart[$request->service_id]['quantity'] * $cart[$request->service_id]['price'];
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'service_id' => 'required'
        ]);

        $cart = session()->get('cart', []);

        if(isset($cart[$request->service_id])) {
            unset($cart[$request->service_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Layanan dihapus dari keranjang!');
    }
}
