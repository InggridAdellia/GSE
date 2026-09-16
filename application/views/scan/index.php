<style>
    .page-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .page-title{ display:flex; align-items:center; gap:.6rem; color:var(--teal-800); font-weight:700; margin-bottom:.25rem; font-size:.92rem; }
    .page-title i{ color:var(--gold-600); }
    .page-subtitle{ color:var(--ink-600); font-size:.72rem; margin-bottom:0; }

    .table-card{ background:#fff; border-radius:1rem; box-shadow:0 1px 3px rgba(10,54,59,.06),0 10px 28px -16px rgba(10,54,59,.18); overflow:hidden; }
    .table-card-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--line); flex-wrap:wrap; gap:.5rem; }
    .table-card-head h6{ margin:0; font-weight:700; color:var(--teal-800); font-size:.8rem; }
    .count-pill{ background:var(--mint-50); color:var(--teal-800); font-size:.62rem; font-weight:600; padding:.22rem .6rem; border-radius:999px; }

    .scan-card{ max-width:560px; margin:0 auto; }
    #qr-reader{ width:100%; border-radius:.75rem; overflow:hidden; background:#000; border:1px solid var(--line); }
    #qr-reader video{ border-radius:.75rem; }

    .scan-result{ margin-top:1rem; }
    .scan-hint{ font-size:.72rem; color:var(--ink-600); text-align:center; margin-top:.75rem; }
    .gse-info-table th{ width:38%; font-weight:600; font-size:.72rem; color:var(--ink-600); border:0; padding:.4rem 0; vertical-align:top; }
    .gse-info-table td{ font-size:.74rem; color:var(--ink-900); border:0; padding:.4rem 0; }

    .badge{ font-weight:600; font-size:.6rem; padding:.35rem .6rem; border-radius:999px; }
    .badge.bg-warning{ background:#FDF2DF !important; color:var(--gold-600) !important; }
    .badge.bg-info{ background:#E6F6F6 !important; color:var(--teal-700) !important; }
    .badge.bg-primary{ background:#EAF1FB !important; color:#2C5FA8 !important; }
    .badge.bg-success{ background:#E9F6EE !important; color:#1F6F45 !important; }
    .badge.bg-danger{ background:#FDECE8 !important; color:#A23B2A !important; }
    .badge.bg-secondary{ background:#EEF1F1 !important; color:var(--ink-600) !important; }

    .reg-code{ background:var(--mint-50); color:var(--teal-800); padding:.15rem .45rem; border-radius:4px; font-size:.68rem; font-family:monospace; }
    .modal-content{ border:0; border-radius:1rem; overflow:hidden; }

    .level-radio-card{
        border: 1px solid var(--line);
        border-radius: .65rem;
        padding: .65rem .85rem;
        cursor: pointer;
        transition: all .15s ease;
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        background: #fff;
    }
    .level-radio-card:hover{
        border-color: var(--teal-600);
        background: var(--mint-50);
    }
    .form-check-input:checked ~ .level-card-body{
        font-weight: 600;
    }
</style>

<div class="page-head">
    <div>
        <h5 class="page-title"><i class="bi bi-qr-code-scan"></i> Scan Kamera & Cek GSE</h5>
        <p class="page-subtitle">Arahkan kamera ke stiker verifikasi GSE untuk melihat info unit atau laporkan kerusakan.</p>
    </div>
    <div>
        <a href="<?= site_url('kerusakan') ?>" class="btn btn-sm btn-outline-primary" style="font-size: .72rem; border-radius: .5rem;">
            <i class="bi bi-card-checklist me-1"></i> Daftar Laporan Kerusakan
        </a>
    </div>
</div>

<div class="table-card scan-card">
    <div class="table-card-head">
        <h6><i class="bi bi-camera me-1"></i> Pemindai QR Code Stiker</h6>
        <span class="count-pill"><i class="bi bi-broadcast me-1"></i> Siap Scan</span>
    </div>
    <div class="p-3 p-sm-4">
        <div id="qr-reader"></div>
        <div id="scan-result" class="scan-result"></div>
        <div class="scan-hint">
            <i class="bi bi-info-circle"></i> Pastikan browser mengizinkan akses kamera pada perangkat.
        </div>
    </div>
</div>

<!-- Modal Laporkan Kerusakan & Beri Peringatan -->
<div class="modal fade" id="modalLaporKerusakan" tabindex="-1" aria-labelledby="modalLaporKerusakanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3" style="background:var(--mint-50); border-bottom:1px solid var(--line);">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalLaporKerusakanLabel" style="color:var(--teal-800); font-size:.86rem;">
                    <i class="bi bi-cone-striped" style="color:var(--gold-600);"></i> Form Laporan Kerusakan GSE
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="formKerusakan" enctype="multipart/form-data">
                <div class="modal-body p-3 p-sm-4">
                    <input type="hidden" name="sticker_ap" id="input_sticker_ap">
                    <input type="hidden" name="nama_gse" id="input_nama_gse">
                    <input type="hidden" name="id_gse" id="input_id_gse">
                    <input type="hidden" name="id_airline" id="input_id_airline">

                    <!-- Info Ringkas Unit -->
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <table class="table table-sm table-borderless mb-0" style="font-size: .74rem;">
                            <tr>
                                <th class="text-muted" style="width: 38%;">Nomor Stiker:</th>
                                <td class="fw-semibold text-dark font-monospace" id="display_sticker_ap">-</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Nama GSE:</th>
                                <td class="fw-semibold text-dark" id="display_nama_gse">-</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Maskapai / GH:</th>
                                <td class="fw-semibold text-dark" id="display_airline">-</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Pilihan Tingkat Peringatan / Kerusakan -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted mb-2" style="font-size:.74rem;">Tingkat Kerusakan / Peringatan <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            <label class="level-radio-card" for="level_ringan">
                                <input class="form-check-input mt-1" type="radio" name="tingkat_kerusakan" id="level_ringan" value="Peringatan Ringan">
                                <div class="level-card-body" style="font-size:.74rem;">
                                    <div class="fw-semibold text-primary"><i class="bi bi-info-circle me-1"></i> Peringatan Ringan</div>
                                    <div class="text-muted" style="font-size:.68rem;">Kerusakan minor / kosmetik, unit masih dapat beroperasi bersyarat.</div>
                                </div>
                            </label>

                            <label class="level-radio-card" for="level_sedang">
                                <input class="form-check-input mt-1" type="radio" name="tingkat_kerusakan" id="level_sedang" value="Peringatan Sedang" checked>
                                <div class="level-card-body" style="font-size:.74rem;">
                                    <div class="fw-semibold" style="color:var(--gold-600);"><i class="bi bi-exclamation-triangle me-1"></i> Peringatan Sedang</div>
                                    <div class="text-muted" style="font-size:.68rem;">Kerusakan fungsi parsial, butuh perbaikan segera / jadwal pemeliharaan.</div>
                                </div>
                            </label>

                            <label class="level-radio-card" for="level_berat">
                                <input class="form-check-input mt-1" type="radio" name="tingkat_kerusakan" id="level_berat" value="Bahaya / Stop Operasi">
                                <div class="level-card-body" style="font-size:.74rem;">
                                    <div class="text-danger fw-semibold"><i class="bi bi-x-octagon-fill me-1"></i> Bahaya / Stop Operasi (Kritis)</div>
                                    <div class="text-muted" style="font-size:.68rem;">Membahayakan keselamatan airside, unit wajib di-grounded/stop operasi.</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Rincian Kerusakan / Peringatan -->
                    <div class="mb-3">
                        <label for="input_keterangan" class="form-label small fw-semibold text-muted" style="font-size:.74rem;">Rincian Kerusakan / Peringatan <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" name="keterangan" id="input_keterangan" rows="3" placeholder="Jelaskan bagian yang rusak, kronologi atau indikasi risiko..." required style="font-size:.74rem; border-radius:.5rem;"></textarea>
                    </div>

                    <!-- Upload Foto Kerusakan (Opsional) -->
                    <div class="mb-2">
                        <label for="input_foto" class="form-label small fw-semibold text-muted" style="font-size:.74rem;">Foto Bukti Kerusakan (Opsional)</label>
                        <input class="form-control form-control-sm" type="file" name="foto_kerusakan" id="input_foto" accept="image/png, image/jpeg, image/webp" style="font-size:.74rem; border-radius:.5rem;">
                        <div class="form-text" style="font-size:.68rem;">Maks. 5 MB (Format: JPG, PNG, WEBP)</div>
                    </div>

                    <div id="kerusakan-alert-box" class="mt-2"></div>
                </div>
                <div class="modal-footer bg-light border-top py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size:.72rem; border-radius:.5rem;">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="btnSubmitKerusakan" style="font-size:.72rem; border-radius:.5rem;">
                        <i class="bi bi-send-fill me-1"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var resultBox      = document.getElementById('scan-result');
    var modalEl        = document.getElementById('modalLaporKerusakan');
    var formKerusakan  = document.getElementById('formKerusakan');
    var btnSubmit      = document.getElementById('btnSubmitKerusakan');
    var alertBox       = document.getElementById('kerusakan-alert-box');
    var bsModal        = null;
    var currentGseData = null;
    var sudahDiarah    = false;

    if (modalEl && typeof bootstrap !== 'undefined') {
        bsModal = new bootstrap.Modal(modalEl);
    }

    function tampilkanHasil(html, kelas) {
        resultBox.innerHTML = '<div class="alert ' + kelas + ' py-2 mb-0" style="font-size:.74rem;">' + html + '</div>';
    }

    function formatTanggal(iso) {
        if (!iso) return null;
        var d = new Date(iso);
        if (isNaN(d.getTime())) return null;
        var pad = function (n) { return (n < 10 ? '0' : '') + n; };
        return pad(d.getDate()) + '-' + pad(d.getMonth() + 1) + '-' + d.getFullYear();
    }

    function formatMasaKontrak(masaMulai, masaSelesai) {
        var mulai   = formatTanggal(masaMulai);
        var selesai = formatTanggal(masaSelesai);
        if (!mulai && !selesai) return null;
        return (mulai || '-') + ' - ' + (selesai || '-');
    }
    
    function tampilkanDataGse(data) {
        currentGseData = data;
        var baris = function (label, value) {
            return '<tr><th>' + label + '</th><td>' + (value ? String(value) : '-') + '</td></tr>';
        };

        var statusKontrakBadge = '';
        var notifKontrakHtml   = '';

        if (data.status_kontrak === 'berakhir') {
            notifKontrakHtml = '<div class="alert alert-danger py-2 mb-2 d-flex align-items-center gap-2" style="font-size:.74rem;">' +
                '<i class="bi bi-x-circle-fill fs-5 flex-shrink-0"></i>' +
                '<div><strong>PERINGATAN:</strong> Masa berlaku kontrak / Pass GSE telah <strong>HABIS (Expired)</strong>!</div>' +
                '</div>';
            statusKontrakBadge = '<span class="badge bg-danger">Masa Kontrak / Pass Habis</span>';
        } else if (data.status_kontrak === 'segera_berakhir') {
            notifKontrakHtml = '<div class="alert alert-warning py-2 mb-2 d-flex align-items-center gap-2" style="font-size:.74rem;">' +
                '<i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>' +
                '<div><strong>PERHATIAN:</strong> Masa berlaku kontrak / Pass GSE <strong>Hampir Habis</strong> (Tersisa ' + data.sisa_hari + ' hari lagi)!</div>' +
                '</div>';
            statusKontrakBadge = '<span class="badge bg-warning">Hampir Expired (' + data.sisa_hari + ' hari lagi)</span>';
        } else if (data.status_kontrak === 'aktif') {
            statusKontrakBadge = '<span class="badge bg-success">Aktif</span>';
        }

        // Tampilkan warning kerusakan aktif jika ada
        var warningKerusakanHtml = '';
        if (data.active_warnings && data.active_warnings.length > 0) {
            var itemsList = '';
            for (var i = 0; i < data.active_warnings.length; i++) {
                var w = data.active_warnings[i];
                var badgeLvl = 'bg-warning';
                if (w.tingkat_kerusakan === 'Bahaya / Stop Operasi') badgeLvl = 'bg-danger';
                itemsList += '<li class="mb-1"><span class="badge ' + badgeLvl + ' me-1">' + (w.tingkat_kerusakan || 'Peringatan') + '</span> ' +
                    (w.keterangan ? w.keterangan.replace(/</g, '&lt;') : '-') +
                    ' <span class="text-muted" style="font-size:.65rem;">(' + (w.created_at || '') + ')</span></li>';
            }

            warningKerusakanHtml = '<div class="alert alert-danger py-2 mb-2 border-danger" style="font-size:.74rem;">' +
                '<div class="fw-bold d-flex align-items-center gap-2 mb-1 text-danger">' +
                '<i class="bi bi-exclamation-octagon-fill fs-6"></i> PERINGATAN KERUSAKAN AKTIF PADA UNIT INI:' +
                '</div>' +
                '<ul class="mb-0 ps-3 text-dark" style="font-size:.72rem;">' + itemsList + '</ul>' +
                '</div>';
        }

        var html =
            '<div class="alert alert-success py-2 mb-2" style="font-size:.74rem;"><i class="bi bi-check-circle me-1"></i> Unit GSE ditemukan.</div>' +
            notifKontrakHtml +
            warningKerusakanHtml +
            '<table class="table table-sm table-borderless gse-info-table mb-2">' +
            baris('Nama GSE', data.nama_gse) +
            baris('Manufacture', data.manufacture_type) +
            baris('No. Asset', data.no_asset) +
            baris('No. Rangka', data.nomor_rangka) +
            baris('No. Mesin', data.nomor_mesin) +
            baris('Sticker AP', '<span class="reg-code">' + (data.sticker_ap || '-') + '</span>') +
            baris('Airlines', data.airline) +
            baris('Status Unit', data.status) +
            (statusKontrakBadge ? baris('Status Masa Berlaku', statusKontrakBadge) : '') +
            baris('Masa Kontrak', formatMasaKontrak(data.masa_mulai, data.masa_selesai)) +
            '</table>' +
            '<div class="d-flex flex-column gap-2 mt-3">' +
            '<button type="button" class="btn btn-warning btn-sm w-100 fw-semibold" id="btnBukaModal" style="font-size:.74rem; border-radius:.5rem;">' +
            '<i class="bi bi-cone-striped me-1"></i> Laporkan Kerusakan GSE</button>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="location.reload()" style="font-size:.72rem; border-radius:.5rem;">' +
            '<i class="bi bi-arrow-repeat me-1"></i> Scan Ulang</button>' +
            '</div>';

        resultBox.innerHTML = html;

        // Pasang event listener ke tombol buka modal
        var btnBuka = document.getElementById('btnBukaModal');
        if (btnBuka) {
            btnBuka.addEventListener('click', function () {
                bukaModalKerusakan(data);
            });
        }
    }

    function bukaModalKerusakan(data) {
        document.getElementById('input_sticker_ap').value = data.sticker_ap || '';
        document.getElementById('input_nama_gse').value   = data.nama_gse || '';
        document.getElementById('input_id_gse').value     = data.id_gse || '';
        document.getElementById('input_id_airline').value = data.id_airline || '';

        document.getElementById('display_sticker_ap').textContent = data.sticker_ap || '-';
        document.getElementById('display_nama_gse').textContent   = data.nama_gse || '-';
        document.getElementById('display_airline').textContent    = data.airline || '-';

        document.getElementById('input_keterangan').value = '';
        document.getElementById('input_foto').value       = '';
        alertBox.innerHTML = '';

        if (modalEl && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    }

    // Submit form laporan kerusakan via AJAX
    if (formKerusakan) {
        formKerusakan.addEventListener('submit', function (e) {
            e.preventDefault();

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';
            alertBox.innerHTML = '';

            var formData = new FormData(formKerusakan);

            fetch('<?= site_url('scan/laporkan_kerusakan') ?>', {
                method: 'POST',
                body: formData
            })
            .then(function (res) { return res.json(); })
            .then(function (resData) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-send-fill me-1"></i> Kirim Laporan';

                if (resData.status === 'success') {
                    alertBox.innerHTML = '<div class="alert alert-success py-2 mb-0 small" style="font-size:.72rem;"><i class="bi bi-check-circle me-1"></i> ' + resData.message + '</div>';
                    setTimeout(function () {
                        if (modalEl && typeof bootstrap !== 'undefined') {
                            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                        }
                        // Refresh data GSE untuk memuat status peringatan terbaru
                        if (currentGseData && currentGseData.sticker_ap) {
                            ambilDataGse(currentGseData.sticker_ap);
                        }
                    }, 1200);
                } else {
                    alertBox.innerHTML = '<div class="alert alert-danger py-2 mb-0 small" style="font-size:.72rem;"><i class="bi bi-exclamation-triangle me-1"></i> ' + (resData.message || 'Gagal menyimpan laporan.') + '</div>';
                }
            })
            .catch(function () {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-send-fill me-1"></i> Kirim Laporan';
                alertBox.innerHTML = '<div class="alert alert-danger py-2 mb-0 small" style="font-size:.72rem;"><i class="bi bi-exclamation-triangle me-1"></i> Terjadi kesalahan jaringan saat mengirim laporan.</div>';
            });
        });
    }

    function ambilDataGse(stickerAp) {
        tampilkanHasil('<i class="bi bi-hourglass-split"></i> Mengambil data unit GSE...', 'alert-info');

        fetch('<?= site_url('scan/get_gse_data/') ?>' + encodeURIComponent(stickerAp))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.error) {
                    tampilkanHasil('<i class="bi bi-exclamation-triangle"></i> ' + data.error, 'alert-danger');
                    return;
                }
                tampilkanDataGse(data);
            })
            .catch(function () {
                tampilkanHasil('<i class="bi bi-exclamation-triangle"></i> Gagal mengambil data. Coba scan ulang.', 'alert-danger');
            });
    }

    function onScanSuccess(decodedText) {
        if (sudahDiarah) return;

        var teks  = decodedText.trim();
        var match = teks.match(/^GE\.[A-Z0-9]+\.[A-Z0-9]+\.\d+\.\d+$/i);

        if (match) {
            sudahDiarah = true;
            qrReader.stop().catch(function () {});
            ambilDataGse(teks);
        } else {
            tampilkanHasil(
                '<i class="bi bi-exclamation-triangle"></i> QR terbaca tapi bukan stiker GSE yang valid: ' +
                '<code>' + teks.replace(/</g, '&lt;') + '</code>',
                'alert-warning'
            );
        }
    }

    function onScanFailure() {
    }

    var qrReader = new Html5Qrcode('qr-reader');
    Html5Qrcode.getCameras().then(function (devices) {
        if (!devices || !devices.length) {
            tampilkanHasil('<i class="bi bi-camera-video-off"></i> Tidak ada kamera yang terdeteksi di perangkat ini.', 'alert-danger');
            return;
        }

        var cameraId = devices[0].id;

        qrReader.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure
        ).catch(function () {
            qrReader.start(cameraId, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess, onScanFailure)
                .catch(function (err) {
                    tampilkanHasil('<i class="bi bi-camera-video-off"></i> Gagal mengakses kamera: ' + err, 'alert-danger');
                });
        });
    }).catch(function (err) {
        tampilkanHasil('<i class="bi bi-camera-video-off"></i> Tidak bisa mengakses kamera: ' + err, 'alert-danger');
    });
})();
</script>