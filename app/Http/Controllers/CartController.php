<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getSessionId()
    {
        return session()->getId();
    }

    private function cartQuery()
    {
        $query = \App\Models\Cart::with('product.brand');
        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        }
        return $query->where('session_id', $this->getSessionId());
    }

    public function index()
    {
        $items = $this->cartQuery()->get();
        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);
        return view('cart.index', compact('items', 'total'));
    }

    public function add(\Illuminate\Http\Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);

        $where = auth()->check()
            ? ['user_id' => auth()->id(), 'product_id' => $request->product_id]
            : ['session_id' => $this->getSessionId(), 'product_id' => $request->product_id];

        $item = \App\Models\Cart::where($where)->first();
        if ($item) {
            $item->increment('quantity', $request->quantity ?? 1);
        } else {
            \App\Models\Cart::create(array_merge($where, [
                'quantity' => $request->quantity ?? 1,
                'user_id'  => auth()->id(),
                'session_id' => $this->getSessionId(),
            ]));
        }

        $count = $this->cartQuery()->sum('quantity');

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'count' => $count]);
        }

        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Cart $cart)
    {
        $cart->update(['quantity' => max(1, $request->quantity)]);
        return redirect()->route('cart.index');
    }

    public function remove(\App\Models\Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Produit retiré du panier.');
    }

    public function count()
    {
        return response()->json(['count' => $this->cartQuery()->sum('quantity')]);
    }
}
