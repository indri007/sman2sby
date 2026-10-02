<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Slip Gaji Pegawai</h1>
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
                    <form action="halaman_cetak_laporan/slip_gaji_pegawai.php" method="POST" target="_blank">
                        <?php if (isset($_POST['id_pegawai'])) : ?>
                            <input type="text" hidden name="id_pegawai" value="<?= $_POST['id_pegawai'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['bulan'])) : ?>
                            <input type="text" hidden name="bulan" value="<?= $_POST['bulan'] ?>">
                        <?php endif; ?>
                        <?php if (isset($_POST['tahun'])) : ?>
                            <input type="text" hidden name="tahun" value="<?= $_POST['tahun'] ?>">
                        <?php endif; ?>
                        <button class="btn btn-success">Cetak</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <?php $result = $mysqli->query("SELECT p.*, j.nama jabatan FROM pegawai p INNER JOIN jabatan j ON j.id=p.id_jabatan ORDER BY p.nama"); ?>
                                    <label for="id_pegawai">Pegawai</label>
                                    <select class="form-control select2bs4" name="id_pegawai" id="id_pegawai" required>
                                        <option value="" disabled selected>Pilih Pegawai</option>
                                        <?php while ($row = $result->fetch_assoc()) : ?>
                                            <?php if ($row['id'] == ($_POST['id_pegawai'] ?? '')) : ?>
                                                <option selected data-nip="<?= $row['nip']; ?>" data-jabatan="<?= $row['jabatan']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                                            <?php else : ?>
                                                <option data-nip="<?= $row['nip']; ?>" data-jabatan="<?= $row['jabatan']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" readonly class="form-control" name="nip" value="<?= $_POST['nip'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="text" readonly class="form-control" name="jabatan" value="<?= $_POST['jabatan'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="bulan">Bulan</label>
                                    <select class="form-control select2bs4" name="bulan" id="bulan" required>
                                        <option value="" disabled selected>Pilih Bulan</option>
                                        <?php foreach (MONTH_IN_INDONESIA as $index => $month) : ?>
                                            <option <?= (($_POST['bulan'] ?? '') == ($index + 1)) ? 'selected' : ''; ?> value="<?= $index + 1; ?>"><?= $month; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" class="form-control" name="tahun" id="tahun" min="1" value="<?= $_POST['tahun'] ?? Date('Y'); ?>" required>
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
    <?php if (isset($_POST['id_pegawai'])) : ?>
        <?php
        $q = "
            SELECT 
                COUNT(*) as kehadiran 
            FROM 
                presensi_pegawai 
            WHERE 
                MONTH(tanggal_waktu)='" . ($_POST['bulan'] ?? Date('m')) . "' 
                AND 
                YEAR(tanggal_waktu)='" . ($_POST['tahun'] ?? Date("Y")) . "' 
                AND 
                id_pegawai=" . $_POST['id_pegawai'] . "
            ";
        $kehadiran = $mysqli->query($q)->fetch_assoc()['kehadiran'];
        $ketidakhadiran = workingDays($_POST['tahun'], $_POST['bulan'], [0, 6]) - $kehadiran;
        ?>
        <?php $pegawai = $mysqli->query("SELECT p.*, j.nama jabatan, j.gaji_pokok FROM pegawai p INNER JOIN jabatan j ON j.id=p.id_jabatan WHERE p.id=" . $_POST['id_pegawai'])->fetch_assoc(); ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4">
                        <table class="table">
                            <tr>
                                <td class="align-middle td-fit">NIP</td>
                                <td class="pl-5"><?= $pegawai['nip']; ?></td>
                            </tr>
                            <tr>
                                <td class="align-middle td-fit">Nama</td>
                                <td class="pl-5"><?= $pegawai['nama']; ?></td>
                            </tr>
                            <tr>
                                <td class="align-middle td-fit">Jabatan</td>
                                <td class="pl-5"><?= $pegawai['jabatan']; ?></td>
                            </tr>
                            <tr>
                                <td class="align-middle td-fit">Bulan</td>
                                <td class="pl-5"><?= MONTH_IN_INDONESIA[$_POST['bulan'] - 1]; ?></td>
                            </tr>
                            <tr>
                                <td class="align-middle td-fit">Tahun</td>
                                <td class="pl-5"><?= $_POST['tahun']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center align-middle td-fit">No</th>
                            <th class="text-center align-middle">Keterangan</th>
                            <th class="text-center align-middle">Jumlah</th>
                        </tr>
                    </thead>
                    <?php
                    $q = "
                            SELECT 
                                t.* 
                            FROM 
                                tunjangan_pegawai tp 
                            INNER JOIN 
                                tunjangan t 
                            ON  
                                t.id=tp.id_tunjangan 
                            WHERE 
                                tp.id_pegawai=" . $_POST['id_pegawai'] . " 
                            ORDER BY 
                                t.nama 
                        ";
                    $tunjangan = $mysqli->query($q);

                    $total = 0;
                    $no = 1;
                    ?>
                    <tbody>
                        <tr>
                            <td class="text-center td-fit"><?= $no++; ?></td>
                            <td class="text-center">Gaji Pokok</td>
                            <td class="text-center">Rp <?= number_format($pegawai['gaji_pokok'], 0, ",", "."); ?></td>
                        </tr>
                        <?php $total += $pegawai['gaji_pokok']; ?>
                        <?php while ($row = $tunjangan->fetch_assoc()) : ?>
                            <tr>
                                <td class="text-center td-fit"><?= $no++; ?></td>
                                <td class="text-center">Tunjangan <?= $row['nama']; ?></td>
                                <td class="text-center">Rp <?= number_format($row['tunjangan'], 0, ",", "."); ?></td>
                            </tr>
                            <?php $total += $row['tunjangan']; ?>
                        <?php endwhile; ?>
                        <tr>
                            <td class="text-center td-fit"><?= $no++; ?></td>
                            <td class="text-center">Potongan / Alpa</td>
                            <td class="text-center">Rp <?= number_format((50000 * (int)$ketidakhadiran), 0, ",", "."); ?></td>
                        </tr>
                        <?php $total -= (50000 * (int)$ketidakhadiran); ?>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-center">Rp <?= number_format($total, 0, ",", "."); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>

<script>
    document.querySelector("select[name=id_pegawai]").addEventListener('change', function() {
        document.querySelector("input[name=nip]").value = this[this.selectedIndex].getAttribute('data-nip');
        document.querySelector("input[name=jabatan]").value = this[this.selectedIndex].getAttribute('data-jabatan');
    });
</script>