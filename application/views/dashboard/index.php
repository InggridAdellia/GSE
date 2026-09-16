<?php
$role = $role ?? $this->session->userdata('role');
?>
<style>
  :root {
    --ignis-teal: #065f67;
    --ignis-orange: #f26c58;
    --ignis-blue: #23b8c5;
    --ignis-yellow: #fcb244;
    --ignis-green: #a2c03b;
    --ignis-dark: #111827;
    --ignis-bg: #f8f9fa;
  }

  .welcome-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    border: 1px solid #f1f1f4;
  }
  .welcome-card h4 { color: var(--ignis-dark); font-weight: 700; }

  .stat-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f1f1f4;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    overflow: hidden;
    display: flex;
    transition: all .2s ease;
  }
  .stat-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.08); transform: translateY(-2px); }
  .stat-card .stripe { width: 4px; }
  .stat-card .body { flex: 1; padding: 1.1rem 1.25rem; }
  .stat-card .icon-box {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
  }
  .stat-card .label { color: #6b7280; font-size: .85rem; margin-top: .8rem; }
  .stat-card .value { font-size: 1.9rem; font-weight: 700; color: var(--ignis-dark); line-height: 1.1; }
  .stat-card .link {
    display: inline-flex; align-items: center; gap: .3rem;
    margin-top: .65rem; font-size: .8rem; font-weight: 600;
    text-decoration: none;
  }
  .stat-card .link:hover { text-decoration: underline; }

  .flow-section h6 {
    text-transform: uppercase; letter-spacing: .05em;
    color: #6b7280; font-size: .8rem; font-weight: 600;
  }
  .flow-step {
    background: #fff; border-radius: 14px; padding: 1rem 1.1rem;
    border: 1px solid #f1f1f4; box-shadow: 0 1px 3px rgba(0,0,0,.04);
    display: flex; align-items: center; gap: .85rem; flex: 1;
    transition: all .2s ease;
  }
  .flow-step:hover { box-shadow: 0 6px 18px rgba(0,0,0,.08); }
  .flow-step .ico {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
  }
  .flow-step .stage { font-size: .72rem; color: #6b7280; }
  .flow-step .name { font-size: .9rem; font-weight: 600; color: var(--ignis-dark); }
  .flow-arrow { color: #d1d5db; font-size: 1.3rem; }
</style>

<!-- Welcome -->
<div class="welcome-card mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4 class="mb-1">Selamat datang, <?= htmlspecialchars($this->session->userdata('nama')) ?></h4>
    <p class="text-muted mb-0">Ringkasan Data Perizinan GSE Bandar Internasional Sultan Hasanuddin.</p>
  </div>
  <div class="text-end d-none d-sm-block">
    <div class="small text-muted">Sesi aktif</div>
    <div class="fw-semibold" style="color: var(--ignis-green);">● Online</div>
  </div>
</div>

<?php if (($jumlah_segera_berakhir ?? 0) > 0 || ($jumlah_berakhir ?? 0) > 0): ?>
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 p-3" style="background: #FDF2DF; border-left: 4px solid var(--gold-600) !important; border-radius: 1rem;">
  <div class="d-flex align-items-center gap-3">
    <i class="bi bi-bell-fill text-warning fs-4"></i>
    <div>
      <div class="fw-bold text-dark mb-0" style="font-size: .88rem;">Notifikasi Masa Berlaku & Pass GSE</div>
      <div class="small text-muted d-flex gap-3 flex-wrap mt-1">
        <?php if (($jumlah_berakhir ?? 0) > 0): ?>
          <span class="text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i><strong><?= $jumlah_berakhir ?> unit</strong> habis (Expired)</span>
        <?php endif; ?>
        <?php if (($jumlah_segera_berakhir ?? 0) > 0): ?>
          <span class="text-warning-emphasis fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i><strong><?= $jumlah_segera_berakhir ?> unit</strong> hampir habis (≤ 30 hari)</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php if ($role !== 'ground_handling'): ?>
  <a href="<?= site_url('gse') ?>" class="btn btn-sm btn-outline-primary" style="font-size: .75rem; border-radius: .5rem;">
    <i class="bi bi-arrow-right-circle me-1"></i> Lihat Data GSE
  </a>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Stat Cards -->
<div class="row g-3">
  <?php
  $role = $this->session->userdata('role');
  $cards = [];

  if ($role !== 'ground_handling') {
      $cards[] = ['title'=>'Total Unit GSE','value'=>$total_gse,'icon'=>'bi-airplane-fill','color'=>'#f26c58','bg'=>'rgba(249,115,22,.1)','link'=>site_url('gse'),'text'=>'Lihat data'];
  }

  $cards[] = ['title'=>'Surat Masuk','value'=>$total_masuk,'icon'=>'bi-envelope-open-fill','color'=>'#fcb244','bg'=>'rgba(226, 238, 135, 0.1)','link'=>site_url('permohonan_masuk'),'text'=>'Lihat data'];
  $cards[] = ['title'=>'Surat Keluar','value'=>$total_keluar,'icon'=>'bi-envelope-fill','color'=>'#23b8c5','bg'=>'rgba(59, 190, 246, 0.1)','link'=>site_url('permohonan_keluar'),'text'=>'Lihat data'];

  $can_verify = in_array($role, ['unit_operasi','unit_equipment','unit_sales', 'unit_security', 'admin']);
  $cards[] = [
      'title'=>'Menunggu Verifikasi','value'=>$total_pending,
      'icon'=>'bi-clock-fill','color'=>'#a2c03b','bg'=>'rgba(117, 255, 117, 0.12)',
      'link'=> $can_verify ? site_url('approval') : '#',
      'text'=> $can_verify ? 'Proses sekarang' : 'Menunggu verifikasi',
  ];
  ?>
  <?php foreach ($cards as $c): ?>
    <div class="col-md-6 col-lg-3">
      <div class="stat-card">
        <div class="stripe" style="background: <?= $c['color'] ?>;"></div>
        <div class="body">
          <div class="icon-box" style="background: <?= $c['bg'] ?>; color: <?= $c['color'] ?>;">
            <i class="bi <?= $c['icon'] ?>"></i>
          </div>
          <div class="label"><?= $c['title'] ?></div>
          <div class="value"><?= $c['value'] ?></div>
          <a href="<?= $c['link'] ?>" class="link" style="color: <?= $c['color'] ?>;">
            <?= $c['text'] ?> <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
