<style>
    .back-link{ display:inline-flex; align-items:center; gap:.35rem; font-size:.85rem; font-weight:600; color:var(--teal-700); text-decoration:none; margin-bottom:.6rem; }
    .back-link:hover{ color:var(--teal-600); }
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.92rem; margin-bottom:0; }
    .form-card{ max-width:800px; }
    .form-section-label{ font-size:.74rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; color:var(--teal-700); margin:1.6rem 0 .9rem; }
    .form-section-label:first-child{ margin-top:0; }
    .form-section-label::before{ content:""; display:inline-block; width:8px; height:8px; border-radius:50%; background:var(--gold-500); margin-right:.5rem; }
    .form-label{ font-weight:600; font-size:.86rem; color:var(--ink-900); }
    .req{ color:#C0392B; margin-left:.15rem; }
    .form-control, .form-select{ border-color:var(--line); border-radius:.6rem; padding:.55rem .8rem; }
    .form-control:focus, .form-select:focus{ border-color:var(--teal-600); box-shadow:0 0 0 .2rem rgba(13,124,133,.13); }
    .form-actions{ margin-top:1.6rem; padding-top:1.25rem; border-top:1px solid var(--line); display:flex; gap:.6rem; }
    .btn-light{ border:1px solid var(--line); background:#fff; }
    .attachment-box{ border:1px solid #F5DDB0; background:#FFFBF3; border-radius:.85rem; padding:1rem; margin-top:.75rem; }
    .alert-info{ background:#E6F6F6; color:var(--teal-700); border:0; border-radius:.75rem; }
    .gse-item{ background:#FAFCFC; border:1px solid var(--line); border-radius:.85rem; padding:1.25rem; margin-bottom:1.25rem; }
    .gse-item-title{ font-weight:700; font-size:.92rem; color:var(--teal-800); margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
    .gse-item-title::before{ content:""; display:inline-block; width:6px; height:18px; background:var(--gold-500); border-radius:3px; }
    .terisi-item{ display:flex; align-items:center; justify-content:space-between; padding:.65rem .9rem; background:#fff; border:1px solid var(--line); border-radius:.65rem; margin-bottom:.5rem; font-size:.86rem; }
    .terisi-item .nama{ font-weight:600; color:var(--ink-900); }
    .kelengkapan-btn{ display:inline-flex; align-items:center; gap:.4rem; padding:.35rem .85rem; border-radius:.6rem; font-size:.82rem; font-weight:600; text-decoration:none; border:1px solid #c0daf0; color:#18558a; background:#e8f4fc; margin-bottom:1rem; }
    .kelengkapan-btn.lengkap{ background:#e6f8f0; color:#0e6b38; border-color:#a8e6c8; }
    .jenis-item-selector{ display:grid; grid-template-columns:1fr 1fr 1fr; gap:.6rem; margin-bottom:1rem; }
    .jenis-item-opt input[type=radio]{ display:none; }
    .jenis-item-opt label{ display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.3rem; padding:.75rem .5rem; border:2px solid var(--line); border-radius:.75rem; cursor:pointer; transition:all .15s; text-align:center; font-weight:600; font-size:.82rem; color:var(--ink-600); background:#fff; height:100%; }
    .jenis-item-opt label i{ font-size:1.2rem; color:var(--ink-400); }
    .jenis-item-opt input:checked + label{ border-color:var(--teal-600); background:var(--mint-50); color:var(--teal-700); }
    .jenis-item-opt input:checked + label i{ color:var(--teal-600); }
</style>

<?php
$total        = (int) $permohonan->jumlah_unit_gse;
$sudah_terisi = $sudah_terisi ?? 0;
$sisa         = $sisa ?? max(0, $total - $sudah_terisi);
$lengkap      = $sisa === 0;
$is_sparepart_perm = ($permohonan->jenis_permohonan === 'Masuk Perbaikan Sparepart' || $permohonan->jenis_permohonan === 'Sparepart');
?>

<a href="<?= site_url('permohonan_masuk') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Permohonan Masuk</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-card-checklist"></i> Input Kelengkapan Data <?= $is_sparepart_perm ? 'Sparepart' : 'Unit GSE' ?></h5>
        <p class="page-subtitle">
            Permohonan <strong><?= htmlspecialchars($permohonan->nomor_permohonan) ?></strong>
            (Total <?= $total ?> item)
        </p>
    </div>
</div>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle-fill"></i> <?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm form-card">
    <div class="card-body p-4">

        <button type="button" class="kelengkapan-btn <?= $lengkap ? 'lengkap' : '' ?>" disabled>
            <i class="bi <?= $lengkap ? 'bi-check-circle-fill' : 'bi-hourglass-split' ?>"></i>
            Kelengkapan (<?= $sudah_terisi ?> dari <?= $total ?> <?= $is_sparepart_perm ? 'Item Sparepart' : 'Unit GSE' ?>)
        </button>

        <?php if (!empty($daftar_gse_terisi)): ?>
        <div class="form-section-label" style="margin-top:0">Item yang Sudah Diisi</div>
        <?php foreach ($daftar_gse_terisi as $g): ?>
        <div class="terisi-item">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span class="nama"><?= htmlspecialchars($g->nama_gse) ?></span>
            <span class="badge bg-secondary"><?= htmlspecialchars($g->manufacture_type ?? '-') ?></span>
            <span class="badge bg-light text-dark border">
                <?php 
                if (($g->jenis_item ?? '') === 'Sparepart') echo 'Sparepart';
                elseif (($g->jenis_item ?? '') === 'Perbaikan') echo 'Perbaikan Unit';
                else echo 'Masuk Baru';
                ?>
            </span>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($sisa > 0): ?>

            <div class="form-section-label">Input Item #<?= $sudah_terisi + 1 ?> (Sisa <?= $sisa ?> Item)</div>

            <?= form_open_multipart('permohonan_masuk/simpan_kelengkapan/' . $permohonan->id_permohonan_masuk) ?>
            <input type="hidden" name="mode_kelengkapan" value="<?= htmlspecialchars($mode ?? 'normal') ?>">

            <?php if (!$is_sparepart_perm): ?>
            <div class="jenis-item-selector">
                <div class="jenis-item-opt">
                    <input type="radio" name="jenis_item" id="jenisItemBaru" value="Masuk Baru" checked
                           onchange="switchJenisItem('Masuk Baru')">
                    <label for="jenisItemBaru"><i class="bi bi-box-arrow-in-right"></i> Masuk Baru</label>
                </div>
                <div class="jenis-item-opt">
                    <input type="radio" name="jenis_item" id="jenisItemPerbaikan" value="Perbaikan"
                           onchange="switchJenisItem('Perbaikan')">
                    <label for="jenisItemPerbaikan"><i class="bi bi-tools"></i> Masuk Setelah Perbaikan</label>
                </div>
                <div class="jenis-item-opt">
                    <input type="radio" name="jenis_item" id="jenisItemSparepart" value="Sparepart"
                           onchange="switchJenisItem('Sparepart')">
                    <label for="jenisItemSparepart"><i class="bi bi-gear-wide-connected"></i> Perbaikan Sparepart</label>
                </div>
            </div>
            <?php else: ?>
                <input type="hidden" name="jenis_item" value="Sparepart">
            <?php endif; ?>

            <div class="gse-item">
                <div class="gse-item-title">Data Item #<?= $sudah_terisi + 1 ?></div>

                <!-- ═══ Bagian khusus jenis "Sparepart" ═══ -->
                <div id="itemSparepart" class="<?= $is_sparepart_perm ? '' : 'd-none' ?>">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Nama Sparepart / Komponen<span class="req">*</span></label>
                            <input type="text" name="nama_sparepart" id="nama_sparepart_input" class="form-control"
                                   placeholder="Contoh: Ban Depan Ring 16 / Alternator 24V / Filter Solar">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Jumlah / Qty<span class="req">*</span></label>
                            <input type="text" name="qty_sparepart" id="qty_sparepart_input" class="form-control"
                                   placeholder="Contoh: 2 Pcs / 1 Set / 4 Buah" value="1 Pcs">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Unit GSE yang Akan Diperbaiki (No. Asset / Nama Unit)<span class="req">*</span></label>

                            <?php if (!empty($daftar_laporan_kerusakan)): ?>
                                <div class="mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i class="bi bi-tools"></i></span>
                                        <select id="pilih_gse_kerusakan" class="form-select bg-light" onchange="onSelectGseKerusakan(this)">
                                            <option value="">-- Pilih dari Kendaraan GSE Laporan Kerusakan --</option>
                                            <?php foreach ($daftar_laporan_kerusakan as $lk): 
                                                if ($lk->status === 'Sudah Ditindak' && (empty($laporan_kerusakan) || $laporan_kerusakan->id != $lk->id)) {
                                                    continue;
                                                }
                                                $is_selected = (!empty($laporan_kerusakan) && $laporan_kerusakan->id == $lk->id);
                                                $gse_text = trim($lk->nama_gse . (!empty($lk->sticker_ap) ? ' (' . $lk->sticker_ap . ')' : ''));
                                                $badge_status = ($lk->status === 'Baru') ? '[Belum Ditindak]' : '[' . $lk->status . ']';
                                            ?>
                                                <option value="<?= htmlspecialchars($gse_text) ?>"
                                                        data-id="<?= $lk->id ?>"
                                                        data-nama="<?= htmlspecialchars($lk->nama_gse) ?>"
                                                        data-stiker="<?= htmlspecialchars($lk->sticker_ap) ?>"
                                                        data-tingkat="<?= htmlspecialchars($lk->tingkat_kerusakan) ?>"
                                                        data-ket="<?= htmlspecialchars($lk->keterangan) ?>"
                                                        data-sp='<?= htmlspecialchars($lk->kebutuhan_sparepart ?? '[]', ENT_QUOTES, 'UTF-8') ?>'
                                                        <?= $is_selected ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($gse_text . ' — ' . $lk->tingkat_kerusakan . ' ' . $badge_status) ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="__manual__">-- Input Manual / Unit Lainnya --</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <input type="hidden" name="id_laporan_kerusakan_item" id="id_laporan_kerusakan_item" value="<?= !empty($laporan_kerusakan) ? $laporan_kerusakan->id : '' ?>">
                            <input type="text" name="gse_terkait" id="gse_terkait_input" class="form-control"
                                   value="<?= !empty($laporan_kerusakan) ? htmlspecialchars(trim($laporan_kerusakan->nama_gse . (!empty($laporan_kerusakan->sticker_ap) ? ' (' . $laporan_kerusakan->sticker_ap . ')' : ''))) : '' ?>"
                                   placeholder="Contoh: Towing Tractor TT-02 (AP-GSE-0045) atau pilih dari daftar di atas">
                            <div class="form-text">Pilih kendaraan dari laporan kerusakan di atas atau ketik nama/stiker unit secara manual.</div>

                            <!-- Alert Info Unit Kerusakan & Rekomendasi Suku Cadang -->
                            <div id="info_unit_kerusakan" class="alert alert-info py-2 px-3 mt-2 small d-none">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-exclamation-diamond-fill text-warning fs-5 mt-1"></i>
                                    <div class="w-100">
                                        <div id="info_unit_kerusakan_text"></div>
                                        <div id="rekomendasi_sparepart_box" class="mt-2 pt-2 border-top border-info-subtle d-none">
                                            <span class="fw-semibold text-dark">Rekomendasi suku cadang dari laporan kerusakan (klik untuk mengisi otomatis):</span>
                                            <div id="rekomendasi_sparepart_chips" class="d-flex flex-wrap gap-1 mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan / Rincian Perbaikan</label>
                            <textarea name="keterangan" id="keterangan_sparepart_input" class="form-control" rows="2"
                                      placeholder="Rincian perbaikan atau spesifikasi sparepart..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- ═══ Bagian khusus jenis "Perbaikan" Unit GSE ═══ -->
                <div id="itemPerbaikan" class="d-none">
                    <div class="row g-3">
                        <div class="col-12 position-relative">
                            <label class="form-label">Pilih Unit GSE (No. Asset)<span class="req">*</span></label>
                            <input type="text" id="cariGseAsal" class="form-control" autocomplete="off"
                                placeholder="Ketik nama GSE atau No. Asset...">
                            <input type="hidden" name="id_gse_asal" id="id_gse_asal_input">
                            <div id="gseAsalOptions" class="list-group shadow-sm"
                                style="display:none; position:absolute; z-index:20; width:100%; max-height:240px; overflow-y:auto;"></div>
                            <div class="form-text">Daftar ini diambil dari unit GSE yang sedang keluar untuk perbaikan (status "Tidak Aktif") milik airline Anda.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama GSE</label>
                            <input type="text" id="namaGseAsalDisplay" class="form-control" value="" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Manufacture Type</label>
                            <input type="text" id="manufactureTypeAsalDisplay" class="form-control" value="" disabled>
                        </div>
                    </div>

                    <div class="attachment-box mt-2">
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label small">No. Rangka Lama</label>
                                <input type="text" id="nomorRangkaLamaDisplay" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">No. Mesin Lama</label>
                                <input type="text" id="nomorMesinLamaDisplay" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="ada_perubahan_nomor"
                                id="adaPerubahanNomor" value="1" onchange="togglePerubahanNomor(this)">
                            <label class="form-check-label" for="adaPerubahanNomor">
                                Ada perubahan Nomor Rangka / Nomor Mesin pada unit ini? <span class="text-muted">(opsional)</span>
                            </label>
                        </div>

                        <div id="boxPerubahanNomor" class="d-none">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Rangka Baru <span class="text-muted small">(isi jika berubah)</span></label>
                                    <input type="text" name="nomor_rangka_baru" class="form-control"
                                        placeholder="Kosongkan jika tidak berubah">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Mesin Baru <span class="text-muted small">(isi jika berubah)</span></label>
                                    <input type="text" name="nomor_mesin_baru" class="form-control"
                                        placeholder="Kosongkan jika tidak berubah">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Dokumentasi Perubahan Rangka</label>
                                    <input type="file" name="file_perubahan_rangka" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="form-text">Foto/dokumen bukti perubahan nomor rangka(PDF/JPG/PNG, maks. 500KB).</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Dokumentasi Perubahan Mesin</label>
                                    <input type="file" name="file_perubahan_mesin" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="form-text">Foto/dokumen bukti perubahan nomor mesin(PDF/JPG/PNG, maks. 500KB).</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="attachment-box mt-2">
                        <div class="alert alert-info py-2 mb-3 small">
                            <i class="bi bi-info-circle"></i> Wajib melampirkan Bukti Perbaikan untuk unit ini (PDF/JPG/PNG, maks. 500KB):
                        </div>
                        <label class="form-label">Bukti Perbaikan<span class="req">*</span></label>
                        <input type="file" name="file_bukti_perbaikan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>

                <!-- ═══ Bagian khusus jenis "Masuk Baru" Unit GSE ═══ -->
                <div id="itemBaru" class="<?= $is_sparepart_perm ? 'd-none' : '' ?>">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama GSE<span class="req">*</span></label>
                            <input type="text" name="nama_gse" class="form-control" placeholder="Contoh: Towing Bar">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Manufacture Type / No. Seri<span class="req">*</span></label>
                            <select name="manufacture_type" id="manufacture_type" class="form-select" onchange="cekKategori(this)">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Motorized">Motorized</option>
                                <option value="Non Motorized">Non Motorized</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">No. Asset <span class="text-muted small fw-normal">(Opsional - Kosongkan jika ingin dibuat otomatis)</span></label>
                            <input type="text" name="no_asset" id="input_no_asset" class="form-control"
                                   placeholder="Ketik No. Asset manual atau kosongkan untuk generate otomatis"
                                   oninput="toggleBuktiNoAsset(this)">
                            <div class="form-text text-muted">
                                Jika No. Asset diketik manual, Anda wajib melampirkan file/foto bukti No. Asset di bawah ini.
                            </div>
                        </div>

                        <!-- Form Lampiran Bukti No. Asset jika diketik manual -->
                        <div class="col-12 d-none" id="box_bukti_no_asset">
                            <div class="attachment-box mt-1 border-warning bg-light">
                                <div class="alert alert-warning py-2 mb-2 small">
                                    <i class="bi bi-exclamation-circle-fill text-warning"></i>
                                    Anda menginputkan No. Asset secara manual. Wajib melampirkan file/foto bukti No. Asset (PDF/JPG/PNG, maks. 500KB).
                                </div>
                                <label class="form-label small fw-semibold">Bukti File No. Asset <span class="req">*</span></label>
                                <input type="file" name="file_nomor_asset" id="file_nomor_asset" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                        </div>
                    </div>

                    <?php if (($mode ?? 'normal') !== 'ajukan_ulang'): ?>
                    <div id="lampiran-box" class="d-none">
                        <div class="attachment-box mt-2">
                            <div class="alert alert-info py-2 mb-3 small">
                                <i class="bi bi-info-circle"></i> GSE Motorized wajib melampirkan dokumen (PDF/JPG/PNG, maks. 500KB):
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">No. Rangka <span class="text-danger">(Tidak Wajib)</span></label>
                                    <input type="text" name="nomor_rangka" class="form-control"
                                        placeholder="Contoh: MH1JF5110PK123456">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Mesin <span class="text-danger">(Tidak Wajib)</span></label>
                                    <input type="text" name="nomor_mesin" class="form-control"
                                        placeholder="Contoh: 2GR-FE1234567">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Foto No. Rangka <span class="req">(Tidak Wajib)</span></label>
                                    <input type="file" name="file_foto_rangka" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Foto No. Mesin<span class="req">(Tidak Wajib)</span></label>
                                    <input type="file" name="file_foto_mesin" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Fotocopy STNK<span class="req">(Tidak Wajib)</span></label>
                                    <input type="file" name="file_stnk" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Fotocopy KTP<span class="req"></span></label>
                                    <input type="file" name="file_ktp" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Fotocopy TIM<span class="req"></span></label>
                                    <input type="file" name="file_tim" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Fotocopy SIM (Driver)<span class="req"></span></label>
                                    <input type="file" name="file_sim" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Surat Keterangan Uji Emisi<span class="req"></span></label>
                                    <input type="file" name="file_emisi" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Surat Keterangan Penugasan<span class="req"></span></label>
                                    <input type="file" name="file_penugasan" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Surat Rekomendasi Laik Bengkel<span class="req"></span></label>
                                    <input type="file" name="file_rekomendasi_bengkel_luar" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row g-3 mt-1">
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="fieldKeterangan" class="form-control" rows="2"
                                    placeholder="Keterangan untuk unit ini"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Lampiran Foto (Untuk Unit GSE & Sparepart) -->
                <div class="attachment-box mt-2" id="box-foto-umum">
                    <div class="alert alert-info py-2 mb-3 small">
                        <i class="bi bi-info-circle"></i> Wajib melampirkan foto <?= $is_sparepart_perm ? 'Sparepart / Dokumen' : 'Unit GSE' ?> (PDF/JPG/PNG, maks. 500KB):
                    </div>
                    <label class="form-label"><?= $is_sparepart_perm ? 'Foto Sparepart' : 'Foto Unit GSE' ?><span class="req">*</span></label>
                    <input type="file" name="file_foto_gse" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Item Ini</button>
                <a href="<?= site_url('permohonan_masuk') ?>" class="btn btn-light">Selesai Nanti</a>
            </div>

            <?= form_close() ?>

        <?php else: ?>

            <div class="alert alert-success py-2 mt-2">
                <i class="bi bi-check-circle"></i> Semua item permohonan sudah lengkap diisi.
            </div>
            <a href="<?= site_url('permohonan_masuk') ?>" class="btn btn-light">Kembali</a>

        <?php endif; ?>

    </div>
</div>

<script>
function switchJenisItem(jenis) {
    var baru      = document.getElementById('itemBaru');
    var perbaikan = document.getElementById('itemPerbaikan');
    var sparepart = document.getElementById('itemSparepart');
    var namaGse   = document.querySelector('#itemBaru [name="nama_gse"]');
    var tipeGse   = document.querySelector('#itemBaru [name="manufacture_type"]');
    var namaSp    = document.getElementById('nama_sparepart_input');
    var fileBuktiPerbaikan = document.querySelector('#itemPerbaikan [name="file_bukti_perbaikan"]');
    var cariGseAsal = document.getElementById('cariGseAsal');

    var ketDefault = { 'Masuk Baru': 'Penambahan Alat', 'Perbaikan': 'Masuk Kembali Setelah Perbaikan', 'Sparepart': 'Perbaikan Sparepart GSE' };
    var ketField = document.getElementById('fieldKeterangan');
    if (ketField) {
        ketField.value = ketDefault[jenis] || '';
    }

    var gseTerkait = document.getElementById('gse_terkait_input');

    var fileNomorAsset = document.getElementById('file_nomor_asset');

    if (jenis === 'Sparepart') {
        if (baru) baru.classList.add('d-none');
        if (perbaikan) perbaikan.classList.add('d-none');
        if (sparepart) sparepart.classList.remove('d-none');
        if (namaGse) namaGse.removeAttribute('required');
        if (tipeGse) tipeGse.removeAttribute('required');
        if (fileNomorAsset) fileNomorAsset.removeAttribute('required');
        if (namaSp) namaSp.setAttribute('required', 'required');
        if (fileBuktiPerbaikan) fileBuktiPerbaikan.removeAttribute('required');
        if (cariGseAsal) cariGseAsal.removeAttribute('required');
        if (gseTerkait) gseTerkait.setAttribute('required', 'required');
        toggleBuktiNoAsset(null);
    } else if (jenis === 'Perbaikan') {
        if (baru) baru.classList.add('d-none');
        if (perbaikan) perbaikan.classList.remove('d-none');
        if (sparepart) sparepart.classList.add('d-none');
        if (namaGse) namaGse.removeAttribute('required');
        if (tipeGse) tipeGse.removeAttribute('required');
        if (fileNomorAsset) fileNomorAsset.removeAttribute('required');
        if (namaSp) namaSp.removeAttribute('required');
        if (fileBuktiPerbaikan) fileBuktiPerbaikan.setAttribute('required', 'required');
        if (cariGseAsal) cariGseAsal.setAttribute('required', 'required');
        if (gseTerkait) gseTerkait.removeAttribute('required');
        toggleBuktiNoAsset(null);
    } else {
        if (perbaikan) perbaikan.classList.add('d-none');
        if (sparepart) sparepart.classList.add('d-none');
        if (baru) baru.classList.remove('d-none');
        if (namaGse) namaGse.setAttribute('required', 'required');
        if (tipeGse) tipeGse.setAttribute('required', 'required');
        if (namaSp) namaSp.removeAttribute('required');
        if (fileBuktiPerbaikan) fileBuktiPerbaikan.removeAttribute('required');
        if (cariGseAsal) cariGseAsal.removeAttribute('required');
        if (gseTerkait) gseTerkait.removeAttribute('required');
        toggleBuktiNoAsset(document.getElementById('input_no_asset'));
    }
}

function toggleBuktiNoAsset(el) {
    var box = document.getElementById('box_bukti_no_asset');
    var fileInput = document.getElementById('file_nomor_asset');
    if (!box) return;
    var jenisAktif = document.querySelector('input[name="jenis_item"]:checked');
    var isMasukBaru = !jenisAktif || jenisAktif.value === 'Masuk Baru';
    var inputAsset = el || document.getElementById('input_no_asset');
    var val = (inputAsset ? inputAsset.value : '').trim();

    if (val !== '' && isMasukBaru) {
        box.classList.remove('d-none');
        if (fileInput) fileInput.setAttribute('required', 'required');
    } else {
        box.classList.add('d-none');
        if (fileInput) {
            fileInput.removeAttribute('required');
            fileInput.value = '';
        }
    }
}

function cekKategori(sel) {
    var manufacture = sel.value;
    var box    = document.getElementById('lampiran-box');
    var inputs = box ? box.querySelectorAll('input[type=file]') : [];
    var opsional = ['file_stnk', 'file_foto_rangka', 'file_foto_mesin'];

    if (manufacture === 'Motorized') {
        if (box) box.classList.remove('d-none');
        inputs.forEach(function (i) {
            if (opsional.indexOf(i.name) === -1) {
                i.setAttribute('required', 'required');
            }
        });
    } else {
        if (box) box.classList.add('d-none');
        inputs.forEach(function (i) { i.removeAttribute('required'); i.value = ''; });
    }
}

function togglePerubahanNomor(chk) {
    var box     = document.getElementById('boxPerubahanNomor');
    var file    = document.querySelector('#boxPerubahanNomor [name="file_perubahan_rangka"]');

    if (chk.checked) {
        box.classList.remove('d-none');
    } else {
        box.classList.add('d-none');
        box.querySelectorAll('input[type=text]').forEach(function(i){ i.value = ''; });
        box.querySelectorAll('input[type=file]').forEach(function(i){ i.value = ''; });
    }
}

var daftarGseAsal = <?= json_encode(array_map(function ($g) {
    return [
        'id'           => $g->id_gse,
        'nama'         => $g->nama_gse,
        'no_asset'     => $g->no_asset,
        'tipe'         => $g->manufacture_type,
        'nomor_rangka' => $g->nomor_rangka,
        'nomor_mesin'  => $g->nomor_mesin,
    ];
}, $gse_tidak_aktif ?? [])) ?>;

(function () {
    var input   = document.getElementById('cariGseAsal');
    var hidden  = document.getElementById('id_gse_asal_input');
    var options = document.getElementById('gseAsalOptions');
    if (!input || !hidden || !options) return;

    function labelFor(g) {
        return g.nama + (g.no_asset ? ' - ' + g.no_asset : '') + ' (' + (g.tipe || '-') + ')';
    }

    function renderList(list) {
        options.innerHTML = '';
        if (list.length === 0) {
            var empty = document.createElement('div');
            empty.className = 'list-group-item text-muted small';
            empty.textContent = 'Tidak ada unit yang cocok.';
            options.appendChild(empty);
        } else {
            list.forEach(function (g) {
                var item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action small';
                item.textContent = labelFor(g);
                item.addEventListener('click', function () {
                    hidden.value = g.id;
                    input.value  = labelFor(g);
                    input.classList.remove('is-invalid');
                    document.getElementById('namaGseAsalDisplay').value = g.nama;
                    document.getElementById('manufactureTypeAsalDisplay').value = g.tipe || '-';

                    var displayRangka = document.getElementById('nomorRangkaLamaDisplay');
                    var displayMesin  = document.getElementById('nomorMesinLamaDisplay');
                    if (displayRangka) displayRangka.value = g.nomor_rangka || '-';
                    if (displayMesin)  displayMesin.value  = g.nomor_mesin  || '-';

                    options.style.display = 'none';
                });
                options.appendChild(item);
            });
        }
        options.style.display = 'block';
    }

    input.addEventListener('focus', function () {
        renderList(daftarGseAsal);
    });

    input.addEventListener('input', function () {
        hidden.value = '';
        document.getElementById('namaGseAsalDisplay').value = '';
        document.getElementById('manufactureTypeAsalDisplay').value = '';
        var kw = input.value.toLowerCase().trim();
        var filtered = daftarGseAsal.filter(function (g) {
            return g.nama.toLowerCase().indexOf(kw) !== -1 ||
                   (g.no_asset && g.no_asset.toLowerCase().indexOf(kw) !== -1);
        });
        renderList(filtered);
    });

    document.addEventListener('click', function (e) {
        if (e.target !== input && !options.contains(e.target)) {
            options.style.display = 'none';
        }
    });

    var formEl = input.closest('form');
    if (formEl) {
        formEl.addEventListener('submit', function (e) {
            var jenisAktif = document.querySelector('input[name="jenis_item"]:checked');
            var isPerbaikanAktif = jenisAktif && jenisAktif.value === 'Perbaikan';
            if (isPerbaikanAktif && !hidden.value) {
                e.preventDefault();
                input.classList.add('is-invalid');
                input.focus();
            }
        });
    }
})();

function escapeHtml(text) {
    if (!text) return '';
    var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}

function onSelectGseKerusakan(sel) {
    var opt = sel.options[sel.selectedIndex];
    var inputGse = document.getElementById('gse_terkait_input');
    var inputIdLaporan = document.getElementById('id_laporan_kerusakan_item');
    var infoBox = document.getElementById('info_unit_kerusakan');
    var infoText = document.getElementById('info_unit_kerusakan_text');
    var spBox = document.getElementById('rekomendasi_sparepart_box');
    var spChips = document.getElementById('rekomendasi_sparepart_chips');

    if (!opt || opt.value === '') {
        if (inputIdLaporan) inputIdLaporan.value = '';
        if (infoBox) infoBox.classList.add('d-none');
        return;
    }

    if (opt.value === '__manual__') {
        if (inputGse) {
            inputGse.value = '';
            inputGse.focus();
        }
        if (inputIdLaporan) inputIdLaporan.value = '';
        if (infoBox) infoBox.classList.add('d-none');
        return;
    }

    if (inputGse) {
        inputGse.value = opt.value;
    }
    if (inputIdLaporan) {
        inputIdLaporan.value = opt.getAttribute('data-id') || '';
    }

    // Tampilkan rincian laporan kerusakan
    var nama    = opt.getAttribute('data-nama') || '';
    var stiker  = opt.getAttribute('data-stiker') || '';
    var tingkat = opt.getAttribute('data-tingkat') || '';
    var ket     = opt.getAttribute('data-ket') || '';
    var spJson  = opt.getAttribute('data-sp') || '[]';

    if (infoBox && infoText) {
        var stikerBadge = stiker ? ' <span class="badge bg-dark">' + escapeHtml(stiker) + '</span>' : '';
        var tingkatColor = (tingkat.indexOf('Bahaya') !== -1) ? 'danger' : 'warning';
        infoText.innerHTML = '<div class="fw-bold text-dark">Unit: ' + escapeHtml(nama) + stikerBadge + 
                             ' &bull; <span class="badge bg-' + tingkatColor + '">' + escapeHtml(tingkat) + '</span></div>' +
                             '<div class="text-muted mt-1"><em>Catatan Kerusakan:</em> ' + escapeHtml(ket || '-') + '</div>';
        infoBox.classList.remove('d-none');
    }

    // Tampilkan rekomendasi sparepart jika ada
    if (spBox && spChips) {
        spChips.innerHTML = '';
        try {
            var listSp = JSON.parse(spJson);
            if (Array.isArray(listSp) && listSp.length > 0) {
                listSp.forEach(function(sp) {
                    var chip = document.createElement('button');
                    chip.type = 'button';
                    chip.className = 'btn btn-outline-primary btn-sm py-1 px-2 rounded-pill mt-1 me-1 text-start';
                    chip.style.fontSize = '0.78rem';
                    chip.innerHTML = '<i class="bi bi-arrow-down-left-circle-fill me-1 text-primary"></i><strong>' + 
                                     escapeHtml(sp.nama_sparepart) + '</strong> (' + escapeHtml(sp.qty || '1') + ')';
                    chip.title = 'Klik untuk mengisi form sparepart dengan item ini';
                    chip.addEventListener('click', function() {
                        var inpNama = document.getElementById('nama_sparepart_input');
                        var inpQty  = document.getElementById('qty_sparepart_input');
                        var inpKet  = document.getElementById('keterangan_sparepart_input');
                        if (inpNama) inpNama.value = sp.nama_sparepart || '';
                        if (inpQty && sp.qty)  inpQty.value = sp.qty;
                        if (inpKet && sp.keterangan) inpKet.value = sp.keterangan;
                    });
                    spChips.appendChild(chip);
                });
                spBox.classList.remove('d-none');
            } else {
                spBox.classList.add('d-none');
            }
        } catch (e) {
            spBox.classList.add('d-none');
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var isSp = <?= $is_sparepart_perm ? 'true' : 'false' ?>;
    if (isSp) {
        switchJenisItem('Sparepart');
    } else {
        switchJenisItem('Masuk Baru');
    }

    var selKerusakan = document.getElementById('pilih_gse_kerusakan');
    if (selKerusakan && selKerusakan.selectedIndex > 0 && selKerusakan.value !== '__manual__') {
        onSelectGseKerusakan(selKerusakan);
    }
});
</script>
