<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouvelle commande {{ $order->order_number }}</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; }
  .wrapper { max-width:620px; margin:32px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:linear-gradient(135deg,#065f46 0%,#047857 100%); padding:36px 32px; text-align:center; }
  .header h1 { color:#ffffff; font-size:22px; font-weight:800; margin:0 0 4px; }
  .header p { color:#a7f3d0; font-size:14px; margin:0; }
  .badge { display:inline-block; background:#fbbf24; color:#78350f; font-size:12px; font-weight:700; padding:4px 14px; border-radius:999px; margin-top:12px; letter-spacing:.04em; }
  .body { padding:32px; }
  .alert { background:#ecfdf5; border-left:4px solid #10b981; border-radius:8px; padding:14px 18px; margin-bottom:24px; font-size:14px; color:#065f46; font-weight:600; }
  .section-title { font-size:12px; font-weight:700; color:#065f46; text-transform:uppercase; letter-spacing:.06em; margin-bottom:12px; margin-top:24px; }
  .info-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; margin-bottom:16px; }
  .info-row { display:flex; justify-content:space-between; font-size:14px; padding:6px 0; border-bottom:1px solid #f1f5f9; }
  .info-row:last-child { border-bottom:none; }
  .info-label { color:#6b7280; }
  .info-value { color:#1f2937; font-weight:600; text-align:right; }
  .client-box { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:16px 20px; margin-bottom:16px; font-size:14px; line-height:1.9; }
  .client-box strong { color:#92400e; }
  table.products { width:100%; border-collapse:collapse; margin-bottom:16px; font-size:14px; }
  table.products th { background:#f1f5f9; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; padding:10px 14px; text-align:left; }
  table.products td { padding:11px 14px; border-bottom:1px solid #f1f5f9; vertical-align:top; }
  table.products tr:last-child td { border-bottom:none; }
  .totals { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:16px; }
  .trow { display:flex; justify-content:space-between; padding:10px 20px; font-size:14px; border-bottom:1px solid #e2e8f0; }
  .trow:last-child { border-bottom:none; background:#065f46; color:#fff; font-weight:800; font-size:15px; }
  .addr-box { background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:16px 20px; margin-bottom:16px; font-size:14px; line-height:1.75; color:#1e3a5f; }
  .pay-label { display:inline-block; background:#fef3c7; border:1px solid #fde68a; color:#92400e; font-size:13px; font-weight:700; padding:8px 16px; border-radius:8px; margin-bottom:16px; }
  .footer { background:#f8fafc; padding:20px 32px; text-align:center; border-top:1px solid #e5e7eb; font-size:12px; color:#9ca3af; line-height:1.7; }
</style>
</head>
<body>
<div class="wrapper">

  <div class="header">
    <h1>☀️ Ma Quincaillerie Solaire</h1>
    <p>Notification interne — Nouvelle commande reçue</p>
    <span class="badge">🛒 À traiter</span>
  </div>

  <div class="body">

    <div class="alert">
      📬 Une nouvelle commande vient d'être passée sur le site web.
    </div>

    <div class="section-title">Informations commande</div>
    <div class="info-box">
      <div class="info-row">
        <span class="info-label">Numéro</span>
        <span class="info-value">{{ $order->order_number }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Date</span>
        <span class="info-value">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Paiement</span>
        <span class="info-value">
          @switch($order->payment_method)
            @case('cod') Espèce en magasin @break
            @case('orange_money') Orange Money @break
            @case('wave') Wave @break
            @case('mtn_money') MTN Money @break
            @case('transfer') Virement bancaire @break
            @case('check') Chèque @break
            @default {{ $order->payment_method }}
          @endswitch
        </span>
      </div>
      @if($order->is_company)
      <div class="info-row">
        <span class="info-label">Société</span>
        <span class="info-value">{{ $order->company_name }} (TVA 18%)</span>
      </div>
      @endif
    </div>

    <div class="section-title">Client</div>
    <div class="client-box">
      <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
      📧 <a href="mailto:{{ $order->email }}" style="color:#065f46;">{{ $order->email }}</a><br>
      @if($order->phone)📞 {{ $order->phone }}<br>@endif
      @if($order->is_company)🏢 {{ $order->company_name }}@endif
    </div>

    <div class="section-title">Articles commandés</div>
    <table class="products">
      <thead>
        <tr>
          <th>Produit</th>
          <th style="text-align:center">Qté</th>
          <th style="text-align:right">Prix unitaire</th>
          <th style="text-align:right">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items_decoded as $item)
        <tr>
          <td style="font-weight:600;color:#1f2937;">{{ $item['name'] }}</td>
          <td style="text-align:center;color:#6b7280;">{{ $item['quantity'] }}</td>
          <td style="text-align:right;color:#6b7280;">{{ number_format($item['price'], 0, ',', ' ') }} F</td>
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
      @if($order->tax > 0)
      <div class="trow">
        <span>TVA (18%)</span>
        <span>{{ number_format($order->tax, 0, ',', ' ') }} F CFA</span>
      </div>
      @endif
      <div class="trow">
        <span>Total TTC</span>
        <span>{{ number_format($order->total, 0, ',', ' ') }} F CFA</span>
      </div>
    </div>

    <div class="section-title">Adresse de livraison</div>
    <div class="addr-box">
      {{ $order->first_name }} {{ $order->last_name }}<br>
      {{ $order->address }}<br>
      {{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}<br>
      {{ $order->country }}
    </div>

    @if($order->notes)
    <div class="section-title">Notes du client</div>
    <div class="info-box" style="font-size:14px;color:#4b5563;">{{ $order->notes }}</div>
    @endif

  </div>

  <div class="footer">
    <p>Ma Quincaillerie Solaire — Clean Energy Services</p>
    <p style="font-size:11px;margin-top:4px;">Email automatique — ne pas répondre directement à cet email.</p>
  </div>

</div>
</body>
</html>
