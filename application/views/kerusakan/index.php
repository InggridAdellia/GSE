<?php
$user_role = $user_role ?? $this->session->userdata('role') ?? '';
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
    .stat-icon-gold{ background:#FDF2DF; color:var(--gold-600); }
    .stat-icon-green{ background:#E9F6EE; color:#1F6F45; }
    .stat-icon-red{ background:#FDECE8; color:#A23B2A; }
    .stat-value{ font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:1rem; color:var(--ink-900); line-height:1.1; }
    .stat-label{ font-size:.62rem; color:var(--ink-600); }
    @media(max-width:767.98px){ .stat-row{ grid-template-columns:1fr; } }

    .table-card{ background:#fff; border-radius:1rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); overflow:hidden; }
    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); flex-wrap:gap; gap:.5rem; }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); font-size:.8rem; }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.62rem; font-weight:600; padding:.22rem .6rem; border-radius:999px; }

    .table-responsive table{ font-size:.7rem; }
    .table-responsive thead th{
        background:#FAFCFB; color:var(--ink-600); font-weight:700; font-size:.62rem;
        text-transform:uppercase; letter-spacing:.05em; border-bottom:1px solid var(--line);
        padding:.65rem .75rem; vertical-align:middle;
    }
    .table-responsive tbody td{
        padding:.65rem .75rem; vertical-align:middle; border-bottom:1px solid #f0f4f4; color:var(--ink-900);
    }
    .table-responsive tbody tr:hover{ background:#F7FAFA; }

    .badge{ font-weight:600; font-size:.6rem; padding:.35rem .6rem; border-radius:999px; }
    .badge.bg-warning{ background:#FDF2DF !important; color:var(--gold-600) !important; }
    .badge.bg-info{ background:#E6F6F6 !important; color:var(--teal-700) !important; }
    .badge.bg-primary{ background:#EAF1FB !important; color:#2C5FA8 !important; }
    .badge.bg-success{ background:#E9F6EE !important; color:#1F6F45 !important; }
    .badge.bg-danger{ background:#FDECE8 !important; color:#A23B2A !important; }
    .badge.bg-secondary{ background:#EEF1F1 !important; color:var(--ink-600) !important; }

    .reg-code{ background:var(--mint-50); color:var(--teal-800); padding:.15rem .45rem; border-radius:4px; font-size:.68rem; font-family:monospace; }
    .row-actions{ display:inline-flex; gap:.35rem; align-items:center; flex-wrap:nowrap; }
    .row-actions .btn{ border-radius:.5rem; font-size:.66rem; padding:.28rem .55rem; }
    .empty-state{ text-align:center; padding:3rem 1rem; color:var(--ink-600); font-size:.74rem; }
    .empty-state i{ font-size:1.8rem; color:var(--teal-600); display:block; margin-bottom:.6rem; }

    .photo-thumb{ width:40px; height:40px; object-fit:cover; border-radius:.45rem; border:1px solid var(--line); cursor:pointer; transition:transform .15s ease; }
    .photo-thumb:hover{ transform:scale(1.08); }
    .modal-content{ border:0; border-radius:1rem; overflow:hidden; }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-cone-striped"></i> Laporan Kerusakan & Peringatan GSE</h5>
        <p class="page-subtitle">Daftar rekapan laporan kerusakan unit GSE dan penindakan di lapangan.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('scan') ?>" class="btn btn-primary btn-sm" style="font-size: .72rem; border-radius: .5rem;">
            <i class="bi bi-qr-code-scan me-1"></i> Scan & Input Kerusakan
        </a>
    </div>
</div>

<!-- Statistik Ringkasan -->
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-icon stat-icon-teal"><i class="bi bi-file-earmark-medical"></i></div>
        <div>
            <div class="stat-value"><?= $summary->total ?? 0 ?></div>
            <div class="stat-label">Total Laporan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-gold"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-value"><?= $summary->baru ?? 0 ?></div>
            <div class="stat-label">Belum Ditindak</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><i class="bi bi-check2-circle"></i></div>
        <div>
            <div class="stat-value"><?= $summary->sudah_ditindak ?? 0 ?></div>
            <div class="stat-label">Sudah Ditindak</div>
        </div>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="table-card">
    <div class="table-card-head">
        <h6><i class="bi bi-list-ul me-1"></i> Daftar Laporan Kerusakan</h6>
        <span class="count-pill"><?= count($laporan) ?> Laporan</span>
    </div>
    <div class="p-3">
        <!-- Form Filter -->
        <form method="get" action="<?= site_url('kerusakan') ?>" class="row g-2 align-items-center mb-3">
            <div class="col-md-3 col-sm-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari stiker, nama GSE, rincian..." value="<?= htmlspecialchars($filter['search'] ?? '') ?>" style="font-size: .72rem; border-radius: .5rem;">
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="status" class="form-select form-select-sm" style="font-size: .72rem; border-radius: .5rem;">
                    <option value="">Semua Status</option>
                    <option value="Baru" <?= ($filter['status'] ?? '') === 'Baru' ? 'selected' : '' ?>>Baru / Belum Ditindak</option>
                    <option value="Sudah Ditindak" <?= ($filter['status'] ?? '') === 'Sudah Ditindak' ? 'selected' : '' ?>>Sudah Ditindak</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="tingkat" class="form-select form-select-sm" style="font-size: .72rem; border-radius: .5rem;">
                    <option value="">Semua Tingkat</option>
                    <option value="Peringatan Ringan" <?= ($filter['tingkat'] ?? '') === 'Peringatan Ringan' ? 'selected' : '' ?>>Peringatan Ringan</option>
                    <option value="Peringatan Sedang" <?= ($filter['tingkat'] ?? '') === 'Peringatan Sedang' ? 'selected' : '' ?>>Peringatan Sedang</option>
                    <option value="Bahaya / Stop Operasi" <?= ($filter['tingkat'] ?? '') === 'Bahaya / Stop Operasi' ? 'selected' : '' ?>>Bahaya / Stop Operasi</option>
                </select>
            </div>
            <?php if ($user_role !== 'ground_handling' && !empty($airlines)): ?>
            <div class="col-md-3 col-sm-6">
                <select name="id_airline" class="form-select form-select-sm" style="font-size: .72rem; border-radius: .5rem;">
                    <option value="">Semua Maskapai</option>
                    <?php foreach ($airlines as $al): ?>
                        <option value="<?= $al->id_airline ?>" <?= ($filter['id_airline'] ?? '') == $al->id_airline ? 'selected' : '' ?>>
                            <?= htmlspecialchars($al->nama_airline) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-auto d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-outline-primary" style="font-size: .72rem; border-radius: .5rem;"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="<?= site_url('kerusakan') ?>" class="btn btn-sm btn-outline-secondary" style="font-size: .72rem; border-radius: .5rem;" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>

        <!-- Tabel Laporan -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th>Stiker AP & Unit GSE</th>
                        <th>Maskapai / GH</th>
                        <th>Tingkat Kerusakan</th>
                        <th>Rincian & Foto</th>
                        <th>Pelapor & Waktu</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <div>Belum ada data laporan kerusakan yang sesuai filter.</div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($laporan as $row): ?>
                            <tr>
                                <td class="text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-semibold" style="color:var(--teal-800);"><?= htmlspecialchars($row->nama_gse) ?></div>
                                    <span class="reg-code mt-1 d-inline-block">
                                        <?= htmlspecialchars($row->sticker_ap) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row->nama_airline ?: '-') ?></td>
                                <td>
                                    <?php if ($row->tingkat_kerusakan === 'Bahaya / Stop Operasi'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-x-octagon-fill me-1"></i> Bahaya / Stop</span>
                                    <?php elseif ($row->tingkat_kerusakan === 'Peringatan Sedang'): ?>
                                        <span class="badge bg-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i> Peringatan Sedang</span>
                                    <?php else: ?>
                                        <span class="badge bg-info"><i class="bi bi-info-circle me-1"></i> Peringatan Ringan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($row->foto_kerusakan)): ?>
                                            <a href="<?= base_url($row->foto_kerusakan) ?>" target="_blank" title="Lihat Foto Bukti">
                                                <img src="<?= base_url($row->foto_kerusakan) ?>" class="photo-thumb" alt="Foto">
                                            </a>
                                        <?php endif; ?>
                                        <div style="max-width: 250px; line-height: 1.35;" class="text-muted">
                                            <?= nl2br(htmlspecialchars(mb_strlen($row->keterangan ?? '') > 90 ? mb_substr($row->keterangan, 0, 90) . '...' : ($row->keterangan ?? '-'))) ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($row->user_pelapor ?: $row->nama_pelapor ?: '-') ?></div>
                                    <div class="text-muted" style="font-size: .65rem;"><?= date('d M Y H:i', strtotime($row->created_at)) ?></div>
                                </td>
                                <td>
                                    <?php if ($row->status === 'Sudah Ditindak'): ?>
                                        <span class="badge bg-success" title="Ditindak oleh <?= htmlspecialchars($row->user_penindak ?: $row->nama_penindak ?: '-') ?> pada <?= $row->ditindak_at ? date('d/m/Y H:i', strtotime($row->ditindak_at)) : '-' ?>">
                                            <i class="bi bi-check-circle me-1"></i> Sudah Ditindak
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="bi bi-hourglass-split me-1"></i> Baru / Menunggu
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="row-actions justify-content-center">
                                        <button type="button" class="btn btn-outline-info btn-detail" data-id="<?= $row->id ?>" title="Lihat Detail & Tindak Lanjut">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                        <?php if ($user_role === 'admin'): ?>
                                            <a href="<?= site_url('kerusakan/hapus/' . $row->id) ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan kerusakan ini?');" title="Hapus Laporan">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail & Tindak Lanjut Laporan -->
<div class="modal fade" id="modalDetailKerusakan" tabindex="-1" aria-labelledby="modalDetailKerusakanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3" style="background:var(--mint-50); border-bottom:1px solid var(--line);">
                <h6 class="modal-title fw-bold" style="color:var(--teal-800); font-size:.86rem;" id="modalDetailKerusakanLabel">
                    <i class="bi bi-file-earmark-medical me-1"></i> Detail Laporan Kerusakan Unit GSE
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div id="modal-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="text-muted small mt-2">Memuat data detail laporan...</div>
                </div>

                <div id="modal-content-box" style="display: none;">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <table class="table table-sm table-borderless mb-0" style="font-size: .74rem;">
                                    <tr>
                                        <th class="text-muted" style="width: 38%;">Nomor Stiker AP</th>
                                        <td class="fw-semibold font-monospace" id="det-sticker-ap">-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Nama GSE</th>
                                        <td class="fw-semibold" id="det-nama-gse">-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Maskapai / GH</th>
                                        <td id="det-airline">-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Tingkat Bahaya</th>
                                        <td id="det-tingkat">-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Waktu Lapor</th>
                                        <td id="det-waktu-lapor">-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Dilaporkan Oleh</th>
                                        <td id="det-pelapor">-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Status</th>
                                        <td id="det-status">-</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-dark mb-1" style="font-size:.74rem;">Rincian Kerusakan / Peringatan:</label>
                                <div class="p-3 rounded border bg-white small" id="det-keterangan" style="white-space: pre-wrap; line-height: 1.45; font-size:.74rem;">-</div>
                            </div>

                            <div id="box-penindakan" class="p-3 rounded-3 border bg-light mb-3" style="display: none;">
                                <div class="small fw-bold text-success d-flex align-items-center gap-1 mb-1" style="font-size:.74rem;">
                                    <i class="bi bi-check-circle-fill"></i> Riwayat Tindakan Perbaikan:
                                </div>
                                <div class="small text-dark mb-2" id="det-tindakan" style="white-space: pre-wrap; font-size:.74rem;">-</div>
                                <div class="text-muted" style="font-size: .68rem;" id="det-penindak-info">-</div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="small fw-bold text-dark mb-1" style="font-size:.74rem;">Foto Bukti Kerusakan:</label>
                            <div id="box-foto-kerusakan" class="text-center p-2 rounded border bg-light">
                                <img id="det-foto-img" src="" class="img-fluid rounded" alt="Foto Kerusakan" style="max-height: 240px; display: none;">
                                <div id="det-no-foto" class="text-muted small py-4" style="font-size:.74rem;">
                                    <i class="bi bi-image fs-1 d-block mb-1 text-secondary"></i>
                                    Tidak ada lampiran foto kerusakan.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kebutuhan Perbaikan Sparepart Section -->
                    <div class="p-3 rounded-3 border bg-white mb-3 mt-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <div class="small fw-bold text-dark d-flex align-items-center gap-1" style="font-size:.78rem;">
                                <i class="bi bi-gear-wide-connected text-primary"></i> Kebutuhan Perbaikan Sparepart
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnToggleFormSparepart" style="font-size:.68rem; padding:.22rem .55rem; border-radius:.4rem;">
                                    <i class="bi bi-pencil-square me-1"></i> Input / Edit Sparepart
                                </button>
                                <a href="#" id="btnAjukanPermohonanSparepart" class="btn btn-sm btn-success text-white" style="font-size:.68rem; padding:.22rem .55rem; border-radius:.4rem;">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Ajukan Masuk Sparepart
                                </a>
                            </div>
                        </div>

                        <!-- Daftar Sparepart Yang Sudah Ada -->
                        <div id="box-list-sparepart" class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" style="font-size:.7rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35px;">No</th>
                                        <th>Nama Sparepart / Komponen</th>
                                        <th style="width: 80px;" class="text-center">Jumlah</th>
                                        <th>Keterangan Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody id="det-tbody-sparepart">
                                    <tr><td colspan="4" class="text-center text-muted py-2">Belum ada daftar sparepart yang dicatat.</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Form Input Multi-Item Sparepart -->
                        <form id="formSparepart" method="post" action="" class="mt-2 pt-2 border-top" style="display:none;">
                            <div class="small text-muted mb-2" style="font-size:.7rem;">
                                Masukkan rincian sparepart yang dibutuhkan untuk perbaikan unit ini (bisa lebih dari 1 item):
                            </div>
                            <div id="container-input-sparepart">
                                <!-- Baris dinamis -->
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddRowSparepart" style="font-size:.68rem;">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Item Sparepart
                                </button>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-light" id="btnCancelSparepart" style="font-size:.68rem;">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-primary" style="font-size:.68rem;">
                                        <i class="bi bi-save me-1"></i> Simpan Daftar Sparepart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Form Tindak Lanjut (Jika status masih 'Baru' dan memiliki hak akses verifikasi/admin) -->
                    <?php if (in_array($user_role, ['admin', 'unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security'])): ?>
                    <form id="formTindakLanjut" method="post" action="" class="mt-3 pt-3 border-top" style="display: none;">
                        <h6 class="fw-bold small text-dark d-flex align-items-center gap-1 mb-2" style="font-size:.78rem;">
                            <i class="bi bi-tools text-primary"></i> Form Penindakan / Penyelesaian Kerusakan
                        </h6>
                        <div class="mb-2">
                            <label for="input_tindakan_perbaikan" class="form-label small text-muted" style="font-size:.72rem;">Catatan Tindakan Perbaikan / Konfirmasi di Lapangan <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" name="tindakan_perbaikan" id="input_tindakan_perbaikan" rows="2" placeholder="Contoh: Telah diperiksa oleh Unit Equipment dan dilakukan perbaikan komponen..." required style="font-size:.72rem; border-radius:.5rem;"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-sm btn-success" style="font-size:.72rem; border-radius:.5rem;">
                                <i class="bi bi-check-lg me-1"></i> Simpan & Tandai Sudah Ditindak
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer bg-light border-top py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size:.72rem; border-radius:.5rem;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl            = document.getElementById('modalDetailKerusakan');
    var bsModal            = modalEl ? new bootstrap.Modal(modalEl) : null;
    var loadingBox         = document.getElementById('modal-loading');
    var contentBox         = document.getElementById('modal-content-box');
    var formTindak         = document.getElementById('formTindakLanjut');
    var formSparepart      = document.getElementById('formSparepart');
    var btnToggleSparepart = document.getElementById('btnToggleFormSparepart');
    var btnCancelSparepart = document.getElementById('btnCancelSparepart');
    var btnAddRowSparepart = document.getElementById('btnAddRowSparepart');
    var containerSparepart = document.getElementById('container-input-sparepart');
    var tbodySparepart     = document.getElementById('det-tbody-sparepart');
    var btnAjukanSP        = document.getElementById('btnAjukanPermohonanSparepart');
    var detailButtons      = document.querySelectorAll('.btn-detail');
    var currentLaporanData = null;

    function renderSparepartRow(nama, qty, ket) {
        nama = nama || '';
        qty  = qty || '1';
        ket  = ket || '';
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 align-items-center item-sparepart-row';
        row.innerHTML = 
            '<div class="col-md-5">' +
                '<input type="text" name="nama_sparepart[]" class="form-control form-control-sm" placeholder="Nama sparepart (misal: Aki / Ban / Filter)..." value="' + nama.replace(/"/g, '&quot;') + '" required style="font-size:.7rem;">' +
            '</div>' +
            '<div class="col-md-2">' +
                '<input type="text" name="qty[]" class="form-control form-control-sm text-center" placeholder="Qty" value="' + qty.replace(/"/g, '&quot;') + '" style="font-size:.7rem;">' +
            '</div>' +
            '<div class="col-md-4">' +
                '<input type="text" name="keterangan_sp[]" class="form-control form-control-sm" placeholder="Keterangan..." value="' + ket.replace(/"/g, '&quot;') + '" style="font-size:.7rem;">' +
            '</div>' +
            '<div class="col-md-1 text-center">' +
                '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="font-size:.65rem; padding:.2rem .4rem;" title="Hapus baris"><i class="bi bi-trash"></i></button>' +
            '</div>';
        
        row.querySelector('.btn-remove-row').addEventListener('click', function() {
            row.remove();
        });
        containerSparepart.appendChild(row);
    }

    if (btnAddRowSparepart) {
        btnAddRowSparepart.addEventListener('click', function() {
            renderSparepartRow('', '1', '');
        });
    }

    if (btnToggleSparepart) {
        btnToggleSparepart.addEventListener('click', function() {
            formSparepart.style.display = (formSparepart.style.display === 'none' || !formSparepart.style.display) ? 'block' : 'none';
        });
    }

    if (btnCancelSparepart) {
        btnCancelSparepart.addEventListener('click', function() {
            formSparepart.style.display = 'none';
        });
    }

    detailButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            if (!id) return;

            loadingBox.style.display = 'block';
            contentBox.style.display = 'none';
            if (formSparepart) formSparepart.style.display = 'none';
            if (modalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }

            fetch('<?= site_url('kerusakan/detail_json/') ?>' + id)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    currentLaporanData = data;
                    loadingBox.style.display = 'none';
                    contentBox.style.display = 'block';

                    document.getElementById('det-sticker-ap').textContent = data.sticker_ap || '-';
                    document.getElementById('det-nama-gse').textContent   = data.nama_gse || '-';
                    document.getElementById('det-airline').textContent    = data.nama_airline || '-';
                    document.getElementById('det-waktu-lapor').textContent= data.created_at || '-';
                    document.getElementById('det-pelapor').textContent    = (data.user_pelapor || data.nama_pelapor || '-') + ' (' + (data.role_pelapor || '-') + ')';
                    document.getElementById('det-keterangan').textContent = data.keterangan || '-';

                    // Link Ajukan Masuk Sparepart
                    if (btnAjukanSP) {
                        btnAjukanSP.href = '<?= site_url('permohonan_masuk/tambah?id_laporan=') ?>' + data.id;
                    }

                    // Tingkat badge
                    var tingkatHtml = '<span class="badge bg-secondary">' + (data.tingkat_kerusakan || '-') + '</span>';
                    if (data.tingkat_kerusakan === 'Bahaya / Stop Operasi') {
                        tingkatHtml = '<span class="badge bg-danger"><i class="bi bi-x-octagon-fill me-1"></i> Bahaya / Stop</span>';
                    } else if (data.tingkat_kerusakan === 'Peringatan Sedang') {
                        tingkatHtml = '<span class="badge bg-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i> Peringatan Sedang</span>';
                    } else {
                        tingkatHtml = '<span class="badge bg-info"><i class="bi bi-info-circle me-1"></i> Peringatan Ringan</span>';
                    }
                    document.getElementById('det-tingkat').innerHTML = tingkatHtml;

                    // Status badge
                    if (data.status === 'Sudah Ditindak') {
                        document.getElementById('det-status').innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Sudah Ditindak</span>';
                    } else {
                        document.getElementById('det-status').innerHTML = '<span class="badge bg-warning"><i class="bi bi-hourglass-split me-1"></i> Baru / Menunggu</span>';
                    }

                    // Riwayat penindakan
                    var boxPenindakan = document.getElementById('box-penindakan');
                    if (data.status === 'Sudah Ditindak' && data.tindakan_perbaikan) {
                        boxPenindakan.style.display = 'block';
                        document.getElementById('det-tindakan').textContent = data.tindakan_perbaikan;
                        document.getElementById('det-penindak-info').textContent = 'Ditindak oleh: ' + (data.user_penindak || data.nama_penindak || '-') + ' pada ' + (data.ditindak_at || '-');
                    } else {
                        boxPenindakan.style.display = 'none';
                    }

                    // Foto
                    var imgEl    = document.getElementById('det-foto-img');
                    var noFotoEl = document.getElementById('det-no-foto');
                    if (data.foto_kerusakan) {
                        imgEl.src = '<?= base_url() ?>' + data.foto_kerusakan;
                        imgEl.style.display = 'block';
                        noFotoEl.style.display = 'none';
                    } else {
                        imgEl.src = '';
                        imgEl.style.display = 'none';
                        noFotoEl.style.display = 'block';
                    }

                    // Render Daftar Kebutuhan Sparepart
                    containerSparepart.innerHTML = '';
                    var spList = [];
                    try {
                        if (data.kebutuhan_sparepart) {
                            spList = typeof data.kebutuhan_sparepart === 'string' ? JSON.parse(data.kebutuhan_sparepart) : data.kebutuhan_sparepart;
                        }
                    } catch(e) { spList = []; }

                    if (Array.isArray(spList) && spList.length > 0) {
                        var tHtml = '';
                        spList.forEach(function(sp, sIdx) {
                            tHtml += '<tr>' +
                                '<td class="text-center text-muted">' + (sIdx + 1) + '</td>' +
                                '<td class="fw-semibold text-dark">' + (sp.nama_sparepart || '-') + '</td>' +
                                '<td class="text-center"><span class="badge bg-light text-dark border">' + (sp.qty || '1') + '</span></td>' +
                                '<td>' + (sp.keterangan || '-') + '</td>' +
                            '</tr>';
                            renderSparepartRow(sp.nama_sparepart, sp.qty, sp.keterangan);
                        });
                        tbodySparepart.innerHTML = tHtml;
                    } else {
                        tbodySparepart.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-2">Belum ada daftar sparepart yang dicatat.</td></tr>';
                        renderSparepartRow('', '1', '');
                    }

                    if (formSparepart) {
                        formSparepart.action = '<?= site_url('kerusakan/simpan_sparepart/') ?>' + data.id;
                    }

                    // Form tindak lanjut
                    if (formTindak) {
                        if (data.status === 'Baru') {
                            formTindak.style.display = 'block';
                            formTindak.action = '<?= site_url('kerusakan/tindak/') ?>' + data.id;
                            document.getElementById('input_tindakan_perbaikan').value = '';
                        } else {
                            formTindak.style.display = 'none';
                        }
                    }
                })
                .catch(function () {
                    loadingBox.innerHTML = '<div class="alert alert-danger py-2 mb-0 small">Gagal memuat data detail laporan.</div>';
                });
        });
    });
});
</script>
