<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Riwayat Presensi</h1>
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
                    <form action="halaman_cetak_laporan/riwayat_presensi.php" method="POST" target="_blank">
                        <?php if (isset($_POST['dari_tanggal'])) : ?>
                            <input type="text" hidden name="dari_tanggal" value="<?= $_POST['dari_tanggal'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['sampai_tanggal'])) : ?>
                            <input type="text" hidden name="sampai_tanggal" value="<?= $_POST['sampai_tanggal'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['status'])) : ?>
                            <input type="text" hidden name="status" value="<?= $_POST['status'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['jenis'])) : ?>
                            <input type="text" hidden name="status" value="<?= $_POST['jenis'] ?>">
                        <?php endif; ?>
                        <button class="btn btn-success">Cetak</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="dari_tanggal">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="dari_tanggal" id="dari_tanggal" value="<?= $_POST['dari_tanggal'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="sampai_tanggal">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="sampai_tanggal" id="sampai_tanggal" value="<?= $_POST['sampai_tanggal'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="status">Status Kehadiran</label>
                                    <select class="form-control select2bs4" name="status" id="status">
                                        <option value="" disabled selected>Pilih Status Kehadiran</option>
                                        <option <?= ($_POST['status'] ?? '') == 'Hadir' ? 'selected' : ''; ?> value="Hadir">Hadir</option>
                                        <option <?= ($_POST['status'] ?? '') == 'Sakit' ? 'selected' : ''; ?> value="Sakit">Sakit</option>
                                        <option <?= ($_POST['status'] ?? '') == 'Izin' ? 'selected' : ''; ?> value="Izin">Izin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="jenis">Jenis Presensi</label>
                                    <select class="form-control select2bs4" name="jenis" id="jenis">
                                        <option value="" disabled selected>Pilih Jenis Presensi</option>
                                        <option <?= ($_POST['jenis'] ?? '') == 'Masuk' ? 'selected' : ''; ?> value="Masuk">Masuk</option>
                                        <option <?= ($_POST['jenis'] ?? '') == 'Pulang' ? 'selected' : ''; ?> value="Pulang">Pulang</option>
                                    </select>
                                </div>
                            </div>
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
                        <th class="text-center">Waktu</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jenis Presensi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "
                        SELECT 
                            pp.id,
                            DATE(pp.tanggal_waktu) tanggal,
                            DATE_FORMAT(pp.tanggal_waktu, '%H:%i') waktu,
                            p.nip,
                            p.nama,
                            pp.status,
                            pp.jenis  
                        FROM 
                            presensi_pegawai pp
                        INNER JOIN 
                            pegawai p 
                        ON 
                            p.id=pp.id_pegawai 
                        WHERE 
                            1=1";

                    if (!empty($_POST['dari_tanggal'] ?? '') && !empty($_POST['sampai_tanggal'] ?? ''))
                        $q .= " AND (DATE(pp.tanggal_waktu) >= '" . $_POST['dari_tanggal'] . "' AND DATE(pp.tanggal_waktu) <= '" . $_POST['sampai_tanggal'] . "')";

                    if (isset($_POST['status']))
                        $q .= " AND pp.status='" . $_POST['status'] . "'";

                    if (isset($_POST['jenis']))
                        $q .= " AND pp.jenis='" . $_POST['jenis'] . "'";

                    $q .= " ORDER BY pp.id DESC";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= indonesiaDate($row['tanggal']) ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $row['status'] == 'Hadir' ? $row['waktu'] : '-'; ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $row['nip'] ?></td>
                            <td style="vertical-align: middle;"><?= $row['nama'] ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['status'] == 'Hadir' ? $row['jenis'] : '-'; ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>