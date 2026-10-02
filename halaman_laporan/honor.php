<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Honor</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title flex-grow-1">Filter</h3>
                    <form action="halaman_cetak_laporan/honor.php" method="POST" target="_blank">
                        <?php if (isset($_POST['dari_tanggal'])) : ?>
                            <input type="text" hidden name="dari_tanggal" value="<?= $_POST['dari_tanggal'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['sampai_tanggal'])) : ?>
                            <input type="text" hidden name="sampai_tanggal" value="<?= $_POST['sampai_tanggal'] ?>">
                        <?php endif; ?>
                        <button class="btn btn-success">Cetak</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="dari_tanggal">Dari Tanggal</label>
                            <input type="date" class="form-control" name="dari_tanggal" id="dari_tanggal" required value="<?= $_POST['dari_tanggal'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="sampai_tanggal">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="sampai_tanggal" id="sampai_tanggal" required value="<?= $_POST['sampai_tanggal'] ?? ''; ?>">
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="?page=<?= $_GET['page']; ?>&method=<?= $_GET['method']; ?>" class="ml-2 btn btn-secondary">Reset</a>
                            <button type="submit" class="ml-2 btn btn-info">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="example3" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center td-fit">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tujuan</th>
                        <th class="text-center">Transportasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "SELECT * FROM honor ";
                    if (isset($_POST['dari_tanggal']) && isset($_POST['sampai_tanggal']))
                        $q .= " WHERE tanggal >= '" . $_POST['dari_tanggal'] . "' AND tanggal <= '" . $_POST['sampai_tanggal'] . "'";

                    $q .= " ORDER BY tanggal DESC, id DESC";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= indonesiaDate($row['tanggal']) ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['tujuan'] ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['transportasi'] ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;">
                                <a href="halaman_cetak_laporan/detail_honor.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-primary btn-sm">Cetak</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>