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
$current_rc = $role_colors[$user->role] ?? ['bg'=>'#f0f0f0','text'=>'#555','dot'=>'#aaa'];
$current_rl = $role_label[$user->role] ?? $user->role;
?>

<div class="gse-page">

    <!-- Page Header -->
    <div class="page-header-bar mb-4">
        <div>
            <p class="page-eyebrow">
                <a href="<?= site_url('users') ?>" class="breadcrumb-link">Kelola User</a>
                <span class="breadcrumb-sep">›</span> Edit Akun
            </p>
            <h4 class="page-title">Edit Akun User</h4>
            <p class="page-subtitle">Memperbarui data akun: <strong><?= htmlspecialchars($user->nama) ?></strong></p>
        </div>
        <div class="header-role-badge" style="background:<?= $current_rc['bg'] ?>;color:<?= $current_rc['text'] ?>;border-color:<?= $current_rc['dot'] ?>33;">
            <span class="hrb-dot" style="background:<?= $current_rc['dot'] ?>"></span>
            <?= $current_rl ?>
        </div>
    </div>

    <div class="form-layout">

        <div class="form-card">
            <div class="form-card-header edit-header">
                <div class="form-section-icon edit-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg>
                </div>
                <div>
                    <p class="form-card-title">Edit Informasi Akun</p>
                    <p class="form-card-desc">Kosongkan field password jika tidak ingin mengubahnya</p>
                </div>
            </div>

            <?= form_open('users/update/' . $user->id_user) ?>
            <div class="form-body">

                <!-- Row 1: Username & Password Baru -->
                <div class="form-grid-2">
                    <div class="field-group">
                        <label class="field-label required">Username</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4z"/></svg>
                            <input type="text" name="username" class="field-input has-icon" required autocomplete="off"
                                   value="<?= htmlspecialchars($user->username) ?>">
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Password Baru</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>
                            <input type="password" name="password" id="password_edit" class="field-input has-icon" minlength="8" placeholder="Kosongkan jika tidak diubah">
                            <button type="button" class="toggle-pw" onclick="togglePw('password_edit', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                            </button>
                        </div>
                        <p class="field-hint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                            Password minimal 8 karakter dengan angka dan tanda baca.
                        </p>
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div class="field-group">
                    <label class="field-label required">Nama Lengkap</label>
                    <input type="text" name="nama" class="field-input" required
                           value="<?= htmlspecialchars($user->nama) ?>">
                </div>

                <div class="form-divider"><span>Hak Akses</span></div>

                <!-- Role -->
                <div class="field-group">
                    <label class="field-label required">Role</label>
                    <div class="select-wrapper">
                        <select name="role" id="role" class="field-input field-select" required onchange="cekRoleGH(this)">
                            <option value="">— Pilih Role —</option>
                            <?php
                            $roles = [
                                'admin'           => 'Admin',
                                'ground_handling' => 'GH Airline',
                                'unit_operasi'    => 'Airport Operation Airside',
                                'unit_equipment'  => 'Airport Equipment',
                                'unit_sales'      => 'Airport Non Aeronautical',
                                'unit_security'   => 'Airport Security Protection',
                            ];
                            foreach ($roles as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $user->role === $val ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                    </div>
                </div>

                <!-- Airline (kondisional) -->
                <div class="field-group airline-box <?= $user->role === 'ground_handling' ? '' : 'hidden' ?>" id="box_airline">
                    <label class="field-label required">Airline</label>
                    <div class="select-wrapper">
                        <select name="id_airline" id="id_airline" class="field-input field-select">
                            <option value="">— Pilih Airline —</option>
                            <?php foreach ($airlines as $a): ?>
                            <option value="<?= $a->id_airline ?>" <?= ((int)$user->id_airline === (int)$a->id_airline) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a->nama_airline) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                    </div>
                </div>

                <!-- Warning -->
                <div class="change-notice">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                    Perubahan role akan langsung mempengaruhi hak akses pengguna ini di seluruh sistem.
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="<?= site_url('users') ?>" class="btn-cancel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4.414A1 1 0 0 0 14.707 4L12 1.293A1 1 0 0 0 11.293 1H2zm9.5 10h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0-3h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm-7-3h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>
            <?= form_close() ?>
        </div>

        <!-- Current Data Panel -->
        <div class="info-panel">
            <div class="info-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/></svg>
                Profil Saat Ini
            </div>

            <!-- User Preview -->
            <div class="user-preview">
                <div class="preview-avatar" style="background: linear-gradient(135deg, <?= $current_rc['dot'] ?>, <?= $current_rc['dot'] ?>88);">
                    <?= strtoupper(substr($user->nama, 0, 1)) ?>
                </div>
                <div class="preview-meta">
                    <p class="preview-nama"><?= htmlspecialchars($user->nama) ?></p>
                    <p class="preview-username">@<?= htmlspecialchars($user->username) ?></p>
                    <span class="preview-role-chip" style="background:<?= $current_rc['bg'] ?>;color:<?= $current_rc['text'] ?>;">
                        <span style="width:6px;height:6px;border-radius:50%;background:<?= $current_rc['dot'] ?>;display:inline-block;"></span>
                        <?= $current_rl ?>
                    </span>
                </div>
            </div>

            <div class="current-data">
                <div class="current-row">
                    <span class="current-label">ID User</span>
                    <span class="current-val mono">#<?= $user->id_user ?></span>
                </div>
                <div class="current-row">
                    <span class="current-label">Nama</span>
                    <span class="current-val"><?= htmlspecialchars($user->nama) ?></span>
                </div>
                <div class="current-row">
                    <span class="current-label">Username</span>
                    <span class="current-val mono">@<?= htmlspecialchars($user->username) ?></span>
                </div>
                <div class="current-row">
                    <span class="current-label">Role</span>
                    <span class="current-val"><?= $current_rl ?></span>
                </div>
                <?php if (!empty($user->id_airline)): ?>
                <div class="current-row">
                    <span class="current-label">Airline</span>
                    <span class="current-val"><?= htmlspecialchars($user->nama_airline ?? '—') ?></span>
                </div>
                <?php endif; ?>
            </div>
            <div class="info-footer">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                Data sebelum diubah
            </div>
        </div>

    </div>

</div>

<style>
.gse-page { padding: 0; }
.page-header-bar { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.page-eyebrow { font-size: 12px; font-weight: 500; color: #6b8a8c; margin: 0 0 4px; display: flex; align-items: center; gap: 6px; }
.breadcrumb-link { color: #0a7a82; text-decoration: none; font-weight: 600; }
.breadcrumb-link:hover { text-decoration: underline; }
.breadcrumb-sep { color: #9ab5b7; }
.page-title { font-size: 1.4rem; font-weight: 700; color: #0d2b2e; margin: 0 0 4px; letter-spacing: -.01em; }
.page-subtitle { font-size: 13px; color: #6b8a8c; margin: 0; }

/* Header role badge */
.header-role-badge {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; border-radius: 20px;
    font-size: 12.5px; font-weight: 700;
    border: 1.5px solid; white-space: nowrap;
}
.hrb-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

/* Layout */
.form-layout { display: grid; grid-template-columns: 1fr 280px; gap: 20px; align-items: start; }

/* Form Card */
.form-card { background: #fff; border-radius: 16px; border: 1px solid #e5eef0; box-shadow: 0 2px 16px rgba(10,122,130,.07); overflow: hidden; }
.form-card-header { display: flex; align-items: center; gap: 14px; padding: 20px 24px; border-bottom: 1px solid #e0d8f5; }
.edit-header { background: linear-gradient(135deg, #f8f4ff, #f0ebff); }
.form-section-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
.edit-icon { background: linear-gradient(135deg, #6b48cc, #9b70f0); box-shadow: 0 4px 12px rgba(100,60,200,.25); }
.form-card-title { font-size: 15px; font-weight: 700; color: #0d2b2e; margin: 0 0 3px; }
.form-card-desc  { font-size: 12.5px; color: #6b8a8c; margin: 0; }

.form-body { padding: 24px; display: flex; flex-direction: column; gap: 18px; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-label { font-size: 12px; font-weight: 600; color: #3a5e62; letter-spacing: .01em; }
.field-label.required::after { content: ' *'; color: #e05a5a; }

.input-icon-wrap { position: relative; }
.input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7a9a9c; pointer-events: none; }
.toggle-pw { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 4px; cursor: pointer; color: #7a9a9c; display: flex; align-items: center; }
.toggle-pw:hover { color: #0a7a82; }

.field-input {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #d4e8ea; border-radius: 10px;
    font-size: 13.5px; color: #0d2b2e; background: #fafefe;
    transition: border-color .2s, box-shadow .2s; outline: none;
    box-sizing: border-box; font-family: inherit;
}
.field-input.has-icon { padding-left: 36px; }
.field-input:focus { border-color: #0a7a82; background: #fff; box-shadow: 0 0 0 3px rgba(10,122,130,.12); }
.field-input::placeholder { color: #a0babc; }

.select-wrapper { position: relative; }
.field-select { appearance: none; padding-right: 36px; cursor: pointer; }
.select-chevron { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #7a9a9c; pointer-events: none; }

.airline-box { transition: opacity .2s; }
.airline-box.hidden { display: none; }

.field-hint { display: flex; align-items: flex-start; gap: 6px; font-size: 12px; color: #7a9a9c; margin: 0; line-height: 1.5; }
.field-hint svg { flex-shrink: 0; margin-top: 1px; }

.form-divider { display: flex; align-items: center; gap: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #9ab5b7; }
.form-divider::before, .form-divider::after { content: ''; flex: 1; height: 1px; background: #e5eef0; }

.change-notice {
    display: flex; align-items: flex-start; gap: 8px;
    background: #fff8e6; border: 1px solid #f5dfa8; border-radius: 10px;
    padding: 11px 14px; font-size: 12.5px; color: #7a5a00; line-height: 1.5;
}

.form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding-top: 6px; border-top: 1px solid #eef4f5; }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 10px;
    font-size: 13px; font-weight: 600; color: #5a7a7c;
    background: #f0f5f6; border: 1.5px solid #d4e8ea; text-decoration: none; transition: background .2s;
}
.btn-cancel:hover { background: #e5eef0; }
.btn-submit {
    display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: 10px;
    font-size: 13.5px; font-weight: 700; color: #fff;
    background: linear-gradient(135deg, #6b48cc, #9b70f0);
    border: none; cursor: pointer; transition: opacity .2s, transform .15s;
    box-shadow: 0 4px 14px rgba(100,60,200,.28);
}
.btn-submit:hover { opacity: .9; transform: translateY(-1px); }

/* Info Panel */
.info-panel { background: #fff; border-radius: 16px; border: 1px solid #e5eef0; box-shadow: 0 2px 12px rgba(10,122,130,.06); overflow: hidden; }
.info-panel-header {
    display: flex; align-items: center; gap: 8px; padding: 14px 18px;
    background: linear-gradient(135deg, #0d2b2e, #1a4a4e);
    color: #fff; font-size: 13px; font-weight: 700;
}

/* User Preview */
.user-preview {
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    padding: 20px 18px; border-bottom: 1px solid #f0f5f6; background: #f8fbfb; text-align: center;
}
.preview-avatar {
    width: 56px; height: 56px; border-radius: 16px;
    color: #fff; font-size: 24px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(0,0,0,.12);
}
.preview-nama { font-size: 14px; font-weight: 700; color: #0d2b2e; margin: 0 0 2px; }
.preview-username { font-size: 12px; color: #7a9a9c; margin: 0 0 8px; }
.preview-role-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 11.5px; font-weight: 600; white-space: nowrap;
}

.current-data { padding: 4px 0; }
.current-row { display: flex; flex-direction: column; padding: 11px 18px; border-bottom: 1px solid #f0f5f6; }
.current-row:last-child { border-bottom: none; }
.current-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #9ab5b7; margin-bottom: 3px; }
.current-val { font-size: 13px; color: #1a3a3e; font-weight: 500; }
.current-val.mono { font-family: 'Courier New', monospace; color: #0a7a82; font-size: 12.5px; }
.info-footer {
    display: flex; align-items: center; gap: 6px; padding: 10px 18px;
    background: #f8fbfb; border-top: 1px solid #eef4f5;
    font-size: 11.5px; color: #9ab5b7; font-style: italic;
}

@media (max-width: 900px) {
    .form-layout { grid-template-columns: 1fr; }
    .info-panel { order: -1; }
    .header-role-badge { display: none; }
}
@media (max-width: 600px) {
    .form-grid-2 { grid-template-columns: 1fr; }
}
</style>

<script>
function cekRoleGH(sel) {
    const box = document.getElementById('box_airline');
    const select = document.getElementById('id_airline');
    if (sel.value === 'ground_handling') {
        box.classList.remove('hidden');
        select.setAttribute('required', 'required');
    } else {
        box.classList.add('hidden');
        select.removeAttribute('required');
        select.value = '';
    }
}

function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '#7a9a9c' : '#0a7a82';
}
</script>