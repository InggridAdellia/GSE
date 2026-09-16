<div class="gse-page">

    <!-- Page Header -->
    <div class="page-header-bar mb-4">
        <div>
            <p class="page-eyebrow">
                <a href="<?= site_url('gse') ?>" class="breadcrumb-link">Ground Support Equipment</a>
                <span class="breadcrumb-sep">›</span> Tambah Unit
            </p>
            <h4 class="page-title">Tambah Data GSE</h4>
            <p class="page-subtitle">Daftarkan unit Ground Support Equipment baru ke dalam sistem</p>
        </div>
    </div>

    <div class="form-layout">
        <div class="form-card">

            <div class="form-card-header">
                <div class="form-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7z"/></svg>
                </div>
                <div>
                    <p class="form-card-title">Informasi Unit GSE</p>
                    <p class="form-card-desc">Lengkapi seluruh detail unit yang akan didaftarkan</p>
                </div>
            </div>

            <?= form_open('gse/simpan') ?>

            <!-- Row 1: Nama GSE -->
            <div class="form-grid-2">
                <div class="field-group">
                    <label class="field-label required">Nama GSE</label>
                    <input type="text" name="nama_gse" class="field-input" placeholder="Contoh: Towing Bar" required>
                </div>
            </div>

            <!-- Row 2: Manufacture -->
            <div class="field-group">
                <label class="field-label required">Manufacture Type / Model / No. Seri</label>
                <div class="select-wrapper">
                    <select name="manufacture_type" class="field-input field-select" required>
                        <option value="">— Pilih Tipe —</option>
                        <option value="Motorized">Motorized</option>
                        <option value="Non Motorized">Non Motorized</option>
                    </select>
                    <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                </div>
            </div>

            <!-- Divider -->
            <div class="form-divider">
                <span>Identifikasi & Kepemilikan</span>
            </div>

            <!-- Row 3: No. Asset -->
            <div class="form-grid-2">
                <div class="field-group">
                    <label class="field-label">No. Asset</label>
                    <input type="text" name="no_asset" class="field-input field-mono" placeholder="Contoh: TB-ATR-004">
                </div>
            </div>

            <!-- Row 4: Airlines -->
            <div class="form-grid-2">
                <div class="field-group">
                    <label class="field-label">Pemilik (Airlines)</label>
                    <div class="select-wrapper">
                        <select name="id_airline" class="field-input field-select">
                            <option value="">— Pilih Airlines —</option>
                            <?php foreach ($daftar_airline as $a): ?>
                            <option value="<?= $a->id_airline ?>">
                                <?= htmlspecialchars($a->kode_airline) ?> — <?= htmlspecialchars($a->nama_airline) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <svg class="select-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="<?= site_url('gse') ?>" class="btn-cancel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/></svg>
                    Simpan Data GSE
                </button>
            </div>

            <?= form_close() ?>
        </div>

        <!-- Info Panel -->
        <div class="info-panel">
            <div class="info-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                Panduan Pengisian
            </div>
            <ul class="info-list">
                <li>Field bertanda <span class="req-marker">*</span> wajib diisi</li>
                <li><strong>Motorized</strong> — unit GSE bermesin (Towing Tractor, GPU, ASU, dll)</li>
                <li><strong>Non-Motorized</strong> — unit tanpa mesin (Baggage Cart, Dolly, dll)</li>
                <li>No. Asset merujuk ke kode inventaris internal perusahaan</li>
                <li>Sticker AP adalah nomor stiker yang ditempelkan pada unit fisik</li>
            </ul>
        </div>
    </div>

</div>

<style>
/* Reuse page-header styles dari index.php */
.gse-page { padding: 0; }
.page-header-bar { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.page-eyebrow { font-size: 12px; font-weight: 500; color: #6b8a8c; margin: 0 0 4px; display: flex; align-items: center; gap: 6px; }
.breadcrumb-link { color: #0a7a82; text-decoration: none; font-weight: 600; }
.breadcrumb-link:hover { text-decoration: underline; }
.breadcrumb-sep { color: #9ab5b7; }
.page-title { font-size: 1.4rem; font-weight: 700; color: #0d2b2e; margin: 0 0 4px; letter-spacing: -.01em; }
.page-subtitle { font-size: 13px; color: #6b8a8c; margin: 0; }

/* Layout */
.form-layout { display: grid; grid-template-columns: 1fr 280px; gap: 20px; align-items: start; }

/* Form Card */
.form-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5eef0;
    box-shadow: 0 2px 16px rgba(10,122,130,.07);
    overflow: hidden;
}
.form-card-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f0fafa 0%, #e6f7f8 100%);
    border-bottom: 1px solid #d6eef0;
}
.form-section-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(10,122,130,.25);
}
.form-card-title { font-size: 15px; font-weight: 700; color: #0d2b2e; margin: 0 0 3px; }
.form-card-desc  { font-size: 12.5px; color: #6b8a8c; margin: 0; }

/* Form Body */
.form-card form { padding: 24px; display: flex; flex-direction: column; gap: 0; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }

.field-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 0; }
.field-group:not(:last-child) { }

.field-label {
    font-size: 12px;
    font-weight: 600;
    color: #3a5e62;
    letter-spacing: .01em;
}
.field-label.required::after { content: ' *'; color: #e05a5a; }

.field-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #d4e8ea;
    border-radius: 10px;
    font-size: 13.5px;
    color: #0d2b2e;
    background: #fafefe;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
}
.field-input:focus {
    border-color: #0a7a82;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(10,122,130,.12);
}
.field-input::placeholder { color: #a0babc; }
.field-mono { font-family: 'Courier New', monospace; font-size: 13px; letter-spacing: .03em; }
.field-textarea { resize: vertical; min-height: 80px; }

/* Select */
.select-wrapper { position: relative; }
.field-select { appearance: none; padding-right: 36px; cursor: pointer; }
.select-chevron { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #7a9a9c; pointer-events: none; }

/* Divider */
.form-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 8px 0 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #9ab5b7;
}
.form-divider::before, .form-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5eef0;
}

/* Actions */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #eef4f5;
}
.btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px; font-weight: 600;
    color: #5a7a7c;
    background: #f0f5f6;
    border: 1.5px solid #d4e8ea;
    text-decoration: none;
    transition: background .2s;
}
.btn-cancel:hover { background: #e5eef0; }
.btn-submit {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px;
    border-radius: 10px;
    font-size: 13.5px; font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    border: none;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
    box-shadow: 0 4px 14px rgba(10,122,130,.28);
}
.btn-submit:hover { opacity: .9; transform: translateY(-1px); }

/* Info Panel */
.info-panel {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5eef0;
    box-shadow: 0 2px 12px rgba(10,122,130,.06);
    overflow: hidden;
}
.info-panel-header {
    display: flex; align-items: center; gap: 8px;
    padding: 14px 18px;
    background: linear-gradient(135deg, #0a7a82, #1aabb6);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
}
.info-list {
    list-style: none;
    padding: 16px 18px;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.info-list li {
    font-size: 12.5px;
    color: #4a6b6e;
    padding-left: 16px;
    position: relative;
    line-height: 1.5;
}
.info-list li::before {
    content: '›';
    position: absolute;
    left: 0;
    color: #0a7a82;
    font-weight: 700;
}
.req-marker { color: #e05a5a; font-weight: 700; }

@media (max-width: 900px) {
    .form-layout { grid-template-columns: 1fr; }
    .info-panel { order: -1; }
}
@media (max-width: 600px) {
    .form-grid-2 { grid-template-columns: 1fr; }
}
</style>