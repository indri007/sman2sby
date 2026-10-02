<?php
if (isset($_POST['submit'])) {
    $tanggal = $mysqli->real_escape_string($_POST['tanggal']);
    $tujuan = $mysqli->real_escape_string($_POST['tujuan']);
    $transportasi = $mysqli->real_escape_string($_POST['transportasi']);
    $alasan = $mysqli->real_escape_string($_POST['alasan']);
    $biaya_perjalanan = $mysqli->real_escape_string($_POST['biaya_perjalanan']);

    try {
        $mysqli->begin_transaction();

        $q = "
            INSERT INTO honor (
                tanggal,
                transportasi,
                alasan,
                tujuan,
                biaya_perjalanan
            ) VALUES (
                '$tanggal',
                '$transportasi',
                '$alasan',
                '$tujuan',
                '$biaya_perjalanan'
            )
        ";
        $mysqli->query($q);


        $mysqli->commit();
        echo "<script>alert('Berhasil menambah data honor');</script>";
        echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
    } catch (\Throwable $e) {
        $mysqli->rollback();
        throw $e;
    };
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12 text-center">
                <h1>Form Honor</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card card-primary">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="tanggal">Tanggal Perjalanan</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" required value="<?= Date("Y-m-d"); ?>">
                            </div>
                            <div class="form-group">
                                <label for="tujuan">Tujuan</label>
                                <input type="text" class="form-control" name="tujuan" id="tujuan" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="transportasi">Transportasi yang digunakan</label>
                                <input type="text" class="form-control" name="transportasi" id="transportasi" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="alasan">Alasan Kepergian</label>
                                <textarea name="alasan" id="alasan" class="form-control" required autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="biaya_perjalanan">Biaya Perjalanan</label>
                                <input type="number" class="form-control" name="biaya_perjalanan" min="0" id="biaya_perjalanan" required autocomplete="off">
                            </div>
                            <div>
                                <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Tambah</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>