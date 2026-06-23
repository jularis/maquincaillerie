<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DevisDemande;

class DevisController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'nom'      => 'required|string|max:100',
            'ville'    => 'required|string|max:100',
            'type'     => 'required|in:Monophasé,Triphasé',
            'amperage' => 'required|string|max:10',
            'facture'  => 'required|numeric|min:0',
            'toiture'  => 'required|in:Dalle,Tôle',
        ]);

        try {
            $recipient = setting('site.email') ?: 'commerciale@cleanenergyservices.net';
            Mail::to($recipient)->send(new DevisDemande($data));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Erreur envoi devis : ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
