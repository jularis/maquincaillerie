<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmation de commande {{ $order->order_number }}</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; }
  .wrapper { max-width:620px; margin:32px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:linear-gradient(135deg,#1a3264 0%,#253d7a 100%); padding:36px 32px; text-align:center; }
  .header h1 { color:#ffffff; font-size:22px; font-weight:800; margin:0 0 4px; }
  .header p { color:#93c5fd; font-size:14px; margin:0; }
  .badge { display:inline-block; background:#f97316; color:#fff; font-size:12px; font-weight:700; padding:4px 14px; border-radius:999px; margin-top:12px; letter-spacing:.04em; }
  .body { padding:32px; }
  .greeting { font-size:16px; font-weight:600; color:#1a3264; margin-bottom:8px; }
  .text { font-size:14px; color:#4b5563; line-height:1.6; margin:0 0 24px; }
  .section-title { font-size:12px; font-weight:700; color:#1a3264; text-transform:uppercase; letter-spacing:.06em; margin-bottom:12px; }
  .info-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; margin-bottom:24px; }
  .info-row { display:flex; justify-content:space-between; font-size:14px; padding:6px 0; border-bottom:1px solid #f1f5f9; }
  .info-row:last-child { border-bottom:none; }
  .info-label { color:#6b7280; }
  .info-value { color:#1f2937; font-weight:600; text-align:right; }
  table.products { width:100%; border-collapse:collapse; margin-bottom:24px; font-size:14px; }
  table.products th { background:#f1f5f9; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; padding:10px 14px; text-align:left; }
  table.products td { padding:11px 14px; border-bottom:1px solid #f1f5f9; vertical-align:top; }
  table.products tr:last-child td { border-bottom:none; }
  .totals { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:24px; }
  .trow { display:flex; justify-content:space-between; padding:10px 20px; font-size:14px; border-bottom:1px solid #e2e8f0; }
  .trow:last-child { border-bottom:none; background:#1a3264; color:#fff; font-weight:800; font-size:15px; }
  .trow.free { color:#16a34a; }
  .addr-box { background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:16px 20px; margin-bottom:20px; font-size:14px; line-height:1.75; color:#1e3a5f; }
  .pay-badge { display:inline-block; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; font-size:13px; font-weight:600; padding:8px 16px; border-radius:8px; margin-bottom:24px; }
  .cta { display:block; background:#f97316; color:#ffffff !important; text-align:center; padding:15px 32px; border-radius:12px; font-weight:700; font-size:15px; text-decoration:none; margin-bottom:24px; }
  hr { border:none; border-top:1px solid #e5e7eb; margin:24px 0; }
  .footer { background:#f8fafc; padding:20px 32px; text-align:center; border-top:1px solid #e5e7eb; font-size:12px; color:#9ca3af; line-height:1.7; }
  .footer a { color:#1a3264; font-weight:600; text-decoration:none; }
</style>
</head>
<body>
<div class="wrapper">

  <div class="header">
    <h1>☀️ Ma Quincaillerie Solaire</h1>
    <p>Experts photovoltaïque et stockage solaire depuis 2011</p>
    <span class="badge">✅ Commande confirmée</span>
  </div>

  <div class="body">

    <p class="greeting">Bonjour {{ $order->first_name }} {{ $order->last_name }},</p>
    <p class="text">
      Nous avons bien reçu votre commande et elle est en cours de traitement.
      Voici le récapitulatif complet de votre achat.
    </p>

    <div class="section-title">Détails de la commande</div>
    <div class="info-box">
      <div class="info-row">
        <span class="info-label">Numéro de commande</span>
        <span class="info-value">{{ $order->order_number }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Date</span>
        <span class="info-value">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Mode de paiement</span>
        <span class="info-value">
          @switch($order->payment_method)
            @case('cod') 🚚 Paiement à la livraison @break
            @case('card') 💳 Carte bancaire @break
            @case('transfer') 🏦 Virement bancaire @break
            @case('check') 📝 Chèque @break
            @default {{ $order->payment_method }}
          @endswitch
        </span>
      </div>
      <div class="info-row">
        <span class="info-label">Statut</span>
        <span class="info-value" style="color:#f97316;">En attente de traitement</span>
      </div>
    </div>

    <div class="section-title">Articles commandés</div>
    <table class="products">
      <thead>
        <tr>
          <th>Produit</th>
          <th style="text-align:center">Qté</th>
          <th style="text-align:right">Prix</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $item)
        <tr>
          <td style="font-weight:600;color:#1f2937;">{{ $item['name'] }}</td>
          <td style="text-align:center;color:#6b7280;">{{ $item['quantity'] }}</td>
          <td style="text-align:right;font-weight:600;">{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} F CFA</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="totals">
      <div class="trow">
        <span>Sous-total HT</span>
        <span>{{ number_format($order->subtotal, 0, ',', ' ') }} F CFA</span>
      </div>
      <div class="trow">
        <span>TVA (18%)</span>
        <span>{{ number_format($order->tax, 0, ',', ' ') }} F CFA</span>
      </div>
      <div class="trow {{ $order->shipping == 0 ? 'free' : '' }}">
        <span>Livraison</span>
        <span>{{ $order->shipping == 0 ? '✅ Gratuite' : number_format($order->shipping, 0, ',', ' ') . ' F CFA' }}</span>
      </div>
      <div class="trow">
        <span>Total TTC</span>
        <span>{{ number_format($order->total, 0, ',', ' ') }} F CFA</span>
      </div>
    </div>

    <div class="section-title">Adresse de livraison</div>
    <div class="addr-box">
      <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
      @if($order->phone){{ $order->phone }}<br>@endif
      {{ $order->address }}<br>
      {{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}<br>
      {{ $order->country }}
    </div>

    @if($order->payment_method === 'cod')
    <div class="pay-badge">🚚 Vous paierez en espèces à la réception — livraison sous 24 à 48h</div>
    @elseif($order->payment_method === 'transfer')
    <div class="pay-badge" style="background:#eff6ff;border-color:#bfdbfe;color:#1e40af;">
      🏦 Veuillez effectuer votre virement dans les 48h pour valider votre commande
    </div>
    @else
    <div class="pay-badge">✅ Votre commande sera expédiée dès validation du paiement</div>
    @endif

    <hr>

    <p class="text" style="text-align:center;color:#6b7280;font-size:13px;">
      Une question sur votre commande ? Notre équipe est disponible<br>du lundi au samedi de 9h à 18h.
    </p>

    <a href="mailto:commerciale@cleanenergyservices.net" class="cta">✉️ Contacter le service client</a>

  </div>

  <div class="footer">
    <p>📞 +225 27 35 95 89 98 &nbsp;|&nbsp; +225 07 69 62 26 44</p>
    <p><a href="mailto:commerciale@cleanenergyservices.net">commerciale@cleanenergyservices.net</a></p>
    <p style="margin-top:8px;">Ma Quincaillerie Solaire — Clean Energy Services</p>
    <p style="font-size:11px;margin-top:6px;">Vous recevez cet e-mail car vous avez passé une commande sur notre site.</p>
  </div>

</div>
</body>
</html>
