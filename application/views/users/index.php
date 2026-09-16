<?php
$role_colors = [
    'admin'           => ['bg' => '#eeeefc', 'text' => '#3a3ab0', 'dot' => '#6b70f0'],
    'ground_handling' => ['bg' => '#e8f0fe', 'text' => '#1a56c4', 'dot' => '#1a56c4'],
    'unit_operasi'    => ['bg' => '#e6f7f3', 'text' => '#0a7a82', 'dot' => '#0a7a82'],
    'unit_equipment'  => ['bg' => '#e8f8ff', 'text' => '#0077a8', 'dot' => '#0099cc'],
    'unit_sales'      => ['bg' => '#fff8e0', 'text' => '#7a5a00', 'dot' => '#e6a800'],
    'unit_security'   => ['bg' => '#fdeceb', 'text' => '#a23b2a', 'dot' => '#c0392b'],
];
$role_label = [
    'admin'           => 'Admin',
    'ground_handling' => 'GH Airline',
    'unit_operasi'    => 'Airport Operation Airside',
    'unit_equipment'  => 'Airport Equipment',
    'unit_sales'      => 'Airport Non Aeronautical',
    'unit_security'   => 'Airport Security Protection',
];

// Count per role
$count_by_role = [];
foreach ((array)$users as $u) {
    $count_by_role[$u->role] = ($count_by_role[$u->role] ?? 0) + 1;
}
?>

<div class="gse-page">

    <!-- Page Header -->
    <div class="page-header-bar mb-4">
        <div>
            <p class="page-eyebrow">Manajemen Sistem</p>
            <h4 class="page-title">Kelola User</h4>
            <p class="page-subtitle">Akun pengguna dan hak akses dalam sistem AirGate GSE</p>
        </div>
        <a href="<?= site_url('users/tambah') ?>" class="btn-primary-custom">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/><path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/></svg>
            Tambah User
        </a>
    </div>

    <!-- Stats Row -->
    <div class="user-stats-row mb-4">
        <div class="user-stat-card">
            <span class="stat-label">Total User</span>
            <span class="stat-value"><?= count($users) ?></span>
        </div>
        <div class="user-stat-card stat-active">
            <span class="stat-label">Aktif</span>
            <?php
            $users_aktif = 0;
            foreach ($users as $u) {
                if ($u->status === 'Aktif') $users_aktif++;
            }
            ?>
            <span class="stat-value"><?= $users_aktif ?></span>
        </div>
        <?php foreach ($role_label as $rval => $rlabel): ?>
        <?php if (!empty($count_by_role[$rval])): ?>
        <div class="user-stat-card" style="border-left-color: <?= $role_colors[$rval]['dot'] ?? '#cbd8d9' ?>">
            <span class="stat-label"><?= $rlabel ?></span>
            <span class="stat-value"><?= $count_by_role[$rval] ?></span>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Table Card -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title-group">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16" class="icon-teal"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
                <span>Daftar Akun User</span>
            </div>
            <span class="data-card-count"><?= count($users) ?> akun terdaftar</span>
        </div>

        <div class="table-responsive">
            <table class="gse-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Airline</th>
                        <th>Dibuat</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
                            </div>
                            <p class="empty-title">Belum ada data user</p>
                            <p class="empty-desc">Klik <strong>Tambah User</strong> untuk membuat akun pertama.</p>
                        </td>
                    </tr>
                    <?php else: $no = 1; foreach ($users as $u):
                        $is_self = (int)$u->id_user === (int)$this->session->userdata('id_user');
                        $rc = $role_colors[$u->role] ?? ['bg'=>'#f0f0f0','text'=>'#555','dot'=>'#aaa'];
                    ?>
                    <tr <?= $is_self ? 'class="row-self"' : '' ?>>
                        <td class="row-num"><?= $no++ ?></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar" style="background: linear-gradient(135deg, <?= $rc['dot'] ?>, <?= $rc['dot'] ?>99);">
                                    <?= strtoupper(substr($u->nama, 0, 1)) ?>
                                </div>
                                <div class="user-info">
                                    <span class="user-nama"><?= htmlspecialchars($u->nama) ?></span>
                                    <span class="user-username">@<?= htmlspecialchars($u->username) ?>
                                        <?php if ($is_self): ?>
                                        <span class="self-badge">Anda</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge" style="background:<?= $rc['bg'] ?>;color:<?= $rc['text'] ?>;">
                                <span class="role-dot" style="background:<?= $rc['dot'] ?>;"></span>
                                <?= $role_label[$u->role] ?? $u->role ?>
                            </span>
                        </td>
                        <td class="text-muted-sm"><?= htmlspecialchars($u->nama_airline ?? '—') ?></td>
                        <td class="text-muted-sm"><?= date('d M Y', strtotime($u->created_at)) ?></td>
                        <td>
                            <?php if ($u->status === 'Aktif'): ?>
                                <span class="badge-aktif">● Aktif</span>
                            <?php else: ?>
                                <span class="badge-nonaktif">● Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <div class="action-group">
                                <a href="<?= site_url('users/edit/' . $u->id_user) ?>" class="btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg>
                                    Edit
                                </a>
                                <?php if (!$is_self): ?>
                                <a href="<?= site_url('users/ubah_status/' . $u->id_user) ?>"
                                   class="btn-toggle <?= $u->status === 'Aktif' ? 'btn-toggle-off' : 'btn-toggle-on' ?>"
                                   onclick="return confirm('Ubah status user <?= htmlspecialchars($u->username) ?>?')">
                                    <?= $u->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                </a>
                                <?php else: ?>
                                <span class="btn-self-lock" title="Tidak bisa mengubah status akun sendiri">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>
                                    Akun Anda
                                </span>
                                <?php endif; ?>
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
.page-header-bar { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.page-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #0a7a82; margin: 0 0 4px; }
.page-title { font-size: 1.4rem; font-weight: 700; color: #0d2b2e; margin: 0 0 4px; letter-spacing: -.01em; }
.page-subtitle { font-size: 13px; color: #6b8a8c; margin: 0; }

.btn-primary-custom {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6); color: #fff !important;
    font-size: 13.5px; font-weight: 600; padding: 9px 18px; border-radius: 10px;
    text-decoration: none; white-space: nowrap;
    box-shadow: 0 4px 14px rgba(10,122,130,.28); transition: opacity .2s, transform .15s;
}
.btn-primary-custom:hover { opacity: .9; transform: translateY(-1px); }

/* Stats */
.user-stats-row { display: flex; gap: 12px; flex-wrap: wrap; }
.user-stat-card {
    background: #fff; border-radius: 14px; padding: 16px 20px;
    display: flex; flex-direction: column; gap: 5px;
    border: 1px solid #e8f0f1; border-left: 4px solid #cbd8d9;
    box-shadow: 0 2px 8px rgba(10,122,130,.05);
    min-width: 120px; flex: 1;
}
.user-stat-card.stat-active { border-left-color: #0a7a82; }
.stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: #7a9a9c; }
.stat-value { font-size: 1.75rem; font-weight: 700; color: #0d2b2e; line-height: 1; }

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
.gse-table tbody tr.row-self { background: #f5f8ff; }
.gse-table td { padding: 11px 14px; vertical-align: middle; color: #1a3a3e; }
.row-num { color: #9ab5b7; font-size: 12px; font-weight: 600; }
.text-muted-sm { color: #7a9a9c; font-size: 12.5px; }
.text-right { text-align: right; }

/* User Cell */
.user-cell { display: flex; align-items: center; gap: 10px; }
.user-avatar {
    width: 34px; height: 34px; border-radius: 9px;
    color: #fff; font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.user-info { display: flex; flex-direction: column; gap: 2px; }
.user-nama { font-size: 13px; font-weight: 600; color: #0d2b2e; }
.user-username { font-size: 11.5px; color: #7a9a9c; display: flex; align-items: center; gap: 5px; }
.self-badge {
    background: #edf6f7; color: #0a7a82; padding: 1px 7px; border-radius: 10px;
    font-size: 10.5px; font-weight: 700; letter-spacing: .03em;
}

/* Role Badge */
.role-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11.5px; font-weight: 600; white-space: nowrap;
}
.role-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

/* Status Badges */
.badge-aktif    { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #e6f7f3; color: #0a7a82; }
.badge-nonaktif { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #f0f0f0; color: #888; }

/* Actions */
.action-group { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
.btn-icon {
    display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; border-radius: 7px;
    font-size: 12px; font-weight: 600; color: #3a6b8a; background: #eaf0f7;
    border: 1px solid #c8dae8; text-decoration: none; transition: background .2s;
}
.btn-icon:hover { background: #d8eaf5; }
.btn-toggle { display: inline-block; padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 600; text-decoration: none; transition: opacity .2s; white-space: nowrap; }
.btn-toggle-off { background: #fdf3e6; color: #b96b00; border: 1px solid #f5d9a8; }
.btn-toggle-on  { background: #e6f7f3; color: #0a7a82; border: 1px solid #b0dfe0; }
.btn-toggle:hover { opacity: .8; }
.btn-self-lock {
    display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; border-radius: 7px;
    font-size: 12px; font-weight: 500; color: #aab8ba; background: #f5f7f8;
    border: 1px dashed #d0dcde; cursor: default; white-space: nowrap;
}

/* Empty State */
.empty-state { text-align: center; padding: 60px 20px; }
.empty-icon { color: #c8dcde; margin-bottom: 14px; }
.empty-title { font-size: 15px; font-weight: 600; color: #4a6b6e; margin: 0 0 6px; }
.empty-desc { font-size: 13px; color: #9ab5b7; margin: 0; }

@media (max-width: 768px) {
    .user-stats-row { display: grid; grid-template-columns: 1fr 1fr; }
    .page-header-bar { flex-direction: column; align-items: flex-start; }
}
</style> 