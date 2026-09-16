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
    .jenis-selector{ display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:1rem; }
    .jenis-opt input[type=radio]{ display:none; }
    .jenis-opt label{ display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.4rem; padding:1rem .75rem; border:2px solid var(--line); border-radius:.85rem; cursor:pointer; transition:all .15s; text-align:center; font-weight:600; font-size:.86rem; color:var(--ink-600); background:#fff; }
    .jenis-opt label i{ font-size:1.4rem; color:var(--ink-400); }
    .jenis-opt label small{ font-weight:400; font-size:.76rem; color:var(--ink-400); }
    .jenis-opt input:checked + label{ border-color:var(--teal-600); background:var(--mint-50); color:var(--teal-700); }
    .jenis-opt input:checked + label i{ color:var(--teal-600); }
    .jenis-opt input:checked + label small{ color:var(--teal-600); }
    .attachment-box{ border:1px solid #F5DDB0; background:#FFFBF3; border-radius:.85rem; padding:1rem; margin-top:.75rem; }
    .alert-info{ background:#E6F6F6; color:var(--teal-700); border:0; border-radius:.75rem; }
    .info-box{ background:#f0fafa; border:1px solid var(--line); border-radius:.85rem; padding:1rem 1.25rem; }
    .info-box .label{ font-size:.75rem; color:var(--ink-400); font-weight:600; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
    .info-box .value{ font-weight:600; color:var(--ink-900); }
    .gse-tag{ display:inline-flex; align-items:center; gap:.35rem; background:var(--mint-50); border:1px solid var(--line); color:var(--teal-800); font-size:.8rem; font-weight:600; padding:.3rem .7rem; border-radius:999px; margin:.2rem; }
    .form-section{ transition:opacity .2s; }
</style>

<a href="<?= site_url('permohonan_masuk') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Permohonan Masuk</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-envelope-arrow-down"></i> Buat Permohonan Masuk</h5>
        <p class="page-subtitle">Ajukan izin masuk unit GSE atau suku cadang / sparepart perbaikan ke area bandara.</p>
    </div>
</div>

<div class="card border-0 shadow-sm form-card">
    <div class="card-body p-4">

        <?php if (!empty($laporan_kerusakan)): ?>
            <?php 
            $sp_list_count = 1;
            if (!empty($laporan_kerusakan->kebutuhan_sparepart)) {
                $sp_arr = json_decode($laporan_kerusakan->kebutuhan_sparepart, true);
                if (is_array($sp_arr) && count($sp_arr) > 0) {
                    $sp_list_count = count($sp_arr);
                }
            }
            ?>
            <div class="alert alert-warning py-3 mb-4 rounded-3 border-0">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-tools fs-4 text-warning mt-1"></i>
                    <div>
                        <div class="fw-bold mb-1">Rujukan Laporan Kerusakan #<?= $laporan_kerusakan->id ?></div>
                        <div class="small">
                            Unit: <strong><?= htmlspecialchars($laporan_kerusakan->nama_gse) ?></strong> (Stiker: <span class="badge bg-dark"><?= htmlspecialchars($laporan_kerusakan->sticker_ap) ?></span>)<br>
                            Maskapai: <?= htmlspecialchars($laporan_kerusakan->nama_airline ?? '-') ?> | Rincian: <em><?= htmlspecialchars(mb_substr($laporan_kerusakan->keterangan ?? '', 0, 100)) ?></em>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- ════ FORM — Buat Permohonan ════ -->
        <div id="form-masuk_baru" class="form-section">
            <?= form_open_multipart('permohonan_masuk/simpan') ?>
                <?php if (!empty($laporan_kerusakan)): ?>
                    <input type="hidden" name="id_laporan_kerusakan" value="<?= $laporan_kerusakan->id ?>">
                <?php endif; ?>

                <div class="form-section-label">Pilih Jenis Pengajuan</div>
                <div class="jenis-selector">
                    <div class="jenis-opt">
                        <input type="radio" name="jenis_pengajuan" id="opt_unit_gse" value="Masuk Baru"
                               <?= empty($laporan_kerusakan) ? 'checked' : '' ?> onchange="switchJenisPengajuan('Masuk Baru')">
                        <label for="opt_unit_gse">
                            <i class="bi bi-truck"></i>
                            <div>Masuk Unit GSE</div>
                            <small>Unit GSE utuh (Masuk Baru / Setelah Perbaikan)</small>
                        </label>
                    </div>
                    <div class="jenis-opt">
                        <input type="radio" name="jenis_pengajuan" id="opt_sparepart" value="Masuk Perbaikan Sparepart"
                               <?= !empty($laporan_kerusakan) ? 'checked' : '' ?> onchange="switchJenisPengajuan('Masuk Perbaikan Sparepart')">
                        <label for="opt_sparepart">
                            <i class="bi bi-gear-wide-connected"></i>
                            <div>Masuk Perbaikan Sparepart</div>
                            <small>Suku cadang / komponen untuk perbaikan GSE</small>
                        </label>
                    </div>
                </div>

                <div class="form-section-label">Informasi Pengajuan</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Surat<span class="req">*</span></label>
                        <input type="text" name="nomor_surat" class="form-control"
                               placeholder="Contoh: 001/GH-LA/VII/2026" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Masuk<span class="req">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" id="label-jumlah-item">Jumlah Unit GSE yang Diajukan<span class="req">*</span></label>
                        <input type="number" name="jumlah_unit_gse" id="input_jumlah_unit_gse" class="form-control"
                               min="1" step="1" value="<?= !empty($laporan_kerusakan) ? $sp_list_count : 1 ?>" placeholder="Contoh: 2" required>
                        <div class="form-text" id="desc-jumlah-item">Jumlah item yang akan diinput detailnya nanti di menu Kelengkapan.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Asal Instansi</label>
                        <?php if ($user_role === 'ground_handling'): ?>
                            <input type="text" class="form-control"
                                   value="<?= htmlspecialchars($airline->nama_airline ?? '-') ?>" disabled>
                        <?php else: ?>
                            <select name="id_airline" class="form-select" required>
                                <option value="">-- Pilih Maskapai / Instansi --</option>
                                <?php foreach ($this->db->get('airlines')->result() as $al): ?>
                                    <option value="<?= $al->id_airline ?>" <?= (!empty($laporan_kerusakan) && $laporan_kerusakan->id_airline == $al->id_airline) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($al->nama_airline) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-section-label">Lampiran</div>
                <div class="attachment-box">
                    <div class="alert alert-info py-2 mb-3 small">
                        <i class="bi bi-info-circle"></i>
                        Wajib melampirkan Bukti Permohonan (PDF/JPG/PNG, maks. 500KB):
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bukti Permohonan<span class="req">*</span></label>
                            <input type="file" name="file_bukti_permohonan" class="form-control"
                                   accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text">Maks. 500KB</div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="<?= site_url('permohonan_masuk') ?>" class="btn btn-light">Batal</a>
                </div>
            <?= form_close() ?>
        </div>

    </div>
</div>

<script>
function switchJenisPengajuan(jenis) {
    var labelJml = document.getElementById('label-jumlah-item');
    var descJml  = document.getElementById('desc-jumlah-item');
    if (jenis === 'Masuk Perbaikan Sparepart') {
        labelJml.innerHTML = 'Jumlah Item Sparepart yang Diajukan<span class="req">*</span>';
        descJml.textContent = 'Jumlah suku cadang / sparepart yang akan dimasukkan untuk perbaikan.';
    } else {
        labelJml.innerHTML = 'Jumlah Unit GSE yang Diajukan<span class="req">*</span>';
        descJml.textContent = 'Jumlah item yang akan diinput detailnya nanti di menu Kelengkapan.';
    }
}

// Inisialisasi awal
document.addEventListener('DOMContentLoaded', function() {
    var optSp = document.getElementById('opt_sparepart');
    if (optSp && optSp.checked) {
        switchJenisPengajuan('Masuk Perbaikan Sparepart');
    }
});
</script>