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
    .jenis-selector{ display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:.5rem; }
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
    .form-section.d-none{ display:none !important; }
</style>

<a href="<?= site_url('permohonan_keluar') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Permohonan keluar</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-envelope-arrow-down"></i> Buat Permohonan keluar</h5>
        <p class="page-subtitle">Ajukan izin keluar unit GSE dari area bandara.</p>
    </div>
</div>

<div class="card border-0 shadow-sm form-card">
    <div class="card-body p-4">

        <div class="alert alert-info py-2 mb-3 small">
            <i class="bi bi-info-circle"></i>
            Satu surat permohonan ini nantinya bisa diisi campuran unit <strong>Keluar Baru</strong>
            maupun unit <strong>Keluar Untuk Perbaikan</strong> — jenis tiap unit dipilih
            belakangan, satu per satu, lewat menu <strong>"Input Kelengkapan"</strong>.
        </div>

        <!-- ════ FORM — Buat Permohonan ════ -->
        <div id="form-keluar_baru" class="form-section">
            <?= form_open_multipart('permohonan_keluar/simpan') ?>

                <div class="form-section-label">Informasi Pengajuan</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Surat<span class="req">*</span></label>
                        <input type="text" name="nomor_surat" class="form-control"
                               placeholder="Contoh: 001/GH-LA/VII/2026" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal keluar<span class="req">*</span></label>
                        <input type="date" name="tanggal_keluar" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Unit GSE yang Diajukan<span class="req">*</span></label>
                        <input type="number" name="jumlah_unit_gse" class="form-control"
                               min="1" step="1" placeholder="Contoh: 2" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Asal Instansi</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($airline->nama_airline ?? '-') ?>" disabled>
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
                    <a href="<?= site_url('permohonan_keluar') ?>" class="btn btn-light">Batal</a>
                </div>
            <?= form_close() ?>
        </div>

    </div>
</div>

<script>
function loadDatakeluar(sel) {
    var opt = sel.options[sel.selectedIndex];
    var box = document.getElementById('info-keluar');
    if (!opt.value) { box.classList.add('d-none'); return; }

    document.getElementById('info-airline').textContent = opt.dataset.airline || '-';
    document.getElementById('info-tanggal').textContent = opt.dataset.tanggal || '-';

    var gse = [];
    try { gse = JSON.parse(opt.dataset.gse); } catch(e) {}

    document.getElementById('info-gse').innerHTML = gse.length
        ? gse.map(function(g) {
            return '<span class="gse-tag"><i class="bi bi-truck"></i>' + (g.nama_gse || '-') + '</span>';
          }).join('')
        : '-';

    box.classList.remove('d-none');
}
</script>