<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        $items = \App\Models\Cart::with('product')
            ->where(function($q) use ($sessionId) {
                if (auth()->check()) $q->where('user_id', auth()->id());
                else $q->where('session_id', $sessionId);
            })->get();

        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');

        $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $tax      = round($subtotal * 0.18, 2);
        $shipping = $subtotal >= 350000 ? 0 : 6500;
        $total    = $subtotal + $tax + $shipping;

        $savedAddresses = auth()->check()
            ? auth()->user()->deliveryAddresses()->orderByDesc('is_default')->orderBy('created_at')->get()
            : collect();

        return view('checkout.index', compact('items', 'subtotal', 'tax', 'shipping', 'total', 'savedAddresses'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:3',
            'payment_method' => 'required|in:cod,card,transfer,check',
        ]);

        $sessionId = session()->getId();
        $cartItems = \App\Models\Cart::with('product')
            ->where(function($q) use ($sessionId) {
                if (auth()->check()) $q->where('user_id', auth()->id());
                else $q->where('session_id', $sessionId);
            })->get();

        if ($cartItems->isEmpty()) return redirect()->route('cart.index');

        $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $tax      = round($subtotal * 0.18, 2);
        $shipping = $subtotal >= 350000 ? 0 : 6500;

        $order = \App\Models\Order::create([
            'order_number'   => 'CMD-' . strtoupper(uniqid()),
            'user_id'        => auth()->id(),
            'status'         => 'pending',
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'shipping'       => $shipping,
            'total'          => $subtotal + $tax + $shipping,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'city'           => $request->city,
            'postal_code'    => $request->postal_code,
            'country'        => $request->country ?? 'CI',
            'payment_method' => $request->payment_method ?? 'cod',
            'payment_status' => 'pending',
            'items'          => $cartItems->map(fn($i) => [
                'product_id' => $i->product_id,
                'name'       => $i->product->name,
                'price'      => $i->product->price,
                'quantity'   => $i->quantity,
            ])->toArray(),
        ]);

        $cartItems->each->delete();

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
        return view('checkout.success', compact('order'));
    }
}
