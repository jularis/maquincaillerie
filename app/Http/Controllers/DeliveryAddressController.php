<?php

namespace App\Http\Controllers;

use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->deliveryAddresses()->orderByDesc('is_default')->orderBy('created_at')->get();
        return view('account.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('account.addresses.form', ['address' => new DeliveryAddress()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'       => 'nullable|string|max:60',
            'first_name'  => 'required|string|max:80',
            'last_name'   => 'required|string|max:80',
            'phone'       => 'nullable|string|max:30',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country'     => 'required|string|max:3',
            'is_default'  => 'boolean',
        ]);

        $data['user_id'] = Auth::id();

        if (!empty($data['is_default'])) {
            Auth::user()->deliveryAddresses()->update(['is_default' => false]);
        } elseif (Auth::user()->deliveryAddresses()->count() === 0) {
            $data['is_default'] = true;
        }

        DeliveryAddress::create($data);

        return redirect()->route('addresses.index')->with('success', 'Adresse ajoutée avec succès.');
    }

    public function edit(DeliveryAddress $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);
        return view('account.addresses.form', compact('address'));
    }

    public function update(Request $request, DeliveryAddress $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'label'       => 'nullable|string|max:60',
            'first_name'  => 'required|string|max:80',
            'last_name'   => 'required|string|max:80',
            'phone'       => 'nullable|string|max:30',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country'     => 'required|string|max:3',
            'is_default'  => 'boolean',
        ]);

        if (!empty($data['is_default'])) {
            Auth::user()->deliveryAddresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', 'Adresse mise à jour.');
    }

    public function destroy(DeliveryAddress $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            Auth::user()->deliveryAddresses()->oldest()->first()?->update(['is_default' => true]);
        }

        return redirect()->route('addresses.index')->with('success', 'Adresse supprimée.');
    }

    public function setDefault(DeliveryAddress $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);
        Auth::user()->deliveryAddresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')->with('success', 'Adresse par défaut mise à jour.');
    }
}
