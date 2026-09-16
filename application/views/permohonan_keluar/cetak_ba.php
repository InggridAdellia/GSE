<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara - <?= $permohonan->nomor_permohonan ?></title>
    <script src="<?= base_url('assets/vendor/qrcode/qrcode.min.js') ?>"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9.5pt; padding: 10px; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td, .header-table th { border: 1px solid #000; padding: 5px 8px; }
        .logo { font-weight: bold; font-size: 14pt; }
        .logo span { color: #003399; }

        h3 { text-align: center; font-size: 11pt; margin: 10px 0 5px; text-transform: uppercase; }
        .subtitle { text-align: center; font-size: 10pt; margin-bottom: 10px; }

        .content { margin: 8px 0; line-height: 1.5; }

        .data-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 5px 8px; font-size: 10pt; }
        .data-table th { background: #f0f0f0; text-align: center; }

        .ttd-section { margin-top: 10px; }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-table td { width: 20%; text-align: center; padding: 5px; vertical-align: top; }
        .ttd-box { margin-top: 5px; height: 50px; border-bottom: 1px solid #000; margin-bottom: 5px; }
        .ttd-label { font-size: 9pt; white-space: nowrap; }

        .qr-box { display: flex; justify-content: center; margin: 5px 0; }
        .qr-box img, .qr-box canvas { width: 80px !important; height: 80px !important; }

        .footer-note { margin-top: 10px; font-size: 9pt; font-style: italic; }
        .tidak-terkendali { border: 1px solid #000; padding: 8px; text-align: center;
                    font-size: 9pt; margin-top: 40px; }

        .btn-print { display: block; margin: 20px auto; padding: 10px 30px;
                     background: #0d6efd; color: white; border: none;
                     border-radius: 5px; cursor: pointer; font-size: 12pt; }

        @media print {
            .btn-print { display: none; }
            body { padding: 5px; font-size: 9pt; }
            @page { size: A4; margin: 10mm; }

            .tidak-terkendali {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>

<button class="btn-print" onclick="window.print()">
    🖨️ Cetak Berita Acara
</button>

<!-- DOKUMEN -->
<div style="text-align:center; margin-bottom:10px;">
    <img src="<?= base_url('logo_injourney.png') ?>" alt="Injourney Airports" style="max-height:60px;">
</div>

<h3>BERITA ACARA</h3>
<div class="subtitle">
    PENGELUARAN KENDARAAN OPERASIONAL/GSE DARI SISI UDARA<br>
    DI BANDAR UDARA INTERNASIONAL SULTAN HASANUDDIN
</div>

<?php
$tgl     = date('d', strtotime($permohonan->updated_at));
$bulan   = date('F', strtotime($permohonan->updated_at));
$bulanId = [
    'January'=>'Januari','February'=>'Februari','March'=>'Maret',
    'April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli',
    'August'=>'Agustus','September'=>'September','October'=>'Oktober',
    'November'=>'November','December'=>'Desember'
];
$bulanIndo = $bulanId[$bulan] ?? $bulan;
$tahun     = date('Y', strtotime($permohonan->updated_at));
$hariId    = [
    'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
    'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
];
$hari = $hariId[date('l', strtotime($permohonan->updated_at))] ?? '';
?>

<div class="content">
    Pada hari ini <strong><?= $hari ?></strong>, Tanggal <strong><?= $tgl ?></strong>
    Bulan <strong><?= $bulanIndo ?></strong> Tahun <strong><?= $tahun ?></strong>
    telah dikeluarkan kendaraan operasinal/peralatan GSE dari Sisi Udara
    dengan data sebagaimana terlampir :
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>MANUFACTURE TYPE / MODEL / NO. SERI</th>
            <th>NO ASSET</th>
            <th>STICKER AP</th>
            <th>KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
    <?php $no = 1; foreach ($daftar_gse as $gse): ?>
    <tr>
        <td style="text-align:center;"><?= $no++ ?></td>
        <td><?= htmlspecialchars($gse->nama_gse) ?></td>
        <td><?= htmlspecialchars($gse->manufacture_type ?? '-') ?></td>
        <td><?= htmlspecialchars($gse->no_asset ?? '-') ?></td>
        <td><?= htmlspecialchars($gse->sticker_ap ?? '-') ?></td>
        <td><?= htmlspecialchars($gse->keterangan ?: '-') ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>

<div class="content">
    Demikian Berita Acara ini dibuat dengan sebenarnya untuk dapat digunakan sebagaimana mestinya.
</div>

<div style="text-align:right; margin: 10px 40px 0 0;">
    Maros, <?= $tgl ?> <?= $bulanIndo ?> <?= $tahun ?>
</div>

<!-- Tanda Tangan -->
<div class="ttd-section">
    <table class="ttd-table">
        <tr>
            <td></td>
            <td>
                <div class="ttd-label">Petugas<br>Ground Handling / Airlines</div>
                <div id="bc_gh" class="qr-box"></div>
                <div>( <?= htmlspecialchars($nama_gh) ?> )</div>
            </td>
            <td></td>
            <td>
                <div class="ttd-label">Petugas<br>Airport Non Aeronautical</div>
                <div id="bc_sales" class="qr-box"></div>
                <div>( <?= htmlspecialchars($nama_sales) ?> )</div>
            </td>
            <td></td>
        </tr>
    </table>

    <table class="ttd-table" style="margin-top:15px;">
        <tr>
            <td></td>
            <td>
                <div class="ttd-label">Petugas<br>Airport Operation Airside</div>
                <div id="bc_ops" class="qr-box"></div>
                <div>( <?= htmlspecialchars($nama_operasi) ?> )</div>
            </td>
            <td></td>
            <td>
                <div class="ttd-label">Petugas<br>Airport Security Protection</div>
                <div id="bc_security" class="qr-box"></div>
                <div>( <?= htmlspecialchars($nama_security) ?> )</div>
            </td>
            <td></td>
        </tr>
    </table>

    <table class="ttd-table" style="margin-top:15px;">
        <tr>
            <td colspan="5" style="text-align:center; padding-bottom:5px;">
                <div class="ttd-label">Mengetahui,</div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div class="ttd-label">AMC Supervisor / AMC On Duty</div>
                <div class="ttd-box" style="margin-top:25px;"></div>
                <div>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            </td>
            <td></td>
            <td>
                <div class="ttd-label">Security On Duty</div>
                <div class="ttd-box" style="margin-top:25px;"></div>
                <div>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            </td>
            <td></td>
        </tr>
    </table>
</div>

<?php
$fmt = function($nama, $waktu) {
    $nama_bersih = str_replace(' ', '', $nama);
    return $nama_bersih . date('dmYHi', strtotime($waktu));
};
?>

<script>
var opts = { width: 80, height: 80, colorDark: "#000", colorLight: "#fff", correctLevel: QRCode.CorrectLevel.M };

var bc_gh       = <?= json_encode($fmt($nama_gh,       $permohonan->updated_at)) ?>;
var bc_ops      = <?= json_encode($fmt($nama_operasi,  $waktu_operasi)) ?>;
var bc_sales    = <?= json_encode($fmt($nama_sales,    $waktu_sales)) ?>;
var bc_security = <?= json_encode($fmt($nama_security, $waktu_security)) ?>;

new QRCode(document.getElementById("bc_gh"),       Object.assign({text: bc_gh},       opts));
new QRCode(document.getElementById("bc_ops"),      Object.assign({text: bc_ops},      opts));
new QRCode(document.getElementById("bc_security"), Object.assign({text: bc_security}, opts));
new QRCode(document.getElementById("bc_sales"),    Object.assign({text: bc_sales},    opts));
</script>

<div class="tidak-terkendali">
    Dokumen yang diunduh, dicetak, dan digandakan dalam bentuk apapun merupakan dokumen <strong>TIDAK TERKENDALI</strong>
</div>

</body>
</html>