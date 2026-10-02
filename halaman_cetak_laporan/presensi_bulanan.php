<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Presensi Bulanan</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Presensi Bulanan</h4>
    <section class="p-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter</span>
        </strong>
        <?php if (isset($_POST['bulan'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Bulan</span>
            <span>: <?= MONTH_IN_INDONESIA[$_POST['bulan'] - 1]; ?></span>
        <?php endif; ?>
        <?php if (isset($_POST['tahun'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Tahun</span>
            <span>: <?= $_POST['tahun']; ?></span>
        <?php endif; ?>
    </section>
    <main class="p-3">
        <table class="table table-bordered">
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
    </main>
    <?php include_once('footer.php'); ?>
</body>

</html>