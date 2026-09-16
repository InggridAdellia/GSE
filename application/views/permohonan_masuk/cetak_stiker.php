<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stiker Verifikasi - <?= htmlspecialchars($permohonan->nomor_permohonan) ?></title>
    <script src="<?= base_url('assets/vendor/qrcode/qrcode.min.js') ?>"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #eef2f2; padding: 20px; }
        .toolbar { display: flex; justify-content: center; margin-bottom: 20px; }
        .btn-print { padding: 10px 30px; background: #0d6efd; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 12pt; }
        .sheet { display: grid; grid-template-columns: repeat(2, 8.6cm); gap: 6mm; justify-content: center; }
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
            .sheet { gap: 4mm; }
            .stiker { box-shadow: none; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
            @page { size: 18cm 12cm; margin: 3mm; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Stiker Verifikasi</button>
</div>

<?php if (empty($daftar_gse)): ?>
<p style="text-align:center; margin-top:20px; color:#888;">Tidak ada unit GSE pada permohonan ini.</p>
<?php else: ?>

<?php
preg_match('/(\d+)$/', $permohonan->nomor_permohonan, $m);
$nomor_seri = isset($m[1]) ? str_pad($m[1], 6, '0', STR_PAD_LEFT) : str_pad($permohonan->id_permohonan_masuk, 6, '0', STR_PAD_LEFT);

// Buat array kode per GSE untuk dipakai di PHP dan JS
$kode_list = [];
foreach ($daftar_gse as $i => $g) {
    $kode_list[$i] = 'GE.' . $kode_bandara . '.' . ($permohonan->kode_airline ?? '') . '.' . $nomor_seri . '.' . ($i + 1);
}
?>

<div class="sheet">
    <?php foreach ($daftar_gse as $i => $g): ?>
    <div class="stiker">
        <div class="stiker-body">
            <div class="stiker-left">
                <div class="qr-wrapper">
                    <div id="qr-<?= $i ?>"></div>
                </div>
            </div>
            <div class="stiker-right">
                <div class="stiker-label">Ground Support Equipment</div>
                <?php $tahun_unit = !empty($g->masa_mulai) ? date('Y', strtotime($g->masa_mulai)) : $tahun_validasi; ?>
                <div class="stiker-year"><?= htmlspecialchars($tahun_unit) ?></div>
                <div class="stiker-code"><?= htmlspecialchars($kode_list[$i]) ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
var qrOpts = {
    width: 72, height: 72,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.M
};
var kodeList = <?= json_encode(array_values($kode_list)) ?>;
<?php foreach ($daftar_gse as $i => $g): ?>
new QRCode(document.getElementById("qr-<?= $i ?>"), Object.assign({ text: kodeList[<?= $i ?>] }, qrOpts));
<?php endforeach; ?>
</script>

<?php endif; ?>

</body>
</html>