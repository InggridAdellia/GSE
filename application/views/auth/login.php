<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IGNIS | Integrated GSE Access and Information System</title>
    <link href="<?= base_url('assets/vendor/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fonts/fonts.css') ?>" rel="stylesheet">      
    <style>
        :root{
            --ignis-teal:#478a90;
            --ignis-teal-dark:#065f67;
            --ignis-orange:#f26c58;
            --ignis-yellow:#fcb244;
            --ignis-green:#a2c03b;
        }
        *{box-sizing:border-box}
        html,body{height:100%;margin:0;font-family:'Inter',sans-serif;color:#1f2937}
        .login-wrapper{display:flex;min-height:100vh}

        .login-left{
            flex:1;position:relative;overflow:hidden;
        }
        .slide{
            position:absolute;inset:0;
            background-size:cover;
            background-position:center;
            opacity:0;
            transition:opacity 1.2s ease-in-out;
            z-index:0;
        }
        .slide.active{opacity:1;z-index:1}
        .login-left::before{
            content:"";position:absolute;inset:0;
            background:linear-gradient(90deg, rgba(255,255,255,0) 40%, rgba(255,255,255,.15) 65%, rgba(234,247,247,.85) 100%);
            z-index:2;
            pointer-events:none;
        }

        /* RIGHT PANEL — Form */
        .login-right{
            flex:0 0 46%;
            max-width:560px;
            padding:48px 56px;
            display:flex;flex-direction:column;
            background:
              radial-gradient(1200px 600px at 110% 0%, #ffe4d6 0%, transparent 60%),
              radial-gradient(900px 500px at 90% 100%, #fff5cf 0%, transparent 55%),
              linear-gradient(135deg,#eaf7f7 0%,#f4f9ec 50%,#fdeee4 100%);
        }
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:48px}
        .brand-title{font-weight:800;font-size:28px;color:var(--ignis-teal);letter-spacing:1px;margin:0}
        .brand-sub{font-size:12px;color:#6b7280;margin:0}

        .welcome h1{color:var(--ignis-teal-dark);font-weight:800;font-size:32px;margin:0 0 6px}
        .welcome h2{color:var(--ignis-teal);font-weight:600;font-size:24px;margin:0 0 4px}
        .welcome p.italic{font-style:italic;color:#898989;margin:0 0 28px;font-size:14px}
        .signin-label{color:#6b7280;text-align:center;font-size:14px;margin:24px 0 14px}

        .form-floating-custom{position:relative;margin-bottom:14px}
        .form-floating-custom label{
            position:absolute;top:8px;left:16px;font-size:11px;color:#6b7280;font-weight:600;
        }
        .form-floating-custom input{
            width:100%;border:1px solid #e5e7eb;background:#fff;border-radius:10px;
            padding:26px 44px 10px 16px;font-size:14px;outline:none;transition:.2s;
        }
        .form-floating-custom input:focus{border-color:var(--ignis-teal);box-shadow:0 0 0 3px rgba(42,138,138,.15)}
        .form-floating-custom .toggle-pass{
            position:absolute;right:14px;top:50%;transform:translateY(-50%);
            background:none;border:none;color:#9ca3af;cursor:pointer;font-size:18px;
        }

        .divider{display:flex;align-items:center;gap:12px;margin:18px 0;color:#9ca3af;font-size:13px}
        .divider::before,.divider::after{content:"";flex:1;height:1px;background:#e5e7eb}

        .btn-ignis{
            width:100%;border:none;border-radius:10px;padding:14px;color:#fff;font-weight:700;font-size:15px;
            background:linear-gradient(135deg,var(--ignis-teal),var(--ignis-teal-dark));
            box-shadow:0 10px 24px -10px rgba(42,138,138,.6);transition:.2s;
            display:flex;align-items:center;justify-content:center;gap:10px;
        }
        .btn-ignis:hover{transform:translateY(-1px);filter:brightness(1.05)}

        .forgot{display:inline-block;color:var(--ignis-teal);font-weight:600;font-size:13px;text-decoration:none;margin:4px 0 18px}
        .forgot:hover{text-decoration:underline}

        .footer{margin-top:auto;padding-top:32px;text-align:center;color:#6b7280;font-size:12px}
        .footer .ver{font-size:10px;color:#9ca3af;margin-top:4px}

        @media(max-width:900px){
            .login-left{display:none}
            .login-right{flex:1;max-width:100%;padding:32px 24px}
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- LEFT: Slideshow Gambar -->
    <div class="login-left">
        <!-- GANTI URL/FILE FOTO DI BAWAH INI -->
        <div class="slide active" style="background-image:url('<?= base_url('assets/img/slide1.jpg') ?>')"></div>
        <div class="slide" style="background-image:url('<?= base_url('assets/img/slide2.jpg') ?>')"></div>
        <div class="slide" style="background-image:url('<?= base_url('assets/img/slide3.jpg') ?>')"></div>
        <div class="slide" style="background-image:url('<?= base_url('assets/img/slide4.jpg') ?>')"></div>
    </div>

    <!-- RIGHT: Form Login -->
    <div class="login-right">
        <div class="brand">
            <img src="<?= base_url('assets/img/Logo_Injourney.png') ?>" alt="IGNIS Logo" height="48">
            <div>
                <img src="<?= base_url('assets/img/InJourney_Airports.png') ?>" alt="IGNIS" style="height:36px;">
                <p class="brand-sub">Integrated GSE Access and Information System</p>
            </div>
        </div>

        <div class="welcome">
            <h1>Welcome to</h1>
            <h2>Integrated GSE Access and Information System</h2>
            <p class="italic">Bandar Udara Internasional Sultan Hasanuddin</p>
        </div>

        <p class="signin-label">Sign in with your account</p>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:13px">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('auth/login') ?>" method="post">
            <div class="form-floating-custom">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">
            </div>
            <div class="form-floating-custom">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" minlength="8" required autocomplete="current-password">
                <small style="color:#6b7280;font-size:12px;margin-top:4px;display:block;">Password minimal 8 karakter dengan angka dan tanda baca.</small>
                <button type="button" class="toggle-pass" onclick="togglePassword()"><i class="bi bi-eye-slash" id="eyeIcon"></i></button>
            </div>
            <a href="#" class="forgot">Forgot password?</a>
            <button type="submit" class="btn-ignis">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>

        <div class="footer">
            <div>&copy; <?= date('Y') ?> - Integrated GSE Access and Information System</div>
            <div>All rights reserved</div>
            <div class="ver">v1.0.0</div>
        </div>
    </div>

</div>

<script>
    // --- Slideshow Logic ---
    const slides = document.querySelectorAll('.login-left .slide');
    let current = 0;
    setInterval(() => {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }, 5000); 

    // --- Toggle Password ---
    function togglePassword(){
        const pw = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if(pw.type === 'password'){
            pw.type = 'text';
            icon.classList.replace('bi-eye-slash','bi-eye');
        }else{
            pw.type = 'password';
            icon.classList.replace('bi-eye','bi-eye-slash');
        }
    }
</script>

</body>
</html>
