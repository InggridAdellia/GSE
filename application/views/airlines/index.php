<div class="gse-page">

    <!-- Page Header -->
    <div class="page-header-bar mb-4">
        <div>
            <p class="page-eyebrow">Manajemen Data</p>
            <h4 class="page-title">Kelola Airline</h4>
            <p class="page-subtitle">Daftar maskapai penerbangan yang terdaftar dalam sistem</p>
        </div>
        <a href="<?= site_url('airlines/tambah') ?>" class="btn-primary-custom">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1.5a.5.5 0 0 1 .5.5v5.5H14a.5.5 0 0 1 0 1H8.5V14a.5.5 0 0 1-1 0V8.5H2a.5.5 0 0 1 0-1h5.5V2a.5.5 0 0 1 .5-.5z"/></svg>
            Tambah Airline
        </a>
    </div>

    <!-- Stats Row -->
    <div class="al-stats-row mb-4">
        <div class="al-stat-card">
            <span class="stat-label">Total Airline</span>
            <span class="stat-value"><?= count($airlines) ?></span>
        </div>
        <div class="al-stat-card stat-active">
            <span class="stat-label">Aktif</span>
            <?php
            $airlines_aktif = 0;
            foreach ($airlines as $a) {
                if ($a->status === 'Aktif') $airlines_aktif++;
            }
            ?>
            <span class="stat-value"><?= $airlines_aktif ?></span>
        </div>
        <div class="al-stat-card stat-inactive">
            <span class="stat-label">Tidak Aktif</span>
            <?php
            $airlines_nonaktif = 0;
            foreach ($airlines as $a) {
                if ($a->status !== 'Aktif') $airlines_nonaktif++;
            }
            ?>
            <span class="stat-value"><?= $airlines_nonaktif ?></span>
        </div>
    </div>

    <!-- Table Card -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title-group">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16" class="icon-teal"><path d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849z"/></svg>
                <span>Data Maskapai</span>
            </div>
            <span class="data-card-count"><?= count($airlines) ?> airline terdaftar</span>
        </div>

        <div class="table-responsive">
            <table class="gse-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>ID Airline</th>
                        <th>Nama Airline</th>
                        <th>Kode IATA</th>
                        <th>Dibuat</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($airlines)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16"><path d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849z"/></svg>
                            </div>
                            <p class="empty-title">Belum ada data airline</p>
                            <p class="empty-desc">Klik <strong>Tambah Airline</strong> untuk mendaftarkan maskapai pertama.</p>
                        </td>
                    </tr>
                    <?php else: $no = 1; foreach ($airlines as $a): ?>
                    <tr>
                        <td class="row-num"><?= $no++ ?></td>
                        <td>
                            <div class="airline-name-cell">
                                <div class="airline-avatar">
                                    <?= strtoupper(substr($a->id_airline, 0, 1)) ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="airline-name-cell">
                                <div class="airline-avatar">
                                    <?= strtoupper(substr($a->nama_airline, 0, 1)) ?>
                                </div>
                                <span class="fw-medium"><?= htmlspecialchars($a->nama_airline) ?></span>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($a->kode_airline)): ?>
                                <code class="iata-code"><?= htmlspecialchars($a->kode_airline) ?></code>
                            <?php else: ?>
                                <span class="text-muted-sm">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted-sm"><?= date('d M Y', strtotime($a->created_at)) ?></td>
                        <td>
                            <?php if ($a->status === 'Aktif'): ?>
                                <span class="badge-aktif">● Aktif</span>
                            <?php else: ?>
                                <span class="badge-nonaktif">● Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <div class="action-group">
                                <a href="<?= site_url('airlines/edit/' . $a->id_airline) ?>" class="btn-icon" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg>
                                    Edit
                                </a>
                                <a href="<?= site_url('airlines/ubah_status/' . $a->id_airline) ?>"
                                   class="btn-toggle <?= $a->status === 'Aktif' ? 'btn-toggle-off' : 'btn-toggle-on' ?>"
                                   onclick="return confirm('Ubah status airline ini?')">
                                    <?= $a->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
.gse-page { padding: 0; }

/* Header */
.page-header-bar { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.page-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #0a7a82; margin: 0 0 4px; }
.page-title { font-size: 1.4rem; font-weight: 700; color: #0d2b2e; margin: 0 0 4px; letter-spacing: -.01em; }
.page-subtitle { font-size: 13px; color: #6b8a8c; margin: 0; }

.btn-primary-custom {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    color: #fff !important; font-size: 13.5px; font-weight: 600;
    padding: 9px 18px; border-radius: 10px; text-decoration: none;
    white-space: nowrap; box-shadow: 0 4px 14px rgba(10,122,130,.28);
    transition: opacity .2s, transform .15s;
}
.btn-primary-custom:hover { opacity: .9; transform: translateY(-1px); }

/* Stats */
.al-stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.al-stat-card {
    background: #fff; border-radius: 14px; padding: 18px 20px;
    display: flex; flex-direction: column; gap: 6px;
    border: 1px solid #e8f0f1; border-left: 4px solid #cbd8d9;
    box-shadow: 0 2px 8px rgba(10,122,130,.06);
}
.al-stat-card.stat-active  { border-left-color: #0a7a82; }
.al-stat-card.stat-inactive { border-left-color: #c0c8ce; }
.stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: #7a9a9c; }
.stat-value { font-size: 1.8rem; font-weight: 700; color: #0d2b2e; line-height: 1; }

/* Data Card */
.data-card { background: #fff; border-radius: 16px; border: 1px solid #e5eef0; box-shadow: 0 2px 16px rgba(10,122,130,.07); overflow: hidden; }
.data-card-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 22px; border-bottom: 1px solid #eef4f5; background: #f8fbfb; }
.data-card-title-group { display: flex; align-items: center; gap: 9px; font-size: 13.5px; font-weight: 600; color: #0d2b2e; }
.icon-teal { color: #0a7a82; }
.data-card-count { font-size: 12px; color: #8aa6a8; background: #edf6f7; padding: 4px 10px; border-radius: 20px; font-weight: 500; }

/* Table */
.gse-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.gse-table thead tr { background: #f4f9fa; }
.gse-table thead th { padding: 11px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #6b8a8c; border-bottom: 1px solid #e5eef0; white-space: nowrap; }
.gse-table tbody tr { border-bottom: 1px solid #f0f5f6; transition: background .15s; }
.gse-table tbody tr:last-child { border-bottom: none; }
.gse-table tbody tr:hover { background: #f7fbfc; }
.gse-table td { padding: 12px 14px; vertical-align: middle; color: #1a3a3e; }
.row-num { color: #9ab5b7; font-size: 12px; font-weight: 600; }
.fw-medium { font-weight: 600; }
.text-muted-sm { color: #7a9a9c; font-size: 12.5px; }
.text-right { text-align: right; }

/* Airline name with avatar */
.airline-name-cell { display: flex; align-items: center; gap: 10px; }
.airline-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

/* IATA Code */
.iata-code {
    background: #edf6f7; color: #0a7a82;
    padding: 3px 9px; border-radius: 6px;
    font-size: 12px; font-family: 'Courier New', monospace;
    font-weight: 700; letter-spacing: .08em;
}

/* Badges */
.badge-aktif    { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #e6f7f3; color: #0a7a82; }
.badge-nonaktif { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #f0f0f0; color: #888; }

/* Action Group */
.action-group { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
.btn-icon {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 7px;
    font-size: 12px; font-weight: 600;
    color: #3a6b8a; background: #eaf0f7;
    border: 1px solid #c8dae8; text-decoration: none;
    transition: background .2s;
}
.btn-icon:hover { background: #d8eaf5; }
.btn-toggle {
    display: inline-block; padding: 5px 12px; border-radius: 7px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    transition: opacity .2s; white-space: nowrap;
}
.btn-toggle-off { background: #fdf3e6; color: #b96b00; border: 1px solid #f5d9a8; }
.btn-toggle-on  { background: #e6f7f3; color: #0a7a82; border: 1px solid #b0dfe0; }
.btn-toggle:hover { opacity: .8; }

/* Empty State */
.empty-state { text-align: center; padding: 60px 20px; }
.empty-icon { color: #c8dcde; margin-bottom: 14px; }
.empty-title { font-size: 15px; font-weight: 600; color: #4a6b6e; margin: 0 0 6px; }
.empty-desc { font-size: 13px; color: #9ab5b7; margin: 0; }

@media (max-width: 768px) {
    .al-stats-row { grid-template-columns: repeat(3, 1fr); }
    .page-header-bar { flex-direction: column; align-items: flex-start; }
}
</style>