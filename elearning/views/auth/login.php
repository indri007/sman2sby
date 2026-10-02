<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi E-Learning SMAN 2 Surabaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= file_exists(__DIR__ . '/../../assets/css/style.css') ? filemtime(__DIR__ . '/../../assets/css/style.css') : time() ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .login-page {
            min-height: 100vh;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 32px 26px 26px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
        }

        .btn-login-green {
            background-color: #05682b !important;
            color: #ffffff !important;
            border: 1px solid #05682b !important;
            border-radius: 6px !important;
            font-weight: 700 !important;
            height: 44px;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-login-green:hover {
            background-color: #045222 !important;
            border-color: #045222 !important;
            box-shadow: 0 4px 12px rgba(5, 104, 43, 0.25);
        }

        .btn-absensi-outline {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #05682b;
            color: #05682b;
            background-color: #ffffff;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13.5px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-absensi-outline:hover {
            background-color: #05682b !important;
            color: #ffffff !important;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group-custom .form-control {
            padding-right: 40px;
            height: 44px;
            border-radius: 6px;
            font-size: 14px;
            border: 1.5px solid #cbd5e1;
        }

        .input-group-custom .form-control:focus {
            border-color: #05682b;
            box-shadow: 0 0 0 3px rgba(5, 104, 43, 0.15);
        }

        .input-group-icon {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body class="login-page">

<div class="login-box">
    <!-- Card Login Style AdminLTE SMAN 2 Surabaya -->
    <div class="login-card">
        <!-- Logo & Judul Aplikasi -->
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-bottom: 24px; text-align: center;">
            <img src="assets/images/logo.webp" alt="Logo SMAN 2 Surabaya" style="width: 120px; height: auto; margin-bottom: 14px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
            <p style="font-weight: 800; font-size: 16px; color: #1e293b; letter-spacing: 0.5px; text-transform: uppercase; margin: 0;">
                APLIKASI E-LEARNING
            </p>
        </div>

        <?php if ($msg = Helper::flash('error')): ?>
            <div class="alert alert-error" style="margin-bottom: 18px; padding: 10px 14px; font-size: 13px;">
                <span><i data-lucide="alert-circle" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i> <?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>
        <?php if ($msg = Helper::flash('success')): ?>
            <div class="alert alert-success" style="margin-bottom: 18px; padding: 10px 14px; font-size: 13px;">
                <span><i data-lucide="check-circle" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i> <?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>

        <form action="index.php?page=login" method="POST">
            <div class="input-group-custom">
                <input type="text" id="username" name="username" class="form-control" placeholder="Username / NIP / NISN" autocomplete="off" autofocus required>
                <span class="input-group-icon">
                    <i data-lucide="user" style="width: 18px; height: 18px;"></i>
                </span>
            </div>

            <div class="input-group-custom" style="margin-bottom: 22px;">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <span class="input-group-icon">
                    <i data-lucide="lock" style="width: 18px; height: 18px;"></i>
                </span>
            </div>

            <button type="submit" class="btn-login-green">
                <i data-lucide="log-in" style="width: 16px; height: 16px;"></i> Login
            </button>
        </form>

        <!-- Link Menuju Aplikasi Absensi (sman2sby.com) -->
        <div style="text-align: center; margin-top: 18px; padding-top: 18px; border-top: 1px solid #e9ecef;">
            <a href="https://sman2sby.com/" target="_blank" class="btn-absensi-outline">
                <i data-lucide="clipboard-check" style="width: 16px; height: 16px;"></i> Menuju Aplikasi Absensi
            </a>
        </div>
    </div>

    <!-- Footer Copyright -->
    <div style="text-align: center; margin-top: 18px; font-size: 12.5px; color: #64748b; font-weight: 600;">
        &copy; 2026 SMAN 2 Surabaya
    </div>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
