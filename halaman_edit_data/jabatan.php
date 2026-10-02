<?php

if (isset($_GET['id'])) {
    $result = $mysqli->query("SELECT * FROM jabatan WHERE id=" . $_GET['id']);
    $data = $result->fetch_assoc();
} else {
    echo "<script>alert('id tidak ditemukan');</script>";
    echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
}

if (isset($_POST['submit'])) {
    $nama = $mysqli->real_escape_string($_POST['nama']);
    $golongan = $mysqli->real_escape_string($_POST['golongan']);
    $gaji_pokok = $mysqli->real_escape_string($_POST['gaji_pokok']);

    $q = "
        UPDATE jabatan SET 
            nama='$nama',
            golongan='$golongan',
            gaji_pokok='$gaji_pokok'  
        WHERE 
            id=" . $_GET['id'] . "
    ";

    if ($mysqli->query($q)) {
        echo "<script>alert('Berhasil memperbaharui data jabatan');</script>";
        echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
    } else echo "Error: " . $q . "<br>" . $mysqli->error;
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row justify-content-center mb-2">
            <div class="col-sm-6">
                <h1 class="text-center">Form Jabatan</h1>
            </div>
        </div>
    </div>
</section>
<form action="" method="POST">
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama">Nama Jabatan</label>
                                <input type="text" class="form-control" name="nama" id="nama" required value="<?= $data['nama']; ?>" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="golongan">Golongan</label>
                                <input type="text" class="form-control" name="golongan" id="golongan" value="<?= $data['golongan']; ?>" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="gaji_pokok">Gaji Pokok</label>
                                <input type="text" class="form-control" name="gaji_pokok" id="gaji_pokok" value="<?= $data['gaji_pokok']; ?>" required autocomplete="off">
                            </div>
                            <div>
                                <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>