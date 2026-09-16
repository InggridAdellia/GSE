<?php
$jenis = $permohonan->jenis_permohonan ?? 'Keluar Baru';
?>
<style>
    .back-link{ display:inline-flex; align-items:center; gap:.35rem; font-size:.85rem; font-weight:600; color:var(--teal-700); text-decoration:none; margin-bottom:.6rem; }
    .back-link:hover{ color:var(--teal-600); }
    .form-section-label{ font-size:.74rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; color:var(--teal-700); margin:1.6rem 0 .9rem; }
    .form-section-label:first-child{ margin-top:0; }
    .form-section-label::before{ content:""; display:inline-block; width:8px; height:8px; border-radius:50%; background:var(--gold-500); margin-right:.5rem; }
    .form-label{ font-weight:600; font-size:.86rem; color:var(--ink-900); }
    .req{ color:#C0392B; margin-left:.15rem; }
    .form-control, .form-select{ border-color:var(--line); border-radius:.6rem; padding:.55rem .8rem; }
    .form-actions{ margin-top:1.6rem; padding-top:1.25rem; border-top:1px solid var(--line); display:flex; gap:.6rem; }
    .btn-light{ border:1px solid var(--line); background:#fff; }
    .attachment-box{ border:1px solid #F5DDB0; background:#FFFBF3; border-radius:.85rem; padding:1rem; margin-top:.75rem; }
    .alert-info{ background:#E6F6F6; color:var(--teal-700); border:0; border-radius:.75rem; }
    .reject-banner{ background:#FDECE8; border:1px solid #F5C6BE; color:#A23B2A; border-radius:.85rem; padding:1rem 1.1rem; margin-bottom:1.25rem; display:flex; gap:.75rem; align-items:flex-start; }
    .reject-banner i{ font-size:1.2rem; margin-top:.1rem; }
    .reject-banner strong{ display:block; margin-bottom:.2rem; }
    .file-lama{ display:flex; align-items:center; gap:.5rem; border:1px solid var(--line); border-radius:.6rem; padding:.5rem .7rem; margin-top:.4rem; background:#fff; font-size:.82rem; }
    .file-lama img{ width:38px; height:38px; object-fit:cover; border-radius:.4rem; }
</style>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<a href="<?= site_url('permohonan_keluar/detail/' . $permohonan->id_permohonan_keluar) ?>" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Detail Permohonan
</a>

<h5 class="mb-3">Ajukan Ulang Unit GSE — <?= htmlspecialchars($unit->nama_gse) ?></h5>

<?php if (!empty($unit->alasan_penolakan_item)): ?>
<div class="reject-banner">
    <i class="bi bi-x-circle"></i>
    <div>
        <strong>Alasan Penolakan</strong>
        <?= nl2br(htmlspecialchars($unit->alasan_penolakan_item)) ?>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="max-width:700px;">
    <div class="card-body p-4">
        <?= form_open_multipart('permohonan_keluar/update_unit/' . $unit->id) ?>

            <div class="form-section-label" style="margin-top:0">Data Unit</div>
            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="form-label">Nama GSE</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($unit->nama_gse) ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Asset</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($unit->no_asset ?? '-') ?>" disabled>
                </div>
            </div>
            <div class="row g-3 mb-2">
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"><?= htmlspecialchars($unit->keterangan ?? '') ?></textarea>
                </div>
            </div>

            <?php if ($jenis === 'Keluar Baru'): ?>
            <div class="form-section-label">Foto Unit GSE</div>
            <div class="attachment-box">
                <div class="alert alert-info py-2 mb-3 small">
                    <i class="bi bi-info-circle"></i> Upload ulang Foto Unit GSE (PDF/JPG/PNG, maks. 500KB). Kosongkan jika ingin memakai file lama.
                </div>
                <input type="file" name="file_foto_gse" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <?php if (!empty($unit->file_foto_gse)):
                    $url = base_url('uploads/lampiran/' . $unit->file_foto_gse);
                    $ext = strtolower(pathinfo($unit->file_foto_gse, PATHINFO_EXTENSION));
                ?>
                <div class="file-lama">
                    <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                        <img src="<?= $url ?>">
                    <?php else: ?>
                        <i class="bi bi-file-earmark-pdf text-danger" style="font-size:1.4rem;"></i>
                    <?php endif; ?>
                    <span>File saat ini — <a href="<?= $url ?>" target="_blank">Lihat file</a></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($jenis === 'Perbaikan'): ?>
            <div class="form-section-label">Bukti Kerusakan</div>
            <div class="attachment-box">
                <div class="alert alert-info py-2 mb-3 small">
                    <i class="bi bi-info-circle"></i> Upload ulang Bukti Kerusakan (PDF/JPG/PNG, maks. 500KB). Kosongkan jika ingin memakai file lama.
                </div>
                <input type="file" name="file_bukti_kerusakan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <?php if (!empty($unit->file_bukti_kerusakan)):
                    $url = base_url('uploads/lampiran/' . $unit->file_bukti_kerusakan);
                    $ext = strtolower(pathinfo($unit->file_bukti_kerusakan, PATHINFO_EXTENSION));
                ?>
                <div class="file-lama">
                    <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                        <img src="<?= $url ?>">
                    <?php else: ?>
                        <i class="bi bi-file-earmark-pdf text-danger" style="font-size:1.4rem;"></i>
                    <?php endif; ?>
                    <span>File saat ini — <a href="<?= $url ?>" target="_blank">Lihat file</a></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Ajukan Ulang</button>
                <a href="<?= site_url('permohonan_keluar/detail/' . $permohonan->id_permohonan_keluar) ?>" class="btn btn-light">Batal</a>
            </div>

        <?= form_close() ?>
    </div>
</div>