<div class="gse-page">

    <!-- Page Header -->
    <div class="page-header-bar mb-4">
        <div>
            <p class="page-eyebrow">
                <a href="<?= site_url('airlines') ?>" class="breadcrumb-link">Kelola Airline</a>
                <span class="breadcrumb-sep">›</span> Tambah Baru
            </p>
            <h4 class="page-title">Tambah Airline</h4>
            <p class="page-subtitle">Daftarkan maskapai penerbangan baru ke dalam sistem</p>
        </div>
    </div>

    <div class="form-layout-narrow">

        <div class="form-card">
            <div class="form-card-header add-header">
                <div class="form-section-icon add-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849z"/></svg>
                </div>
                <div>
                    <p class="form-card-title">Informasi Maskapai Baru</p>
                    <p class="form-card-desc">Isi nama dan kode IATA (opsional) airline</p>
                </div>
            </div>

            <?= form_open('airlines/simpan') ?>

            <div class="form-body">
                <div class="field-group">
                    <label class="field-label required">Nama Airline</label>
                    <input type="text" name="nama_airline" class="field-input" required
                           placeholder="Contoh: Garuda Indonesia">
                </div>

                <div class="field-group">
                    <label class="field-label">Kode IATA</label>
                    <div class="input-with-hint">
                        <input type="text" name="kode_airline" class="field-input field-mono field-short" maxlength="10"
                               placeholder="GA" style="text-transform: uppercase;">
                        <p class="field-hint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                            Opsional. Harus unik bila diisi. Otomatis diubah ke huruf kapital.
                        </p>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= site_url('airlines') ?>" class="btn-cancel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4.414A1 1 0 0 0 14.707 4L12 1.293A1 1 0 0 0 11.293 1H2zm9.5 10h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0-3h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm-7-3h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/></svg>
                        Simpan Airline
                    </button>
                </div>
            </div>

            <?= form_close() ?>
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

.form-layout-narrow { max-width: 520px; }

/* Form Card */
.form-card { background: #fff; border-radius: 16px; border: 1px solid #e5eef0; box-shadow: 0 2px 16px rgba(10,122,130,.07); overflow: hidden; }
.form-card-header { display: flex; align-items: center; gap: 14px; padding: 20px 24px; border-bottom: 1px solid #d8efe9; }
.add-header { background: linear-gradient(135deg, #eafbfa 0%, #e4f7f5 100%); }
.form-section-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
.add-icon { background: linear-gradient(135deg, #0a7a82, #1aabb6); box-shadow: 0 4px 12px rgba(10,122,130,.25); }
.form-card-title { font-size: 15px; font-weight: 700; color: #0d2b2e; margin: 0 0 3px; }
.form-card-desc  { font-size: 12.5px; color: #6b8a8c; margin: 0; }

.form-body { padding: 24px; display: flex; flex-direction: column; gap: 18px; }
.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-label { font-size: 12px; font-weight: 600; color: #3a5e62; letter-spacing: .01em; }
.field-label.required::after { content: ' *'; color: #e05a5a; }
.field-input {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #d4e8ea; border-radius: 10px;
    font-size: 13.5px; color: #0d2b2e; background: #fafefe;
    transition: border-color .2s, box-shadow .2s; outline: none;
    box-sizing: border-box; font-family: inherit;
}
.field-input:focus { border-color: #0a7a82; background: #fff; box-shadow: 0 0 0 3px rgba(10,122,130,.12); }
.field-mono { font-family: 'Courier New', monospace; letter-spacing: .06em; }
.field-short { max-width: 140px; }
.input-with-hint { display: flex; flex-direction: column; gap: 7px; }
.field-hint { display: flex; align-items: flex-start; gap: 6px; font-size: 12px; color: #7a9a9c; margin: 0; line-height: 1.5; }
.field-hint svg { flex-shrink: 0; margin-top: 1px; }

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

@media (max-width: 900px) {
    .form-layout-narrow { max-width: 100%; }
}
</style>