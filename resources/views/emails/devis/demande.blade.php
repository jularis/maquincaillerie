<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Nouvelle demande de devis</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; }
  .wrapper { max-width:580px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:linear-gradient(135deg,#1a3264 0%,#253d7a 100%); padding:32px; text-align:center; }
  .header h1 { color:#fff; font-size:20px; font-weight:800; margin:0 0 4px; }
  .header p { color:#93c5fd; font-size:13px; margin:0; }
  .badge { display:inline-block; background:#f97316; color:#fff; font-size:12px; font-weight:700; padding:4px 14px; border-radius:999px; margin-top:10px; }
  .body { padding:32px; }
  .row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
  .row:last-child { border-bottom:none; }
  .label { color:#6b7280; }
  .value { color:#1f2937; font-weight:700; text-align:right; }
  .footer { background:#f8fafc; padding:16px 32px; text-align:center; border-top:1px solid #e5e7eb; font-size:12px; color:#9ca3af; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>☀️ Ma Quincaillerie Solaire</h1>
    <p>Clean Energy Services</p>
    <span class="badge">📋 Nouvelle demande de devis</span>
  </div>
  <div class="body">
    <p style="font-size:15px;font-weight:600;color:#1a3264;margin-bottom:20px;">Un client souhaite un devis pour son installation solaire.</p>
    <div class="row"><span class="label">Nom & Prénom</span><span class="value">{{ $data['nom'] }}</span></div>
    <div class="row"><span class="label">Ville</span><span class="value">{{ $data['ville'] }}</span></div>
    <div class="row"><span class="label">Type d'installation</span><span class="value">{{ $data['type'] }}</span></div>
    <div class="row"><span class="label">Ampérage compteur</span><span class="value">{{ $data['amperage'] }}A</span></div>
    <div class="row"><span class="label">Facture CIE (2 mois)</span><span class="value">{{ number_format($data['facture'], 0, ',', ' ') }} F CFA</span></div>
    <div class="row"><span class="label">Type de toiture</span><span class="value">{{ $data['toiture'] }}</span></div>
  </div>
  <div class="footer">Ma Quincaillerie Solaire — Clean Energy Services</div>
</div>
</body>
</html>
