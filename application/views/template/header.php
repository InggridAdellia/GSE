<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perizinan GSE Bandara</title>

    <link href="<?= base_url('assets/vendor/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fonts/fonts.css') ?>" rel="stylesheet">
    
    <style>
        :root{
            --teal-900:#0A363B;
            --teal-800:#0D4F55;
            --teal-700:#0D7C85;
            --teal-600:#15929B;
            --teal-500:#3FB0B6;
            --turquoise:#5BC4BF;
            --coral:#F2876B;
            --gold-500:#F5B342;
            --gold-600:#E69F2B;
            --olive:#98C255;
            --mint-50:#EFF7F3;
            --peach-50:#FDF1E3;
            --cream-50:#FAF8F2;
            --ink-900:#1E293B;
            --ink-600:#4B5C68;
            --ink-400:#8AA0A0;
            --line:#E3EBE7;
        }

        *{ box-sizing:border-box; }

        body{
            margin:0;
            min-height:100vh;
            font-family:'Inter',system-ui,-apple-system,sans-serif;
            color:var(--ink-900);
            background-image: linear-gradient(135deg, rgba(230,247,246,.90) 0%, rgba(255,251,243,.88) 48%, rgba(255,235,224,.85) 100%), url('<?= base_url('assets/img/main-bg.png') ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        h1,h2,h3,h4,h5,h6,.brand-title{ font-family:'Plus Jakarta Sans',sans-serif; }

        a{ color:var(--teal-700); }
        a:hover{ color:var(--teal-600); }

        a:focus-visible, button:focus-visible, .btn:focus-visible{
            outline:2px solid var(--gold-500);
            outline-offset:2px;
        }

        /* ---------- Top navbar ---------- */
        .app-navbar{
            background:#fff;
            padding:.6rem 0;
            box-shadow:0 1px 2px rgba(10,54,59,.05);
        }
        .navbar-underline{
            height:3px;
            background:linear-gradient(90deg,var(--teal-700) 0%, var(--turquoise) 55%, var(--gold-500) 100%);
        }
        .brand-mark{ flex-shrink:0; }
        .navbar-brand{ display:flex; align-items:center; gap:.65rem; padding:0; }
        .brand-title{
            font-weight:800;
            font-size:1.15rem;
            color:var(--teal-800);
            line-height:1.15;
            letter-spacing:.01em;
            display:block;
        }
        .brand-sub{
            font-size:.7rem;
            color:var(--ink-400);
            display:block;
            font-weight:500;
        }
        .sidebar-toggle-btn{
            border:1px solid var(--line);
            background:#fff;
            color:var(--teal-800);
            border-radius:.6rem;
            width:2.4rem;
            height:2.4rem;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            transition:background .15s ease;
        }
        .sidebar-toggle-btn:hover{ background:var(--mint-50); }
        .role-pill{
            font-size:.7rem;
            font-weight:600;
            letter-spacing:.04em;
            text-transform:uppercase;
            color:var(--teal-800);
            background:var(--mint-50);
            border:1px solid var(--line);
            padding:.35rem .75rem;
            border-radius:999px;
            white-space:nowrap;
        }
        .user-trigger{
            display:flex;
            align-items:center;
            gap:.55rem;
            background:transparent;
            border:1px solid var(--line);
            border-radius:999px;
            padding:.25rem .8rem .25rem .3rem;
            color:var(--ink-900);
        }
        .user-trigger:hover{ background:var(--mint-50); }
        .avatar{
            width:30px; height:30px;
            border-radius:50%;
            background:linear-gradient(135deg,var(--teal-700),var(--teal-600));
            color:#fff;
            font-weight:700;
            font-size:.78rem;
            display:flex; align-items:center; justify-content:center;
            flex-shrink:0;
        }
        .user-name{ font-size:.86rem; font-weight:600; }
        .dropdown-menu{ border:0; border-radius:.85rem; box-shadow:0 12px 30px -10px rgba(10,54,59,.25); padding:.5rem; }
        .dropdown-item{ border-radius:.55rem; padding:.5rem .75rem; font-size:.88rem; }
        .dropdown-item:active,.dropdown-item:hover{ background:var(--mint-50); }
        .dropdown-item.text-danger:hover{ background:#FDECE8; }

        /* ---------- Shell layout ---------- */
        .app-shell{ align-items:stretch; min-height:calc(100vh - 67px); }

        /* ---------- Sidebar ---------- */
        .app-sidebar{
            width:260px;
            flex-shrink: 0;
            min-width: 260px;
            background-image: linear-gradient(195deg, rgba(10,54,59,.88) 0%, rgba(15,74,79,.82) 55%, rgba(20,94,89,.78) 100%), url('<?= base_url('assets/img/sidebar-bg1.png') ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color:#D9EFEE;
            border:0;
            transition:width .22s ease, min-width .22s ease, padding .22s ease;
        }
        .offcanvas-body{ padding:0; display:flex; flex-direction:column; }
        .sidebar-section-label{
            font-size:.66rem;
            text-transform:uppercase;
            letter-spacing:.09em;
            font-weight:600;
            color:#8FC2C0;
            padding:1.15rem 1.3rem .4rem;
        }
        .sidebar-nav{ display:flex; flex-direction:column; padding:0 .65rem; }
        .sidebar-nav a{
            display:flex;
            align-items:center;
            gap:.75rem;
            color:#D9EFEE;
            text-decoration:none;
            padding:.62rem 1rem;
            margin-bottom:.18rem;
            border-radius:.6rem;
            font-size:.89rem;
            font-weight:500;
            transition:background .15s ease, color .15s ease;
        }
        .sidebar-nav a i{ font-size:1.05rem; width:1.35rem; text-align:center; flex-shrink:0; }
        .sidebar-nav a:hover{ background:rgba(255,255,255,.10); color:#fff; }
        .sidebar-nav a.active{ background:#fff; color:var(--teal-800); font-weight:700; }
        .sidebar-nav a.active i{ color:var(--gold-600); }

        /* IDS menu item — warna sedikit berbeda untuk penanda keamanan */
        .sidebar-nav a.ids-link{
            color:#FFD6C8;
        }
        .sidebar-nav a.ids-link:hover{
            background:rgba(242,135,107,.15);
            color:#fff;
        }
        .sidebar-nav a.ids-link.active{
            background:#fff;
            color:#A23B2A;
        }
        .sidebar-nav a.ids-link.active i{ color:#A23B2A; }

        .sidebar-foot{
            margin-top:auto;
            padding:1rem 1.3rem 1.3rem;
            border-top:1px solid rgba(255,255,255,.12);
        }
        .sidebar-airport{
            font-size:.74rem;
            color:#9FCFCD;
            display:flex;
            align-items:flex-start;
            gap:.5rem;
            line-height:1.35;
        }

        @media (min-width:768px){
            .app-sidebar.offcanvas-md{
                position:static;
                z-index:auto;
                display:flex !important;
                visibility:visible !important;
                transform:none !important;
                box-shadow:none !important;
                height:auto;
            }
            .app-shell.sidebar-collapsed .app-sidebar.offcanvas-md{
                width:0;
                min-width:0;
                padding:0;
                overflow:hidden;
                border:0;
            }
            .app-shell.sidebar-collapsed .app-sidebar.offcanvas-md .offcanvas-body{
                width:260px;
            }
        }

        /* ---------- Main content ---------- */
        .app-content{ min-width:0; }
        .app-main{ padding:1.85rem; flex:1 0 auto; }

        .card{
            border:0;
            border-radius:1rem;
            box-shadow:0 1px 3px rgba(10,54,59,.06), 0 10px 28px -16px rgba(10,54,59,.18);
        }
        .table thead th{
            background:var(--mint-50);
            color:var(--ink-600);
            font-size:.74rem;
            text-transform:uppercase;
            letter-spacing:.04em;
            font-weight:600;
            border-bottom:2px solid var(--line);
        }
        .btn-primary{ background:var(--teal-700); border-color:var(--teal-700); }
        .btn-primary:hover, .btn-primary:focus{ background:var(--teal-600); border-color:var(--teal-600); }
        .btn-outline-primary{ color:var(--teal-700); border-color:var(--teal-700); }
        .btn-outline-primary:hover{ background:var(--teal-700); border-color:var(--teal-700); color:#fff; }
        .badge.bg-secondary{ background:var(--ink-400) !important; }

        .alert{ border:0; border-radius:.85rem; box-shadow:0 1px 3px rgba(10,54,59,.06); }
        .alert-success{ background:#E9F6EE; color:#1F6F45; }
        .alert-danger{ background:#FDECE8; color:#A23B2A; }

        /* ---------- Footer ---------- */
        .app-footer{
            padding:1rem 1.85rem;
            border-top:1px solid var(--line);
            font-size:.78rem;
            color:var(--ink-400);
            text-align:center;
        }

        @media (prefers-reduced-motion: reduce){
            *{ transition:none !important; animation:none !important; }
        }
    </style>
</head>
<body>

<?php
    $role = $this->session->userdata('role');
    $nama = $this->session->userdata('nama');

    $role_labels = [
        'admin'           => 'Admin',
        'ground_handling' => 'GH Airline',
        'unit_operasi'    => 'Airport Operation Airside',
        'unit_equipment'  => 'Airport Equipment',
        'unit_sales'      => 'Airport Non Aeronautical',
        'unit_security'   => 'Airport Security Protection',
    ];
    $rl = $role_labels[$role] ?? $role;

    $current   = $this->uri->segment(1);
    $is_active = function ($segment) use ($current) {
        return $segment === $current ? 'active' : '';
    };
    $initial = $nama ? mb_strtoupper(mb_substr($nama, 0, 1)) : '?';
?>

<nav class="app-navbar navbar sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <button class="sidebar-toggle-btn me-2" type="button" id="sidebarToggleBtn"
                aria-label="Buka/tutup menu" aria-controls="appSidebar">
            <i class="bi bi-list fs-5"></i>
        </button>

        <a class="navbar-brand" href="<?= site_url('dashboard') ?>">
            <div class="brand-mark">
                <img src="<?= base_url('assets/img/Logo_InJourney.png') ?>" alt="IGNIS Logo" height="48">
            </div>
            <span>
                <img src="<?= base_url('assets/img/InJourney_Airports.png') ?>" alt="IGNIS" style="height:36px;">
                <span class="brand-sub">Integrated GSE Access and Information System</span>
            </span>
        </a>

        <div class="ms-auto d-flex align-items-center gap-2 gap-lg-3">
            <span class="role-pill d-none d-sm-inline-block"><?= htmlspecialchars($rl ?? '') ?></span>

            <div class="dropdown">
                <button class="user-trigger dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar"><?= htmlspecialchars($initial) ?></span>
                    <span class="user-name d-none d-lg-inline"><?= htmlspecialchars($nama ?? '') ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">Masuk sebagai</span></li>
                    <li><span class="dropdown-item-text fw-semibold"><?= htmlspecialchars($nama ?? '') ?></span></li>
                    <li><span class="dropdown-item-text small text-muted d-sm-none"><?= htmlspecialchars($rl ?? '') ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="navbar-underline"></div>
</nav>

<div class="app-shell d-flex" id="appShell">

    <div class="offcanvas-md offcanvas-start app-sidebar" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">
        <div class="offcanvas-header d-md-none">
            <span class="offcanvas-title text-white fw-semibold" id="appSidebarLabel">Menu</span>
            <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Tutup"></button>
        </div>

        <div class="offcanvas-body">

            <!-- ── Menu Utama ─────────────────────────────────────────── -->
            <div class="sidebar-section-label">Menu Utama</div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('dashboard') ?>" class="<?= $is_active('dashboard') ?>">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>

                <a href="<?= site_url('gse') ?>" class="<?= $is_active('gse') ?>">
                    <i class="bi bi-truck"></i><span>Data GSE</span>
                </a>

                <a href="<?= site_url('permohonan_masuk') ?>" class="<?= $is_active('permohonan_masuk') ?>">
                    <i class="bi bi-envelope-arrow-down"></i><span>Permohonan Masuk</span>
                </a>

                <a href="<?= site_url('permohonan_keluar') ?>" class="<?= $is_active('permohonan_keluar') ?>">
                    <i class="bi bi-envelope-arrow-up"></i><span>Permohonan Keluar</span>
                </a>

                <a href="<?= site_url('scan') ?>" class="<?= $is_active('scan') ?>">
                    <i class="bi bi-qr-code-scan"></i><span>Scan Kamera</span>
                </a>

                <a href="<?= site_url('kerusakan') ?>" class="<?= $is_active('kerusakan') ?>">
                    <i class="bi bi-cone-striped"></i><span>Laporan Kerusakan</span>
                </a>
            </nav>

            <!-- ── Administrasi ───────────────────────────────────────── -->
            <?php if (in_array($role, ['unit_operasi', 'unit_equipment', 'unit_sales', 'unit_security', 'admin'])): ?>
            <div class="sidebar-section-label">Administrasi</div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('approval') ?>" class="<?= $is_active('approval') ?>">
                    <i class="bi bi-check2-square"></i><span>Verifikasi</span>
                </a>

                <?php if ($role === 'admin'): ?>
                <a href="<?= site_url('users') ?>" class="<?= $is_active('users') ?>">
                    <i class="bi bi-people"></i><span>Kelola User</span>
                </a>
                <a href="<?= site_url('airlines') ?>" class="<?= $is_active('airlines') ?>">
                    <i class="bi bi-airplane"></i><span>Kelola Airlines</span>
                </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>

            <!-- ── Monitor (Keamanan & Operasional) ────────────────────── -->
            <?php if (in_array($role, ['admin', 'unit_operasi'])): ?>
            <div class="sidebar-section-label">Monitor</div>
            <nav class="sidebar-nav">
                <?php if ($role === 'admin'): ?>
                <a href="<?= site_url('ids') ?>" class="ids-link <?= $is_active('ids') ?>">
                    <i class="bi bi-shield-check"></i><span>Keamanan</span>
                </a>
                <?php endif; ?>

                <a href="<?= site_url('bi') ?>" class="<?= $is_active('bi') ?>">
                    <i class="bi bi-graph-up"></i><span>Operasional (BI)</span>
                </a>
            </nav>
            <?php endif; ?>

            <div class="sidebar-foot">
                <div class="sidebar-airport">
                    <i class="bi bi-airplane-engines"></i>
                    <span>Bandar Udara Internasional<br>Sultan Hasanuddin</span>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content d-flex flex-column flex-grow-1">
        <main class="app-main flex-grow-1">

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>