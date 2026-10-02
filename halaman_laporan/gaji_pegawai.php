<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Gaji Pegawai</h1>
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
                    <form action="halaman_cetak_laporan/gaji_pegawai.php" method="POST" target="_blank">
                        <?php if (isset($_POST['minimal_total_gaji'])) : ?>
                            <input type="text" hidden name="minimal_total_gaji" value="<?= $_POST['minimal_total_gaji'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['maksimal_total_gaji'])) : ?>
                            <input type="text" hidden name="maksimal_total_gaji" value="<?= $_POST['maksimal_total_gaji'] ?>">
                        <?php endif; ?>
                        <button class="btn btn-success">Cetak</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="minimal_total_gaji">Minimal Total Gaji</label>
                            <input type="text" class="form-control" name="minimal_total_gaji" id="minimal_total_gaji"    required value="<?= $_POST['minimal_total_gaji'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="maksimal_total_gaji">Maksimal Total Gaji</label>
                            <input type="text" class="form-control" name="maksimal_total_gaji" id="maksimal_total_gaji" required value="<?= $_POST['maksimal_total_gaji'] ?? ''; ?>">
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
                        <th class="text-center">Gaji Pokok</th>
                        <th class="text-center">Tunjangan</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "
                        SELECT 
                            p.*,
                            j.nama jabatan,
                            j.gaji_pokok,
                            (SELECT IFNULL(SUM(t.tunjangan), 0) FROM tunjangan_pegawai tp INNER JOIN tunjangan t ON t.id=tp.id_tunjangan WHERE id_pegawai=p.id) tunjangan 
                        FROM 
                            pegawai p
                        INNER JOIN 
                            jabatan j 
                        ON 
                            j.id=p.id_jabatan
                        ";

                    if (isset($_POST['minimal_total_gaji']) && isset($_POST['maksimal_total_gaji']))
                        $q .= " 
                            WHERE 
                                (j.gaji_pokok+(SELECT IFNULL(SUM(t.tunjangan), 0) FROM tunjangan_pegawai tp INNER JOIN tunjangan t ON t.id=tp.id_tunjangan WHERE id_pegawai=p.id)) BETWEEN '" . $_POST['minimal_total_gaji'] . "' AND '" . $_POST['maksimal_total_gaji'] . "'";

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
                            <td class="text-center" style="vertical-align: middle;">Rp <?= number_format($row['gaji_pokok'], 0, ",", "."); ?></td>
                            <td class="text-center" style="vertical-align: middle;">Rp <?= number_format($row['tunjangan'], 0, ",", "."); ?></td>
                            <td class="text-center" style="vertical-align: middle;">Rp <?= number_format($row['gaji_pokok'] + $row['tunjangan'], 0, ",", "."); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>