<h5 class="mb-3">Edit Airline</h5>

<div class="card border-0 shadow-sm" style="max-width: 500px;">
    <div class="card-body">
        <?= form_open('airlines/update/' . $airline->id_airline) ?>

            <div class="mb-3">
                <label class="form-label">Nama Airline</label>
                <input type="text" name="nama_airline" class="form-control" required
                       value="<?= htmlspecialchars($airline->nama_airline) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Kode Airline</label>
                <input type="text" name="kode_airline" class="form-control" maxlength="10"
                       value="<?= htmlspecialchars($airline->kode_airline ?? '') ?>">
                <div class="form-text">Opsional, harus unik kalau diisi. Dibuat otomatis huruf besar.</div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
            <a href="<?= site_url('airlines') ?>" class="btn btn-light">Batal</a>

        <?= form_close() ?>
    </div>
</div>