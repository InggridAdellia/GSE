<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stiker - <?= htmlspecialchars($gse->nama_gse) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #eef2f2; padding: 20px; }
        .toolbar { display: flex; justify-content: center; margin-bottom: 20px; }
        .btn-print { padding: 10px 30px; background: #0d6efd; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 12pt; }
        .sheet { display: flex; justify-content: center; }
        .stiker {
            width: 8.6cm; height: 5.4cm; position: relative;
            background-image: url('<?= base_url('assets/img/bg_stiker_gse.png') ?>');
            background-size: 100% 100%; background-repeat: no-repeat;
            box-shadow: 0 2px 8px rgba(0,0,0,.3); page-break-inside: avoid;
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
            body { background: #fff; padding: 0; margin: 0; width: fit-content; height: fit-content; }
            .toolbar { display: none; }
            .stiker { box-shadow: none; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
            @page { size: 9cm 6cm; margin: 2mm; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Stiker</button>
</div>

<div class="sheet">
    <div class="stiker">
        <div class="stiker-body">
            <div class="stiker-left">
                <div class="qr-wrapper">
                    <div id="qr-item"></div>
                </div>
            </div>
            <div class="stiker-right">
                <div class="stiker-label">Ground Support Equipment</div>
                <div class="stiker-year"><?= htmlspecialchars($tahun_validasi) ?></div>
                <div class="stiker-code"><?= htmlspecialchars($sticker_ap) ?></div>
            </div>
        </div>
    </div>
</div>

<script>
new QRCode(document.getElementById("qr-item"), {
    text: <?= json_encode($sticker_ap) ?>,
    width: 72,
    height: 72,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.M
});
</script>

</body>
</html>