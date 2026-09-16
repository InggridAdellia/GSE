<?php
$semua = $semua ?? [];

$badge = [
    'Menunggu Verifikasi Operasi'     => 'bg-warning text-dark',
    'Menunggu Verifikasi Sales'       => 'bg-primary',
    'Menunggu Verifikasi Security'    => 'bg-secondary',
    'Disetujui'                       => 'bg-success',
    'Ditolak'                         => 'bg-danger',
    'Sebagian Ditolak'                => 'bg-danger',
];

// Statistik
$all          = $semua;
$total_p      = count($all);
$count_setuju = 0;
$count_tolak  = 0;
foreach ($all as $row) {
    if ($row->status === 'Disetujui') $count_setuju++;
    if (in_array($row->status, ['Ditolak', 'Sebagian Ditolak'])) $count_tolak++;
}
$count_proses = $total_p - $count_setuju - $count_tolak;
$is_gh        = $this->session->userdata('role') === 'ground_handling';
?>
<style>
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; font-size:.92rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.72rem; margin-bottom:0; }

    .stat-row{ display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; margin-bottom:1.25rem; }
    .stat-card{ background:#fff; border-radius:1rem; padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); }
    .stat-icon{ width:38px; height:38px; border-radius:.7rem; display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
    .stat-icon-teal{ background:var(--mint-50); color:var(--teal-700); }
    .stat-icon-green{ background:#E9F6EE; color:#1F6F45; }
    .stat-icon-red{ background:#FDECE8; color:#A23B2A; }
    .stat-value{ font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:1rem; color:var(--ink-900); line-height:1.1; }
    .stat-label{ font-size:.62rem; color:var(--ink-600); }
    @media(max-width:767.98px){ .stat-row{ grid-template-columns:1fr; } }

    /* Tab jenis */
    .jenis-tabs{ display:flex; gap:.5rem; margin-bottom:1rem; flex-wrap:wrap; }
    .jenis-tab{
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.4rem .9rem; border-radius:999px; font-size:.68rem; font-weight:600;
        border:1.5px solid var(--line); background:#fff; color:var(--ink-600);
        cursor:pointer; transition:all .15s;
    }
    .jenis-tab:hover{ border-color:var(--teal-600); color:var(--teal-700); }
    .jenis-tab.active{ border-color:var(--teal-600); background:var(--mint-50); color:var(--teal-700); }
    .jenis-tab .pill{ background:var(--line); color:var(--ink-600); border-radius:999px; padding:.1rem .4rem; font-size:.58rem; }
    .jenis-tab.active .pill{ background:var(--teal-600); color:#fff; }

    .tab-pane-custom{ display:none; }
    .tab-pane-custom.show{ display:block; }

    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); font-size:.8rem; }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.62rem; font-weight:600; padding:.22rem .6rem; border-radius:999px; }

    /* Semua teks isi tabel dikecilkan */
    .table-responsive table{ font-size:.7rem; }

    .badge{ font-weight:600; font-size:.6rem; padding:.35rem .6rem; border-radius:999px; }
    .badge.bg-warning{ background:#FDF2DF !important; color:var(--gold-600) !important; }
    .badge.bg-info{ background:#E6F6F6 !important; color:var(--teal-700) !important; }
    .badge.bg-primary{ background:#EAF1FB !important; color:#2C5FA8 !important; }
    .badge.bg-success{ background:#E9F6EE !important; color:#1F6F45 !important; }
    .badge.bg-danger{ background:#FDECE8 !important; color:#A23B2A !important; }
    .badge.bg-secondary{ background:#EEF1F1 !important; color:var(--ink-600) !important; }

    .stage-pill{ font-size:.55rem !important; padding:.2rem .45rem !important; border-radius:6px !important; font-weight:600; }
    .stage-ok{ background:#E9F6EE !important; color:#1F6F45 !important; }
    .stage-no{ background:#FDECE8 !important; color:#A23B2A !important; }
    .stage-idle{ background:#F2F4F3 !important; color:var(--ink-400) !important; }

    .reject-hint{ font-size:.62rem; color:#A23B2A; }
    .row-actions{ display:inline-flex; gap:.35rem; flex-wrap:nowrap; }
    .row-actions .btn{ border-radius:.5rem; font-size:.66rem; }
    .empty-state{ text-align:center; padding:3rem 1rem; color:var(--ink-600); font-size:.74rem; }
    .empty-state i{ font-size:1.8rem; color:var(--teal-600); display:block; margin-bottom:.6rem; }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-envelope-arrow-down"></i> Pengajuan Permohonan Izin keluar GSE</h5>
        <p class="page-subtitle">Daftar permohonan izin keluarnya unit GSE ke area bandara.</p>
    </div>
    <?php if ($is_gh): ?>
    <a href="<?= site_url('permohonan_keluar/tambah') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Buat Permohonan keluar
    </a>
    <?php endif; ?>
</div>

<!-- Statistik -->
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-icon stat-icon-teal"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="stat-value"><?= $count_proses ?></div><div class="stat-label">Dalam Proses</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><i class="bi bi-check2-circle"></i></div>
        <div><div class="stat-value"><?= $count_setuju ?></div><div class="stat-label">Disetujui</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-red"><i class="bi bi-x-circle"></i></div>
        <div><div class="stat-value"><?= $count_tolak ?></div><div class="stat-label">Ditolak</div></div>
    </div>
</div>

<!-- Permohonan Asset Keluar (gabungan Keluar Baru + Perbaikan + Campuran) -->
<?php echo render_table_keluar($semua, 'Permohonan Asset Keluar', $badge, $is_gh); ?>

<?php
function render_table_keluar($rows, $jenis_label, $badge, $is_gh) {
    ob_start();
    ?>
    <div class="card border-0">
        <div class="table-card-head">
            <h6><?= htmlspecialchars($jenis_label) ?></h6>
            <span class="count-pill"><?= count($rows) ?> permohonan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>No. Surat</th>
                            <th>Tanggal Dibuat</th>
                            <th>Unit GSE</th>
                            <th>Kelengkapan</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" class="p-0">
                                <div class="empty-state">
                                    <i class="bi bi-envelope"></i>
                                    <p class="mb-0 fw-semibold">Belum ada permohonan <?= htmlspecialchars(strtolower($jenis_label)) ?></p>
                                    <?php if ($is_gh): ?>
                                    <p class="text-muted small mb-0">Klik "Buat Permohonan keluar" untuk mengajukan yang baru.</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php else: foreach ($rows as $row): ?>
                        <?php $cls = $badge[$row->status] ?? 'bg-secondary'; ?>
                        <tr>
                            <td>
                                <span class="small text-muted"><?= htmlspecialchars($row->nomor_permohonan) ?></span>
                            </td>
                            <td>
                                <span class="small fw-semibold d-block"><?= htmlspecialchars($row->nomor_surat) ?></span>
                                <span class="small text-muted"><?= htmlspecialchars($row->asal_instansi) ?></span>
                            </td>
                            <td class="small text-muted"><?= date('d-m-Y H:i', strtotime($row->created_at)) ?></td>
                            <td>
                                <?php if (!empty($row->daftar_gse)): ?>
                                    <?php foreach ($row->daftar_gse as $g): ?>
                                    <div class="small">
                                        <?= htmlspecialchars($g->nama_gse) ?>
                                        <span class="badge bg-secondary stage-pill">
                                            <?= htmlspecialchars($g->manufacture_type ?? '-') ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $jml_terisi = count($row->daftar_gse ?? []);
                                    $jml_total  = (int) ($row->jumlah_unit_gse ?? 0);
                                    $sdh_lengkap = $jml_total > 0 && $jml_terisi >= $jml_total;
                                ?>
                                <?php if (in_array($row->jenis_permohonan, ['Keluar Baru', 'Perbaikan', 'Campuran']) && $jml_total > 0): ?>
                                    <?php if ($is_gh): ?>
                                    <a href="<?= site_url('permohonan_keluar/kelengkapan/' . $row->id_permohonan_keluar) ?>"
                                       class="btn btn-sm <?= $sdh_lengkap ? 'btn-danger' : 'btn-outline-secondary' ?>">
                                        <i class="bi <?= $sdh_lengkap ? 'bi-check-circle-fill' : 'bi-pencil-square' ?>"></i>
                                        <?= $jml_terisi ?>/<?= $jml_total ?> Unit
                                    </a>
                                    <?php else: ?>
                                    <span class="badge <?= $sdh_lengkap ? 'bg-danger' : 'bg-secondary' ?>">
                                        <?= $jml_terisi ?>/<?= $jml_total ?> Unit
                                    </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="min-width:190px;">
                                <?php
                                    $status_label = $row->status;
                                    $status_cls   = $cls;
                                    // Selama kelengkapan unit GSE belum penuh, jangan tampilkan
                                    // status "Menunggu Verifikasi Sales" — belum layak diverifikasi.
                                    if (in_array($row->jenis_permohonan, ['Keluar Baru', 'Campuran']) && $jml_total > 0 && !$sdh_lengkap
                                        && $row->status === 'Menunggu Verifikasi Sales') {
                                        $status_label = 'Lengkapi Berkas Permohonan';
                                        $status_cls   = 'bg-secondary';
                                    }
                                ?>
                                <span class="badge <?= $status_cls ?>"><?= htmlspecialchars($status_label) ?></span>
                                <?php
                                $alasan_list = [];
                                foreach (($row->daftar_gse ?? []) as $g) {
                                    if (($g->status_item ?? null) === 'Ditolak' && !empty($g->alasan_penolakan_item)) {
                                        $alasan_list[] = $g->nama_gse . ': ' . $g->alasan_penolakan_item;
                                    }
                                }
                                ?>
                                <?php if (!empty($alasan_list)): ?>
                                <div class="reject-hint mt-1">
                                    <i class="bi bi-info-circle"></i>
                                    <?= htmlspecialchars(mb_strimwidth(implode(' | ', $alasan_list), 0, 60, '...')) ?>
                                </div>
                                <?php endif; ?>
                                <div class="mt-1 d-flex gap-1 flex-wrap">
                                    <?php
                                    $tahap_pill = [
                                        'Sales' => 'sales',
                                        'Ops'   => 'operasi',
                                        'Sec'   => 'security',
                                    ];
                                    foreach ($tahap_pill as $label => $kode):
                                        $st = status_tahap_gabungan($row->daftar_gse ?? [], $kode);
                                        if ($st['value'] === 'Disetujui'):
                                            echo "<span class='badge stage-pill stage-ok'>✓ $label</span>";
                                        elseif ($st['value'] === 'Ditolak'):
                                            echo "<span class='badge stage-pill stage-no'>✗ $label</span>";
                                        else:
                                            echo "<span class='badge stage-pill stage-idle'>- $label</span>";
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="row-actions">
                                    <a href="<?= site_url('permohonan_keluar/detail/' . $row->id_permohonan_keluar) ?>"
                                       class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($is_gh): ?>
                                        <?php if ($row->status === 'Menunggu Verifikasi Operasi'): ?>
                                        <a href="<?= site_url('permohonan_keluar/hapus/' . $row->id_permohonan_keluar) ?>"
                                           class="btn btn-sm btn-outline-danger" title="Hapus"
                                           onclick="return confirm('Hapus permohonan keluar ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (in_array($row->status, ['Ditolak', 'Sebagian Ditolak'])): ?>
                                        <a href="<?= site_url('permohonan_keluar/detail/' . $row->id_permohonan_keluar) ?>"
                                           class="btn btn-sm btn-outline-primary" title="Ajukan Ulang">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>