<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Presensi Bulanan</h1>
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
                    <form action="halaman_cetak_laporan/presensi_bulanan.php" method="POST" target="_blank">
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
                        <div class="form-group">
                            <label for="bulan">Bulan</label>
                            <select class="form-control select2bs4" name="bulan" id="bulan" required>
                                <?php foreach (MONTH_IN_INDONESIA as $index => $month) : ?>
                                    <option <?= ($_POST['bulan'] ?? Date('m')) == (($index + 1) < 10 ? ('0' . ($index + 1)) : ($index + 1)) ? 'selected' : ''; ?> value="<?= (($index + 1) < 10 ? ('0' . ($index + 1)) : ($index + 1)); ?>"><?= $month; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <input type="number" class="form-control" name="tahun" id="tahun" min="1" value="<?= $_POST['tahun'] ?? Date('Y'); ?>" required>
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
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="2">NIP</th>
                        <th class="text-center align-middle" rowspan="2">Nama</th>
                        <th class="text-center align-middle" colspan="<?= Date('t'); ?>">Kehadiran</th>
                    </tr>
                    <tr>
                        <?php for ($i = 1; $i <= Date('t', mktime(0, 0, 0, (($_POST['bulan'] ?? Date('m'))), 1, ($_POST['tahun'] ?? Date('Y')))); $i++) : ?>
                            <th class="text-center"><?= $i; ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <?php
                $q = "
                    SELECT 
                        * 
                    FROM 
                        pegawai 
                    ORDER BY 
                        nama";
                $result = $mysqli->query($q);
                $pegawai = [];
                while ($row = $result->fetch_assoc()) {
                    $pegawai[] = array_merge($row, ['presensi' => []]);
                }

                $q = "
                    SELECT 
                        id_pegawai, 
                        DAY(tanggal_waktu) tanggal,
                        status 
                    FROM 
                        presensi_pegawai 
                    WHERE 
                        MONTH(tanggal_waktu)='" . ($_POST['bulan'] ?? Date('m')) . "' 
                        AND 
                        YEAR(tanggal_waktu)='" . ($_POST['tahun'] ?? Date("Y")) . "' ORDER BY id_pegawai";
                $presensi_pegawai = $mysqli->query($q)->fetch_all(MYSQLI_ASSOC);
                foreach ($pegawai as $index => $value_pegawai) {

                    for ($i = 1; $i <= Date('t', mktime(0, 0, 0, (($_POST['bulan'] ?? Date('m'))), 1, ($_POST['tahun'] ?? Date('Y')))); $i++) {
                        if ((($_POST['tahun'] ?? Date('Y')) . '-' . ($_POST['bulan'] ?? Date('m')) . '-' . ($i < 10 ? ('0' . $i) : $i)) <= Date('Y-m-d')) {
                            $ada = false;
                            foreach ($presensi_pegawai as $value_presensi_pegawai) {
                                if ($value_pegawai['id'] == $value_presensi_pegawai['id_pegawai'] && $value_presensi_pegawai['tanggal'] == $i) {
                                    if ($value_presensi_pegawai['status'] == 'Hadir') {
                                        $pegawai[$index]['presensi'][] = 'H';
                                    } elseif ($value_presensi_pegawai['status'] == 'Izin') {
                                        $pegawai[$index]['presensi'][] = 'I';
                                    } elseif ($value_presensi_pegawai['status'] == 'Sakit') {
                                        $pegawai[$index]['presensi'][] = 'S';
                                    }
                                    $ada = !$ada;
                                    break;
                                }
                            }
                            if (!$ada) {
                                $pegawai[$index]['presensi'][] = '-';
                            }
                        } else {
                            $pegawai[$index]['presensi'][] = '';
                        }
                    }
                }
                ?>
                <tbody>
                    <?php foreach ($pegawai as $value) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $value['nip'] ?></td>
                            <td style="vertical-align: middle;"><?= $value['nama'] ?></td>
                            <?php foreach ($value['presensi'] as $presensi) : ?>
                                <td class="text-center"><?= $presensi; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mt-5">
                <table>
                    <tr>
                        <td colspan="2">Keterangan</td>
                    </tr>
                    <tr>
                        <td>H</td>
                        <td>: Hadir</td>
                    </tr>
                    <tr>
                        <td>S</td>
                        <td>: Sakit</td>
                    </tr>
                    <tr>
                        <td>I</td>
                        <td>: Izin</td>
                    </tr>
                    <tr>
                        <td>-</td>
                        <td>: Tidak Hadir/Alpa</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>