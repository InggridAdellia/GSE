<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Semua Stiker (<?= count($daftar_gse) ?> Unit)</title>
    <script src="<?= base_url('assets/vendor/qrcode/qrcode.min.js') ?>"></script>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #eef2f2; padding: 20px; }
    .toolbar { display: flex; justify-content: center; margin-bottom: 20px; }
    .btn-print { padding: 10px 30px; background: #0d6efd; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 12pt; }

    .page {
        width: 19cm;
        margin: 0 auto 1cm;
        display: grid;
        grid-template-columns: repeat(2, 8.6cm);
        grid-auto-rows: 5.4cm;
        column-gap: 0.4cm;
        row-gap: 0.15cm;
        justify-content: center;
        background: #fff;
        padding: 1cm;
    }

    .stiker {
        width: 8.6cm; height: 5.4cm; position: relative;
        background-image: url('<?= base_url('assets/img/bg_stiker_gse.png') ?>');
        background-size: 100% 100%; background-repeat: no-repeat;
        box-shadow: 0 2px 8px rgba(0,0,0,.3);
        page-break-inside: avoid;
        break-inside: avoid;
        overflow: hidden;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    .stiker-body { position: absolute; top: 38%; left: 0; right: 0; bottom: 0; display: flex; align-items: stretch; }
    .stiker-left { width: 2.5cm; display: flex; align-items: center; justify-content: center; padding-left: 0.35cm; }
    .qr-wrapper { background: #fff; padding: 3px; display: inline-flex; align-items: center; justify-content: center; width: 2.65cm; height: 2.65cm; }
    .qr-wrapper canvas, .qr-wrapper img { display: block; width: 2.5cm !important; height: 2.5cm !important; }
    .stiker-right { flex: 1; display: flex; flex-direction: column; justify-content: center; padding-left: 0.3cm; padding-right: 0.25cm; }
    .stiker-label { font-size: 0.25cm; font-weight: 900; letter-spacing: 0.06em; color: #000000; text-transform: uppercase; text-align: center; margin-bottom: 0.05cm; }
    .stiker-year { font-size: 2cm; font-weight: 400; line-height: 1; color: #00acee; text-align: center; letter-spacing: 0.02em; }
    .stiker-code { font-size: 0.25cm; font-weight: 600; letter-spacing: .04em; color: #1a2b33; text-align: center; margin-top: 0.1cm; }

    @media print {
        body { background: #fff; padding: 0; margin: 0; }
        .toolbar { display: none; }
        .page {
            box-shadow: none;
            page-break-after: always;
            margin: 0;
        }
        .page:last-child { page-break-after: auto; }
        .stiker { box-shadow: none; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
        @page { size: A4; margin: 0; }
    }
</style>
</head>
<body>

<div class="toolbar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Semua Stiker (<?= count($daftar_gse) ?>)</button>
</div>

<div class="sheet">
    <?php foreach ($daftar_gse as $i => $g): ?>
    <div class="stiker">
        <div class="stiker-body">
            <div class="stiker-left">
                <div class="qr-wrapper">
                    <div id="qr-item-<?= $i ?>"></div>
                </div>
            </div>
            <div class="stiker-right">
                <div class="stiker-label">Ground Support Equipment</div>
                <div class="stiker-year"><?= date('Y', strtotime($g->masa_mulai ?? $g->created_at)) ?></div>
                <div class="stiker-code"><?= htmlspecialchars($g->sticker_ap) ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
<?php foreach ($daftar_gse as $i => $g): ?>
new QRCode(document.getElementById("qr-item-<?= $i ?>"), {
    text: <?= json_encode($g->sticker_ap) ?>,
    width: 72,
    height: 72,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.M
});
<?php endforeach; ?>
</script>

</body>
</html>