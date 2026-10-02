<?php

if (isset($_GET['id'])) {
    if ($mysqli->query("DELETE FROM presensi_pegawai WHERE id=" . $_GET['id'])) {
        echo "<script>alert('Presensi berhasil dihapus.')</script>";
        echo "<script>" .
            "window.location.href='?page=" . $_GET['page'] . "';" .
            "</script>";
    } else echo "Error: " . $mysqli->error;
} else {
    echo "<script>alert('id tidak ditemukan');</script>";
    echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
}
