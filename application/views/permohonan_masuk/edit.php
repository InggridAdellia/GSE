<style>
    .back-link{ display:inline-flex; align-items:center; gap:.35rem; font-size:.85rem; font-weight:600; color:var(--teal-700); text-decoration:none; margin-bottom:.6rem; }
    .back-link:hover{ color:var(--teal-600); }
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; }
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
    .reject-banner{ background:#FDECE8; border:1px solid #F5C6BE; color:#A23B2A; border-radius:.85rem; padding:1rem 1.1rem; margin-bottom:1.25rem; display:flex; gap:.75rem; align-items:flex-start; }
    .reject-banner i{ font-size:1.2rem; margin-top:.1rem; }
    .reject-banner strong{ display:block; margin-bottom:.2rem; }
    .alert-info{ background:#E6F6F6; color:var(--teal-700); border:0; border-radius:.75rem; }
    .gse-item{ border:1px solid var(--line); border-radius:.85rem; padding:1.25rem; margin-bottom:1rem; background:#fafefe; }
    .gse-item-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:.85rem; }
    .gse-item-title{ font-weight:700; font-size:.9rem; color:var(--teal-800); }
    .btn-remove-gse{ background:#FDECE8; color:#A23B2A; border:none; border-radius:.5rem; padding:.3rem .7rem; font-size:.8rem; cursor:pointer; }
    .attachment-box{ border:1px solid #F5DDB0; background:#FFFBF3; border-radius:.85rem; padding:1rem; margin-top:.75rem; }
</style>

<a href="<?= site_url('permohonan_masuk') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Pengajuan</a>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-arrow-repeat"></i> Ajukan Ulang Permohonan</h5>
        <p class="page-subtitle">No. Permohonan: <strong><?= htmlspecialchars($permohonan->nomor_permohonan) ?></strong></p>
    </div>
</div>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($permohonan->alasan_penolakan): ?>
<div class="reject-banner">
    <i class="bi bi-x-circle"></i>
    <div>
        <strong>Alasan Penolakan Sebelumnya</strong>
        <?= nl2br(htmlspecialchars($permohonan->alasan_penolakan)) ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($gse_terpilih)): ?>
<div class="card border-0 shadow-sm form-card mb-3">
    <div class="card-body p-4">
        <div class="form-section-label">Status Unit GSE</div>
        <?php foreach ($gse_terpilih as $g): ?>
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <div>
                <div class="fw-semibold small"><?= htmlspecialchars($g->nama_gse) ?></div>
                <div class="text-muted small"><?= htmlspecialchars($g->no_asset ?? '-') ?></div>
            </div>
            <?php if (($g->status_item ?? null) === 'Ditolak'): ?>
                <span class="badge bg-danger">Ditolak - perlu diajukan ulang</span>
            <?php else: ?>
                <span class="badge bg-success">Disetujui / Sesuai</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <div class="form-text mt-2">Unit yang ditolak dapat diperbarui datanya melalui tombol <strong>"Ajukan Ulang"</strong> pada tabel unit di halaman Detail. Unit yang diajukan ulang akan langsung masuk ke tahapan verifikasi yang menolak (tanpa mengulang dari awal).</div>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm form-card">
    <div class="card-body p-4">
        <?= form_open_multipart('permohonan_masuk/update/' . $permohonan->id_permohonan_masuk) ?>


            <div class="form-section-label">Informasi Pengajuan</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nomor Surat<span class="req">*</span></label>
                    <input type="text" name="nomor_surat" class="form-control"
                           value="<?= set_value('nomor_surat', $permohonan->nomor_surat); ?>"
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Masuk<span class="req">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control"
                           value="<?= $permohonan->tanggal_masuk ?>" required>
                </div>
                <div class="col-md-6">
                <label class="form-label">Jumlah Unit GSE</label>
                <input type="number" class="form-control" value="<?= $permohonan->jumlah_unit_gse ?>" disabled>
                <div class="form-text">Total unit tetap sama.</div>
            </div>
                <div class="col-md-6">
                    <label class="form-label">Asal Instansi</label>
                    <input type="text" class="form-control"
                           value="<?= htmlspecialchars($permohonan->asal_instansi ?? '-') ?>" disabled>
                </div>
            </div>

            <div class="form-section-label">Keterangan</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Keterangan<span class="req">*</span></label>
                    <textarea name="keterangan" class="form-control" rows="3" required><?= htmlspecialchars($permohonan->keterangan) ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle"></i> Simpan &amp; Lihat Detail Unit
                </button>
                <a href="<?= site_url('permohonan_masuk/detail/' . $permohonan->id_permohonan_masuk) ?>" class="btn btn-light">Batal</a>
            </div>

        <?= form_close() ?>
    </div>
</div>

<script>
var gseCount = <?= count($gse_terpilih) ?>;

// FIX: pakai manufacture_type, simpan sebagai flag 1/0 untuk hindari case-sensitivity
var daftarGse = <?= json_encode(array_map(function($g) {
    return [
        'id'       => $g->id_gse,
        'nama'     => $g->nama_gse,
        'motorized'=> strtolower($g->manufacture_type ?? '') === 'motorized' ? 1 : 0,
    ];
}, $daftar_gse)) ?>;

// FIX: baca data-motorized (0 atau 1), bukan string manufacture_type
function cekMotorized(sel, index) {
    var isMotorized = sel.options[sel.selectedIndex].dataset.motorized === '1';
    var box         = document.getElementById('lampiran-' + index);
    if (!box) return;
    var inputs      = box.querySelectorAll('input[type=file]');

    if (isMotorized) {
        box.classList.remove('d-none');
        inputs.forEach(function(inp) { inp.setAttribute('required', 'required'); });
    } else {
        box.classList.add('d-none');
        inputs.forEach(function(inp) { inp.removeAttribute('required'); inp.value = ''; });
    }
}

function tambahGse() {
    var index = gseCount;
    var opts  = '<option value="">-- Pilih unit GSE --</option>' +
        daftarGse.map(function(g) {
            return '<option value="' + g.id + '" data-motorized="' + g.motorized + '">' +
                g.nama + ' (' + (g.motorized ? 'Motorized' : 'Non-Motorized') + ')</option>';
        }).join('');

    var lampiranDok = ['ktp','tim','stnk','sim'].map(function(dok, i) {
        var label = ['Fotocopy KTP','Fotocopy TIM','Fotocopy STNK','Fotocopy SIM (Driver)'][i];
        return '<div class="col-md-6"><label class="form-label small">' + label +
            '<span class="req">*</span></label>' +
            '<input type="file" name="file_' + dok + '_' + index +
            '" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf"></div>';
    }).join('');

    var html =
        '<div class="gse-item" id="gse-item-' + index + '">' +
            '<div class="gse-item-header">' +
                '<span class="gse-item-title">Unit GSE #' + (index + 1) + '</span>' +
                '<button type="button" class="btn-remove-gse" onclick="hapusGse(' + index + ')">' +
                    '<i class="bi bi-trash"></i> Hapus</button>' +
            '</div>' +
            '<div class="row g-3"><div class="col-12">' +
                '<label class="form-label">Pilih GSE<span class="req">*</span></label>' +
                '<select name="id_gse[]" class="form-select" required ' +
                    'onchange="cekMotorized(this,' + index + ')">' + opts + '</select>' +
            '</div></div>' +
            '<div id="lampiran-' + index + '" class="d-none">' +
                '<div class="attachment-box mt-2">' +
                    '<div class="alert alert-info py-2 mb-3 small">' +
                        '<i class="bi bi-info-circle"></i> GSE Motorized wajib melampirkan dokumen (PDF/JPG/PNG, maks. 500KB):</div>' +
                    '<div class="row g-2">' + lampiranDok + '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

    document.getElementById('gse-container').insertAdjacentHTML('beforeend', html);
    gseCount++;
}

function hapusGse(index) {
    var el = document.getElementById('gse-item-' + index);
    if (el) el.remove();
}
</script>