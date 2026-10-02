<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Pegawai</h1>
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
                    <form action="halaman_cetak_laporan/pegawai.php" method="POST" target="_blank">
                        <?php if (isset($_POST['dari_tmt'])) : ?>
                            <input type="text" hidden name="dari_tmt" value="<?= $_POST['dari_tmt'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['sampai_tmt'])) : ?>
                            <input type="text" hidden name="sampai_tmt" value="<?= $_POST['sampai_tmt'] ?>">
                        <?php endif; ?>
                        <button class="btn btn-success">Cetak</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="dari_tmt">Dari TMT</label>
                            <input type="date" class="form-control" name="dari_tmt" id="dari_tmt" required value="<?= $_POST['dari_tmt'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="sampai_tmt">Sampai TMT</label>
                            <input type="date" class="form-control" name="sampai_tmt" id="sampai_tmt" required value="<?= $_POST['sampai_tmt'] ?? ''; ?>">
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
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">TMT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "
                        SELECT 
                            p.*,
                            j.nama jabatan 
                        FROM 
                            pegawai p
                        INNER JOIN 
                            jabatan j 
                        ON 
                            j.id=p.id_jabatan
                        ";

                    if (isset($_POST['dari_tmt']) && isset($_POST['sampai_tmt']))
                        $q .= " WHERE tmt >= '" . $_POST['dari_tmt'] . "' AND tmt <= '" . $_POST['sampai_tmt'] . "'";

                    $q .= " ORDER BY p.nama";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $row['nip'] ?></td>
                            <td style="vertical-align: middle;"><?= $row['nama'] ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['jabatan'] ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= indonesiaDate($row['tmt']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>