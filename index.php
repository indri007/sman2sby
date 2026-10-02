<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
include_once "templates/header.php";
include_once "utils/utils.php";
include_once "database/koneksi.php";
if (isset($_SESSION['user'])) {
    include_once "templates/navbar.php";
    if ($_SESSION['user']['status'] == 'ADMIN') {
        include_once "templates/sidebar/sidebar_admin.php";
        if (isset($_GET['page'])) {
            switch ($_GET['page']) {
                case "jabatan":
                    if (isset($_GET['method'])) {
                        if ($_GET['method'] === 'tambah')
                            include_once "halaman_tambah_data/jabatan.php";
                        elseif ($_GET['method'] === 'edit')
                            include_once "halaman_edit_data/jabatan.php";
                        elseif ($_GET['method'] === 'hapus')
                            include_once "halaman_hapus_data/jabatan.php";
                    } else
                        include_once "halaman_tampil_data/jabatan.php";
                    break;
                case "tunjangan":
                    if (isset($_GET['method'])) {
                        if ($_GET['method'] === 'tambah')
                            include_once "halaman_tambah_data/tunjangan.php";
                        elseif ($_GET['method'] === 'edit')
                            include_once "halaman_edit_data/tunjangan.php";
                        elseif ($_GET['method'] === 'hapus')
                            include_once "halaman_hapus_data/tunjangan.php";
                    } else
                        include_once "halaman_tampil_data/tunjangan.php";
                    break;
                case "pegawai":
                    if (isset($_GET['method'])) {
                        if ($_GET['method'] === 'tambah')
                            include_once "halaman_tambah_data/pegawai.php";
                        elseif ($_GET['method'] === 'edit')
                            include_once "halaman_edit_data/pegawai.php";
                        elseif ($_GET['method'] === 'hapus')
                            include_once "halaman_hapus_data/pegawai.php";
                        elseif ($_GET['method'] === 'detail')
                            include_once "halaman_detail/pegawai.php";
                    } else
                        include_once "halaman_tampil_data/pegawai.php";
                    break;
                case "honor":
                    if (isset($_GET['method'])) {
                        if ($_GET['method'] === 'tambah')
                            include_once "halaman_tambah_data/honor.php";
                        elseif ($_GET['method'] === 'edit')
                            include_once "halaman_edit_data/honor.php";
                        elseif ($_GET['method'] === 'hapus')
                            include_once "halaman_hapus_data/honor.php";
                        elseif ($_GET['method'] === 'detail')
                            include_once "halaman_detail/honor.php";
                    } else
                        include_once "halaman_tampil_data/honor.php";
                    break;
                case "tabel_presensi":
                    include_once "halaman_lainnya/tabel_presensi.php";
                    break;
                case "leaderboard":
                    include_once "halaman_lainnya/leaderboard.php";
                    break;
                case "scanner":
                    include_once "halaman_lainnya/scanner.php";
                    break;
                case "ganti_password":
                    include_once "halaman_lainnya/ganti_password.php";
                    break;
                case "setting_lokasi":
                    include_once "halaman_lainnya/setting_lokasi.php";
                    break;
                case "setting_telegram":
                    include_once "halaman_lainnya/setting_telegram.php";
                    break;
                case "riwayat_presensi":
                    if (isset($_GET['method'])) {
                        if ($_GET['method'] === 'tambah')
                            include_once "halaman_tambah_data/riwayat_presensi.php";
                        elseif ($_GET['method'] === 'edit')
                            include_once "halaman_edit_data/riwayat_presensi.php";
                        elseif ($_GET['method'] === 'hapus')
                            include_once "halaman_hapus_data/riwayat_presensi.php";
                        elseif ($_GET['method'] === 'detail')
                            include_once "halaman_detail/riwayat_presensi.php";
                    } else
                        include_once "halaman_tampil_data/riwayat_presensi.php";
                    break;
                case "laporan":
                    if (isset($_GET['method'])) {
                        if ($_GET['method'] === 'pegawai')
                            include_once "halaman_laporan/pegawai.php";
                        elseif ($_GET['method'] === 'riwayat_presensi')
                            include_once "halaman_laporan/riwayat_presensi.php";
                        elseif ($_GET['method'] === 'presensi_bulanan')
                            include_once "halaman_laporan/presensi_bulanan.php";
                        elseif ($_GET['method'] === 'gaji_pegawai')
                            include_once "halaman_laporan/gaji_pegawai.php";
                        elseif ($_GET['method'] === 'tunjangan_pegawai')
                            include_once "halaman_laporan/tunjangan_pegawai.php";
                        elseif ($_GET['method'] === 'slip_gaji_pegawai')
                            include_once "halaman_laporan/slip_gaji_pegawai.php";
                        elseif ($_GET['method'] === 'honor')
                            include_once "halaman_laporan/honor.php";
                        elseif ($_GET['method'] === 'kenaikan_gaji')
                            include_once "halaman_laporan/kenaikan_gaji.php";
                    }
                    break;
                default:
                    include_once "beranda.php";
            }
        } else
            include_once "beranda.php";
    } else if ($_SESSION['user']['status'] == 'PEGAWAI') {
        include_once "templates/sidebar/sidebar_pegawai.php";
        if (isset($_GET['page'])) {
            switch ($_GET['page']) {
                case "ganti_password":
                    include_once "halaman_lainnya/ganti_password.php";
                    break;
                case "data_diri":
                    include_once "halaman_detail/pegawai.php";
                    break;
                default:
                    include_once "beranda_pegawai.php";
            }
        } else
            include_once "beranda_pegawai.php";
    }
} else     echo "<script>window.location.href = 'halaman_auth/login.php';</script>";
include_once "templates/footer.php";
