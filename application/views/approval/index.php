<?php
$user_role = $user_role ?? $this->session->userdata('role') ?? '';

$label_role = [
    'unit_operasi'   => 'Airport Operation Airside',
    'unit_equipment' => 'Airport Equipment',
    'unit_sales'     => 'Unit Sales',
    'unit_security'  => 'Airport Security Protection',
    'admin'          => 'Admin',
];
$role_label = $label_role[$user_role] ?? $user_role;

$item_masuk  = $item_masuk  ?? [];
$item_keluar = $item_keluar ?? [];

$total_item_masuk  = count($item_masuk);
$total_item_keluar = count($item_keluar);
$total_menunggu    = $total_item_masuk + $total_item_keluar;
?>

<style>
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.92rem; margin-bottom:0; }
    .role-chip{ display:inline-flex; align-items:center; gap:.4rem; background:var(--mint-50); border:1px solid var(--line); color:var(--teal-800); font-weight:600; font-size:.8rem; padding:.45rem .9rem; border-radius:999px; white-space:nowrap; }
    .tip-bar{ display:flex; align-items:flex-start; gap:.6rem; background:var(--mint-50); border:1px solid var(--line); color:var(--ink-600); font-size:.86rem; border-radius:.75rem; padding:.7rem .9rem; margin-bottom:1.25rem; }
    .tip-bar i{ color:var(--teal-700); margin-top:.1rem; }
    .stat-row{ display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; margin-bottom:1.25rem; }
    .stat-card{ background:#fff; border-radius:1rem; padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); }
    .stat-icon{ width:42px; height:42px; border-radius:.7rem; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
    .stat-icon-teal{ background:var(--mint-50); color:var(--teal-700); }
    .stat-icon-green{ background:#E9F6EE; color:#1F6F45; }
    .stat-icon-blue{ background:#EFF4FF; color:#3B5BDB; }
    .stat-value{ font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:1.35rem; color:var(--ink-900); line-height:1.1; }
    .stat-label{ font-size:.78rem; color:var(--ink-600); }
    .jenis-tabs{ display:flex; gap:.5rem; margin-bottom:1rem; flex-wrap:wrap; }
    .jenis-tab{ display:inline-flex; align-items:center; gap:.4rem; padding:.45rem 1rem; border-radius:999px; font-size:.82rem; font-weight:600; border:1.5px solid var(--line); background:#fff; color:var(--ink-600); cursor:pointer; transition:all .15s; }
    .jenis-tab:hover{ border-color:var(--teal-600); color:var(--teal-700); }
    .jenis-tab.active{ border-color:var(--teal-600); background:var(--mint-50); color:var(--teal-700); }
    .jenis-tab .pill{ background:var(--line); color:var(--ink-600); border-radius:999px; padding:.1rem .45rem; font-size:.72rem; }
    .jenis-tab.active .pill{ background:var(--teal-600); color:#fff; }
    .tab-pane-custom{ display:none; }
    .tab-pane-custom.show{ display:block; }
    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.76rem; font-weight:600; padding:.25rem .65rem; border-radius:999px; }
    .doc-ref{ display:inline-flex; align-items:center; gap:.4rem; background:var(--mint-50); color:var(--teal-800); font-weight:600; font-size:.8rem; padding:.3rem .6rem; border-radius:.5rem; white-space:nowrap; }
    .badge-soft{ display:inline-flex; align-items:center; gap:.3rem; font-size:.74rem; font-weight:600; padding:.3rem .6rem; border-radius:999px; white-space:nowrap; }
    .badge-status-Menunggu{ background:#FDF2DF; color:var(--gold-600); }
    .row-actions{ display:flex; gap:.4rem; justify-content:flex-end; flex-wrap:wrap; }
    .row-actions .btn{ border-radius:.55rem; white-space:nowrap; }
    @media(max-width:767.98px){
        .row-actions{ justify-content:flex-start; }
        .row-actions .btn{ font-size:.78rem; padding:.35rem .6rem; }
    }
    .empty-state{ text-align:center; padding:3rem 1rem; color:var(--ink-600); }
    .empty-state i{ font-size:2.4rem; color:var(--teal-600); display:block; margin-bottom:.6rem; }
    .modal-content{ border:0; border-radius:1rem; overflow:hidden; }
    .stage-pill{ font-size:.62rem !important; padding:.15rem .4rem !important; }
    @media(max-width:767.98px){ .stat-row{ grid-template-columns:1fr; } }

    /* ==== Tampilan kartu untuk tabel Verifikasi di layar HP (tanpa scroll ke samping) ==== */
    @media(max-width:767.98px){
        .table-responsive{ overflow-x:visible; }
        .table-responsive table, .table-responsive thead, .table-responsive tbody,
        .table-responsive tr, .table-responsive td{ display:block; width:100%; }
        .table-responsive thead{ display:none; }
        .table-responsive tr{
            border:1px solid var(--line); border-radius:.85rem; padding:.85rem .9rem;
            margin-bottom:.75rem; box-shadow:0 1px 3px rgba(10,54,59,.06);
        }
        .table-responsive td{
            border:0; padding:.3rem 0; display:flex; align-items:center;
            justify-content:space-between; gap:.75rem; text-align:right;
        }
        .table-responsive td[data-label]::before{
            content:attr(data-label); font-weight:600; font-size:.76rem;
            color:var(--ink-600); text-align:left; flex-shrink:0;
        }
        .table-responsive td.action-cell{ display:block; padding-top:.6rem; }
        .table-responsive td.action-cell::before{ content:none; }
        .table-responsive .row-actions{ justify-content:flex-start; }
        .table-responsive .row-actions .btn{ flex:1 1 auto; text-align:center; }
    }

    .file-feedback{ font-size:.78rem; margin-top:.35rem; }
    .file-feedback.valid{ color:#1F6F45; }
    .file-feedback.invalid{ color:#A23B2A; }

    .apv-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; margin-bottom:1.25rem; }
    .apv-stat  { background:#fff; border-radius:1rem; padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 3px rgba(10,54,59,.06); }
    .apv-stat-icon { width:42px; height:42px; border-radius:.7rem; display:flex; align-items:center; justify-content:center; font-size:1.1rem; }
    .apv-stat-val   { font-size:1.5rem; font-weight:700; color:var(--ink-900); line-height:1; }
    .apv-stat-label { font-size:.76rem; color:var(--ink-600); }
    .badge-motorized { background:#FDECE8; color:#A23B2A; }
    .badge-nonmotor  { background:#EEF1F1; color:var(--ink-600); }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-check2-square"></i> Verifikasi Permohonan</h5>
        <p class="page-subtitle">Daftar permohonan yang menunggu verifikasi dari unit Anda.</p>
    </div>
    <span class="role-chip"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($role_label) ?></span>
</div>

<div class="tip-bar">
    <i class="bi bi-info-circle"></i>
    <span>Klik <strong>Detail</strong> untuk memeriksa kelengkapan dokumen lampiran sebelum menyetujui atau menolak permohonan.</span>
</div>

<div class="stat-row">
    <div class="stat-card">
        <div class="stat-icon stat-icon-teal"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="stat-value"><?= $total_menunggu ?></div><div class="stat-label">Total Unit Menunggu</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><i class="bi bi-envelope-arrow-down"></i></div>
        <div><div class="stat-value"><?= count($item_masuk) ?></div><div class="stat-label">Unit Masuk</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue"><i class="bi bi-envelope-arrow-up"></i></div>
        <div><div class="stat-value"><?= count($item_keluar) ?></div><div class="stat-label">Unit Keluar</div></div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title mb-0"><i class="bi bi-x-circle me-1"></i> Tolak Permohonan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open('', ['id' => 'formTolak', 'method' => 'post']) ?>
            <div class="modal-body">
                <p class="mb-2">Anda akan <strong>menolak</strong> permohonan ini.</p>

                <div id="tolakItemWrap" class="mb-3" style="display:none;">
                    <label class="form-label small fw-semibold">Pilih unit GSE yang ditolak<span class="text-danger">*</span></label>
                    <div id="tolakItemList" class="border rounded p-2" style="max-height:200px; overflow-y:auto;"></div>
                    <div class="form-text">Unit yang tidak dicentang tidak perlu diinput ulang saat pemohon mengajukan ulang.</div>
                </div>

                <label class="form-label small fw-semibold">Alasan penolakan<span class="text-danger">*</span></label>
                <textarea name="alasan_penolakan" class="form-control" rows="4"
                          placeholder="Tuliskan alasan penolakan..." required></textarea>
                <div class="form-text">Alasan ini akan dikirimkan kepada pemohon.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i> Tolak Permohonan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Modal Upload BA Uji Laik (unit_equipment) -->
<div class="modal fade" id="modalUjiLaik" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Upload BA Uji Laik Peralatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open_multipart('', ['id' => 'formUjiLaik', 'method' => 'post']) ?>
            <div class="modal-body">
                <p class="mb-2">Upload <strong>Berita Acara Uji Laik Peralatan/Kendaraan</strong>:</p>

                <!-- Lapis 1 & 2: input dengan validasi JS -->
                <input type="file" name="file_ba_uji_laik" id="inputBaUjiLaik"
                       class="form-control" accept=".jpg,.jpeg,.png,.pdf"
                       onchange="validasiFile(this)" required>
                <div id="fileFeedback" class="file-feedback"></div>

                <div class="alert alert-info mt-3 py-2 small">
                    <i class="bi bi-info-circle"></i>
                    Format: PDF/JPG/PNG · Maks. <strong>500KB</strong>.
                    Setelah upload, status kelayakan otomatis menjadi <strong>Layak</strong>
                    dan permohonan diteruskan ke tahap berikutnya.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="btnUploadSubmit" class="btn btn-warning btn-sm" disabled>
                    <i class="bi bi-upload"></i> Upload & Setujui
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Modal Upload Dispo (unit_operasi) -->
<div class="modal fade" id="modalDispo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Upload Dispo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open_multipart('', ['id' => 'formDispo', 'method' => 'post']) ?>
            <div class="modal-body">
                <p class="mb-2">Upload <strong>Dokumen Dispo</strong>:</p>
                <input type="file" name="file_dispo" id="inputDispo"
                       class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <div class="alert alert-info mt-3 py-2 small">
                    <i class="bi bi-info-circle"></i> Format: PDF/JPG/PNG · Maks. <strong>500KB</strong>.
                    Setelah upload, unit otomatis disetujui dan diteruskan ke tahap Equipment.
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

<!-- Modal Input Dimensi & Masa Berlaku (unit_sales) -->
<div class="modal fade" id="modalDimensi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-rulers me-1"></i> Input Dimensi &amp; Masa Berlaku</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDimensi" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">P (M)</label>
                            <input type="number" step="0.01" min="0.01" name="dimensi_p" id="dimP2" class="form-control" required oninput="hitungLuas2()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">L (M)</label>
                            <input type="number" step="0.01" min="0.01" name="dimensi_l" id="dimL2" class="form-control" required oninput="hitungLuas2()">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Luas (M²)</label>
                            <input type="text" id="dimLuas2" class="form-control" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Mulai</label>
                            <input type="text" name="masa_mulai" id="dimMulai2" class="form-control" required autocomplete="off">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Selesai</label>
                            <input type="text" name="masa_selesai" id="dimSelesai2" class="form-control" required autocomplete="off">
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

<!-- Tab Jenis -->
<div class="jenis-tabs">
    <button class="jenis-tab active" onclick="switchTab('masuk', this)">
        <i class="bi bi-envelope-arrow-down"></i> Permohonan Masuk
        <span class="pill"><?= count($item_masuk) ?></span>
    </button>
    <button class="jenis-tab" onclick="switchTab('keluar', this)">
        <i class="bi bi-envelope-arrow-up"></i> Permohonan Keluar
        <span class="pill"><?= count($item_keluar) ?></span>
    </button>
</div>

<pre>

</pre>

<!-- Modal Upload Dispo Keluar (unit_sales) -->
<div class="modal fade" id="modalDispoKeluar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title mb-0"><i class="bi bi-upload me-1"></i> Upload Dispo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open_multipart('', ['id' => 'formDispoKeluar', 'method' => 'post']) ?>
            <div class="modal-body">
                <p class="mb-2">Upload <strong>Dokumen Dispo</strong>:</p>
                <input type="file" name="file_dispo" id="inputDispoKeluar"
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

<!-- TAB SURAT MASUK -->
<div id="tab-masuk" class="tab-pane-custom show">
    <div class="card border-0">
        <div class="table-card-head">
            <h6><i class="bi bi-envelope-arrow-down me-1"></i> Unit Masuk Menunggu Verifikasi</h6>
            <span class="count-pill"><?= count($item_masuk) ?> unit</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Permohonan</th>
                            <th>Pemohon</th>
                            <th>Nama GSE</th>
                            <th>No. Asset</th>
                            <th>Kategori</th>
                            <th>Tahap</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($item_masuk)): ?>
                        <tr><td colspan="7" class="p-0">
                            <div class="empty-state">
                                <i class="bi bi-check2-circle"></i>
                                <p class="mb-0 fw-semibold">Tidak ada unit GSE yang menunggu</p>
                            </div>
                        </td></tr>
                        <?php else: foreach ($item_masuk as $it):
                            $is_sparepart = (($it->jenis_item ?? '') === 'Sparepart' || strtolower($it->manufacture_type ?? '') === 'sparepart' || ($it->jenis_permohonan ?? '') === 'Masuk Perbaikan Sparepart');
                            $is_motor = !$is_sparepart && (strtolower($it->manufacture_type ?? '') === 'motorized');
                            $label_tahap = [
                                'operasi' => 'Operasi', 'equipment' => 'Equipment',
                                'sales' => 'Sales', 'security' => 'Security',
                            ];
                        ?>
                        <tr>
                            <td data-label="No. Permohonan"><span class="doc-ref"><i class="bi bi-file-earmark-text"></i><?= htmlspecialchars($it->nomor_permohonan) ?></span></td>
                            <td data-label="Pemohon" class="small text-muted"><?= htmlspecialchars($it->asal_instansi ?? '-') ?></td>
                            <td data-label="Nama GSE"><?= htmlspecialchars($it->nama_gse) ?></td>
                            <td data-label="No. Asset" class="small"><?= htmlspecialchars($it->no_asset ?: ($it->tipe_permohonan_gse ?: '-')) ?></td>
                            <td data-label="Kategori">
                                <?php if ($is_sparepart): ?>
                                    <span class="badge bg-warning text-dark stage-pill">Sparepart</span>
                                <?php else: ?>
                                    <span class="badge <?= $is_motor ? 'bg-danger' : 'bg-secondary' ?> stage-pill"><?= $is_motor ? 'Motorized' : 'Non-Motorized' ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Tahap"><span class="badge-soft badge-status-Menunggu"><?= $label_tahap[$it->tahap_saat_ini] ?? $it->tahap_saat_ini ?></span></td>
                            <td class="action-cell">
                            <div class="row-actions">
                                <a href="<?= site_url('permohonan_masuk/detail/' . $it->id_permohonan_masuk) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <?php
                                    $menunggu_pass = !$is_sparepart
                                        && ($it->tahap_saat_ini === 'security')
                                        && $is_motor
                                        && empty($it->file_pass_kendaraan);
                                ?>
                                <?php if ($menunggu_pass): ?>
                                    <span class="badge bg-warning text-dark py-2 px-3">
                                        <i class="bi bi-hourglass-split"></i> Menunggu Pass Kendaraan dari GH
                                    </span>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="bukaModalTolakItem(<?= $it->id ?>)">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                <?php elseif ($user_role !== 'admin'): ?>
                                    <?php if ($it->tahap_saat_ini === 'equipment' && $user_role === 'unit_equipment'): ?>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalUjiLaik(<?= $it->id ?>)">
                                            <i class="bi bi-upload"></i> Upload BA Laik
                                        </button>
                                    <?php elseif ($it->tahap_saat_ini === 'sales' && $user_role === 'unit_sales'):
                                        // Nilai sudah disiapkan di controller, tidak perlu panggil model dari view
                                        $tahun_kontrak_baru_it = $it->tahun_kontrak_baru ?? null;
                                    ?>
                                        <?php
                                        $onclick_args = implode(', ', [
                                            (int) $it->id,
                                            json_encode($it->masa_mulai ?? null),
                                            json_encode($it->masa_selesai ?? null),
                                            json_encode($it->dimensi_p ?? null),
                                            json_encode($it->dimensi_l ?? null),
                                            json_encode($tahun_kontrak_baru_it),
                                        ]);
                                        ?>
                                        <button type="button" class="btn btn-sm btn-warning"
                                                onclick="bukaModalDimensi(<?= htmlspecialchars($onclick_args, ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="bi bi-rulers"></i> Input Dimensi
                                        </button>

                                        <?php elseif ($it->tahap_saat_ini === 'operasi' && $user_role === 'unit_operasi' && empty($it->file_dispo) && empty($it->permohonan_file_dispo)): ?>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalDispo(<?= $it->id ?>)">
                                            <i class="bi bi-upload"></i> Upload Dispo
                                        </button>

                                    <?php else: ?>
                                        <form method="post" action="<?= site_url('approval/setujui_item/' . $it->id) ?>" style="display:inline;" onsubmit="return confirm('Setujui unit GSE ini?')">
                                            <input type="hidden" name="tahap_sekarang" value="<?= $it->tahap_saat_ini ?>">
                                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
                                        </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="bukaModalTolakItem(<?= $it->id ?>)">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
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
</div>
<div class="modal fade" id="modalTolakItem" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title mb-0"><i class="bi bi-x-circle me-1"></i> Tolak Unit GSE</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTolakItem" method="post">
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

<script>
function bukaModalTolakItem(idDetail) {
    document.getElementById('formTolakItem').action = "<?= site_url('approval/tolak_item/') ?>" + idDetail;
    new bootstrap.Modal(document.getElementById('modalTolakItem')).show();
}
</script>

<!-- TAB SURAT KELUAR -->
<div id="tab-keluar" class="tab-pane-custom">
    <div class="card border-0">
        <div class="table-card-head">
            <h6><i class="bi bi-envelope-arrow-up me-1"></i> Unit Keluar Menunggu Verifikasi</h6>
            <span class="count-pill"><?= count($item_keluar) ?> unit</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Permohonan</th>
                            <th>Pemohon</th>
                            <th>Nama GSE</th>
                            <th>No. Asset</th>
                            <th>Kategori</th>
                            <th>Tahap</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($item_keluar)): ?>
                        <tr><td colspan="7" class="p-0">
                            <div class="empty-state">
                                <i class="bi bi-check2-circle"></i>
                                <p class="mb-0 fw-semibold">Tidak ada unit GSE keluar yang menunggu</p>
                            </div>
                        </td></tr>
                        <?php else: foreach ($item_keluar as $it):
                            $label_tahap = [
                                'operasi' => 'Operasi', 'equipment' => 'Equipment',
                                'sales' => 'Sales', 'security' => 'Security',
                            ];
                        ?>
                        <tr>
                            <td data-label="No. Permohonan"><span class="doc-ref"><i class="bi bi-file-earmark-text"></i><?= htmlspecialchars($it->nomor_permohonan) ?></span></td>
                            <td data-label="Pemohon" class="small text-muted"><?= htmlspecialchars($it->asal_instansi ?? '-') ?></td>
                            <td data-label="Nama GSE"><?= htmlspecialchars($it->nama_gse) ?></td>
                            <td data-label="No. Asset" class="small"><?= htmlspecialchars($it->no_asset ?? '-') ?></td>
                            <td data-label="Kategori"><span class="badge <?= strtolower($it->manufacture_type ?? '') === 'motorized' ? 'bg-danger' : 'bg-secondary' ?> stage-pill"><?= strtolower($it->manufacture_type ?? '') === 'motorized' ? 'Motorized' : 'Non-Motorized' ?></span></td>
                            <td data-label="Tahap">
                                <span class="badge-soft badge-status-Menunggu">
                                <?= $label_tahap[$it->tahap_saat_ini] ?? $it->tahap_saat_ini ?>
                            </span>
                        </td>
                        <td class="action-cell">
                            <div class="row-actions">
                                <a href="<?= site_url('permohonan_keluar/detail/' . $it->id_permohonan_keluar) ?>"
                                class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                    <?php if ($user_role !== 'admin'): ?>
                                    <?php if ($it->tahap_saat_ini === 'operasi' && $user_role === 'unit_operasi' && empty($it->file_dispo) && empty($it->permohonan_file_dispo)): ?>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalDispoKeluar(<?= $it->id ?>)">
                                            <i class="bi bi-upload"></i> Upload Dispo
                                        </button>
                                    <?php else: ?>
                                <form method="post" action="<?= site_url('approval/setujui_item_keluar/' . $it->id) ?>"
                                    style="display:inline;" onsubmit="return confirm('Setujui unit GSE ini?')">
                                    <input type="hidden" name="tahap_sekarang" value="<?= $it->tahap_saat_ini ?>">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-lg"></i> Setujui
                                    </button>
                                </form>
                                    <?php endif; ?>
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="bukaModalTolakItemKeluar(<?= $it->id ?>)">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<script>
function bukaModalTolakItemKeluar(idDetail) {
    document.getElementById('formTolakItem').action = "<?= site_url('approval/tolak_item_keluar/') ?>" + idDetail;
    new bootstrap.Modal(document.getElementById('modalTolakItem')).show();
}

function switchTab(id, el) {
    document.querySelectorAll('.tab-pane-custom').forEach(p => p.classList.remove('show'));
    document.querySelectorAll('.jenis-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('show');
    el.classList.add('active');
}

function bukaModalTolak(tipe, id, gseList) {
    document.getElementById('formTolak').action =
        "<?= site_url('approval/tolak/') ?>" + tipe + '/' + id;

    var wrap = document.getElementById('tolakItemWrap');
    var list = document.getElementById('tolakItemList');
    list.innerHTML = '';

    if (tipe === 'masuk' && gseList && gseList.length > 0) {
        gseList.forEach(function (g) {
            var div = document.createElement('div');
            div.className = 'form-check';

            var input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input';
            input.name = 'item_ditolak[]';
            input.value = g.id;
            input.id = 'itemDitolak' + g.id;

            var label = document.createElement('label');
            label.className = 'form-check-label';
            label.setAttribute('for', 'itemDitolak' + g.id);
            label.textContent = g.nama_gse + (g.no_asset ? ' (' + g.no_asset + ')' : '');

            div.appendChild(input);
            div.appendChild(label);
            list.appendChild(div);
        });
        wrap.style.display = '';
    } else {
        wrap.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('modalTolak')).show();
}

function bukaModalUjiLaik(id) {
    document.getElementById('inputBaUjiLaik').value = '';
    document.getElementById('fileFeedback').textContent = '';
    document.getElementById('fileFeedback').className = 'file-feedback';
    document.getElementById('btnUploadSubmit').disabled = true;

    document.getElementById('formUjiLaik').action =
        '<?= site_url('approval/upload_ba_uji_laik/') ?>' + id;
    new bootstrap.Modal(document.getElementById('modalUjiLaik')).show();
}

function bukaModalDispo(id) {
    document.getElementById('inputDispo').value = '';
    document.getElementById('formDispo').action =
        '<?= site_url('approval/upload_dispo_item/') ?>' + id;
    new bootstrap.Modal(document.getElementById('modalDispo')).show();
}

function bukaModalDispoKeluar(id) {
    document.getElementById('inputDispoKeluar').value = '';
    document.getElementById('formDispoKeluar').action =
        '<?= site_url('approval/upload_dispo_item_keluar/') ?>' + id;
    new bootstrap.Modal(document.getElementById('modalDispoKeluar')).show();
}

var fpMulai2   = null;
var fpSelesai2 = null;

document.addEventListener('DOMContentLoaded', function () {
    var elMulai   = document.getElementById('dimMulai2');
    var elSelesai = document.getElementById('dimSelesai2');

    if (elMulai) {
        fpMulai2 = flatpickr(elMulai, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y'
        });
    }
    if (elSelesai) {
        fpSelesai2 = flatpickr(elSelesai, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y'
        });
    }
});

function bukaModalDimensi(idDetail, masaMulaiDiajukan, masaSelesaiDiajukan, pDiajukan, lDiajukan, tahunKontrakBaru) {
    document.getElementById('dimP2').value = pDiajukan || '';
    document.getElementById('dimL2').value = lDiajukan || '';
    hitungLuas2();

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

    if (fpMulai2 && fpSelesai2) {
        fpMulai2.setDate(defMulai, true);
        fpSelesai2.setDate(defSelesai, true);
    } else {
        document.getElementById('dimMulai2').value   = defMulai;
        document.getElementById('dimSelesai2').value = defSelesai;
    }

    document.getElementById('formDimensi').action = '<?= site_url('approval/simpan_dimensi_item/') ?>' + idDetail;
    new bootstrap.Modal(document.getElementById('modalDimensi')).show();
}

function hitungLuas2() {
    var p = parseFloat(document.getElementById('dimP2').value) || 0;
    var l = parseFloat(document.getElementById('dimL2').value) || 0;
    document.getElementById('dimLuas2').value = (p * l).toFixed(2);
}

function validasiFile(input) {
    var feedback = document.getElementById('fileFeedback');
    var btn      = document.getElementById('btnUploadSubmit');
    var allowed  = ['jpg', 'jpeg', 'png', 'pdf'];
    var maxSize  = 500 * 1024; // 500KB

    if (!input.files || !input.files[0]) {
        feedback.textContent = '';
        btn.disabled = true;
        return;
    }

    var file = input.files[0];
    var ext  = file.name.split('.').pop().toLowerCase();
    var size = file.size;

    if (!allowed.includes(ext)) {
        feedback.textContent = '✗ Format tidak didukung. Gunakan JPG, PNG, atau PDF.';
        feedback.className   = 'file-feedback invalid';
        btn.disabled = true;
        input.value  = '';
        return;
    }

    if (size > maxSize) {
        var kb = (size / 1024).toFixed(0);
        feedback.textContent = '✗ Ukuran file ' + kb + 'KB melebihi batas 500KB.';
        feedback.className   = 'file-feedback invalid';
        btn.disabled = true;
        input.value  = '';
        return;
    }

    // File valid
    var kb = (size / 1024).toFixed(0);
    feedback.textContent = '✓ ' + file.name + ' (' + kb + 'KB) — siap diupload.';
    feedback.className   = 'file-feedback valid';
    btn.disabled = false;
}
</script>