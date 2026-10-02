<?php

if (isset($_GET['id'])) {
    if ($mysqli->query("DELETE FROM honor WHERE id=" . $_GET['id'])) {
        echo "<script>alert('Honor berhasil dihapus.')</script>";
        echo "<script>" .
            "window.location.href='?page=" . $_GET['page'] . "';" .
            "</script>";
    } else echo "Error: " . $mysqli->error;
} else {
    echo "<script>alert('id tidak ditemukan');</script>";
    echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
}
