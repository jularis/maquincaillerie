<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'nom'       => 'required|string|max:100',
            'telephone' => 'required|string|max:30',
            'email'     => 'required|email|max:150',
            'ville'     => 'required|string|max:100',
            'type'      => 'required|in:Monophasé,Triphasé',
            'amperage'  => 'required|string|max:10',
            'facture'   => 'required|numeric|min:0',
            'toiture'   => 'required|in:Dalle,Tôle,Tuile',
        ]);

        $to      = setting('site.email') ?: 'commerciale@cleanenergyservices.net';
        $subject = '=?UTF-8?B?' . base64_encode('📋 Nouvelle demande de devis — ' . $data['nom']) . '?=';
        $facture = number_format((float) $data['facture'], 0, ',', ' ');

        $body  = "Nouvelle demande de devis reçue depuis le site.\r\n\r\n";
        $body .= "Nom complet    : {$data['nom']}\r\n";
        $body .= "Téléphone      : {$data['telephone']}\r\n";
        $body .= "Email          : {$data['email']}\r\n";
        $body .= "Ville          : {$data['ville']}\r\n";
        $body .= "Type           : {$data['type']}\r\n";
        $body .= "Ampérage       : {$data['amperage']}A\r\n";
        $body .= "Facture CIE    : {$facture} F CFA (tous les 2 mois)\r\n";
        $body .= "Type de toiture: {$data['toiture']}\r\n";

        $headers  = "From: Ma Quincaillerie Solaire <commerciale@cleanenergyservices.net>\r\n";
        $headers .= "Reply-To: commerciale@cleanenergyservices.net\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        $sent = mail($to, $subject, $body, $headers);

        if ($sent) {
            return response()->json(['success' => true]);
        }

        \Illuminate\Support\Facades\Log::error('Erreur mail() devis pour ' . $data['nom']);
        return response()->json(['success' => false], 500);
    }
}
