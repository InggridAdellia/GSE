<?php
$total   = (int) $permohonan->jumlah_unit_gse;
$lengkap = $sudah_terisi >= $total && $total > 0;
?>
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
    .form-actions{ margin-top:1.6rem; padding-top:1.25rem; border-top:1px solid var(--line); display:flex; gap:.6rem; }
    .btn-light{ border:1px solid var(--line); background:#fff; }

    .gse-item{ border:1px solid var(--line); border-radius:.85rem; padding:1.25rem; margin-bottom:1rem; background:#fafefe; }
    .gse-item-title{ font-weight:700; font-size:.9rem; color:var(--teal-800); margin-bottom:.85rem; }
    .attachment-box{ border:1px solid #F5DDB0; background:#FFFBF3; border-radius:.85rem; padding:1rem; margin-top:.75rem; }
    .alert-info{ background:#E6F6F6; color:var(--teal-700); border:0; border-radius:.75rem; }

    .kelengkapan-btn{
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.65rem 1.2rem; border-radius:999px; font-weight:700; font-size:.92rem;
        border:none; margin-bottom:1.25rem;
        background:#EEF1F1; color:var(--ink-600);
    }
    .kelengkapan-btn.lengkap{ background:#C0392B; color:#fff; }
    .kelengkapan-btn i{ font-size:1rem; }

    .terisi-item{ display:flex; align-items:center; justify-content:between; gap:.6rem; padding:.6rem .85rem; border:1px solid var(--line); border-radius:.7rem; margin-bottom:.5rem; background:#fff; }
    .terisi-item .nama{ font-weight:600; font-size:.88rem; flex:1; }

    .jenis-item-selector{ display:grid; grid-template-columns:1fr 1fr; gap:.6rem; margin-bottom:1rem; }
    .jenis-item-opt input[type=radio]{ display:none; }
    .jenis-item-opt label{ display:flex; align-items:center; justify-content:center; gap:.5rem; padding:.7rem .75rem; border:2px solid var(--line); border-radius:.7rem; cursor:pointer; font-weight:600; font-size:.85rem; color:var(--ink-600); background:#fff; }
    .jenis-item-opt input:checked + label{ border-color:var(--teal-600); background:var(--mint-50); color:var(--teal-700); }
</style>

<a href="<?= site_url('permohonan_keluar') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Permohonan Keluar</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-clipboard-check"></i> Input Kelengkapan Unit GSE</h5>
        <p class="page-subtitle">No. Permohonan: <strong><?= htmlspecialchars($permohonan->nomor_permohonan) ?></strong></p>
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
            Kelengkapan (<?= $sudah_terisi ?> dari <?= $total ?> unit Pengajuan)
        </button>

        <?php if (!empty($daftar_gse_terisi)): ?>
        <div class="form-section-label" style="margin-top:0">Unit yang Sudah Diisi</div>
        <?php foreach ($daftar_gse_terisi as $g): ?>
        <div class="terisi-item">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span class="nama"><?= htmlspecialchars($g->nama_gse) ?></span>
            <span class="badge bg-secondary"><?= htmlspecialchars($g->manufacture_type ?? '-') ?></span>
            <span class="badge bg-light text-dark border">
                <?= ($g->jenis_item ?? '') === 'Perbaikan' ? 'Perbaikan' : 'Keluar Baru' ?>
            </span>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($sisa > 0): ?>
        <div class="form-section-label">Input Unit GSE #<?= $sudah_terisi + 1 ?> (Sisa <?= $sisa ?> Unit)</div>

        <?= form_open_multipart('permohonan_keluar/simpan_kelengkapan/' . $permohonan->id_permohonan_keluar) ?>

        <?php if (!empty($gse_tersedia)): ?>
        <div class="jenis-item-selector">
            <div class="jenis-item-opt">
                <input type="radio" name="jenis_item" id="jenisItemKeluarBaru" value="Keluar Baru" checked
                       onchange="switchJenisItemKeluar('Keluar Baru')">
                <label for="jenisItemKeluarBaru"><i class="bi bi-box-arrow-in-right"></i> Keluar Hapus Asset</label>
            </div>
            <div class="jenis-item-opt">
                <input type="radio" name="jenis_item" id="jenisItemKeluarPerbaikan" value="Perbaikan"
                       onchange="switchJenisItemKeluar('Perbaikan')">
                <label for="jenisItemKeluarPerbaikan"><i class="bi bi-tools"></i> Keluar Untuk Perbaikan</label>
            </div>
        </div>
        <?php endif; ?>

        <div class="gse-item">
            <div class="gse-item-title">Data Unit GSE #<?= $sudah_terisi + 1 ?></div>
            <?php if (empty($gse_tersedia)): ?>
            <div class="alert alert-warning py-2 mb-0">
                <i class="bi bi-exclamation-triangle"></i>
                Tidak ada unit GSE aktif yang terdaftar untuk airline ini. Unit harus terdaftar lebih dulu lewat Permohonan Masuk yang sudah <strong>Disetujui</strong> sebelum bisa diajukan keluar.
            </div>
            <?php else: ?>
            <div class="row g-3">
                <div class="col-12" style="position:relative;">
                    <label class="form-label">Pilih Unit GSE<span class="req">*</span></label>
                    <input type="text" id="cariGse" class="form-control" autocomplete="off"
                           placeholder="Ketik nama GSE atau No. Asset..." required>
                    <input type="hidden" name="id_gse" id="id_gse_hidden">
                    <div id="gseOptions" class="list-group shadow-sm"
                         style="display:none; position:absolute; z-index:20; width:100%; max-height:240px; overflow-y:auto;"></div>
                    <div class="form-text">Ketik nama atau No. Asset untuk mencari unit GSE yang tersedia.</div>

                    <?php
                    // Data GSE untuk JavaScript
                    $gse_js = [];
                    foreach ($gse_tersedia as $g) {
                        $gse_js[] = [
                            'id'    => $g->id_gse,
                            'label' => $g->nama_gse . ($g->no_asset ? ' - ' . $g->no_asset : '') . ' (' . ($g->manufacture_type ?? '-') . ')',
                            'nama'  => $g->nama_gse,
                            'asset' => $g->no_asset ?? '',
                            'tipe'  => $g->manufacture_type ?? '',
                        ];
                    }
                    ?>
                    <script>
                    (function() {
                        var data = <?= json_encode($gse_js) ?>;
                        var input = document.getElementById('cariGse');
                        var hidden = document.getElementById('id_gse_hidden');
                        var dropdown = document.getElementById('gseOptions');

                        input.addEventListener('input', function() {
                            var q = this.value.toLowerCase().trim();
                            hidden.value = '';
                            dropdown.innerHTML = '';

                            if (q.length < 1) { dropdown.style.display = 'none'; return; }

                            var hasil = data.filter(function(g) {
                                return g.label.toLowerCase().includes(q);
                            });

                            if (hasil.length === 0) {
                                dropdown.style.display = 'none';
                                return;
                            }

                            hasil.forEach(function(g) {
                                var item = document.createElement('a');
                                item.href = 'javascript:void(0)';
                                item.className = 'list-group-item list-group-item-action py-2';
                                item.textContent = g.label;
                                item.addEventListener('click', function() {
                                    input.value  = g.label;
                                    hidden.value = g.id;
                                    dropdown.style.display = 'none';
                                });
                                dropdown.appendChild(item);
                            });

                            dropdown.style.display = 'block';
                        });

                        document.addEventListener('click', function(e) {
                            if (e.target !== input) dropdown.style.display = 'none';
                        });

                        // Validasi: pastikan pilihan dari daftar
                        input.closest('form').addEventListener('submit', function(e) {
                            if (!hidden.value) {
                                e.preventDefault();
                                input.setCustomValidity('Pilih unit GSE dari daftar.');
                                input.reportValidity();
                            } else {
                                input.setCustomValidity('');
                            }
                        });
                    })();
                    </script>
                </div>
            </div>

            <!-- Foto Unit GSE — wajib untuk semua jenis permohonan keluar (Keluar Baru & Perbaikan) -->
            <div class="attachment-box mt-2">
                <div class="alert alert-info py-2 mb-3 small">
                    <i class="bi bi-info-circle"></i> Wajib melampirkan Foto Unit GSE untuk unit ini (PDF/JPG/PNG, maks. 500KB):
                </div>
                <label class="form-label">Foto Unit GSE<span class="req">*</span></label>
                <input type="file" name="file_foto_gse" class="form-control"
                       accept=".jpg,.jpeg,.png,.pdf" required>
            </div>

            <!-- Bukti Kerusakan — hanya wajib kalau unit ini dipilih jenis "Perbaikan" -->
            <div id="boxBuktiKerusakan" class="attachment-box mt-2 d-none">
                <div class="alert alert-info py-2 mb-3 small">
                    <i class="bi bi-info-circle"></i> Wajib melampirkan Bukti Kerusakan untuk unit ini (PDF/JPG/PNG, maks. 500KB):
                </div>
                <label class="form-label">Bukti Kerusakan<span class="req">*</span></label>
                <input type="file" name="file_bukti_kerusakan" class="form-control"
                       accept=".jpg,.jpeg,.png,.pdf">
            </div>
            
            <div class="row g-3 mt-1">
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="fieldKeterangan" class="form-control" rows="2"
                                placeholder="Keterangan untuk unit ini"></textarea>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <?php if (!empty($gse_tersedia)): ?>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Unit Ini</button>
            <?php endif; ?>
            <a href="<?= site_url('permohonan_keluar') ?>" class="btn btn-light">Selesai Nanti</a>
        </div>

        <?= form_close() ?>

        <?php else: ?>
        <div class="alert alert-success py-2 mt-2">
            <i class="bi bi-check-circle"></i> Semua unit GSE sudah lengkap diisi.
        </div>
        <a href="<?= site_url('permohonan_keluar') ?>" class="btn btn-light">Kembali</a>
        <?php endif; ?>

    </div>
</div>

<script>
function switchJenisItemKeluar(jenis) {
    var box  = document.getElementById('boxBuktiKerusakan');
    var file = document.querySelector('#boxBuktiKerusakan [name="file_bukti_kerusakan"]');
    if (!box) return;

    var ketDefault = { 'Keluar Baru': 'Keluar Hapus Asset', 'Perbaikan': 'Keluar Untuk Perbaikan' };
    var ketField = document.getElementById('fieldKeterangan');
    if (ketField) {
        ketField.value = ketDefault[jenis] || '';
    }

    if (jenis === 'Perbaikan') {
        box.classList.remove('d-none');
        if (file) file.setAttribute('required', 'required');
    } else {
        box.classList.add('d-none');
        if (file) { file.removeAttribute('required'); file.value = ''; }
    }
}

(function () {
    var forms = document.querySelectorAll('form');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (e.defaultPrevented) return; 
            var btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                setTimeout(function () {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
                }, 0);
            }
        });
    });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    switchJenisItem('Masuk Baru');
});
</script>