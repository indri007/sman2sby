<?php
if (isset($_GET['id'])) {
    $result = $mysqli->query("SELECT * FROM honor WHERE id=" . $_GET['id']);
    $data = $result->fetch_assoc();
} else {
    echo "<script>alert('id tidak ditemukan');</script>";
    echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
}
if (isset($_POST['submit'])) {
    $tanggal = $mysqli->real_escape_string($_POST['tanggal']);
    $tujuan = $mysqli->real_escape_string($_POST['tujuan']);
    $transportasi = $mysqli->real_escape_string($_POST['transportasi']);
    $alasan = $mysqli->real_escape_string($_POST['alasan']);
    $biaya_perjalanan = $mysqli->real_escape_string($_POST['biaya_perjalanan']);

    try {
        $mysqli->begin_transaction();

        $q = "
            UPDATE honor SET 
                tanggal='$tanggal',
                transportasi='$transportasi',
                alasan='$alasan',
                tujuan='$tujuan', 
                biaya_perjalanan='$biaya_perjalanan' 
            WHERE 
                id=" . $_GET['id'] . "
        ";
        $mysqli->query($q);


        $mysqli->commit();
        echo "<script>alert('Berhasil memperbaharui data honor');</script>";
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
                                <input type="date" class="form-control" name="tanggal" id="tanggal" required value="<?= $data['tanggal']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="tujuan">Tujuan</label>
                                <input type="text" class="form-control" name="tujuan" id="tujuan" required autocomplete="off" value="<?= $data['tujuan']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="transportasi">Transportasi yang digunakan</label>
                                <input type="text" class="form-control" name="transportasi" id="transportasi" required autocomplete="off" value="<?= $data['transportasi']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="alasan">Alasan Kepergian</label>
                                <textarea name="alasan" id="alasan" class="form-control" required autocomplete="off"><?= $data['alasan']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="biaya_perjalanan">Biaya Perjalanan</label>
                                <input type="text" class="form-control" name="biaya_perjalanan" id="biaya_perjalanan" required min="0" autocomplete="off" value="<?= $data['biaya_perjalanan']; ?>">
                            </div>
                            <div>
                                <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>