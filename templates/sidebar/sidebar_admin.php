<style>
    .nav-link {
        color: white !important;
    }

    .nav-link:hover {
        background-color: #fff !important;
        color: #00640E !important;
    }

    .nav-link.active {
        background-color: #fff !important;
        color: #00640E !important;
    }
</style>
<aside class="main-sidebar elevation-4 text-white" style="background-color: #00640E;">
    <div class="sidebar">
        <div class="p-3 d-flex flex-column align-items-center">
            <img src="assets/img/logo.png" width="120" class="mb-3">
            <h5 class="text-center">APLIKASI ABSENSI</h5>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "dashboard") ? "active" : "")  : "active" ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">MASTER DATA</li>
                <li class="nav-item">
                    <a href="?page=jabatan" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "jabatan") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Jabatan
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="?page=pegawai" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "pegawai") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Siswa
                        </p>
                    </a>
                </li>
               
                <li class="nav-header">PRESENSI SISWA</li>
                <li class="nav-item">
                    <a href="?page=leaderboard" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "leaderboard") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-trophy text-warning"></i>
                        <p>
                            Leaderboard Siswa
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=scanner" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "scanner") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-qrcode"></i>
                        <p>
                            Scanner
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=riwayat_presensi" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "riwayat_presensi") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>
                            Riwayat Presensi
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=tabel_presensi" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "tabel_presensi") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Tabel Presensi
                        </p>
                    </a>
                </li>
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item <?= isset($_GET['page']) ? (($_GET['page'] == 'laporan') ? "menu-open" : "")  : "" ?>">
                    <a href="#" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] == 'laporan') ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="?page=laporan&method=pegawai" class="nav-link <?= isset($_GET['method']) ? (($_GET['method'] === "pegawai") ? "active" : "")  : "" ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?page=laporan&method=riwayat_presensi" class="nav-link <?= isset($_GET['method']) ? (($_GET['method'] === "riwayat_presensi") ? "active" : "")  : "" ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Riwayat Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?page=laporan&method=presensi_bulanan" class="nav-link <?= isset($_GET['method']) ? (($_GET['method'] === "presensi_bulanan") ? "active" : "")  : "" ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Presensi Bulanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">PENGATURAN</li>
                <li class="nav-item">
                    <a href="?page=ganti_password" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "ganti_password") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Ganti Password
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=setting_lokasi" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "setting_lokasi") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>
                            Setting Lokasi GPS
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=setting_telegram" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "setting_telegram") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fab fa-telegram-plane"></i>
                        <p>
                            Setting Bot Telegram
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="halaman_auth/logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<div class="content-wrapper">