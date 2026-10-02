<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Membaca Buku Digital - SMAN 2 Surabaya') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= file_exists(__DIR__ . '/../../assets/css/style.css') ? filemtime(__DIR__ . '/../../assets/css/style.css') : time() ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            width: 100%;
            overflow: hidden;
            background-color: #0f172a;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Top Reading Bar */
        .reader-navbar {
            height: 60px;
            background-color: #1e293b;
            border-bottom: 1.5px solid #334155;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            color: #ffffff;
            z-index: 100;
        }

        .reader-title-area {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
            overflow: hidden;
        }

        .reader-btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .reader-btn-back:hover {
            background-color: #05682b;
            border-color: #05682b;
        }

        .reader-book-title {
            font-size: 14.5px;
            font-weight: 800;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reader-meta-tag {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 4px;
            background-color: #05682b;
            color: #ffffff;
            flex-shrink: 0;
        }

        .reader-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .reader-security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #86efac;
            background-color: rgba(5, 104, 43, 0.35);
            border: 1px solid rgba(134, 239, 172, 0.4);
            padding: 5px 12px;
            border-radius: 20px;
        }

        .reader-btn-fullscreen {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .reader-btn-fullscreen:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        /* Container PDF Viewer */
        .reader-viewer-container {
            width: 100%;
            height: calc(100vh - 60px);
            position: relative;
            background-color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pdf-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
            background-color: #334155;
        }

        /* Anti-Download Print Security */
        @media print {
            body {
                display: none !important;
            }
        }

        /* Toast Warning for Download Attempts */
        #antiDownloadToast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background-color: #05682b;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #86efac;
        }

        #antiDownloadToast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>

    <!-- Top Reading Bar -->
    <header class="reader-navbar">
        <div class="reader-title-area">
            <?php
            $backUrl = 'index.php?page=' . (Auth::role() === 'SISWA' ? 'siswa_buku' : (Auth::role() === 'ADMIN' ? 'admin_buku' : 'guru_buku'));
            ?>
            <a href="<?= $backUrl ?>" class="reader-btn-back" title="Kembali ke Perpustakaan">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
                <span class="d-none-mobile">Kembali ke Perpus</span>
            </a>

            <span class="reader-meta-tag"><?= htmlspecialchars($buku['nama_mapel'] ?? 'Buku') ?></span>

            <h1 class="reader-book-title" title="<?= htmlspecialchars($buku['judul']) ?>">
                <?= htmlspecialchars($buku['judul']) ?>
            </h1>
        </div>

        <div class="reader-controls">
            <div class="reader-security-badge" title="Buku ini hanya untuk dibaca online tanpa opsi download">
                <i data-lucide="lock" style="width:14px;height:14px;"></i>
                <span>Cuman Baca (Anti-Download)</span>
            </div>

            <button type="button" class="reader-btn-fullscreen" id="btnFullscreen" title="Layar Penuh (Fullscreen)">
                <i data-lucide="maximize" style="width:18px;height:18px;"></i>
            </button>
        </div>
    </header>

    <!-- Main PDF Embed Viewer (toolbar=0 navpanes=0 hides browser download buttons) -->
    <main class="reader-viewer-container" id="viewerContainer">
        <iframe
            src="index.php?page=api_buku_stream&id=<?= $buku['id'] ?>#toolbar=0&navpanes=0&scrollbar=1"
            class="pdf-frame"
            id="pdfIframe"
            title="<?= htmlspecialchars($buku['judul']) ?>">
            <p style="color:#ffffff;padding:30px;text-align:center;">
                Peramban Anda tidak mendukung penampil PDF langsung.
            </p>
        </iframe>
    </main>

    <!-- Anti-Download Notification Toast -->
    <div id="antiDownloadToast">
        <i data-lucide="shield-alert" style="width:20px;height:20px;color:#86efac;"></i>
        <span>Buku ini dilindungi perpustakaan digital SMAN 2 Surabaya (Hanya untuk dibaca online).</span>
    </div>

    <script>
        lucide.createIcons();

        // Toast Helper
        const toast = document.getElementById('antiDownloadToast');
        let toastTimeout;
        function showWarning(msg) {
            if (msg) toast.querySelector('span').innerText = msg;
            toast.classList.add('show');
            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        // 1. Disable Right-Click Context Menu
        document.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            showWarning('Klik kanan dinonaktifkan pada mode baca perpustakaan online.');
            return false;
        });

        // 2. Block Keyboard Shortcuts (Ctrl+S, Cmd+S, Ctrl+P, Cmd+P, Ctrl+U)
        document.addEventListener('keydown', (e) => {
            // Save shortcut (Ctrl+S / Cmd+S)
            if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
                showWarning('Unduh berkas PDF tidak diizinkan. Silakan membaca langsung di perpustakaan online.');
                return false;
            }

            // Print shortcut (Ctrl+P / Cmd+P)
            if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 'P')) {
                e.preventDefault();
                showWarning('Fitur cetak dinonaktifkan untuk buku digital terlindungi ini.');
                return false;
            }

            // View source shortcut (Ctrl+U / Cmd+U)
            if ((e.ctrlKey || e.metaKey) && (e.key === 'u' || e.key === 'U')) {
                e.preventDefault();
                return false;
            }
        });

        // 3. Fullscreen Toggle
        const btnFullscreen = document.getElementById('btnFullscreen');
        const container = document.documentElement;

        btnFullscreen.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                }
                btnFullscreen.innerHTML = '<i data-lucide="minimize" style="width:18px;height:18px;"></i>';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
                btnFullscreen.innerHTML = '<i data-lucide="maximize" style="width:18px;height:18px;"></i>';
            }
            lucide.createIcons();
        });
    </script>
</body>
</html>
