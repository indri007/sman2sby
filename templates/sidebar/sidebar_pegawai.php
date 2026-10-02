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
                <li class="nav-header">MENU UTAMA</li>
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "dashboard") ? "active" : "")  : "active" ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=data_diri" class="nav-link <?= isset($_GET['page']) ? (($_GET['page'] === "data_diri") ? "active" : "")  : "" ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Data Diri
                        </p>
                    </a>
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