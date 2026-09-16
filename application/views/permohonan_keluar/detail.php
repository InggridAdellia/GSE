<?php
$badge = [
    'Menunggu Verifikasi Operasi'   => 'bg-warning text-dark',
    'Menunggu Verifikasi Sales'     => 'bg-primary',
    'Menunggu Verifikasi Security'  => 'bg-secondary',
    'Disetujui'                     => 'bg-success',
    'Ditolak'                       => 'bg-danger',
    'Sebagian Ditolak'              => 'bg-danger',
];
$cls = $badge[$permohonan->status] ?? 'bg-secondary';

$jml_total_unit   = (int) ($permohonan->jumlah_unit_gse ?? 0);
$jml_terisi_unit  = count($daftar_gse ?? []);
$sdh_lengkap_unit = $jml_total_unit === 0 || $jml_terisi_unit >= $jml_total_unit;

$status_label = $permohonan->status;
if (in_array($permohonan->jenis_permohonan, ['Keluar Baru', 'Perbaikan']) && !$sdh_lengkap_unit
    && $permohonan->status === 'Menunggu Verifikasi Sales') {
    $status_label = 'Lengkapi Berkas Permohonan';
    $cls          = 'bg-secondary';
}

// Filter GSE motorized berdasarkan manufacture_type (dipakai untuk badge kategori saja)
$gse_motorized = array_values(array_filter($daftar_gse, function ($g) {
    return strtolower($g->manufacture_type ?? '') === 'motorized';
}));

function verif_state($value, $is_waiting = false) {
    if ($value === 'Disetujui') return ['icon'=>'bi-check-lg','label'=>'Disetujui','badge'=>'bg-success','dot'=>'verif-dot-success'];
    if ($value === 'Ditolak')   return ['icon'=>'bi-x-lg','label'=>'Ditolak','badge'=>'bg-danger','dot'=>'verif-dot-danger'];
    if ($is_waiting)            return ['icon'=>'bi-hourglass-split','label'=>'Menunggu','badge'=>'bg-warning text-dark','dot'=>'verif-dot-warning'];
    return ['icon'=>'bi-dash','label'=>'Belum','badge'=>'bg-secondary','dot'=>'verif-dot-idle'];
}

$st_op    = status_tahap_gabungan($daftar_gse, 'operasi');
$st_sales = status_tahap_gabungan($daftar_gse, 'sales');
$st_sec   = status_tahap_gabungan($daftar_gse, 'security');

$op_state       = verif_state($st_op['value'],    $st_op['waiting']);
$sales_state    = verif_state($st_sales['value'], $st_sales['waiting']);
$security_state = verif_state($st_sec['value'],   $st_sec['waiting']);

$role = $this->session->userdata('role');

$peta_tahap_role = [
    'unit_operasi'   => 'operasi',
    'unit_sales'     => 'sales',
    'unit_security'  => 'security',
];

$label_tahap_item = [
    'operasi'   => 'Operasi',
    'sales'     => 'Sales',
    'security'  => 'Security',
    'selesai'   => 'Selesai',
];

$jumlah_kolom_tabel = 11;
if (($permohonan->jenis_permohonan ?? '') === 'Perbaikan') {
    $jumlah_kolom_tabel++;
}
?>

<style>
    .back-link{ display:inline-flex; align-items:center; gap:.35rem; font-size:.85rem; font-weight:600; color:var(--teal-700); text-decoration:none; margin-bottom:.6rem; }
    .back-link:hover{ color:var(--teal-600); }
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.92rem; margin-bottom:0; }
    .badge{ font-weight:600; font-size:.74rem; padding:.4rem .7rem; border-radius:999px; }
    .badge.bg-warning{ background:#FDF2DF !important; color:var(--gold-600) !important; }
    .badge.bg-info{ background:#E6F6F6 !important; color:var(--teal-700) !important; }
    .badge.bg-primary{ background:#EAF1FB !important; color:#2C5FA8 !important; }
    .badge.bg-success{ background:#E9F6EE !important; color:#1F6F45 !important; }
    .badge.bg-danger{ background:#FDECE8 !important; color:#A23B2A !important; }
    .badge.bg-secondary{ background:#EEF1F1 !important; color:var(--ink-600) !important; }
    .badge-lg{ font-size:.82rem; padding:.5rem .9rem; }
    .section-card{ border:0; }
    .section-head{ padding:1rem 1.25rem; border-bottom:1px solid var(--line); font-weight:700; color:var(--teal-800); display:flex; align-items:center; gap:.5rem; }
    .info-table th{ width:42%; font-weight:500; font-size:.84rem; color:var(--ink-600); border:0; padding:.55rem 0; vertical-align:top; }
    .info-table td{ font-size:.9rem; color:var(--ink-900); border:0; padding:.55rem 0; }
    .reject-note{ background:#FDECE8; color:#A23B2A; border-radius:.6rem; padding:.6rem .8rem; font-size:.86rem; }
    .gse-doc-table { min-width: 1200px; }
    .gse-doc-table th{ font-size:.66rem; font-weight:700; color:var(--ink-600); text-transform:uppercase; letter-spacing:.02em; background:#fafefe; border-bottom:1px solid var(--line); padding:.5rem .6rem; white-space:nowrap; }
    .gse-doc-table td{ font-size:.74rem; padding:.5rem .6rem; vertical-align:middle; }
    .gse-doc-table .badge{ font-size:.62rem; padding:.3rem .5rem; }
    .gse-doc-table .btn-sm{ font-size:.68rem; padding:.25rem .55rem; }
    .gse-doc-table small, .gse-doc-table .small{ font-size:.68rem; }
    .verif-list{ list-style:none; margin:0; padding:1.1rem 1.25rem; }
    .verif-step{ display:flex; gap:.9rem; }
    .verif-dot-col{ display:flex; flex-direction:column; align-items:center; flex-shrink:0; }
    .verif-dot{ width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.95rem; color:#fff; flex-shrink:0; }
    .verif-dot-success{ background:#1F6F45; }
    .verif-dot-danger{ background:#A23B2A; }
    .verif-dot-warning{ background:var(--gold-500); }
    .verif-dot-idle{ background:#D7DEDC; color:var(--ink-600); }
    .verif-line{ flex:1; width:2px; background:var(--line); margin:.3rem 0; min-height:1.4rem; }
    .verif-step:last-child .verif-line{ display:none; }
    .verif-content{ flex:1; padding-bottom:1.4rem; }
    .verif-step:last-child .verif-content{ padding-bottom:0; }
    .verif-title{ font-weight:700; font-size:.92rem; color:var(--ink-900); }
    .verif-stage-label{ font-size:.72rem; color:var(--ink-400); text-transform:uppercase; letter-spacing:.04em; }
    .action-bar{ background:var(--mint-50); border:1px solid var(--line); border-radius:1rem; padding:1rem 1.25rem; display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; margin-top:1rem; }
    .action-bar.has-warning{ background:#FDF2DF; border-color:#F5DDB0; }
    .modal-content{ border:0; border-radius:1rem; overflow:hidden; }
    .modal-header.bg-danger{ background:#C0392B !important; }
    .keterangan-text{ font-size:.86rem; color:var(--ink-900); white-space:pre-line; }
    .keterangan-empty{ font-size:.84rem; color:var(--ink-400); font-style:italic; }
</style>

<a href="<?= site_url('permohonan_keluar') ?>" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Pengajuan
</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-envelope-arrow-up"></i> Detail Permohonan Keluar</h5>
        <p class="page-subtitle">No. Permohonan: <strong><?= htmlspecialchars($permohonan->nomor_permohonan) ?></strong></p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <?php if ($permohonan->status === 'Disetujui'): ?>
        <a href="javascript:void(0)"
            onclick="previewDokumen('<?= site_url('permohonan_keluar/cetak_ba/' . $permohonan->id_permohonan_keluar) ?>', 'Berita Acara - <?= htmlspecialchars($permohonan->id_permohonan_keluar) ?>')"
            class="btn btn-sm btn-outline-info">
            <i class="bi bi-file-earmark-text"></i>BA
        </a>
        <span class="badge badge-lg <?= $cls ?>"><?= htmlspecialchars($status_label) ?></span>
        <?php endif; ?>
    </div>
    </div>
<div class="row g-3">

    <!-- Informasi Permohonan -->
    <div class="col-md-6">
        <div class="card section-card shadow-sm h-100">
            <div class="section-head"><i class="bi bi-info-circle"></i> Informasi Permohonan</div>
            <div class="card-body">
                <table class="table table-sm table-borderless info-table mb-0">
                    <tr>
                        <th>No. Surat</th>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span><?= htmlspecialchars($permohonan->nomor_surat) ?></span>
                                <?php if (!empty($permohonan->file_bukti_permohonan)): ?>
                                <?php
                                    $bp_url = base_url('uploads/lampiran/' . $permohonan->file_bukti_permohonan);
                                ?>
                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-info py-0 px-2" style="font-size:.72rem;"
                                   onclick="previewDokumen('<?= $bp_url ?>', 'Bukti Permohonan - <?= htmlspecialchars($permohonan->nomor_permohonan) ?>')">
                                    <i class="bi bi-file-earmark-text"></i> Lihat Bukti Permohonan
                                </a>
                                <?php else: ?>
                                <span class="small text-muted">(Bukti Permohonan belum ada)</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr><th>Tanggal Dibuat</th><td><?= date('d-m-Y H:i:s', strtotime($permohonan->created_at)) ?></td></tr>
                   <tr><th>Tanggal Pembuatan Permohonan</th><td><?= date('d-m-Y', strtotime($permohonan->tanggal_keluar)) ?></td></tr>
                   <tr>
                        <th>Jumlah Unit Diajukan</th>
                        <td>
                            <?= (int) $permohonan->jumlah_unit_gse ?> unit
                            <?php if ($role === 'admin'): ?>
                                <?php
                                $sisa_edit = 2 - (int) ($permohonan->jumlah_unit_edit_count ?? 0);
                                $bisa_edit = $permohonan->status !== 'Disetujui' && $sisa_edit > 0 && (
                                    empty($permohonan->jumlah_unit_diedit_pertama_at)
                                    || time() <= strtotime($permohonan->jumlah_unit_diedit_pertama_at . ' +30 days')
                                );
                                ?>
                                <?php if ($bisa_edit): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 ms-2" style="font-size:.72rem;"
                                            data-bs-toggle="modal" data-bs-target="#modalEditJumlahUnit">
                                        <i class="bi bi-pencil"></i> Edit (sisa <?= $sisa_edit ?>x)
                                    </button>
                                <?php elseif ($permohonan->status === 'Disetujui'): ?>
                                    <span class="badge bg-secondary ms-2" style="font-size:.68rem;">Permohonan sudah selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary ms-2" style="font-size:.68rem;">Batas edit habis</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><th>Asal Instansi</th><td><?= htmlspecialchars($permohonan->asal_instansi) ?></td></tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge <?= $cls ?>"><?= htmlspecialchars($status_label) ?></span></td>
                    </tr>
                    <tr>
                        <th>Kelengkapan Dokumen</th>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <?php
                                    $ada_lamp_keluar = false;
                                    if (!empty($daftar_gse)) {
                                        foreach ($daftar_gse as $gk) {
                                            if (!empty($gk->file_foto_gse) || !empty($gk->file_bukti_kerusakan)) {
                                                $ada_lamp_keluar = true;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                <?php if ($ada_lamp_keluar): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lengkap</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Belum Lengkap</span>
                                <?php endif; ?>

                                <?php
                                    $file_dispo_pk = !empty($permohonan->file_dispo) ? $permohonan->file_dispo : null;
                                    if (!$file_dispo_pk && !empty($daftar_gse)) {
                                        foreach ($daftar_gse as $gk) {
                                            if (!empty($gk->file_dispo)) {
                                                $file_dispo_pk = $gk->file_dispo;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                <?php if (!empty($file_dispo_pk)): ?>
                                    <?php $dispo_pk_url = base_url('uploads/lampiran/' . $file_dispo_pk); ?>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info py-0 px-2" style="font-size:.72rem;"
                                       onclick="previewDokumen('<?= $dispo_pk_url ?>', 'Dispo - <?= htmlspecialchars($permohonan->nomor_permohonan) ?>')">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Dispo
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php if ($permohonan->alasan_penolakan): ?>
                    <tr>
                        <th>Alasan Ditolak</th>
                        <td><div class="reject-note"><?= nl2br(htmlspecialchars($permohonan->alasan_penolakan)) ?></div></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Verifikasi (ringkasan gabungan seluruh unit) -->
    <div class="col-md-6">
        <div class="card section-card shadow-sm h-100">
            <div class="section-head"><i class="bi bi-check2-all"></i> Riwayat Verifikasi</div>
            <ul class="verif-list">
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $sales_state['dot'] ?>"><i class="bi <?= $sales_state['icon'] ?>"></i></div>
                        <div class="verif-line"></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 1 dari 3</div><div class="verif-title">Airport Non Aeronautical</div></div>
                            <span class="badge <?= $sales_state['badge'] ?>"><?= $sales_state['label'] ?></span>
                        </div>
                        <?php if ($permohonan->verifikasi_sales_at): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_sales_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $op_state['dot'] ?>"><i class="bi <?= $op_state['icon'] ?>"></i></div>
                        <div class="verif-line"></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 2 dari 3</div><div class="verif-title">Airport Operation Airside</div></div>
                            <span class="badge <?= $op_state['badge'] ?>"><?= $op_state['label'] ?></span>
                        </div>
                        <?php if ($permohonan->verifikasi_operasi_at): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_operasi_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $security_state['dot'] ?>"><i class="bi <?= $security_state['icon'] ?>"></i></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 3 dari 3</div><div class="verif-title">Airport Security Protection</div></div>
                            <span class="badge <?= $security_state['badge'] ?>"><?= $security_state['label'] ?></span>
                        </div>
                        <?php if (!empty($permohonan->verifikasi_security_at)): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_security_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Daftar GSE & Dokumen -->
    <div class="col-12">
        <div class="card section-card shadow-sm">
            <div class="section-head"><i class="bi bi-paperclip"></i> Daftar GSE &amp; Dokumen Lampiran</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0 gse-doc-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th>Nama GSE</th>
                                <th>No. Asset</th>
                                <th>Stiker AP</th>
                                <th>Kategori</th>
                                <th class="text-center">Jenis Permohonan</th>
                                <th class="text-center">Tahap Saat Ini</th>
                                <th class="text-center">Lampiran</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($daftar_gse)): ?>
                            <?php foreach ($daftar_gse as $i => $g):
                                $is_motor = strtolower($g->manufacture_type ?? '') === 'motorized';
                                $tahap_g  = $g->tahap_saat_ini ?? 'operasi';
                                $status_g = $g->status_item ?? null;
                                $bisa_aksi_item = $status_g !== 'Ditolak' && $tahap_g !== 'selesai'
                                    && (($peta_tahap_role[$role] ?? null) === $tahap_g);
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?= htmlspecialchars($g->nama_gse) ?>
                                    <?php if ($status_g === 'Ditolak' && !empty($g->alasan_penolakan_item)): ?>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.72rem;"
                                                onclick="showAlasanPenolakan('<?= htmlspecialchars(addslashes($g->alasan_penolakan_item), ENT_QUOTES) ?>')">
                                            <i class="bi bi-exclamation-circle"></i> Lihat Alasan Penolakan
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($g->no_asset) ?></td>
                                <td><?= htmlspecialchars($g->sticker_ap) ?></td>
                                <td>
                                    <span class="badge <?= $is_motor ? 'bg-danger' : 'bg-secondary' ?>">
                                        <?= $is_motor ? 'Motorized' : 'Non-Motorized' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if (($g->jenis_item ?? '') === 'Perbaikan'): ?>
                                        <span class="badge bg-info text-dark"><i class="bi bi-tools"></i> Keluar Untuk Perbaikan</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary"><i class="bi bi-box-arrow-in-right"></i> Keluar Hapus Assets</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $badge_tahap = $status_g === 'Ditolak' ? 'bg-danger'
                                                    : ($tahap_g === 'selesai' ? 'bg-success' : 'bg-info text-dark');
                                    ?>
                                    <span class="badge <?= $badge_tahap ?>"><?= $label_tahap_item[$tahap_g] ?? '-' ?></span>
                                </td>

                                <?php
                                $lamp_id = 'modal-lamp-keluar-' . $g->id;
                                $lamp_docs = [];

                                if (!empty($g->file_foto_gse)) {
                                    $lamp_docs['Foto Unit GSE'] = $g->file_foto_gse;
                                }
                                if (!empty($g->file_bukti_kerusakan)) {
                                    $lamp_docs['Bukti Kerusakan'] = $g->file_bukti_kerusakan;
                                }
                                if (!empty($g->file_dispo)) {
                                    $lamp_docs['Dispo'] = $g->file_dispo;
                                }
                                ?>
                                <td class="text-center align-middle">
                                    <?php if (empty($lamp_docs) && empty($g->nomor_rangka) && empty($g->nomor_mesin)): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Belum</span>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#<?= $lamp_id ?>">
                                            <i class="bi bi-eye"></i> Lihat (<?= count($lamp_docs) ?>)
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <!-- Modal Lampiran -->
                                <?php if (!empty($lamp_docs) || !empty($g->nomor_rangka) || !empty($g->nomor_mesin)): ?>
                                <div class="modal fade" id="<?= $lamp_id ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header py-2">
                                                <h6 class="modal-title mb-0">
                                                    <i class="bi bi-paperclip"></i> Lampiran — <?= htmlspecialchars($g->nama_gse) ?>
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if (!empty($g->nomor_rangka) || !empty($g->nomor_mesin)): ?>
                                                <div class="row g-2 mb-3">
                                                    <?php if (!empty($g->nomor_rangka)): ?>
                                                    <div class="col-md-6">
                                                        <div class="border rounded p-2">
                                                            <div class="small text-muted mb-1">No. Rangka</div>
                                                            <div class="fw-semibold"><?= htmlspecialchars($g->nomor_rangka) ?></div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($g->nomor_mesin)): ?>
                                                    <div class="col-md-6">
                                                        <div class="border rounded p-2">
                                                            <div class="small text-muted mb-1">No. Mesin</div>
                                                            <div class="fw-semibold"><?= htmlspecialchars($g->nomor_mesin) ?></div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>

                                                <?php if (!empty($lamp_docs)): ?>
                                                <div class="row g-3">
                                                    <?php foreach ($lamp_docs as $label => $filename):
                                                        $url = base_url('uploads/lampiran/' . $filename);
                                                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                    ?>
                                                    <div class="col-md-6">
                                                        <div class="border rounded p-2 text-center">
                                                            <div class="small fw-semibold mb-2"><?= htmlspecialchars($label) ?></div>
                                                            <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                                                                <a href="javascript:void(0)"
                                                                onclick="previewDokumen('<?= $url ?>', '<?= htmlspecialchars($g->nama_gse) ?> - <?= htmlspecialchars($label) ?>')">
                                                                    <img src="<?= $url ?>" class="img-fluid rounded" style="max-height:120px; object-fit:cover;">
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0)"
                                                                onclick="previewDokumen('<?= $url ?>', '<?= htmlspecialchars($g->nama_gse) ?> - <?= htmlspecialchars($label) ?>')"
                                                                class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-file-earmark-pdf"></i> Lihat PDF
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <td class="text-center align-middle" style="min-width:160px;">
                                    <?= nl2br(htmlspecialchars($g->keterangan ?? $permohonan->keterangan ?? '-')) ?>
                                </td>

                                <td class="text-center align-middle">
                                <?php if ($bisa_aksi_item): ?>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <?php if ($tahap_g === 'operasi' && $role === 'unit_operasi' && empty($permohonan->file_dispo) && empty($g->file_dispo)): ?>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalDispoKeluarDetail(<?= $g->id ?>)" title="Upload Dispo">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        <?php else: ?>
                                            <form method="post" action="<?= site_url('approval/setujui_item_keluar/' . $g->id) ?>"
                                                style="display:inline;" onsubmit="return confirm('Setujui unit GSE ini?')">
                                                <input type="hidden" name="tahap_sekarang" value="<?= $tahap_g ?>">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="bukaModalTolakItemDetail(<?= $g->id ?>)">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                <?php elseif ($tahap_g === 'selesai'): ?>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="javascript:void(0)"
                                        onclick="previewDokumen('<?= site_url('permohonan_keluar/cetak_ba_item/' . $permohonan->id_permohonan_keluar . '/' . $g->id) ?>', 'Berita Acara - <?= htmlspecialchars($g->nama_gse) ?>')"
                                        class="btn btn-sm btn-outline-info" title="Cetak BA">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                    </div>

                                <?php elseif ($status_g === 'Ditolak' && $role === 'ground_handling'): ?>
                                    <a href="<?= site_url('permohonan_keluar/edit_unit/' . $g->id) ?>"
                                    class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-repeat"></i> Ajukan Ulang
                                    </a>

                                <?php else: ?>
                                    <span class="text-muted small">-</span>

                                <?php endif; ?>
                            </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="<?= $jumlah_kolom_tabel ?>" class="text-center text-muted py-3">Inputkan data GSE yang diajukan terlebih dahulu.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Notice: kelengkapan belum lengkap, belum bisa diverifikasi -->
<?php if (in_array($permohonan->jenis_permohonan, ['Keluar Baru', 'Perbaikan']) && !$sdh_lengkap_unit
    && $permohonan->status === 'Menunggu Verifikasi Sales'): ?>
<div class="action-bar has-warning mt-3">
    <i class="bi bi-exclamation-triangle text-warning"></i>
    <span class="small me-auto" style="color:var(--gold-600);">
        Kelengkapan unit GSE baru terisi <?= $jml_terisi_unit ?> dari <?= $jml_total_unit ?> unit.
        Permohonan ini belum bisa diverifikasi sampai Ground Handling melengkapi seluruh data GSE.
    </span>
</div>
<?php endif; ?>

<!-- Modal Tolak Per Unit -->
<div class="modal fade" id="modalTolakItemDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title mb-0"><i class="bi bi-x-circle me-1"></i> Tolak Unit GSE</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTolakItemDetail" method="post">
                <div class="modal-body">
                    <textarea name="alasan_penolakan" class="form-control" rows="4"
                              placeholder="Alasan penolakan unit ini..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Tolak Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Riwayat Jumlah Unit -->
<div class="modal fade" id="modalEditJumlahUnit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title mb-0"><i class="bi bi-pencil me-1"></i> Edit Jumlah Unit Diajukan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="<?= site_url('permohonan_keluar/edit_jumlah_unit/' . $permohonan->id_permohonan_keluar) ?>">
            <div class="modal-body">
                <div class="alert alert-warning py-2 small">
                    <i class="bi bi-exclamation-triangle"></i>
                    Maksimal 2x edit dalam 30 hari sejak edit pertama. Tidak boleh kurang dari jumlah unit yang sudah diisi (<?= count($daftar_gse) ?> unit).
                </div>
                    <label class="form-label">Jumlah Unit Diajukan</label>
                    <input type="number" name="jumlah_unit_gse" class="form-control" min="<?= count($daftar_gse) ?>"
                        value="<?= (int) $permohonan->jumlah_unit_gse ?>" required>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Dokumen (gambar & PDF) -->
<div class="modal fade" id="modalPreviewDokumen" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title mb-0" id="previewDokumenTitle">
                    <i class="bi bi-file-earmark"></i> Preview Dokumen
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0" style="min-height:300px;">
                <img id="previewDokumenImg" src="" class="img-fluid" style="max-height:75vh; display:none;">
                <iframe id="previewDokumenFrame" src="" style="width:100%; height:75vh; border:0; display:none;"></iframe>
            </div>
            <div class="modal-footer">
                <a id="previewDokumenDownload" href="" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right"></i> Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alasan Penolakan -->
<div class="modal fade" id="modalAlasanPenolakan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title mb-0"><i class="bi bi-exclamation-circle me-1"></i> Alasan Penolakan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="alasanPenolakanText" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showAlasanPenolakan(teks) {
    document.getElementById('alasanPenolakanText').textContent = teks;
    new bootstrap.Modal(document.getElementById('modalAlasanPenolakan')).show();
}

function previewDokumen(url, judul) {
    var ext = url.split('.').pop().toLowerCase().split('?')[0];
    var imgTag   = document.getElementById('previewDokumenImg');
    var frameTag = document.getElementById('previewDokumenFrame');

    document.getElementById('previewDokumenTitle').innerHTML =
        '<i class="bi bi-file-earmark"></i> ' + judul;
    document.getElementById('previewDokumenDownload').href = url;

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
        imgTag.src = url;
        imgTag.style.display   = 'inline-block';
        frameTag.style.display = 'none';
        frameTag.src = '';
    } else {
        frameTag.src = url;
        frameTag.style.display = 'block';
        imgTag.style.display   = 'none';
        imgTag.src = '';
    }

    new bootstrap.Modal(document.getElementById('modalPreviewDokumen')).show();
}

function bukaModalTolakItemDetail(idDetail) {
    document.getElementById('formTolakItemDetail').action = '<?= site_url('approval/tolak_item_keluar/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalTolakItemDetail')).show();
}

function bukaModalDispoKeluarDetail(id) {
    document.getElementById('inputDispoKeluarDetail').value = '';
    document.getElementById('formDispoKeluarDetail').action =
        '<?= site_url('approval/upload_dispo_item_keluar/') ?>' + id;
    new bootstrap.Modal(document.getElementById('modalDispoKeluarDetail')).show();
}
</script>

<!-- Modal Upload Dispo Keluar Detail (unit_operasi) -->
<div class="modal fade" id="modalDispoKeluarDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Upload Dispo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open_multipart('', ['id' => 'formDispoKeluarDetail', 'method' => 'post']) ?>
            <input type="hidden" name="redirect_to" value="<?= current_url() ?>">
            <div class="modal-body">
                <p class="mb-2">Upload <strong>Dokumen Dispo</strong>:</p>
                <input type="file" name="file_dispo" id="inputDispoKeluarDetail"
                       class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <div class="alert alert-info mt-3 py-2 small">
                    <i class="bi bi-info-circle"></i> Format: PDF/JPG/PNG · Maks. <strong>500KB</strong>.
                    Setelah upload, unit otomatis disetujui dan diteruskan ke tahap Security.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-upload"></i> Upload & Setujui
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>