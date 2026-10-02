<?php
if (isset($_POST['submit'])) {
    $nama = $mysqli->real_escape_string($_POST['nama']);
    $tunjangan = $mysqli->real_escape_string($_POST['tunjangan']);
    $jenis_pemberian = $mysqli->real_escape_string($_POST['jenis_pemberian']);

    $q = "
        INSERT INTO tunjangan (
            nama,
            tunjangan,
            jenis_pemberian  
        ) VALUES (
            '$nama',
            '$tunjangan',
            '$jenis_pemberian' 
        )
    ";

    if ($mysqli->query($q)) {
        echo "<script>alert('Berhasil menambah data tunjangan');</script>";
        echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
    } else echo "Error: " . $q . "<br>" . $mysqli->error;
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row justify-content-center mb-2">
            <div class="col-sm-6">
                <h1 class="text-center">Form Tunjangan</h1>
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
                                <label for="nama">Nama Tunjangan</label>
                                <input type="text" class="form-control" name="nama" id="nama" required autofocus autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="tunjangan">Nominal Tunjangan</label>
                                <input type="text" class="form-control" name="tunjangan" id="tunjangan" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis_pemberian">Jenis Pemberian</label>
                                <input type="text" class="form-control" name="jenis_pemberian" id="jenis_pemberian" required autocomplete="off">
                            </div>
                            <div>
                                <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>