<?php
$stat       = $stat       ?? ['success' => 0, 'failed' => 0, 'blocked' => 0];
$log        = $log        ?? [];
$blocked    = $blocked    ?? [];
$mencurigai = $mencurigai ?? [];
$ancaman    = $ancaman    ?? [];
$aktivitas  = $aktivitas  ?? [];
?>

<style>
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.92rem; margin-bottom:0; }

    .stat-row{ display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; margin-bottom:1.25rem; }
    .stat-card{ background:#fff; border-radius:1rem; padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); }
    .stat-icon{ width:42px; height:42px; border-radius:.7rem; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
    .stat-icon-green{ background:#E9F6EE; color:#1F6F45; }
    .stat-icon-red{ background:#FDECE8; color:#A23B2A; }
    .stat-icon-gold{ background:#FDF2DF; color:var(--gold-600); }
    .stat-value{ font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:1.35rem; color:var(--ink-900); line-height:1.1; }
    .stat-label{ font-size:.78rem; color:var(--ink-600); }

    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.76rem; font-weight:600; padding:.25rem .65rem; border-radius:999px; }

    .badge-soft{ display:inline-flex; align-items:center; gap:.3rem; font-size:.74rem; font-weight:600; padding:.3rem .6rem; border-radius:999px; white-space:nowrap; }
    .badge-success{ background:#E9F6EE; color:#1F6F45; }
    .badge-danger{ background:#FDECE8; color:#A23B2A; }
    .badge-warning{ background:#FDF2DF; color:var(--gold-600); }
    .badge-secondary{ background:#EEF1F1; color:var(--ink-600); }
    .badge-info{ background:#E6F1FB; color:#1D5C9E; }
    .badge-purple{ background:#F1EAFB; color:#6b48cc; }

    .ip-chip{ font-family:monospace; font-size:.82rem; background:var(--mint-50); color:var(--teal-800); padding:.2rem .5rem; border-radius:.4rem; }

    /* Setiap tabel punya area scroll sendiri, supaya scroll di satu tabel
       tidak menggeser posisi card/tabel lain di halaman */
    .scroll-box{ max-height:420px; overflow-y:auto; }
    .scroll-box thead th{
        position:sticky; top:0; z-index:2;
        background:#fff; box-shadow:0 1px 0 var(--line);
    }
    /* Scrollbar tipis biar rapi */
    .scroll-box::-webkit-scrollbar{ width:8px; }
    .scroll-box::-webkit-scrollbar-thumb{ background:var(--line); border-radius:999px; }
    .scroll-box::-webkit-scrollbar-track{ background:transparent; }

    @media(max-width:767.98px){ .stat-row{ grid-template-columns:1fr; } }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-shield-check"></i> IDS — Monitoring Login</h5>
        <p class="page-subtitle">Pantau aktivitas login dan deteksi ancaman keamanan sistem.</p>
    </div>
    <span class="text-muted small">Update otomatis setiap refresh &bull; <?= date('d-m-Y H:i:s') ?></span>
</div>

<!-- Statistik hari ini -->
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-icon stat-icon-green"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value"><?= $stat['success'] ?></div>
            <div class="stat-label">Login Berhasil Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-red"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-value"><?= $stat['failed'] ?></div>
            <div class="stat-label">Login Gagal Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-gold"><i class="bi bi-ban"></i></div>
        <div>
            <div class="stat-value"><?= $stat['blocked'] ?></div>
            <div class="stat-label">IP Diblokir Aktif</div>
        </div>
    </div>
</div>

<!-- IP Mencurigakan -->
<?php if (!empty($mencurigai)): ?>
<div class="card border-0 mb-3" style="border-left:4px solid #A23B2A !important;">
    <div class="table-card-head">
        <h6><i class="bi bi-exclamation-triangle text-danger"></i> IP Mencurigakan Hari Ini</h6>
        <span class="count-pill"><?= count($mencurigai) ?> IP</span>
    </div>
    <div class="card-body p-0">
        <div class="scroll-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Total Gagal</th>
                    <th>Terakhir Percobaan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mencurigai as $row): ?>
                <tr>
                    <td><span class="ip-chip"><?= htmlspecialchars($row->ip_address) ?></span></td>
                    <td><span class="badge-soft badge-danger"><?= $row->total_gagal ?>x gagal</span></td>
                    <td class="small text-muted"><?= date('d-m-Y H:i:s', strtotime($row->terakhir)) ?></td>
                    <td class="text-end">
                        <a href="<?= site_url('ids/block_manual/' . urlencode($row->ip_address)) ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Blokir permanen IP <?= htmlspecialchars($row->ip_address) ?>?')">
                            <i class="bi bi-ban"></i> Blokir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- IP Diblokir -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-ban"></i> Daftar IP Diblokir</h6>
        <span class="count-pill"><?= count($blocked) ?> aktif</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scroll-box">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Alasan</th>
                        <th>Diblokir Sejak</th>
                        <th>Blokir Hingga</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($blocked)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada IP yang diblokir.</td></tr>
                    <?php else: foreach ($blocked as $row): ?>
                    <tr>
                        <td><span class="ip-chip"><?= htmlspecialchars($row->ip_address) ?></span></td>
                        <td class="small"><?= htmlspecialchars($row->alasan) ?></td>
                        <td class="small text-muted"><?= date('d-m-Y H:i:s', strtotime($row->blocked_at)) ?></td>
                        <td class="small">
                            <?= $row->blocked_until
                                ? date('d-m-Y H:i', strtotime($row->blocked_until))
                                : '<span class="badge-soft badge-danger">Permanen</span>' ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= site_url('ids/unblock/' . $row->id) ?>"
                               class="btn btn-sm btn-outline-success"
                               onclick="return confirm('Unblock IP ini?')">
                                <i class="bi bi-unlock"></i> Unblock
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ancaman Terdeteksi (SQLi, XSS, Credential Stuffing, dll) -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-bug"></i> Daftar Ancaman Terdeteksi</h6>
        <span class="count-pill"><?= count($ancaman) ?> entri</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scroll-box">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>IP Address</th>
                        <th>Payload / Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ancaman)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada ancaman terdeteksi.</td></tr>
                    <?php else: foreach ($ancaman as $row): ?>
                    <tr>
                        <td class="small text-muted"><?= date('d-m-Y H:i:s', strtotime($row->created_at)) ?></td>
                        <td>
                            <?php
                                $tipe_label = [
                                    'sql_injection'       => ['badge-danger',  'bi-database-x',       'SQL Injection'],
                                    'xss'                 => ['badge-warning', 'bi-code-slash',       'XSS'],
                                    'credential_stuffing' => ['badge-purple',  'bi-people-fill',      'Credential Stuffing'],
                                    'anomali_waktu'       => ['badge-info',    'bi-clock-history',    'Anomali Waktu'],
                                    'ip_baru'             => ['badge-secondary','bi-geo-alt',         'IP Baru'],
                                ];
                                $t = $tipe_label[$row->tipe] ?? ['badge-secondary', 'bi-question-circle', $row->tipe];
                            ?>
                            <span class="badge-soft <?= $t[0] ?>"><i class="bi <?= $t[1] ?>"></i> <?= htmlspecialchars($t[2]) ?></span>
                        </td>
                        <td><span class="ip-chip"><?= htmlspecialchars($row->ip_address) ?></span></td>
                        <td class="small text-muted" style="max-width:320px; overflow-wrap:break-word;">
                            <?= htmlspecialchars($row->payload) ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Log Login Terbaru -->
<div class="card border-0 mb-3">
    <div class="table-card-head">
        <h6><i class="bi bi-clock-history"></i> Log Login Terbaru</h6>
        <span class="count-pill"><?= count($log) ?> entri</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scroll-box">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Username</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($log)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada log.</td></tr>
                    <?php else: foreach ($log as $row): ?>
                    <tr>
                        <td class="small text-muted"><?= date('d-m-Y H:i:s', strtotime($row->created_at)) ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row->username) ?></td>
                        <td><span class="ip-chip"><?= htmlspecialchars($row->ip_address) ?></span></td>
                        <td>
                            <?php if ($row->status === 'success'): ?>
                                <span class="badge-soft badge-success"><i class="bi bi-check-circle"></i> Berhasil</span>
                            <?php else: ?>
                                <span class="badge-soft badge-danger"><i class="bi bi-x-circle"></i> Gagal</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?= htmlspecialchars($row->keterangan) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════ TAMBAHAN BARU: Log Aktivitas User ═══════════════ -->
<div class="card border-0">
    <div class="table-card-head">
        <h6><i class="bi bi-journal-text"></i> Log Aktivitas User</h6>
        <span class="count-pill"><?= count($aktivitas) ?> entri</span>
    </div>

    <!-- Filter -->
    <div class="p-3 border-bottom">
        <form method="get" action="<?= site_url('ids') ?>#log-aktivitas" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Username</label>
                <input type="text" name="username" class="form-control form-control-sm"
                       placeholder="Cari username..."
                       value="<?= htmlspecialchars($filter['username'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($filter['tanggal_dari'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($filter['tanggal_sampai'] ?? '') ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="<?= site_url('ids') ?>#log-aktivitas" class="btn btn-sm btn-outline-secondary flex-fill">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="card-body p-0" id="log-aktivitas">
        <div class="table-responsive scroll-box">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>IP Address</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($aktivitas)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada log aktivitas yang cocok dengan filter.</td></tr>
                    <?php else: foreach ($aktivitas as $row): ?>
                    <tr>
                        <td class="small text-muted"><?= date('d-m-Y H:i:s', strtotime($row->created_at)) ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row->username) ?></td>
                        <td class="small"><?= htmlspecialchars($row->role ?? '-') ?></td>
                        <td><span class="ip-chip"><?= htmlspecialchars($row->ip_address) ?></span></td>
                        <td><span class="badge-soft badge-info"><?= htmlspecialchars($row->modul) ?></span></td>
                        <td class="small"><?= htmlspecialchars($row->aksi) ?></td>
                        <td class="small text-muted" style="max-width:280px; overflow-wrap:break-word;"><?= htmlspecialchars($row->keterangan) ?></td>
                        <td>
                            <?php if ($row->status === 'success'): ?>
                                <span class="badge-soft badge-success"><i class="bi bi-check-circle"></i> Berhasil</span>
                            <?php else: ?>
                                <span class="badge-soft badge-danger"><i class="bi bi-x-circle"></i> Gagal</span>
                             <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
