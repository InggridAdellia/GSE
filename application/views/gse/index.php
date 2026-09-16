<style>
    :root {
        --teal-800: #0a7a82;
        --teal-700: #0d8c94;
        --teal-600: #1aabb6;
        --mint-50: #eefaf9;
        --gold-600: #d4a017;
        --ink-900: #1e293b;
        --ink-600: #64748b;
        --line: #e2e8f0;
    }
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; font-size:.92rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.72rem; margin-bottom:0; }

    .stat-row{ display:grid; grid-template-columns:repeat(4,1fr); gap:.85rem; margin-bottom:1.25rem; }
    .stat-card{ background:#fff; border-radius:1rem; padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); }
    .stat-icon{ width:38px; height:38px; border-radius:.7rem; display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
    .stat-icon-teal{ background:var(--mint-50); color:var(--teal-700); }
    .stat-icon-green{ background:#E9F6EE; color:#1F6F45; }
    .stat-icon-gold{ background:#FDF2DF; color:var(--gold-600); }
    .stat-icon-red{ background:#FDECE8; color:#A23B2A; }
    .stat-value{ font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:1rem; color:var(--ink-900); line-height:1.1; }
    .stat-label{ font-size:.62rem; color:var(--ink-600); }
    @media(max-width:991.98px){ .stat-row{ grid-template-columns:repeat(2,1fr); } }
    @media(max-width:575.98px){ .stat-row{ grid-template-columns:1fr; } }

    .table-card{ background:#fff; border-radius:1rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); overflow:hidden; }
    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); flex-wrap:wrap; gap:.5rem; }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); font-size:.8rem; }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.62rem; font-weight:600; padding:.22rem .6rem; border-radius:999px; }

    .gse-search-input{
        border:1px solid var(--line); border-radius:.5rem; padding:.35rem .75rem; font-size:.72rem;
        background:#fff; color:var(--ink-900); outline:none; transition:border-color .15s; width:260px;
    }
    .gse-search-input:focus{ border-color:var(--teal-600); }

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

    .stage-pill{ font-size:.55rem !important; padding:.2rem .45rem !important; border-radius:6px !important; font-weight:600; }
    .row-actions{ display:inline-flex; gap:.35rem; align-items:center; flex-wrap:nowrap; }
    .row-actions .btn{ border-radius:.5rem; font-size:.66rem; padding:.28rem .55rem; }
    .empty-state{ text-align:center; padding:3rem 1rem; color:var(--ink-600); font-size:.74rem; }
    .empty-state i{ font-size:1.8rem; color:var(--teal-600); display:block; margin-bottom:.6rem; }
    .reg-code{ background:var(--mint-50); color:var(--teal-800); padding:.15rem .45rem; border-radius:4px; font-size:.68rem; font-family:monospace; }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-truck"></i> Data Ground Support Equipment</h5>
        <p class="page-subtitle"><?= ($user_role ?? '') === 'ground_handling' ? 'Daftar seluruh unit GSE milik maskapai Anda yang terdaftar di sistem.' : 'Daftar seluruh unit GSE yang terdaftar di sistem.' ?></p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <button type="submit" form="formCetakSemuaStiker" id="btnCetakSemuaStiker"
                class="btn btn-outline-info btn-sm" disabled style="font-size: .72rem; border-radius: .5rem;">
            <i class="bi bi-qr-code"></i> Cetak Stiker Terpilih (<span id="jumlahTerpilih">0</span>)
        </button>
        <?php if (($user_role ?? '') === 'ground_handling'): ?>
        <button type="button" id="btnPerbaruiKontrak"
                class="btn btn-primary btn-sm" disabled style="font-size: .72rem; border-radius: .5rem;">
            <i class="bi bi-arrow-repeat"></i> Perbaharui Kontrak (<span id="jumlahTerpilihKontrak">0</span>)
        </button>
        <?php endif; ?>
    </div>
</div>

<?php
$aktif = 0;
foreach ($gse as $r) {
    if ($r->status === 'Aktif') $aktif++;
}
?>

<!-- Statistik -->
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-icon stat-icon-teal"><i class="bi bi-truck"></i></div>
        <div><div class="stat-value"><?= count($gse) ?></div><div class="stat-label">Total Unit GSE</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><i class="bi bi-check2-circle"></i></div>
        <div><div class="stat-value"><?= $aktif ?></div><div class="stat-label">Unit Aktif</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-gold"><i class="bi bi-exclamation-triangle"></i></div>
        <div><div class="stat-value"><?= $jumlah_segera_berakhir ?? 0 ?></div><div class="stat-label">Hampir Expired (≤30 Hari)</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-red"><i class="bi bi-x-circle"></i></div>
        <div><div class="stat-value"><?= $jumlah_berakhir ?? 0 ?></div><div class="stat-label">Kontrak / Pass Habis</div></div>
    </div>
</div>

<?php if (($jumlah_segera_berakhir ?? 0) > 0 || ($jumlah_berakhir ?? 0) > 0): ?>
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-3 p-3" style="background: #FDF2DF; border-left: 4px solid var(--gold-600) !important; border-radius: .75rem;">
    <i class="bi bi-bell-fill text-warning fs-5"></i>
    <div class="small">
        <div class="fw-bold text-dark mb-1">Pemberitahuan Status Masa Berlaku & Pass GSE</div>
        <div class="text-muted d-flex gap-3 flex-wrap">
            <?php if (($jumlah_berakhir ?? 0) > 0): ?>
                <span class="text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i><strong><?= $jumlah_berakhir ?> unit</strong> masa kontrak / pass telah habis (Expired)</span>
            <?php endif; ?>
            <?php if (($jumlah_segera_berakhir ?? 0) > 0): ?>
                <span class="text-warning-emphasis fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i><strong><?= $jumlah_segera_berakhir ?> unit</strong> masa kontrak / pass hampir habis (≤ 30 hari)</span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Table Card -->
<div class="table-card">
    <div class="table-card-head">
        <div class="d-flex align-items-center gap-2">
            <h6>Daftar Unit GSE</h6>
            <span class="count-pill"><?= count($gse) ?> Unit</span>
        </div>
        <div>
            <input type="text" id="searchGse" class="gse-search-input" placeholder="Cari unit GSE, No. Asset, Stiker...">
        </div>
    </div>

    <form id="formCetakSemuaStiker" method="post"
        action="<?= site_url('gse/cetak_semua_stiker') ?>" target="_blank">

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabelGse">
            <thead>
                <tr>
                    <th style="width:36px;" class="text-center">
                        <input type="checkbox" id="checkAllGse" title="Pilih semua" class="form-check-input">
                    </th>
                    <th>ID</th>
                    <th>Nama GSE</th>
                    <th>Manufacture</th>
                    <th>No. Asset</th>
                    <th>No. Rangka</th>
                    <th>No. Mesin</th>
                    <th>Sticker AP</th>
                    <th>Airlines</th>
                    <th>Status Unit dan Masa Berlaku</th>
                    <th>Masa Berlaku</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($gse)): ?>
                <tr>
                    <td colspan="12" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        Belum ada data GSE yang terdaftar.
                    </td>
                </tr>
                <?php else: foreach ($gse as $row): ?>
                <tr>
                    <td class="text-center">
                        <?php if ($row->status === 'Proses Perbaruan'): ?>
                        <input type="checkbox" disabled title="Sedang dalam proses perpanjangan kontrak" class="form-check-input">
                        <?php elseif ($row->status === 'Sedang Diperbaiki'): ?>
                        <input type="checkbox" disabled title="Unit sedang keluar untuk perbaikan" class="form-check-input">
                        <?php elseif ($row->status === 'Tidak Aktif'): ?>
                        <input type="checkbox" disabled title="Unit sedang Nonaktif" class="form-check-input">
                        <?php elseif (!empty($row->sticker_ap)): ?>
                        <input type="checkbox" name="id_gse[]" value="<?= $row->id_gse ?>" class="checkGseItem form-check-input">
                        <?php else: ?>
                        <input type="checkbox" disabled title="Belum ada Sticker AP" class="form-check-input">
                        <?php endif; ?>
                    </td>
                    <td><span class="reg-code">#<?= $row->id_gse ?></span></td>
                    <td>
                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row->nama_gse) ?></div>
                    </td>
                    <td>
                        <span class="badge bg-secondary stage-pill">
                            <?= htmlspecialchars($row->manufacture_type ?? '—') ?>
                        </span>
                    </td>
                    <td class="small text-muted"><?= htmlspecialchars($row->no_asset ?? '—') ?></td>
                    <td class="small text-muted"><?= strtolower($row->manufacture_type ?? '') === 'motorized' ? htmlspecialchars($row->nomor_rangka ?? '—') : '<span class="text-muted fst-italic">Tidak wajib</span>' ?></td>
                    <td class="small text-muted"><?= strtolower($row->manufacture_type ?? '') === 'motorized' ? htmlspecialchars($row->nomor_mesin ?? '—') : '<span class="text-muted fst-italic">Tidak wajib</span>' ?></td>
                    <td>
                        <?php if (!empty($row->sticker_ap)): ?>
                            <span class="badge bg-info"><?= htmlspecialchars($row->sticker_ap) ?></span>
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted"><?= htmlspecialchars($row->nama_airline ?? '—') ?></td>
                    <td>
                        <?php if ($row->status === 'Aktif'): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php elseif ($row->status === 'Proses Perbaruan'): ?>
                            <span class="badge bg-warning">Perpanjangan</span>
                        <?php elseif ($row->status === 'Sedang Diperbaiki'): ?>
                            <span class="badge bg-info">Diperbaiki</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Nonaktif</span>
                        <?php endif; ?>

                        <?php if (($row->status_kontrak ?? null) === 'berakhir'): ?>
                            <div class="mt-1">
                                <span class="badge bg-danger">Expired</span>
                            </div>
                        <?php elseif (($row->status_kontrak ?? null) === 'segera_berakhir'): ?>
                            <div class="mt-1">
                                <span class="badge bg-warning"><?= $row->sisa_hari ?> hari lagi</span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted">
                        <?php if (!empty($row->masa_mulai) && !empty($row->masa_selesai)): ?>
                            <?= date('d/m/Y', strtotime($row->masa_mulai)) ?> - <?= date('d/m/Y', strtotime($row->masa_selesai)) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="row-actions justify-content-end">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick='bukaModalDetailGse(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                    title="Lihat Detail">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            <?php if (!empty($row->sticker_ap)): ?>
                                <a href="javascript:void(0)"
                                   onclick="previewDokumen('<?= site_url('gse/cetak_stiker_item/' . $row->id_gse) ?>', 'Stiker Verifikasi - <?= htmlspecialchars($row->nama_gse) ?>')"
                                   class="btn btn-sm btn-outline-info" title="Cetak Stiker">
                                    <i class="bi bi-qr-code"></i> Stiker
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Belum memiliki Sticker AP">
                                    <i class="bi bi-qr-code"></i> Stiker
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    </form>
</div>

<!-- Modal Detail GSE -->
<div class="modal fade" id="modalDetailGse" tabindex="-1" aria-labelledby="modalDetailGseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 1rem;">
            <div class="modal-header border-bottom py-3" style="background: #FAFCFB;">
                <h6 class="modal-title mb-0 fw-bold" id="modalDetailGseLabel" style="color: var(--teal-800);">
                    <i class="bi bi-truck me-1" style="color: var(--gold-600);"></i> Detail Unit GSE
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1" id="detNamaGse">-</h6>
                        <div class="text-muted small" id="detAirline">-</div>
                    </div>
                    <span class="badge bg-secondary" id="detKategori">-</span>
                </div>

                <table class="table table-sm table-borderless mb-0" style="font-size: .78rem;">
                    <tr>
                        <th class="text-muted" style="width: 44%; font-weight: 500;">ID GSE</th>
                        <td class="fw-semibold" id="detIdGse">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">No. Asset</th>
                        <td class="fw-semibold" id="detNoAsset">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">No. Rangka</th>
                        <td id="detNoRangka">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">No. Mesin</th>
                        <td id="detNoMesin">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">Sticker AP</th>
                        <td id="detStickerAp">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">Status Operasional</th>
                        <td id="detStatusUnit">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">Status Masa Berlaku</th>
                        <td id="detStatusKontrak">-</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 500;">Masa Berlaku</th>
                        <td id="detMasaKontrak">-</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-between" style="background: #FAFCFB;">
                <div id="detCetakBtnWrapper"></div>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: .75rem; border-radius: .5rem;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function bukaModalDetailGse(data) {
    document.getElementById('detNamaGse').textContent = data.nama_gse || '-';
    document.getElementById('detAirline').textContent = data.nama_airline ? ('Airline: ' + data.nama_airline) : '-';
    document.getElementById('detKategori').textContent = data.manufacture_type || 'Non-Motorized';
    document.getElementById('detKategori').className = 'badge ' + (String(data.manufacture_type).toLowerCase() === 'motorized' ? 'bg-danger' : 'bg-secondary');
    
    document.getElementById('detIdGse').textContent = '#' + data.id_gse;
    document.getElementById('detNoAsset').textContent = data.no_asset || '-';
    document.getElementById('detNoRangka').textContent = (String(data.manufacture_type).toLowerCase() === 'motorized') ? (data.nomor_rangka || '-') : 'Tidak wajib';
    document.getElementById('detNoMesin').textContent = (String(data.manufacture_type).toLowerCase() === 'motorized') ? (data.nomor_mesin || '-') : 'Tidak wajib';
    
    if (data.sticker_ap) {
        document.getElementById('detStickerAp').innerHTML = '<span class="badge bg-info">' + data.sticker_ap + '</span>';
    } else {
        document.getElementById('detStickerAp').innerHTML = '<span class="text-muted">-</span>';
    }

    // Status unit
    var statusUnit = data.status || 'Aktif';
    var statusCls = (statusUnit === 'Aktif') ? 'bg-success' : ((statusUnit === 'Proses Perbaruan') ? 'bg-warning' : ((statusUnit === 'Sedang Diperbaiki') ? 'bg-info' : 'bg-secondary'));
    document.getElementById('detStatusUnit').innerHTML = '<span class="badge ' + statusCls + '">' + statusUnit + '</span>';

    // Status kontrak
    var statusKontrakHtml = '<span class="text-muted">-</span>';
    if (data.status_kontrak === 'berakhir') {
        statusKontrakHtml = '<span class="badge bg-danger">Habis (Expired)</span>';
    } else if (data.status_kontrak === 'segera_berakhir') {
        statusKontrakHtml = '<span class="badge bg-warning">Hampir Expired (' + (data.sisa_hari || '≤30') + ' hari)</span>';
    } else if (data.status_kontrak === 'aktif') {
        statusKontrakHtml = '<span class="badge bg-success">Aktif</span>';
    }
    document.getElementById('detStatusKontrak').innerHTML = statusKontrakHtml;

    // Masa kontrak
    var masaHtml = '-';
    if (data.masa_mulai && data.masa_selesai) {
        var formatTgl = function(iso) {
            if (!iso) return '-';
            var p = iso.split('-');
            if (p.length === 3) return p[2] + '/' + p[1] + '/' + p[0];
            return iso;
        };
        masaHtml = formatTgl(data.masa_mulai) + ' - ' + formatTgl(data.masa_selesai);
    }
    document.getElementById('detMasaKontrak').textContent = masaHtml;

    // Cetak button
    var wrapper = document.getElementById('detCetakBtnWrapper');
    if (data.sticker_ap) {
        wrapper.innerHTML = '<a href="javascript:void(0)" onclick="previewDokumen(\'<?= site_url('gse/cetak_stiker_item/') ?>' + data.id_gse + '\', \'Stiker Verifikasi - ' + (data.nama_gse ? data.nama_gse.replace(/'/g, "\\'") : '') + '\')" class="btn btn-sm btn-outline-info" style="font-size: .75rem; border-radius: .5rem;"><i class="bi bi-qr-code me-1"></i> Stiker</a>';
    } else {
        wrapper.innerHTML = '<button type="button" class="btn btn-sm btn-outline-secondary" disabled style="font-size: .75rem; border-radius: .5rem;"><i class="bi bi-qr-code me-1"></i> Stiker</button>';
    }

    new bootstrap.Modal(document.getElementById('modalDetailGse')).show();
}

(function () {
    var checkAll     = document.getElementById('checkAllGse');
    var btnCetak      = document.getElementById('btnCetakSemuaStiker');
    var jumlahSpan    = document.getElementById('jumlahTerpilih');
    var btnKontrak    = document.getElementById('btnPerbaruiKontrak');
    var jumlahKontrak = document.getElementById('jumlahTerpilihKontrak');

    function getItemChecks() {
        return document.querySelectorAll('.checkGseItem');
    }

    function updateBtnState() {
        var checkedBoxes = document.querySelectorAll('.checkGseItem:checked');
        var count = checkedBoxes.length;

        if (btnCetak && jumlahSpan) {
            jumlahSpan.textContent = count;
            btnCetak.disabled = (count === 0);
        }
        if (btnKontrak && jumlahKontrak) {
            jumlahKontrak.textContent = count;
            btnKontrak.disabled = (count === 0);
        }

        var allChecks = getItemChecks();
        if (checkAll && allChecks.length > 0) {
            checkAll.checked = (count === allChecks.length);
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            var checks = getItemChecks();
            checks.forEach(function (cb) {
                cb.checked = checkAll.checked;
            });
            updateBtnState();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList && e.target.classList.contains('checkGseItem')) {
            updateBtnState();
        }
    });

    if (btnKontrak) {
        btnKontrak.addEventListener('click', function () {
            var checkedBoxes = document.querySelectorAll('.checkGseItem:checked');
            if (checkedBoxes.length === 0) return;

            if (!confirm('Buat permohonan perbaruan kontrak untuk ' + checkedBoxes.length + ' unit GSE terpilih?')) {
                return;
            }

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('gse/perbarui_kontrak') ?>';

            checkedBoxes.forEach(function (cb) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_gse[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });
    }

    // Search bar — filter baris tabel di sisi client
    var searchInput = document.getElementById('searchGse');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var keyword = searchInput.value.trim().toLowerCase();
            var rows = document.querySelectorAll('#tabelGse tbody tr');
            rows.forEach(function (tr) {
                if (tr.querySelector('.empty-state')) return;
                var teks = tr.textContent.toLowerCase();
                tr.style.display = teks.includes(keyword) ? '' : 'none';
            });
        });
    }
})();
</script> 