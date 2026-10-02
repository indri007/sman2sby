<?php
if (isset($_POST['submit'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $q = "INSERT INTO honor_pegawai VALUES (null, " . $_GET['id'] . "," . $id_pegawai . ")";
    $mysqli->query($q);
}
if (isset($_POST['delete'])) {
    $id_honor_pegawai = $_POST['id_honor_pegawai'];
    $q = "DELETE FROM honor_pegawai WHERE id=" . $id_honor_pegawai;
    $mysqli->query($q);
}
if (isset($_GET['id'])) {
    $result = $mysqli->query("SELECT * FROM honor WHERE id=" . $_GET['id']);
    $data = $result->fetch_assoc();

    $q = "
        SELECT 
            hp.id id_honor_pegawai,
            p.* 
        FROM 
            honor_pegawai hp 
        INNER JOIN 
            pegawai p 
        ON 
            p.id=hp.id_pegawai 
        WHERE 
            id_honor=" . $data['id'] . " 
        ORDER BY 
            hp.id DESC 
        ";
    $pegawai = $mysqli->query($q);
} else {
    echo "<script>alert('id tidak ditemukan');</script>";
    echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1>Form Honor</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title m-1">Detail Honor</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Tanggal Perjalanan</label>
                            <input type="text" class="form-control" disabled value="<?= indonesiaDate($data['tanggal']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Tujuan</label>
                            <input type="text" class="form-control" disabled value="<?= $data['tujuan']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Transportasi yang digunakan</label>
                            <input type="text" class="form-control" disabled value="<?= $data['transportasi']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Alasan Kepergian</label>
                            <textarea name="alasan" id="alasan" class="form-control" disabled autocomplete="off"><?= $data['alasan']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Biaya Perjalanan</label>
                            <input type="text" class="form-control" disabled value="<?= number_format($data['biaya_perjalanan'], 0, ",", "."); ?>">
                        </div>
                        <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title m-0">Detail Honor</h3>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center td-fit">No</th>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center td-fit">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                ?>
                                <?php while ($row = $pegawai->fetch_assoc()) : ?>
                                    <tr>
                                        <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                                        <td class="text-center td-fit" style="vertical-align: middle;"><?= $row['nip'] ?></td>
                                        <td style="vertical-align: middle;"><?= $row['nama'] ?></td>
                                        <td class="text-center td-fit">
                                            <form action="" method="POST" class="d-inline">
                                                <input type="text" name="id_honor_pegawai" value="<?= $row['id_honor_pegawai']; ?>" hidden>
                                                <button name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Honor Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" class="form-control" name="nip" disabled>
                    </div>
                    <div class="form-group">
                        <label for="id_pegawai">Pegawai</label>
                        <?php
                        if (!$result = $mysqli->query("SELECT * FROM pegawai"))
                            echo "Error: " . $q . "<br>" . $mysqli->error;
                        ?>
                        <select class="form-control select2bs4" name="id_pegawai" id="id_pegawai" required>
                            <option value="" disabled selected>Pilih Pegawai</option>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <option data-nip="<?= $row['nip']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tutup</button>
                        <button name="submit" type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelector("select[name=id_pegawai]").addEventListener('change', function() {
        document.querySelector("input[name=nip]").value = this[this.selectedIndex].getAttribute('data-nip');
    });
</script>