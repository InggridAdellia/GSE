</main>

        <footer class="app-footer">
            © <?= date('Y') ?> · IGNIS — Integrated GSE Access and Information System · Bandar Udara Internasional Sultan Hasanuddin
        </footer>
    </div>
</div>

<div class="modal fade" id="modalPreviewDok" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="previewDokTitle">Preview Dokumen</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="previewDokBody" style="min-height:300px;">
            </div>
            <div class="modal-footer">
                <a href="#" id="previewDokDownload" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download"></i> Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/vendor/flatpickr/flatpickr.min.css') ?>">
<style>
    .flatpickr-calendar { font-size: 0.78rem; }
    .flatpickr-current-month { font-size: 0.85rem; }
    .flatpickr-current-month input.cur-year { font-size: 0.85rem; }
    .flatpickr-weekday { font-size: 0.7rem; }
    .flatpickr-day { line-height: 30px; height: 30px; }
</style>
<script src="<?= base_url('assets/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/flatpickr/flatpickr.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.flatpickrInstances = window.flatpickrInstances || {};
    var ids = ['dimMulai', 'dimSelesai', 'editDimMulai', 'editDimSelesai', 'ajuMasaMulai', 'ajuMasaSelesai'];
    ids.forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            window.flatpickrInstances[id] = flatpickr(el, {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y'
            });
        }
    });
});
</script>
<script>
(function () {
    var btn      = document.getElementById('sidebarToggleBtn');
    var shell    = document.getElementById('appShell');
    var sidebar  = document.getElementById('appSidebar');
    var offcanvasInstance = null;

    function isDesktop() {
        return window.matchMedia('(min-width: 768px)').matches;
    }

    if (isDesktop() && localStorage.getItem('ignis_sidebar_collapsed') === '1') {
        shell.classList.add('sidebar-collapsed');
    }

    if (btn) {
        btn.addEventListener('click', function () {
            if (isDesktop()) {
                shell.classList.toggle('sidebar-collapsed');
                var collapsed = shell.classList.contains('sidebar-collapsed');
                localStorage.setItem('ignis_sidebar_collapsed', collapsed ? '1' : '0');
            } else {
                offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(sidebar);
                offcanvasInstance.toggle();
            }
        });
    }

    window.addEventListener('resize', function () {
        if (isDesktop()) {
            var oc = bootstrap.Offcanvas.getInstance(sidebar);
            if (oc) oc.hide();
        } else {
            shell.classList.remove('sidebar-collapsed');
        }
    });
})();
</script>

<script>
function previewDokumen(url, judul) {
    var ext = url.split('.').pop().toLowerCase();
    var body = document.getElementById('previewDokBody');
    document.getElementById('previewDokTitle').textContent = judul || 'Preview Dokumen';
    document.getElementById('previewDokDownload').href = url;
    if (['jpg','jpeg','png'].includes(ext)) {
        body.innerHTML = '<img src="' + url + '" style="max-width:100%; max-height:70vh;">';
    } else {
        body.innerHTML = '<iframe src="' + url + '" style="width:100%; height:70vh; border:0;"></iframe>';
    }
    new bootstrap.Modal(document.getElementById('modalPreviewDok')).show();
}
</script>
</body>
</html>