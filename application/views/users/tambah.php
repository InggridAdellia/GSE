<div class="gse-page">

    <!-- Page Header -->
    <div class="page-header-bar mb-4">
        <div>
            <p class="page-eyebrow">
                <a href="<?= site_url('users') ?>" class="breadcrumb-link">Kelola User</a>
                <span class="breadcrumb-sep">›</span> Tambah
            </p>
            <h4 class="page-title">Tambah Akun User</h4>
            <p class="page-subtitle">Buat akun pengguna baru dengan role dan hak akses yang sesuai</p>
        </div>
    </div>

    <div class="form-layout">

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/><path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/></svg>
                </div>
                <div>
                    <p class="form-card-title">Informasi Akun</p>
                    <p class="form-card-desc">Lengkapi data pengguna dan tentukan role akses sistem</p>
                </div>
            </div>

            <?= form_open('users/simpan') ?>
            <div class="form-body">

                <!-- Row 1: Username & Password -->
                <div class="form-grid-2">
                    <div class="field-group">
                        <label class="field-label required">Username</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4z"/></svg>
                            <input type="text" name="username" class="field-input has-icon" required autocomplete="off" placeholder="Contoh: nama.lengkap">
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label required">Password</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>
                            <input type="password" name="password" id="password_tambah" class="field-input has-icon" required minlength="5" placeholder="Contoh: Admin@123">
                            <button type="button" class="toggle-pw" onclick="togglePw('password_tambah', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                            </button>
                        </div>
                        <p class="field-hint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                            Minimal 8 karakter, harus mengandung angka dan tanda baca
                        </p>
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div class="field-group">
                    <label class="field-label required">Nama Lengkap</label>
                    <input type="text" name="nama" class="field-input" required placeholder="Contoh: Nama Lengkap">
                </div>

                <div class="form-divider"><span>Hak Akses</span></div>

                <!-- Role -->
                <div class="field-group">
                    <label class="field-label required">Role</label>
                    <div class="select-wrapper">
                        <select name="role" id="role" class="field-input field-select" required onchange="cekRoleGH(this)">
                            <option value="">— Pilih Role —</option>
                            <option value="admin">Admin</option>
                            <option value="ground_handling">GH Airline</option>
                            <option value="unit_operasi">Airport Operation Airside</option>
                            <option value="unit_equipment">Airport Equipment</option>
                            <option value="unit_sales">Airport Non Aeronautical</option>
                            <option value="unit_security">Airport Security Protection</option>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                    </div>
                </div>

                <!-- Airline (kondisional) -->
                <div class="field-group airline-box hidden" id="box_airline">
                    <label class="field-label required">Airline</label>
                    <div class="select-wrapper">
                        <select name="id_airline" id="id_airline" class="field-input field-select">
                            <option value="">— Pilih Airline —</option>
                            <?php foreach ($airlines as $a): ?>
                            <option value="<?= $a->id_airline ?>"><?= htmlspecialchars($a->nama_airline) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                    </div>
                    <?php if (empty($airlines)): ?>
                    <p class="field-hint danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>
                        Belum ada airline terdaftar. Tambahkan dulu melalui menu Airlines.
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="<?= site_url('users') ?>" class="btn-cancel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4.414A1 1 0 0 0 14.707 4L12 1.293A1 1 0 0 0 11.293 1H2zm9.5 10h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0-3h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm-7-3h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/></svg>
                        Simpan Akun
                    </button>
                </div>

            </div>
            <?= form_close() ?>
        </div>

        <!-- Info Panel -->
        <div class="info-panel">
            <div class="info-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                Panduan Role
            </div>
            <div class="role-guide-list">
                <div class="role-guide-item" style="--rc:#6b70f0">
                    <span class="rg-dot"></span>
                    <div>
                        <p class="rg-name">Admin</p>
                        <p class="rg-desc">Akses penuh — manajemen user, GSE, airline, dan laporan</p>
                    </div>
                </div>
                <div class="role-guide-item" style="--rc:#1a56c4">
                    <span class="rg-dot"></span>
                    <div>
                        <p class="rg-name">Gound Handling Airline</p>
                        <p class="rg-desc">Mengajukan permohonan masuk GSE untuk maskapai tertentu. Wajib pilih airline.</p>
                    </div>
                </div>
                <div class="role-guide-item" style="--rc:#0a7a82">
                    <span class="rg-dot"></span>
                    <div>
                        <p class="rg-name">Airport Operation Airside</p>
                        <p class="rg-desc">Mereview dan menyetujui permohonan masuk GSE dari sisi operasi</p>
                    </div>
                </div>
                <div class="role-guide-item" style="--rc:#0099cc">
                    <span class="rg-dot"></span>
                    <div>
                        <p class="rg-name">Airport Equipment Operation</p>
                        <p class="rg-desc">Memverifikasi dan menerbitkan uji laik unit GSE yang masuk</p>
                    </div>
                </div>
                <div class="role-guide-item" style="--rc:#e6a800">
                    <span class="rg-dot"></span>
                    <div>
                        <p class="rg-name">Airport Non Aeronautical</p>
                        <p class="rg-desc">Mengelola aspek komersial dan non-penerbangan terkait GSE</p>
                    </div>
                </div>
                <div class="role-guide-item" style="--rc:#0099cc">
                    <span class="rg-dot"></span>
                    <div>
                        <p class="rg-name">Airport Security Protection</p>
                        <p class="rg-desc">Memverifikasi keamanan dan kelayakan unit GSE yang masuk</p>
                    </div>
                </div>
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

/* Layout */
.form-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

/* Form Card */
.form-card { background: #fff; border-radius: 16px; border: 1px solid #e5eef0; box-shadow: 0 2px 16px rgba(10,122,130,.07); overflow: hidden; }
.form-card-header {
    display: flex; align-items: center; gap: 14px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f0fafa, #e6f7f8);
    border-bottom: 1px solid #d6eef0;
}
.form-section-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    border-radius: 12px; display: flex; align-items: center; justify-content: center;
    color: #fff; flex-shrink: 0; box-shadow: 0 4px 12px rgba(10,122,130,.25);
}
.form-card-title { font-size: 15px; font-weight: 700; color: #0d2b2e; margin: 0 0 3px; }
.form-card-desc  { font-size: 12.5px; color: #6b8a8c; margin: 0; }

.form-body { padding: 24px; display: flex; flex-direction: column; gap: 18px; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-label { font-size: 12px; font-weight: 600; color: #3a5e62; letter-spacing: .01em; }
.field-label.required::after { content: ' *'; color: #e05a5a; }

/* Input with icon */
.input-icon-wrap { position: relative; }
.input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7a9a9c; pointer-events: none; }
.toggle-pw {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; padding: 4px; cursor: pointer; color: #7a9a9c;
    display: flex; align-items: center;
}
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

/* Select */
.select-wrapper { position: relative; }
.field-select { appearance: none; padding-right: 36px; cursor: pointer; }
.select-chevron { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #7a9a9c; pointer-events: none; }

/* Role desc pills */
.role-desc-grid { display: flex; flex-direction: column; gap: 5px; margin-top: 4px; }
.role-pill {
    font-size: 11.5px; color: #6b8a8c; padding: 5px 10px;
    border-radius: 7px; background: #f4f9fa;
    border-left: 3px solid var(--rc, #cbd8d9);
    transition: background .2s, color .2s; cursor: default;
}
.role-pill.active { background: color-mix(in srgb, var(--rc) 10%, white); color: #0d2b2e; font-weight: 600; }

/* Airline box animation */
.airline-box { transition: opacity .2s; }
.airline-box.hidden { display: none; }

/* Hints */
.field-hint { display: flex; align-items: flex-start; gap: 6px; font-size: 12px; color: #7a9a9c; margin: 0; line-height: 1.5; }
.field-hint.danger { color: #c0392b; }
.field-hint svg { flex-shrink: 0; margin-top: 1px; }

/* Divider */
.form-divider { display: flex; align-items: center; gap: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #9ab5b7; }
.form-divider::before, .form-divider::after { content: ''; flex: 1; height: 1px; background: #e5eef0; }

/* Actions */
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
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    border: none; cursor: pointer; transition: opacity .2s, transform .15s;
    box-shadow: 0 4px 14px rgba(10,122,130,.28);
}
.btn-submit:hover { opacity: .9; transform: translateY(-1px); }

/* Info / Role Guide Panel */
.info-panel { background: #fff; border-radius: 16px; border: 1px solid #e5eef0; box-shadow: 0 2px 12px rgba(10,122,130,.06); overflow: hidden; }
.info-panel-header {
    display: flex; align-items: center; gap: 8px; padding: 14px 18px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    color: #fff; font-size: 13px; font-weight: 700;
}
.role-guide-list { padding: 6px 0; }
.role-guide-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 18px; border-bottom: 1px solid #f0f5f6;
}
.role-guide-item:last-child { border-bottom: none; }
.rg-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--rc); flex-shrink: 0; margin-top: 5px; }
.rg-name { font-size: 12.5px; font-weight: 700; color: #0d2b2e; margin: 0 0 3px; }
.rg-desc { font-size: 11.5px; color: #6b8a8c; margin: 0; line-height: 1.5; }

@media (max-width: 900px) {
    .form-layout { grid-template-columns: 1fr; }
    .info-panel { order: -1; }
}
@media (max-width: 600px) {
    .form-grid-2 { grid-template-columns: 1fr; }
}
</style>

<script>
function cekRoleGH(sel) {
    const box = document.getElementById('box_airline');
    const select = document.getElementById('id_airline');
    const pills = document.querySelectorAll('.role-pill');

    // Show/hide airline box
    if (sel.value === 'ground_handling') {
        box.classList.remove('hidden');
        select.setAttribute('required', 'required');
    } else {
        box.classList.add('hidden');
        select.removeAttribute('required');
        select.value = '';
    }

    // Highlight active role pill
    pills.forEach(p => {
        p.classList.toggle('active', p.dataset.role === sel.value);
    });
}

function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '#7a9a9c' : '#0a7a82';
}
</script>