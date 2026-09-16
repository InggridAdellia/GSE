<?php
$rasio_persetujuan = $rasio_persetujuan ?? [];
$waktu_proses      = $waktu_proses ?? [];
$utilisasi_gse     = $utilisasi_gse ?? [];
$tren_bulanan      = $tren_bulanan ?? [];
$bottleneck        = $bottleneck ?? null;
?>

<style>
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.92rem; margin-bottom:0; }
    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.76rem; font-weight:600; padding:.25rem .65rem; border-radius:999px; }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-graph-up"></i> Dashboard Business Intelligence — Efisiensi Operasional</h5>
        <p class="page-subtitle">Analisis KPI operasional Ground Support Equipment berdasarkan data permohonan.</p>
    </div>
    <span class="text-muted small">Update otomatis setiap refresh &bull; <?= date('d-m-Y H:i:s') ?></span>
</div>

<!-- KPI 1: Rasio Persetujuan per Airline -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-airplane"></i> Rasio Persetujuan per Airline</h6>
        <span class="count-pill"><?= count($rasio_persetujuan) ?> airline</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Airline</th>
                        <th>Total Permohonan</th>
                        <th>Disetujui</th>
                        <th>Ditolak</th>
                        <th>% Disetujui</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rasio_persetujuan)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data permohonan.</td></tr>
                    <?php else: foreach ($rasio_persetujuan as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($row->nama_airline) ?></td>
                        <td><?= $row->total_permohonan ?></td>
                        <td><span class="badge bg-success"><?= $row->disetujui ?></span></td>
                        <td><span class="badge bg-danger"><?= $row->ditolak ?></span></td>
                        <td><?= $row->persentase_disetujui ?>%</td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KPI 2: Waktu Rata-rata Proses -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-stopwatch"></i> Waktu Rata-rata Proses Persetujuan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Jenis Permohonan</th>
                        <th>Rata-rata (jam)</th>
                        <th>Tercepat (jam)</th>
                        <th>Terlama (jam)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($waktu_proses)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data permohonan yang disetujui.</td></tr>
                    <?php else: foreach ($waktu_proses as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($row->jenis_permohonan) ?></td>
                        <td><?= $row->rata_rata_jam ?> jam</td>
                        <td><?= $row->tercepat_jam ?> jam</td>
                        <td><?= $row->terlama_jam ?> jam</td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- KPI 4: Grafik Tren Bulanan -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-bar-chart-line"></i> Tren Permohonan per Bulan</h6>
    </div>
    <div class="card-body">
        <canvas id="chartTren" height="90"></canvas>
    </div>
</div>

<!-- KPI 5: Bottleneck Verifikasi -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-hourglass-split"></i> Rata-rata Waktu Antar Tahap Verifikasi</h6>
    </div>
    <div class="card-body">
        <canvas id="chartBottleneck" height="90"></canvas>
    </div>
</div>

<!-- KPI 3: Utilisasi GSE -->
<div class="card border-0">
    <div class="table-card-head">
        <h6><i class="bi bi-truck"></i> Utilisasi GSE (Frekuensi Pemakaian)</h6>
        <span class="count-pill"><?= count($utilisasi_gse) ?> unit</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama GSE</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Total Pemakaian</th>
                        <th>Terakhir Dipakai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisasi_gse)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data GSE.</td></tr>
                    <?php else: foreach ($utilisasi_gse as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($row->nama_gse) ?></td>
                        <td><?= htmlspecialchars($row->manufacture_type ?? '-') ?></td>
                        <td>
                            <span class="badge <?= $row->status === 'Aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= htmlspecialchars($row->status) ?>
                            </span>
                        </td>
                        <td><?= $row->total_pemakaian ?>x</td>
                        <td class="small text-muted">
                            <?= $row->terakhir_dipakai ? date('d-m-Y', strtotime($row->terakhir_dipakai)) : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Tren Bulanan
fetch('<?= site_url("bi/data_tren_bulanan") ?>')
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById('chartTren'), {
            type: 'line',
            data: {
                labels: data.map(d => d.bulan),
                datasets: [
                    { label: 'Total', data: data.map(d => d.total_permohonan), borderColor: '#0D4F55', tension: 0.3 },
                    { label: 'Masuk Baru', data: data.map(d => d.masuk_baru), borderColor: '#1F6F45', tension: 0.3 },
                    { label: 'Perbaikan', data: data.map(d => d.perbaikan), borderColor: '#A23B2A', tension: 0.3 }
                ]
            },
            options: { responsive: true }
        });
    });

// Grafik Bottleneck Verifikasi
new Chart(document.getElementById('chartBottleneck'), {
    type: 'bar',
    data: {
        labels: ['Operasi → Equipment', 'Equipment → Sales', 'Sales → Security'],
        datasets: [{
            label: 'Rata-rata Jam',
            data: [
                <?= $bottleneck->operasi_ke_equipment ?? 0 ?>,
                <?= $bottleneck->equipment_ke_sales ?? 0 ?>,
                <?= $bottleneck->sales_ke_security ?? 0 ?>
            ],
            backgroundColor: ['#1F6F45', '#E69F2B', '#A23B2A']
        }]
    },
    options: { responsive: true }
});
</script>