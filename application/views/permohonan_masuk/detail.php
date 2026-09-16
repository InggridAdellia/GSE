<?php
$badge = [
    'Menunggu Verifikasi Operasi'   => 'bg-warning text-dark',
    'Menunggu Verifikasi Equipment' => 'bg-info text-dark',
    'Menunggu Verifikasi Sales'     => 'bg-primary',
    'Menunggu Verifikasi Security'  => 'bg-secondary',
    'Disetujui'                     => 'bg-success',
    'Ditolak'                       => 'bg-danger',
];
$cls = $badge[$permohonan->status] ?? 'bg-secondary';

$is_sparepart = ($permohonan->jenis_permohonan === 'Masuk Perbaikan Sparepart' || $permohonan->jenis_permohonan === 'Sparepart');

$jml_total_unit = (int) ($permohonan->jumlah_unit_gse ?? 0);
$jml_terisi_unit = count($daftar_gse ?? []);
$sdh_lengkap_unit = $jml_total_unit === 0 || $jml_terisi_unit >= $jml_total_unit;

$status_label = $permohonan->status;
if (in_array($permohonan->jenis_permohonan, ['Masuk Baru', 'Perbaikan', 'Masuk Perbaikan Sparepart', 'Sparepart']) && !$sdh_lengkap_unit
    && $permohonan->status === 'Menunggu Verifikasi Operasi') {
    $status_label = 'Lengkapi Berkas Permohonan';
    $cls          = 'bg-secondary';
}

$dokumen = [
    'file_ktp'              => 'Fotocopy KTP',
    'file_tim'              => 'Fotocopy TIM',
    'file_stnk'             => 'Fotocopy STNK',
    'file_sim'              => 'Fotocopy SIM (Driver)',
    'file_emisi'            => 'File emisi',
    'file_foto_rangka'      => 'Foto No. Rangka',
    'file_foto_mesin'       => 'Foto No. Mesin',
    'file_penugasan'        => 'Surat Keterangan Penugasan',
    'file_rekomendasi_bengkel_luar' => 'Surat Rekomendasi Laik Bengkel',
];

$gse_motorized = array_values(array_filter($daftar_gse, function ($g) {
    return strtolower($g->manufacture_type ?? '') === 'motorized';
}));
$is_motorized = count($gse_motorized) > 0;
$total_dok    = $is_motorized ? (count($gse_motorized) * count($dokumen)) : 0;
$lengkap_dok  = 0;
foreach ($gse_motorized as $g) {
    foreach (array_keys($dokumen) as $f) {
        if (!empty($g->$f)) $lengkap_dok++;
    }
}

function verif_state($value, $is_waiting = false) {
    if ($value === 'Disetujui') return ['icon'=>'bi-check-lg','label'=>'Disetujui','badge'=>'bg-success','dot'=>'verif-dot-success'];
    if ($value === 'Ditolak')   return ['icon'=>'bi-x-lg','label'=>'Ditolak','badge'=>'bg-danger','dot'=>'verif-dot-danger'];
    if ($is_waiting)            return ['icon'=>'bi-hourglass-split','label'=>'Menunggu','badge'=>'bg-warning text-dark','dot'=>'verif-dot-warning'];
    return ['icon'=>'bi-dash','label'=>'Belum','badge'=>'bg-secondary','dot'=>'verif-dot-idle'];
}

$st_op    = status_tahap_gabungan($daftar_gse, 'operasi');
$st_eq    = status_tahap_gabungan($daftar_gse, 'equipment');
$st_sales = status_tahap_gabungan($daftar_gse, 'sales');
$st_sec   = status_tahap_gabungan($daftar_gse, 'security');

$op_state       = verif_state($st_op['value'],    $st_op['waiting']);
$eq_state       = verif_state($st_eq['value'],    $st_eq['waiting']);
$sales_state    = verif_state($st_sales['value'], $st_sales['waiting']);
$security_state = verif_state($st_sec['value'],   $st_sec['waiting']);

$role = $this->session->userdata('role');
$tahap_unit = [
    'Menunggu Verifikasi Operasi'   => 'unit_operasi',
    'Menunggu Verifikasi Equipment' => 'unit_equipment',
    'Menunggu Verifikasi Sales'     => 'unit_sales',
    'Menunggu Verifikasi Security'  => 'unit_security',
];
$bisa_verifikasi   = isset($tahap_unit[$permohonan->status]) && ($tahap_unit[$permohonan->status] === $role);
if (in_array($permohonan->jenis_permohonan, ['Masuk Baru', 'Perbaikan', 'Masuk Perbaikan Sparepart', 'Sparepart']) && !$sdh_lengkap_unit) {
    $bisa_verifikasi = false;
}
$dok_belum_lengkap = $is_motorized && $lengkap_dok < $total_dok;
$is_equipment      = in_array($role, ['unit_equipment', 'admin'])
    && $permohonan->status === 'Menunggu Verifikasi Equipment';

$peta_tahap_role = [
    'unit_operasi'   => 'operasi',
    'unit_equipment' => 'equipment',
    'unit_sales'     => 'sales',
    'unit_security'  => 'security',
];
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
</style>

<a href="<?= site_url('permohonan_masuk') ?>" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Pengajuan
</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-envelope-arrow-down"></i> Detail Permohonan</h5>
        <p class="page-subtitle">No. Permohonan: <strong><?= htmlspecialchars($permohonan->nomor_permohonan) ?></strong></p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <?php if ($permohonan->status === 'Disetujui' && !$is_sparepart): ?>
        <a href="javascript:void(0)"
            onclick="previewDokumen('<?= site_url('permohonan_masuk/cetak_ba/' . $permohonan->id_permohonan_masuk) ?>', 'Berita Acara - <?= htmlspecialchars($permohonan->id_permohonan_masuk) ?>')"
            class="btn btn-sm btn-outline-info">
            <i class="bi bi-file-earmark-text"></i>BA
        </a>
        <?php endif; ?>

        <?php if ($permohonan->status === 'Disetujui' && !$is_sparepart): ?>
        <a href="javascript:void(0)"
            onclick="previewDokumen('<?= site_url('permohonan_masuk/cetak_stiker/' . $permohonan->id_permohonan_masuk) ?>', 'Stiker Verifikasi - <?= htmlspecialchars($permohonan->nomor_permohonan) ?>')"
            class="btn btn-sm btn-outline-info">
                <i class="bi bi-qr-code"></i>Stiker
        </a>
        <?php endif; ?>
        <span class="badge badge-lg <?= $cls ?>"><?= htmlspecialchars($status_label) ?></span>
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
                                <span><?= htmlspecialchars($permohonan->nomor_surat ?? '-') ?></span>
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
                    <tr><th>Tanggal Masuk</th><td><?= date('d-m-Y', strtotime($permohonan->tanggal_masuk)) ?></td></tr>
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
                                <?php if ($is_sparepart): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sparepart (Foto Sparepart terlampir)</span>
                                <?php elseif (($permohonan->jenis_permohonan ?? '') === 'Perbaikan'): ?>
                                    <?php $ada_bukti = !empty($daftar_gse) && !empty($daftar_gse[0]->file_bukti_perbaikan); ?>
                                    <?php if ($ada_bukti): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lengkap</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Belum Lengkap</span>
                                    <?php endif; ?>
                                <?php elseif (!$is_motorized): ?>
                                    <span class="badge bg-secondary">Non-Motorized (tidak wajib lampiran)</span>
                                <?php elseif ($lengkap_dok === $total_dok): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lengkap (<?= $lengkap_dok ?>/<?= $total_dok ?>)</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Belum Lengkap (<?= $lengkap_dok ?>/<?= $total_dok ?>)</span>
                                <?php endif; ?>

                                <?php
                                    $file_dispo_pm = !empty($permohonan->file_dispo) ? $permohonan->file_dispo : null;
                                    if (!$file_dispo_pm && !empty($daftar_gse)) {
                                        foreach ($daftar_gse as $g_it) {
                                            if (!empty($g_it->file_dispo)) {
                                                $file_dispo_pm = $g_it->file_dispo;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                <?php if (!empty($file_dispo_pm)): ?>
                                    <?php $dispo_pm_url = base_url('uploads/lampiran/' . $file_dispo_pm); ?>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info py-0 px-2" style="font-size:.72rem;"
                                       onclick="previewDokumen('<?= $dispo_pm_url ?>', 'Dispo - <?= htmlspecialchars($permohonan->nomor_permohonan) ?>')">
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

    <!-- Riwayat Verifikasi -->
    <div class="col-md-6">
        <div class="card section-card shadow-sm h-100">
            <div class="section-head"><i class="bi bi-check2-all"></i> Riwayat Verifikasi</div>
            <?php if ($is_sparepart): ?>
            <ul class="verif-list">
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $op_state['dot'] ?>"><i class="bi <?= $op_state['icon'] ?>"></i></div>
                        <div class="verif-line"></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 1 dari 2</div><div class="verif-title">Airport Operation Airside</div></div>
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
                            <div><div class="verif-stage-label">Tahap 2 dari 2</div><div class="verif-title">Airport Security Protection</div></div>
                            <span class="badge <?= $security_state['badge'] ?>"><?= $security_state['label'] ?></span>
                        </div>
                        <?php if (!empty($permohonan->verifikasi_security_at)): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_security_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
            <?php else: ?>
            <ul class="verif-list">
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $op_state['dot'] ?>"><i class="bi <?= $op_state['icon'] ?>"></i></div>
                        <div class="verif-line"></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 1 dari 4</div><div class="verif-title">Airport Operation Airside</div></div>
                            <span class="badge <?= $op_state['badge'] ?>"><?= $op_state['label'] ?></span>
                        </div>
                        <?php if ($permohonan->verifikasi_operasi_at): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_operasi_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $eq_state['dot'] ?>"><i class="bi <?= $eq_state['icon'] ?>"></i></div>
                        <div class="verif-line"></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 2 dari 4</div><div class="verif-title">Airport Equipment</div></div>
                            <span class="badge <?= $eq_state['badge'] ?>"><?= $eq_state['label'] ?></span>
                        </div>
                        <?php if ($permohonan->verifikasi_equipment_at): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_equipment_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $sales_state['dot'] ?>"><i class="bi <?= $sales_state['icon'] ?>"></i></div>
                        <div class="verif-line"></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 3 dari 4</div><div class="verif-title">Airport Non Aeronautical</div></div>
                            <span class="badge <?= $sales_state['badge'] ?>"><?= $sales_state['label'] ?></span>
                        </div>
                        <?php if ($permohonan->verifikasi_sales_at): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_sales_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
                <li class="verif-step">
                    <div class="verif-dot-col">
                        <div class="verif-dot <?= $security_state['dot'] ?>"><i class="bi <?= $security_state['icon'] ?>"></i></div>
                    </div>
                    <div class="verif-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div><div class="verif-stage-label">Tahap 4 dari 4</div><div class="verif-title">Airport Security Protection</div></div>
                            <span class="badge <?= $security_state['badge'] ?>"><?= $security_state['label'] ?></span>
                        </div>
                        <?php if (!empty($permohonan->verifikasi_security_at)): ?>
                        <div class="small text-muted mt-1"><i class="bi bi-clock"></i> <?= date('d-m-Y H:i:s', strtotime($permohonan->verifikasi_security_at)) ?></div>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Daftar GSE & Dokumen -->
    <div class="col-13">
        <div class="card section-card shadow-sm">
            <div class="section-head"><i class="bi bi-paperclip"></i> Daftar GSE &amp; Dokumen Lampiran</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0 gse-doc-table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama GSE</th>
                                <th class="text-center">No. Asset</th>
                                <th class="text-center">Stiker AP</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Jenis Permohonan</th>
                                <th class="text-center">Tahap Saat Ini</th>
                                <th class="text-center">Status Kelayakan</th>
                                <th class="text-center">Surat Rekomendasi</th>
                                <th class="text-center">Pass Kendaraan</th>
                                <th class="text-center">Lampiran</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($daftar_gse)): ?>
                            <?php foreach ($daftar_gse as $i => $g):
                                $is_item_sparepart = (($g->jenis_item ?? '') === 'Sparepart' || strtolower($g->manufacture_type ?? '') === 'sparepart' || $is_sparepart);
                                $is_motor = !$is_item_sparepart && (strtolower($g->manufacture_type ?? '') === 'motorized');
                                $tahun_kontrak_baru = null;
                                if ($permohonan->jenis_permohonan === 'Perbaruan Kontrak' && !empty($g->no_asset)) {
                                    $this->load->model('Gse_model');
                                    $masa_selesai_lama = $this->Gse_model->get_masa_selesai_lama($g->no_asset);
                                    if (!empty($masa_selesai_lama)) {
                                        $tahun_kontrak_baru = ((int) date('Y', strtotime($masa_selesai_lama))) + 1;
                                    }
                                }

                                $dispo_url = !empty($g->file_dispo) ? base_url('uploads/lampiran/' . $g->file_dispo) : null;
                                $dispo_ext = !empty($g->file_dispo) ? strtolower(pathinfo($g->file_dispo, PATHINFO_EXTENSION)) : null;
                                $dispo_id  = 'modal-dispo-' . $g->id;

                                $ba_url    = !empty($g->file_ba_uji_laik) ? base_url('uploads/lampiran/' . $g->file_ba_uji_laik) : null;
                                $ba_ext    = !empty($g->file_ba_uji_laik) ? strtolower(pathinfo($g->file_ba_uji_laik, PATHINFO_EXTENSION)) : null;
                                $ba_id     = 'modal-ba-' . $g->id;
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?= htmlspecialchars($g->nama_gse) ?>
                                    <?php if (($g->status_item ?? null) === 'Ditolak' && !empty($g->alasan_penolakan_item)): ?>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.72rem;"
                                                onclick="showAlasanPenolakan('<?= htmlspecialchars(addslashes($g->alasan_penolakan_item), ENT_QUOTES) ?>')">
                                            <i class="bi bi-exclamation-circle"></i> Lihat Alasan Penolakan
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($g->no_asset ?: ($g->tipe_permohonan_gse ?: '-')) ?></td>
                                <td><?= $is_item_sparepart ? '-' : htmlspecialchars($g->sticker_ap ?? '-') ?></td>
                                <td>
                                    <?php if ($is_item_sparepart): ?>
                                        <span class="badge bg-warning text-dark">Sparepart</span>
                                    <?php else: ?>
                                        <span class="badge <?= $is_motor ? 'bg-danger' : 'bg-secondary' ?>">
                                            <?= $is_motor ? 'Motorized' : 'Non-Motorized' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($is_item_sparepart): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-gear"></i> Sparepart</span>
                                    <?php elseif (($g->jenis_item ?? '') === 'Perbaikan'): ?>
                                        <span class="badge bg-info text-dark"><i class="bi bi-tools"></i> Masuk Setelah Perbaikan</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary"><i class="bi bi-box-arrow-in-right"></i> Masuk Baru</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $label_tahap_item = ['operasi'=>'Operasi','equipment'=>'Equipment','sales'=>'Sales','security'=>'Security','selesai'=>'Selesai'];
                                    $tahap_g_now  = $g->tahap_saat_ini ?? 'operasi';
                                    $badge_tahap  = ($g->status_item ?? null) === 'Ditolak' ? 'bg-danger'
                                                    : ($tahap_g_now === 'selesai' ? 'bg-success' : 'bg-info text-dark');
                                    ?>
                                    <span class="badge <?= $badge_tahap ?>"><?= $label_tahap_item[$tahap_g_now] ?? '-' ?></span>
                                </td>
                                <td class="text-center">
                                <?php if ($is_item_sparepart): ?>
                                    <span class="text-muted small">-</span>
                                <?php else: ?>
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <?php $kelayakan_g = $g->status_kelayakan ?? 'Belum Diperiksa'; ?>
                                    <?php if ($kelayakan_g === 'Layak'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Layak</span>
                                    <?php elseif ($kelayakan_g === 'Tidak Layak'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Tidak Layak</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><i class="bi bi-hourglass"></i> Belum Diperiksa</span>
                                    <?php endif; ?>

                                    <?php if (!empty($g->file_ba_uji_laik)): ?>
                                    <?php
                                        $ba_url = base_url('uploads/lampiran/' . $g->file_ba_uji_laik);
                                        $ba_ext = strtolower(pathinfo($g->file_ba_uji_laik, PATHINFO_EXTENSION));
                                        $ba_id  = 'modal-ba-' . $g->id;
                                    ?>
                                    <a href="#" class="small" data-bs-toggle="modal" data-bs-target="#<?= $ba_id ?>">
                                        <i class="bi bi-file-earmark-text"></i> Lihat BA Laik
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($g->masa_mulai)):
                                        $dim_id        = 'modal-dimensi-' . $g->id;
                                        $edit_count_g  = (int) ($g->dimensi_edit_count ?? 0);
                                        $bisa_edit_dim = in_array($role, ['unit_sales', 'admin'])
                                            && !empty($g->dimensi_diisi_pertama_at)
                                            && $edit_count_g < 2
                                            && strtotime($g->dimensi_diisi_pertama_at) >= strtotime('-30 days');
                                        $batas_edit_dim = !empty($g->dimensi_diisi_pertama_at)
                                            ? date('d-m-Y', strtotime($g->dimensi_diisi_pertama_at . ' +30 days'))
                                            : null;
                                        $riwayat_g = $this->Permohonan_masuk_model->get_riwayat_dimensi_by_no_asset($g->no_asset);
                                        $riwayat_dimensi_sorted = array_values(array_filter($riwayat_g, function ($r) {
                                            return in_array($r->aksi, ['Input Dimensi', 'Edit Dimensi']);
                                        }));
                                        usort($riwayat_dimensi_sorted, function ($a, $b) {
                                            return strtotime($a->created_at) <=> strtotime($b->created_at);
                                        });
                                        $label_dimensi_by_id = [];
                                        $label_urutan = ['Input Awal', 'Revisi Pertama', 'Revisi Kedua'];
                                        foreach ($riwayat_dimensi_sorted as $idx => $r) {
                                            $label_dimensi_by_id[$r->id] = $label_urutan[$idx] ?? ('Revisi ke-' . $idx);
                                        }
                                    ?>
                                    <a href="#" class="small" data-bs-toggle="modal" data-bs-target="#<?= $dim_id ?>">
                                        <i class="bi bi-rulers"></i> Lihat Dimensi
                                    </a>

                                    <?php
                                    $ba_ukur_url = !empty($g->file_ba_pengukuran) ? base_url('uploads/lampiran/' . $g->file_ba_pengukuran) : null;
                                    $ba_ukur_ext = !empty($g->file_ba_pengukuran) ? strtolower(pathinfo($g->file_ba_pengukuran, PATHINFO_EXTENSION)) : null;
                                    $ba_ukur_id  = 'modal-ba-ukur-' . $g->id;
                                    ?>

                                    <div class="modal fade" id="<?= $dim_id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header py-2" style="background:var(--gold-500,#F0B429); color:#212529;">
                                                    <h6 class="modal-title mb-0">Dimensi &amp; Masa Berlaku — <?= htmlspecialchars($g->nama_gse) ?></h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-sm table-borderless mb-2">
                                                        <tr><th style="width:40%;">P (M)</th><td><?= htmlspecialchars($g->dimensi_p ?? '-') ?></td></tr>
                                                        <tr><th>L (M)</th><td><?= htmlspecialchars($g->dimensi_l ?? '-') ?></td></tr>
                                                        <tr><th>Luas (M²)</th><td><?= htmlspecialchars($g->dimensi_luas ?? '-') ?></td></tr>
                                                        <tr>
                                                            <th>Masa Berlaku</th>
                                                            <td>
                                                                <?= !empty($g->masa_mulai) ? date('d-m-Y', strtotime($g->masa_mulai)) : '-' ?>
                                                                &ndash;
                                                                <?= !empty($g->masa_selesai) ? date('d-m-Y', strtotime($g->masa_selesai)) : '-' ?>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div class="small text-muted mb-2">
                                                        Sudah diedit <?= $edit_count_g ?> dari 2 kali diperbolehkan.
                                                        <?php if ($batas_edit_dim): ?>
                                                            Batas edit sampai <strong><?= $batas_edit_dim ?></strong>.
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="d-flex gap-2 flex-wrap border-top pt-2">
                                                    <?php if ($bisa_edit_dim): ?>
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="bukaModalEditDimensi(<?= $g->id ?>, <?= (float) $g->dimensi_p ?>, <?= (float) $g->dimensi_l ?>, '<?= $g->masa_mulai ?>', '<?= $g->masa_selesai ?>')">
                                                        <i class="bi bi-pencil"></i> Edit Dimensi
                                                    </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            data-bs-toggle="modal" data-bs-target="#modal-riwayat-<?= $g->id ?>">
                                                        <i class="bi bi-clock-history"></i> Riwayat Verifikasi
                                                    </button>
                                                    <?php if (!empty($g->file_ba_pengukuran)): ?>
                                                    <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#<?= $ba_ukur_id ?>" data-dim-id="<?= $dim_id ?>">
                                                        <i class="bi bi-file-earmark-text"></i> Lihat BA Pengukuran
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                                </div>
                                                <div class="modal-footer py-2">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php if (!empty($g->file_ba_pengukuran)): ?>
                                    <div class="modal fade modal-ba-pengukuran-item" id="<?= $ba_ukur_id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header py-2">
                                                    <h6 class="modal-title mb-0">BA Pengukuran — <?= htmlspecialchars($g->nama_gse) ?></h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-0" style="min-height:400px;">
                                                    <?php if (in_array($ba_ukur_ext, ['jpg','jpeg','png'])): ?>
                                                        <img src="<?= $ba_ukur_url ?>" class="img-fluid d-block mx-auto p-2">
                                                    <?php else: ?>
                                                        <iframe src="<?= $ba_ukur_url ?>" width="100%" height="500px" style="border:none;"></iframe>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer py-2">
                                                    <a href="<?= $ba_ukur_url ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-box-arrow-up-right"></i> Buka di Tab Baru
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Modal Riwayat Verifikasi -->
                                    <div class="modal fade" id="modal-riwayat-<?= $g->id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header py-2">
                                                    <h6 class="modal-title mb-0"><i class="bi bi-clock-history"></i> Riwayat Verifikasi — <?= htmlspecialchars($g->nama_gse) ?></h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if (empty($riwayat_g)): ?>
                                                    <p class="text-muted small mb-0">Belum ada riwayat verifikasi tercatat untuk unit ini.</p>
                                                    <?php else: ?>
                                                    <ul class="list-unstyled mb-0">
                                                        <?php foreach ($riwayat_g as $r):
                                                            $label_tahap_r = ['operasi'=>'Operasi','equipment'=>'Equipment','sales'=>'Sales','security'=>'Security'][$r->tahap] ?? $r->tahap;
                                                            $badge_r = $r->aksi === 'Disetujui' ? 'bg-success' : ($r->aksi === 'Ditolak' ? 'bg-danger' : 'bg-warning text-dark');
                                                        ?>
                                                        <li class="border-bottom pb-2 mb-2">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <?php $label_aksi_tampil = $label_dimensi_by_id[$r->id] ?? $r->aksi; ?>
                                                                    <span class="badge <?= $badge_r ?>"><?= htmlspecialchars($label_aksi_tampil) ?></span>
                                                                    <span class="small fw-semibold ms-1"><?= htmlspecialchars($label_tahap_r) ?></span>
                                                                </div>
                                                                <span class="small text-muted"><?= date('d-m-Y H:i', strtotime($r->created_at)) ?></span>
                                                            </div>
                                                            <div class="small text-muted mt-1">
                                                                Oleh: <?= htmlspecialchars($r->nama_user ?? '-') ?>
                                                            </div>
                                                            <?php
                                                                $snapshot = null;
                                                                if (in_array($r->aksi, ['Input Dimensi', 'Edit Dimensi'], true) && !empty($r->catatan)) {
                                                                    $decoded = json_decode($r->catatan, true);
                                                                    if (is_array($decoded)) {
                                                                        // Dukung format lama (sebelum/sesudah) maupun format baru (snapshot langsung)
                                                                        $snapshot = $decoded['sesudah'] ?? $decoded;
                                                                    }
                                                                }
                                                            ?>
                                                            <?php if ($snapshot): ?>
                                                            <table class="table table-sm table-borderless mb-0 mt-1" style="font-size:.78rem;">
                                                                <tr>
                                                                    <td class="text-muted" style="width:40%;">P (M)</td>
                                                                    <td><?= htmlspecialchars($snapshot['dimensi_p'] ?? '-') ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">L (M)</td>
                                                                    <td><?= htmlspecialchars($snapshot['dimensi_l'] ?? '-') ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Luas (M²)</td>
                                                                    <td><?= htmlspecialchars($snapshot['dimensi_luas'] ?? '-') ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Masa Berlaku</td>
                                                                    <td>
                                                                        <?= !empty($snapshot['masa_mulai']) ? date('d-m-Y', strtotime($snapshot['masa_mulai'])) : '-' ?>
                                                                        &ndash;
                                                                        <?= !empty($snapshot['masa_selesai']) ? date('d-m-Y', strtotime($snapshot['masa_selesai'])) : '-' ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <?php elseif (!empty($r->catatan)): ?>
                                                            <div class="small text-muted mt-1">Catatan: <?= htmlspecialchars($r->catatan) ?></div>
                                                            <?php endif; ?>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer py-2">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>



                                <?php if (!empty($g->file_ba_uji_laik)): ?>
                                <div class="modal fade" id="<?= $ba_id ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header py-2">
                                                <h6 class="modal-title mb-0">BA Uji Laik — <?= htmlspecialchars($g->nama_gse) ?></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-0" style="min-height:400px;">
                                                <?php if (in_array($ba_ext, ['jpg','jpeg','png'])): ?>
                                                    <img src="<?= $ba_url ?>" class="img-fluid d-block mx-auto p-2">
                                                <?php else: ?>
                                                    <iframe src="<?= $ba_url ?>" width="100%" height="500px" style="border:none;"></iframe>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <a href="<?= $ba_url ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-box-arrow-up-right"></i> Buka di Tab Baru
                                                </a>
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php if ($is_item_sparepart): ?>
                                    <span class="text-muted small">-</span>
                                <?php else: ?>
                                <?php
                                $rekom_id = 'modal-rekom-' . $g->id;
                                $lulus_3_tahap = in_array($g->tahap_saat_ini ?? 'operasi', ['security', 'selesai']);
                                ?>
                                <?php if (!empty($g->file_surat_rekomendasi)): ?>
                                    <?php
                                        $rekom_url = base_url('uploads/lampiran/' . $g->file_surat_rekomendasi);
                                        $rekom_ext = strtolower(pathinfo($g->file_surat_rekomendasi, PATHINFO_EXTENSION));
                                    ?>
                                    <a href="javascript:void(0)"
                                    class="btn btn-sm btn-outline-success"
                                    onclick="previewDokumen('<?= $rekom_url ?>', 'Surat Rekomendasi - <?= htmlspecialchars($g->nama_gse) ?>')">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                <?php elseif ($role === 'unit_operasi' && $lulus_3_tahap): ?>
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#<?= $rekom_id ?>">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                <?php elseif (!$lulus_3_tahap): ?>
                                    <span class="text-muted small">Menunggu 3 tahap</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum tersedia</span>
                                <?php endif; ?>

                                <?php if ($role === 'unit_operasi' && $lulus_3_tahap): ?>
                                <div class="modal fade" id="<?= $rekom_id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Surat Rekomendasi — <?= htmlspecialchars($g->nama_gse) ?></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <?= form_open_multipart('permohonan_masuk/upload_surat_rekomendasi_item/' . $g->id) ?>
                                            <div class="modal-body">
                                                <label class="form-label">File Surat Rekomendasi <span class="text-danger">*</span></label>
                                                <input type="file" name="file_surat_rekomendasi" class="form-control"
                                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                                <div class="form-text">Format: PDF/JPG/PNG, maks. 500KB</div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload"></i> Upload</button>
                                            </div>
                                            <?= form_close() ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php if ($is_item_sparepart): ?>
                                    <span class="text-muted small">-</span>
                                <?php else: ?>
                                <?php
                                $rekom_id      = 'modal-rekom-pass-' . $g->id;
                                $lulus_4_tahap = in_array($g->tahap_saat_ini ?? '', ['sales', 'security', 'selesai'], true);
                                $is_motor_g    = strtolower($g->manufacture_type ?? '') === 'motorized';
                                ?>
                                <?php if (!$is_motor_g): ?>
                                    <span class="text-muted small">Tidak wajib</span>
                                <?php elseif (!empty($g->file_pass_kendaraan)): ?>
                                    <?php
                                        $rekom_url = base_url('uploads/lampiran/' . $g->file_pass_kendaraan);
                                        $rekom_ext = strtolower(pathinfo($g->file_pass_kendaraan, PATHINFO_EXTENSION));
                                    ?>
                                    <a href="javascript:void(0)"
                                    class="btn btn-sm btn-outline-success"
                                    onclick="previewDokumen('<?= $rekom_url ?>', 'Pass Kendaraan - <?= htmlspecialchars($g->nama_gse) ?>')">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                <?php elseif ($role === 'ground_handling'&& $lulus_4_tahap): ?>
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#<?= $rekom_id ?>">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                <?php elseif (!$lulus_4_tahap): ?>
                                    <span class="text-muted small">Menunggu tahap Equipment selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum tersedia</span>
                                <?php endif; ?>

                                <?php if ($is_motor_g && $role === 'ground_handling' && $lulus_4_tahap): ?>
                                <div class="modal fade" id="<?= $rekom_id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Pass Kendaraan — <?= htmlspecialchars($g->nama_gse) ?></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <?= form_open_multipart('permohonan_masuk/upload_file_pass_kendaraan_item/' . $g->id) ?>
                                            <div class="modal-body">
                                                <label class="form-label">File Pass Kendaraan <span class="text-danger">*</span></label>
                                                <input type="file" name="file_pass_kendaraan" class="form-control"
                                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                                <div class="form-text">Format: PDF/JPG/PNG, maks. 500KB</div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload"></i> Upload</button>
                                            </div>
                                            <?= form_close() ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </td>

                                <?php
                                $lamp_id = 'modal-lamp-' . $g->id;
                                $lamp_docs = [];

                                if (!empty($g->file_foto_gse)) {
                                    $lamp_docs[$is_item_sparepart ? 'Foto Sparepart' : 'Foto Unit GSE'] = $g->file_foto_gse;
                                }

                                if (!empty($g->file_nomor_asset)) {
                                    $lamp_docs['Bukti No. Asset'] = $g->file_nomor_asset;
                                }

                                if (($g->jenis_item ?? '') === 'Perbaikan') {
                                    if (!empty($g->file_bukti_perbaikan))
                                        $lamp_docs['Bukti Perbaikan'] = $g->file_bukti_perbaikan;
                                } elseif ($is_motor && !$is_item_sparepart) {
                                    $dok_fields = [
                                        'file_ktp'              => 'Fotocopy KTP',
                                        'file_tim'              => 'Fotocopy TIM',
                                        'file_stnk'             => 'Fotocopy STNK',
                                        'file_sim'              => 'Fotocopy SIM (Driver)',
                                        'file_emisi'            => 'File emisi',
                                        'file_foto_rangka'      => 'Foto No. Rangka',
                                        'file_foto_mesin'       => 'Foto No. Mesin',
                                        'file_penugasan'        => 'Surat Keterangan Penugasan',
                                        'file_rekomendasi_bengkel_luar' => 'Surat Rekomendasi Laik Bengkel',
                                    ];
                                    foreach ($dok_fields as $field => $label) {
                                        if (!empty($g->$field))
                                            $lamp_docs[$label] = $g->$field;
                                    }
                                }
                                ?>
                                <td class="text-center align-middle">
                                    <?php if (empty($lamp_docs)): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Belum</span>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="modal" data-bs-target="#<?= $lamp_id ?>">
                                            <i class="bi bi-eye"></i> Lihat (<?= count($lamp_docs) ?>)
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <!-- Modal Lampiran -->
                                <?php if (!empty($lamp_docs)): ?>
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
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <td class="text-center align-middle"><?= nl2br(htmlspecialchars($g->keterangan ?? $permohonan->keterangan ?? '-')) ?></td>

                                <td class="text-center align-middle">
                                    <?php
                                    $tahap_g  = $g->tahap_saat_ini ?? 'operasi';
                                    $status_g = $g->status_item ?? null;
                                    $bisa_aksi_item = $status_g !== 'Ditolak' && $tahap_g !== 'selesai'
                                        && (($peta_tahap_role[$role] ?? null) === $tahap_g);
                                    $menunggu_pass_g = !$is_item_sparepart && ($tahap_g === 'security')
                                        && (strtolower($g->manufacture_type ?? '') === 'motorized')
                                        && empty($g->file_pass_kendaraan);
                                    ?>
                                    <?php if ($bisa_aksi_item): ?>
                                        <div class="d-flex gap-1 justify-content-center align-items-center">
                                            <?php if ($menunggu_pass_g): ?>
                                                <span class="badge bg-warning text-dark p-2" title="Menunggu Pass Kendaraan dari GH">
                                                    <i class="bi bi-hourglass-split"></i> Menunggu Pass Kendaraan
                                                </span>
                                             <?php elseif ($tahap_g === 'operasi' && $role === 'unit_operasi' && empty($permohonan->file_dispo) && empty($g->file_dispo)): ?>
                                                <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalDispoDetail(<?= $g->id ?>)" title="Upload Dispo">
                                                    <i class="bi bi-upload"></i>
                                                </button>
                                             <?php elseif ($tahap_g === 'equipment' && $role === 'unit_equipment'): ?>
                                                <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalUjiLaikDetail(<?= $g->id ?>)">
                                                    <i class="bi bi-upload"></i>
                                                </button>
                                            <?php elseif ($tahap_g === 'sales' && $role === 'unit_sales'): ?>
                                                <button type="button" class="btn btn-sm btn-warning"
                                                        onclick="bukaModalDimensiDetail(<?= $g->id ?>, <?= json_encode($g->masa_mulai ?? null, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, <?= json_encode($g->masa_selesai ?? null, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, <?= json_encode($g->dimensi_p ?? null, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, <?= json_encode($g->dimensi_l ?? null, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, <?= json_encode($tahun_kontrak_baru) ?>)">
                                                    <i class="bi bi-rulers"></i>
                                                </button>
                                            <?php else: ?>
                                                <form method="post" action="<?= site_url('approval/setujui_item/' . $g->id) ?>" style="display:inline;" onsubmit="return confirm('Setujui unit GSE ini?')">
                                                    <input type="hidden" name="tahap_sekarang" value="<?= $tahap_g ?>">
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <button type="button" class="btn btn-sm btn-danger" onclick="bukaModalTolakItemDetail(<?= $g->id ?>)">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    <?php elseif (($status_g ?? null) === 'Ditolak' && $role === 'ground_handling'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-ajukan-ulang-item"
                                            data-gse='<?= json_encode([
                                                "id"                    => $g->id,
                                                "nama_gse"              => $g->nama_gse,
                                                "manufacture_type"      => $g->manufacture_type ?? "",
                                                "no_asset"              => $g->no_asset ?? "",
                                                "sticker_ap"            => $g->sticker_ap ?? "",
                                                "nomor_rangka"          => $g->nomor_rangka ?? "",
                                                "nomor_mesin"           => $g->nomor_mesin ?? "",
                                                "file_foto_gse"         => $g->file_foto_gse ?? "",
                                                "file_nomor_asset"      => $g->file_nomor_asset ?? "",
                                                "file_bukti_perbaikan"  => $g->file_bukti_perbaikan ?? "",
                                                "file_ktp"              => $g->file_ktp ?? "",
                                                "file_tim"              => $g->file_tim ?? "",
                                                "file_stnk"             => $g->file_stnk ?? "",
                                                "file_sim"              => $g->file_sim ?? "",
                                                "file_emisi"            => $g->file_emisi ?? "",
                                                "file_foto_rangka"      => $g->file_foto_rangka ?? "",
                                                "file_foto_mesin"       => $g->file_foto_mesin ?? "",
                                                "file_penugasan"        => $g->file_penugasan ?? "",
                                                "file_rekomendasi_bengkel_luar" => $g->file_rekomendasi_bengkel_luar ?? "",
                                                "file_ba_uji_laik" => $g->file_ba_uji_laik ?? "",
                                                "dimensi_p"             => $g->dimensi_p ?? "",
                                                "dimensi_l"             => $g->dimensi_l ?? "",
                                                "masa_mulai"       => $g->masa_mulai ?? "",
                                                "masa_selesai"     => $g->masa_selesai ?? "",
                                                "tahun_kontrak_baru" => $tahun_kontrak_baru,
                                                "keterangan" => $g->keterangan ?? "",
                                            ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                        <i class="bi bi-arrow-repeat"></i> Ajukan Ulang
                                    </button>
                                <?php elseif ($tahap_g === 'selesai'): ?>
                                    <?php if (!$is_item_sparepart): ?>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="javascript:void(0)"
                                           onclick="previewDokumen('<?= site_url('permohonan_masuk/cetak_ba_item/' . $permohonan->id_permohonan_masuk . '/' . $g->id) ?>', 'Berita Acara - <?= htmlspecialchars($g->nama_gse) ?>')"
                                           class="btn btn-sm btn-outline-info" title="Cetak BA">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                           onclick="previewDokumen('<?= site_url('permohonan_masuk/cetak_stiker_item/' . $permohonan->id_permohonan_masuk . '/' . $g->id) ?>', 'Stiker Verifikasi - <?= htmlspecialchars($g->nama_gse) ?>')"
                                           class="btn btn-sm btn-outline-info" title="Cetak Stiker">
                                            <i class="bi bi-qr-code"></i>
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="13" class="text-center text-muted py-3">Inputkan data GSE yang diajukan terlebih dahulu.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php if (in_array($permohonan->jenis_permohonan, ['Masuk Baru', 'Perbaikan', 'Masuk Perbaikan Sparepart', 'Sparepart']) && !$sdh_lengkap_unit
    && $permohonan->status === 'Menunggu Verifikasi Operasi'
    && (($tahap_unit['Menunggu Verifikasi Operasi'] ?? null) === $role || $role === 'admin')): ?>
<div class="action-bar has-warning mt-3">
    <i class="bi bi-exclamation-triangle text-warning"></i>
    <span class="small me-auto" style="color:var(--gold-600);">
        Kelengkapan data pengajuan baru terisi <?= $jml_terisi_unit ?> dari <?= $jml_total_unit ?> item.
        Permohonan ini belum bisa diverifikasi sampai Ground Handling melengkapi seluruh data pengajuan.
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
                    <textarea name="alasan_penolakan" class="form-control" rows="4" placeholder="Alasan penolakan unit ini..." required></textarea>
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
            <form method="post" action="<?= site_url('permohonan_masuk/edit_jumlah_unit/' . $permohonan->id_permohonan_masuk) ?>">
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

<!-- Modal Upload Dispo Per Unit (unit_operasi) -->
<div class="modal fade" id="modalDispoDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Upload Dispo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open_multipart('', ['id' => 'formDispoDetail', 'method' => 'post']) ?>
            <input type="hidden" name="redirect_to" value="<?= current_url() ?>">
            <div class="modal-body">
                <p class="mb-2">Upload <strong>Dokumen Dispo</strong>:</p>
                <input type="file" name="file_dispo" id="inputDispoDetail"
                       class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <div class="alert alert-info mt-3 py-2 small">
                    <i class="bi bi-info-circle"></i> Format: PDF/JPG/PNG · Maks. <strong>500KB</strong>.
                    Dispo akan berlaku untuk seluruh item dalam permohonan ini.
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

<!-- Modal Upload BA Uji Laik Per Unit -->
<div class="modal fade" id="modalUjiLaikDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Upload BA Uji Laik Unit GSE</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUjiLaikDetailItem" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="file_ba_uji_laik" id="inputBaDetailItem"
                           class="form-control" accept=".jpg,.jpeg,.png,.pdf"
                           onchange="validasiFileDetailItem(this)" required>
                    <div id="feedbackBaDetailItem" class="mt-1" style="font-size:.78rem;"></div>
                    <div class="form-text">Format: PDF/JPG/PNG · Maks. 500KB</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnUjiLaikDetailItemSubmit" class="btn btn-warning btn-sm" disabled>
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Input Dimensi & Masa Berlaku Per Unit -->
<div class="modal fade" id="modalDimensiDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-rulers me-1"></i> Input Dimensi &amp; Masa Berlaku</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDimensiDetailItem" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">P (M)</label>
                            <input type="number" step="0.01" min="0.01" name="dimensi_p" id="dimP" class="form-control" required oninput="hitungLuasDetail()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">L (M)</label>
                            <input type="number" step="0.01" min="0.01" name="dimensi_l" id="dimL" class="form-control" required oninput="hitungLuasDetail()">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Luas (M²)</label>
                            <input type="text" id="dimLuas" class="form-control" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Mulai</label>
                            <input type="text" name="masa_mulai" id="dimMulai" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Selesai</label>
                            <input type="text" name="masa_selesai" id="dimSelesai" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">BA Pengukuran<span class="text-danger">*</span></label>
                            <input type="file" name="file_ba_pengukuran" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text">Format: PDF/JPG/PNG · Maks. 500KB</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-check-lg"></i> Simpan & Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Dimensi (dipakai bersama, diisi dinamis via JS) -->
<div class="modal fade" id="modalEditDimensi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-pencil me-1"></i> Edit Dimensi &amp; Masa Berlaku</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditDimensi" method="post">
                <div class="modal-body">
                    <div class="alert alert-warning py-2 mb-3 small">
                        <i class="bi bi-exclamation-triangle"></i> Edit ini akan mengurangi kuota edit yang tersisa. Pastikan data sudah benar.
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">P (M)</label>
                            <input type="number" step="0.01" min="0.01" name="dimensi_p" id="editDimP" class="form-control" required oninput="hitungLuasEditDim()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">L (M)</label>
                            <input type="number" step="0.01" min="0.01" name="dimensi_l" id="editDimL" class="form-control" required oninput="hitungLuasEditDim()">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Luas (M²)</label>
                            <input type="text" id="editDimLuas" class="form-control" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Mulai</label>
                            <input type="text" name="masa_mulai" id="editDimMulai" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Selesai</label>
                            <input type="text" name="masa_selesai" id="editDimSelesai" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
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
                <a id="previewDokumenDownloadFile" href="" download class="btn btn-sm btn-success">
                    <i class="bi bi-download"></i> Download
                </a>
                <a id="previewDokumenDownload" href="" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right"></i> Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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
</script>

<script>
function previewDokumen(url, judul) {
    var ext = url.split('.').pop().toLowerCase().split('?')[0];
    var imgTag   = document.getElementById('previewDokumenImg');
    var frameTag = document.getElementById('previewDokumenFrame');

    document.getElementById('previewDokumenTitle').innerHTML =
        '<i class="bi bi-file-earmark"></i> ' + judul;
    document.getElementById('previewDokumenDownload').href = url;
    document.getElementById('previewDokumenDownloadFile').href = url;

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
        imgTag.src = url;
        imgTag.style.display   = 'inline-block';
        frameTag.style.display = 'none';
        frameTag.src = '';
    } else {
        // PDF atau format lain — tampilkan lewat iframe
        frameTag.src = url;
        frameTag.style.display = 'block';
        imgTag.style.display   = 'none';
        imgTag.src = '';
    }

    new bootstrap.Modal(document.getElementById('modalPreviewDokumen')).show();
}

function printDokumen() {
    var imgTag   = document.getElementById('previewDokumenImg');
    var frameTag = document.getElementById('previewDokumenFrame');

    if (frameTag.style.display !== 'none' && frameTag.src) {
        try {
            frameTag.contentWindow.focus();
            frameTag.contentWindow.print();
        } catch (e) {
            // Kalau browser blokir akses (beda origin dll), fallback buka tab baru
            window.open(frameTag.src, '_blank');
        }
    } else if (imgTag.style.display !== 'none' && imgTag.src) {
        var w = window.open('', '_blank');
        w.document.write(
            '<html><head><title>Cetak Gambar</title></head><body style="margin:0;text-align:center;">' +
            '<img src="' + imgTag.src + '" style="max-width:100%;" onload="window.print();">' +
            '</body></html>'
        );
        w.document.close();
    }
}
</script>



<!-- Modal Ajukan Ulang Per Unit GSE -->
<div class="modal fade" id="modalAjukanUlangItem" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--teal-700); color:#fff;">
                <h6 class="modal-title mb-0"><i class="bi bi-arrow-repeat me-1"></i> Ajukan Ulang Unit GSE</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAjukanUlangItem" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle"></i>
                        Data lama tidak akan terhapus. Kosongkan bagian file jika tidak ingin menggantinya —
                        file/data lama akan tetap dipakai.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama GSE</label>
                            <input type="text" name="nama_gse" id="ajuNamaGse" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Manufacture Type</label>
                            <input type="text" name="manufacture_type" id="ajuManufactureType" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. Asset</label>
                            <input type="text" name="no_asset" id="ajuNoAsset" class="form-control">
                        </div>

                        <?php if ($permohonan->jenis_permohonan !== 'Perbaruan Kontrak'): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Sticker AP</label>
                            <input type="text" name="sticker_ap" id="ajuStickerAp" class="form-control">
                        </div>
                        <?php else: ?>
                        <input type="hidden" name="sticker_ap" id="ajuStickerAp" value="">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Sticker AP</label>
                            <input type="text" class="form-control" value="Akan dibuat otomatis setelah verifikasi selesai" disabled>
                        </div>
                        <?php endif; ?>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Keterangan</label>
                            <textarea name="keterangan" id="ajuKeterangan" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-12"><hr class="my-1"></div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Foto Unit GSE</label>
                            <input type="file" name="file_foto_gse" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="mt-1" id="ajuFileFotoLama"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Bukti File No. Asset <span class="text-muted small">(Opsional/jika manual)</span></label>
                            <input type="file" name="file_nomor_asset" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="mt-1" id="ajuFileNomorAssetLama"></div>
                        </div>

                        <?php if ($permohonan->jenis_permohonan === 'Perbaikan'): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Bukti Perbaikan</label>
                            <input type="file" name="file_bukti_perbaikan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="mt-1" id="ajuFileBuktiPerbaikanLama"></div>
                        </div>
                        <?php endif; ?>

                        <?php if ($permohonan->jenis_permohonan === 'Masuk Baru'): ?>
                        <div id="ajuMotorizedBox" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">No. Rangka</label>
                                <input type="text" name="nomor_rangka" id="ajuNomorRangka" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">No. Mesin</label>
                                <input type="text" name="nomor_mesin" id="ajuNomorMesin" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Foto No. Rangka</label>
                                <input type="file" name="file_foto_rangka" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileFotoRangkaLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Foto No. Mesin</label>
                                <input type="file" name="file_foto_mesin" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileFotoMesinLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy KTP</label>
                                <input type="file" name="file_ktp" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileKtpLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy TIM</label>
                                <input type="file" name="file_tim" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileTimLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy STNK</label>
                                <input type="file" name="file_stnk" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileStnkLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy SIM (Driver)</label>
                                <input type="file" name="file_sim" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileSimLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">File emisi</label>
                                <input type="file" name="file_emisi" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileEmisiLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Surat Keterangan Penugasan</label>
                                <input type="file" name="file_penugasan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFilePenugasanLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Surat Rekomendasi Laik Bengkel</label>
                                <input type="file" name="file_rekomendasi_bengkel_luar" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileRekomendasiBengkelLama"></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($permohonan->jenis_permohonan === 'Perbaruan Kontrak'): ?>
                        <div id="ajuKontrakBox" class="row g-3">
                            <div id="ajuKontrakDokumenMotorized" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy KTP</label>
                                <input type="file" name="file_ktp" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileKtpLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy TIM</label>
                                <input type="file" name="file_tim" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileTimLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy STNK</label>
                                <input type="file" name="file_stnk" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileStnkLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fotocopy SIM (Driver)</label>
                                <input type="file" name="file_sim" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileSimLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">File Emisi</label>
                                <input type="file" name="file_emisi" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileEmisiLama"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">BA Uji Laik</label>
                                <input type="file" name="file_ba_uji_laik" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="mt-1" id="ajuFileBaUjiLaikLama"></div>
                            </div>
                            </div>

                            <div class="col-12"><hr class="my-1"></div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">P (M)</label>
                                <input type="number" step="0.01" min="0.01" name="dimensi_p" id="ajuDimP" class="form-control" oninput="hitungLuasAjuKontrak()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">L (M)</label>
                                <input type="number" step="0.01" min="0.01" name="dimensi_l" id="ajuDimL" class="form-control" oninput="hitungLuasAjuKontrak()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Luas (M²)</label>
                                <input type="text" id="ajuDimLuas" class="form-control" readonly>
                            </div>

                            <div class="col-12"><hr class="my-1"></div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Masa Berlaku Mulai</label>
                                <input type="date" name="masa_mulai" id="ajuMasaMulai" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Masa Berlaku Selesai</label>
                                <input type="date" name="masa_selesai" id="ajuMasaSelesai" class="form-control">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send"></i> Ajukan Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.btn-ajukan-ulang-item');
    if (!btn) return;
    var g = JSON.parse(btn.getAttribute('data-gse'));
    bukaModalAjukanUlangItem(g);
});
</script>

<script>
function bukaModalAjukanUlangItem(g) {
    document.getElementById('formAjukanUlangItem').action = '<?= site_url('permohonan_masuk/update_item/') ?>' + g.id;

    document.getElementById('ajuNamaGse').value         = g.nama_gse;
    document.getElementById('ajuManufactureType').value = g.manufacture_type;
    document.getElementById('ajuNoAsset').value          = g.no_asset;
    var jenisPermohonanUnit = <?= json_encode($permohonan->jenis_permohonan) ?>;
    if (jenisPermohonanUnit === 'Perbaruan Kontrak') {
        document.getElementById('ajuKeterangan').value = g.keterangan || 'Perbaruan kontrak untuk 1 unit GSE.';
    } else {
        document.getElementById('ajuKeterangan').value = g.keterangan || '';
    }
    document.getElementById('ajuStickerAp').value        = g.sticker_ap;
    if (document.getElementById('ajuNomorRangka')) {
        document.getElementById('ajuNomorRangka').value = g.nomor_rangka || '';
    }
    if (document.getElementById('ajuNomorMesin')) {
        document.getElementById('ajuNomorMesin').value = g.nomor_mesin || '';
    }
    if (document.getElementById('ajuDimP')) {
        document.getElementById('ajuDimP').value = g.dimensi_p || '';
        document.getElementById('ajuDimL').value = g.dimensi_l || '';
        hitungLuasAjuKontrak();
    }
    var motorBox = document.getElementById('ajuMotorizedBox');
    if (motorBox) {
        var isMotor = (g.manufacture_type || '').toLowerCase() === 'motorized';
        motorBox.style.display = isMotor ? '' : 'none';
        if (!isMotor) {
            motorBox.querySelectorAll('input[type=file]').forEach(function (i) { i.value = ''; });
        }
    }
    

    var kontrakDokBox = document.getElementById('ajuKontrakDokumenMotorized');
    if (kontrakDokBox) {
        var isMotorKontrak = (g.manufacture_type || '').toLowerCase() === 'motorized';
        kontrakDokBox.style.display = isMotorKontrak ? '' : 'none';
        if (!isMotorKontrak) {
            kontrakDokBox.querySelectorAll('input[type=file]').forEach(function (i) { i.value = ''; });
        }
    }

    var baseUploadUrl = '<?= base_url('uploads/lampiran/') ?>';

    var tandaiFileLama = function(elId, filename, label) {
        var el = document.getElementById(elId);
        if (!el) return; // elemen tidak ada di DOM, skip
        if (!filename) {
            el.innerHTML = '<span class="small text-muted">Belum ada file sebelumnya.</span>';
            return;
        }
        var url = baseUploadUrl + filename;
        var ext = filename.split('.').pop().toLowerCase();
        var isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
        var html = '<div class="d-flex align-items-center gap-2 border rounded p-1" style="background:#fafefe;">';
        if (isImage) {
            html += '<img src="' + url + '" style="width:44px;height:44px;object-fit:cover;border-radius:.35rem;">';
        } else {
            html += '<i class="bi bi-file-earmark-pdf text-danger" style="font-size:1.6rem;"></i>';
        }
        html += '<div class="flex-grow-1 small">';
        html += '<div class="fw-semibold text-muted">File ' + label + ' saat ini</div>';
        html += '<a href="javascript:void(0)" onclick="previewDokumen(\'' + url + '\', \'' + label + '\')">Lihat file</a>';
        html += '</div></div>';

        el.innerHTML = html;
    };

    tandaiFileLama('ajuFileFotoLama', g.file_foto_gse, 'Foto Unit GSE');
    if (document.getElementById('ajuFileNomorAssetLama')) {
        tandaiFileLama('ajuFileNomorAssetLama', g.file_nomor_asset, 'Bukti No. Asset');
    }
    if (document.getElementById('ajuFileBuktiPerbaikanLama')) {
        tandaiFileLama('ajuFileBuktiPerbaikanLama', g.file_bukti_perbaikan, 'Bukti Perbaikan');
    }
    if (document.getElementById('ajuFileFotoRangkaLama')) {
        tandaiFileLama('ajuFileFotoRangkaLama', g.file_foto_rangka, 'Foto No. Rangka');
    }
    if (document.getElementById('ajuFileFotoMesinLama')) {
        tandaiFileLama('ajuFileFotoMesinLama', g.file_foto_mesin, 'Foto No. Mesin');
    }
    if (document.getElementById('ajuFileKtpLama')) {
        tandaiFileLama('ajuFileKtpLama',  g.file_ktp,  'Fotocopy KTP');
    }
    if (document.getElementById('ajuFileTimLama')) {
        tandaiFileLama('ajuFileTimLama',  g.file_tim,  'Fotocopy TIM');
    }
    if (document.getElementById('ajuFileStnkLama')) {
        tandaiFileLama('ajuFileStnkLama', g.file_stnk, 'Fotocopy STNK');
    }
    if (document.getElementById('ajuFileSimLama')) {
        tandaiFileLama('ajuFileSimLama',  g.file_sim,  'Fotocopy SIM');
    }
    if (document.getElementById('ajuFileEmisiLama')) {
        tandaiFileLama('ajuFileEmisiLama',  g.file_emisi,  'File emisi');
    }
    if (document.getElementById('ajuFileBaUjiLaikLama')) {
        tandaiFileLama('ajuFileBaUjiLaikLama', g.file_ba_uji_laik, 'BA Uji Laik');
    }
    if (document.getElementById('ajuFilePenugasanLama')) {
        tandaiFileLama('ajuFilePenugasanLama', g.file_penugasan, 'Surat Keterangan Penugasan');
    }
    if (document.getElementById('ajuFileRekomendasiBengkelLama')) {
        tandaiFileLama('ajuFileRekomendasiBengkelLama', g.file_rekomendasi_bengkel_luar, 'Surat Rekomendasi Laik Bengkel');
    }
    
    var defMasaMulaiAju, defMasaSelesaiAju;
    if (jenisPermohonanUnit === 'Perbaruan Kontrak' && g.tahun_kontrak_baru) {
        defMasaMulaiAju   = g.tahun_kontrak_baru + '-01-01';
        defMasaSelesaiAju = g.tahun_kontrak_baru + '-12-31';
    } else if (g.masa_mulai || g.masa_selesai) {
        defMasaMulaiAju   = g.masa_mulai   || '';
        defMasaSelesaiAju = g.masa_selesai || '';
    } else {
        defMasaMulaiAju   = '';
        defMasaSelesaiAju = '';
    }

    if (window.flatpickrInstances && window.flatpickrInstances['ajuMasaMulai']) {
        window.flatpickrInstances['ajuMasaMulai'].setDate(defMasaMulaiAju || null, true);
    } else if (document.getElementById('ajuMasaMulai')) {
        document.getElementById('ajuMasaMulai').value = defMasaMulaiAju;
    }

    if (window.flatpickrInstances && window.flatpickrInstances['ajuMasaSelesai']) {
        window.flatpickrInstances['ajuMasaSelesai'].setDate(defMasaSelesaiAju || null, true);
    } else if (document.getElementById('ajuMasaSelesai')) {
        document.getElementById('ajuMasaSelesai').value = defMasaSelesaiAju;
    }

    new bootstrap.Modal(document.getElementById('modalAjukanUlangItem')).show();
}

function hitungLuasAjuKontrak() {
    var p = parseFloat(document.getElementById('ajuDimP').value) || 0;
    var l = parseFloat(document.getElementById('ajuDimL').value) || 0;
    document.getElementById('ajuDimLuas').value = (p * l).toFixed(2);
}

</script>

<script>
function bukaModalDispoDetail(idDetail) {
    document.getElementById('inputDispoDetail').value = '';
    document.getElementById('formDispoDetail').action = '<?= site_url('approval/upload_dispo_item/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalDispoDetail')).show();
}

function bukaModalTolakItemDetail(idDetail) {
    document.getElementById('formTolakItemDetail').action = '<?= site_url('approval/tolak_item/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalTolakItemDetail')).show();
}

function bukaModalUjiLaikDetail(idDetail) {
    document.getElementById('inputBaDetailItem').value = '';
    document.getElementById('feedbackBaDetailItem').textContent = '';
    document.getElementById('btnUjiLaikDetailItemSubmit').disabled = true;
    document.getElementById('formUjiLaikDetailItem').action = '<?= site_url('approval/upload_ba_uji_laik/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalUjiLaikDetail')).show();
}

function bukaModalDimensiDetail(idDetail, masaMulaiDiajukan, masaSelesaiDiajukan, pDiajukan, lDiajukan, tahunKontrakBaru) {
    document.getElementById('dimP').value = pDiajukan || '';
    document.getElementById('dimL').value = lDiajukan || '';
    hitungLuasDetail();

    var defMulai, defSelesai;

    if (masaMulaiDiajukan || masaSelesaiDiajukan) {
        defMulai   = masaMulaiDiajukan   || '';
        defSelesai = masaSelesaiDiajukan || '';
    } else if (tahunKontrakBaru) {
        defMulai   = tahunKontrakBaru + '-01-01';
        defSelesai = tahunKontrakBaru + '-12-31';
    } else {
        var hariIni = new Date();
        var pad2 = function (n) { return (n < 10 ? '0' : '') + n; };
        defMulai   = hariIni.getFullYear() + '-' + pad2(hariIni.getMonth() + 1) + '-' + pad2(hariIni.getDate());
        defSelesai = hariIni.getFullYear() + '-12-31';
    }

    if (window.flatpickrInstances && window.flatpickrInstances['dimMulai']) {
        window.flatpickrInstances['dimMulai'].setDate(defMulai, true);
        window.flatpickrInstances['dimSelesai'].setDate(defSelesai, true);
    } else {
        document.getElementById('dimMulai').value   = defMulai;
        document.getElementById('dimSelesai').value = defSelesai;
    }

    document.getElementById('formDimensiDetailItem').action = '<?= site_url('approval/simpan_dimensi_item/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalDimensiDetail')).show();
}

function bukaModalEditDimensi(idDetail, p, l, masaMulai, masaSelesai) {
    document.getElementById('editDimP').value       = p || '';
    document.getElementById('editDimL').value       = l || '';

    if (window.flatpickrInstances && window.flatpickrInstances['editDimMulai']) {
        window.flatpickrInstances['editDimMulai'].setDate(masaMulai || null, true);
        window.flatpickrInstances['editDimSelesai'].setDate(masaSelesai || null, true);
    } else {
        document.getElementById('editDimMulai').value   = masaMulai || '';
        document.getElementById('editDimSelesai').value = masaSelesai || '';
    }

    hitungLuasEditDim();
    document.getElementById('formEditDimensi').action = '<?= site_url('approval/edit_dimensi_item/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalEditDimensi')).show();
}

function hitungLuasEditDim() {
    var p = parseFloat(document.getElementById('editDimP').value) || 0;
    var l = parseFloat(document.getElementById('editDimL').value) || 0;
    document.getElementById('editDimLuas').value = (p * l).toFixed(2);
}

function hitungLuasDetail() {
    var p = parseFloat(document.getElementById('dimP').value) || 0;
    var l = parseFloat(document.getElementById('dimL').value) || 0;
    document.getElementById('dimLuas').value = (p * l).toFixed(2);
}

function validasiFileDetailItem(input) {
    var feedback = document.getElementById('feedbackBaDetailItem');
    var btn      = document.getElementById('btnUjiLaikDetailItemSubmit');
    var allowed  = ['jpg','jpeg','png','pdf'];
    var maxSize  = 500 * 1024;
    if (!input.files || !input.files[0]) { btn.disabled = true; return; }
    var file = input.files[0];
    var ext  = file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext) || file.size > maxSize) {
        feedback.style.color = '#A23B2A';
        feedback.textContent = !allowed.includes(ext) ? '✗ Format tidak didukung.' : '✗ Ukuran melebihi 500KB.';
        btn.disabled = true;
        input.value  = '';
        return;
    }
    feedback.style.color = '#1F6F45';
    feedback.textContent = '✓ ' + file.name;
    btn.disabled = false;
}

document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-dim-id]');
    if (!btn) return;

    var dimModalEl = document.getElementById(btn.getAttribute('data-dim-id'));
    var baModalEl  = document.getElementById(btn.getAttribute('data-bs-target').substring(1));
    if (!dimModalEl || !baModalEl) return;

    // Begitu modal BA Pengukuran selesai ditutup, buka lagi modal Dimensi.
    // {once: true} supaya listener ini hanya jalan sekali per klik, tidak menumpuk.
    baModalEl.addEventListener('hidden.bs.modal', function () {
        new bootstrap.Modal(dimModalEl).show();
    }, { once: true });
});

</script>