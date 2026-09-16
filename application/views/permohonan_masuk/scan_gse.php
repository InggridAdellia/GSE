<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Info Unit GSE — <?= htmlspecialchars($gse->nama_gse) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    body{
        background:#0A363B; min-height:100vh; margin:0;
        display:flex; align-items:center; justify-content:center; padding:1.5rem;
        font-family:'Segoe UI',sans-serif;
    }
    .card-gse{
        max-width:420px; width:100%; border-radius:1.25rem; overflow:hidden;
        box-shadow:0 20px 50px -20px rgba(0,0,0,.5); background:#fff;
    }
    .card-gse .head{ background:#0A363B; color:#fff; padding:1.5rem; text-align:center; }
    .card-gse .head i{ font-size:2.2rem; color:#F0B429; }
    .info-row{ display:flex; justify-content:space-between; align-items:center; padding:.85rem 1.25rem; border-bottom:1px solid #EEF1F1; gap:1rem; }
    .info-row:last-child{ border-bottom:0; }
    .info-row .label{ font-size:.78rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:.03em; }
    .info-row .value{ font-size:.95rem; font-weight:700; color:#111827; text-align:right; }
    .badge-kategori{ font-size:.78rem; padding:.35rem .7rem; border-radius:999px; font-weight:700; color:#fff; }
    .footer-note{ text-align:center; padding:.9rem 1.25rem; font-size:.72rem; color:#9ca3af; }
</style>
</head>
<body>

<div class="card-gse">
    <div class="head">
        <i class="bi bi-truck-front"></i>
        <h5 class="mt-2 mb-0"><?= htmlspecialchars($gse->nama_gse) ?></h5>
        <div class="small" style="opacity:.8;">Info Unit GSE</div>
    </div>
    <div>
        <div class="info-row">
            <span class="label">Nama GSE</span>
            <span class="value"><?= htmlspecialchars($gse->nama_gse) ?></span>
        </div>
        <div class="info-row">
            <span class="label">No. Stiker AP</span>
            <span class="value"><?= htmlspecialchars($gse->sticker_ap ?: '-') ?></span>
        </div>
        <div class="info-row">
            <span class="label">No. Asset</span>
            <span class="value"><?= htmlspecialchars($gse->no_asset ?: '-') ?></span>
        </div>
        <div class="info-row">
            <span class="label">Kategori</span>
            <span class="value">
                <?php $is_motor = strtolower($gse->manufacture_type ?? '') === 'motorized'; ?>
                <span class="badge-kategori" style="background:<?= $is_motor ? '#A23B2A' : '#6b7280' ?>;">
                    <?= $is_motor ? 'Motorized' : 'Non-Motorized' ?>
                </span>
            </span>
        </div>
    </div>
    <div class="footer-note">Dipindai dari stiker verifikasi GSE</div>
</div>

</body>
</html>