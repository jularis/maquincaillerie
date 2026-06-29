<?php

namespace App\Http\Controllers;

use App\Mail\OrderAdminNotification;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $shipping = 0;
        $total    = $subtotal + $tax;

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
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:3',
            'customer_type'  => 'required|in:individual,company',
            'company_name'   => 'required_if:customer_type,company|nullable|string|max:255',
            'payment_method' => 'required|in:cod,orange_money,wave,mtn_money,transfer,check',
        ]);

        $sessionId = session()->getId();
        $cartItems = \App\Models\Cart::with('product')
            ->where(function($q) use ($sessionId) {
                if (auth()->check()) $q->where('user_id', auth()->id());
                else $q->where('session_id', $sessionId);
            })->get();

        if ($cartItems->isEmpty()) return redirect()->route('cart.index');

        $isCompany = $request->customer_type === 'company';
        $subtotal  = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $tax       = $isCompany ? round($subtotal * 0.18, 2) : 0;
        $shipping  = 0;

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
            'is_company'     => $isCompany,
            'company_name'   => $isCompany ? $request->company_name : null,
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

        try {
            Mail::to($order->email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::warning('Email de confirmation non envoyé : ' . $e->getMessage());
        }

        try {
            Mail::to(setting('site.email'))->send(new OrderAdminNotification($order));
        } catch (\Exception $e) {
            Log::warning('Email admin non envoyé : ' . $e->getMessage());
        }

        try {
            $this->sendWhatsApp($order);
        } catch (\Exception $e) {
            Log::warning('WhatsApp non envoyé : ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    private function sendWhatsApp(\App\Models\Order $order): void
    {
        $phone  = preg_replace('/[^0-9]/', '', setting('site.phone_1') ?? '');
        $apikey = config('services.callmebot.apikey');

        if (empty($phone) || empty($apikey)) return;

        $items = collect($order->items)
            ->map(fn($i) => $i['quantity'] . 'x ' . $i['name'])
            ->implode(', ');

        $paymentLabels = [
            'cod'          => 'Espèce en magasin',
            'orange_money' => 'Orange Money',
            'wave'         => 'Wave',
            'mtn_money'    => 'MTN Money',
            'transfer'     => 'Virement bancaire',
            'check'        => 'Chèque',
        ];
        $payment = $paymentLabels[$order->payment_method] ?? $order->payment_method;

        $message = implode("\n", [
            '🛒 *Nouvelle commande — ' . $order->order_number . '*',
            '👤 ' . $order->first_name . ' ' . $order->last_name,
            '📧 ' . $order->email,
            $order->phone ? '📞 ' . $order->phone : null,
            '📦 ' . $items,
            '💰 Total : ' . number_format($order->total, 0, ',', ' ') . ' F CFA',
            '💳 ' . $payment,
            '🏙️ ' . $order->city,
        ]);
        $message = implode("\n", array_filter(explode("\n", $message)));

        Http::get('https://api.callmebot.com/whatsapp.php', [
            'phone'  => $phone,
            'text'   => $message,
            'apikey' => $apikey,
        ]);
    }
}
