<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Slip Gaji Pegawai</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Slip Gaji Pegawai</h4>
    <section class="p-3">
        <?php $pegawai = $mysqli->query("SELECT p.*, j.nama jabatan, j.gaji_pokok FROM pegawai p INNER JOIN jabatan j ON j.id=p.id_jabatan WHERE p.id=" . $_POST['id_pegawai'])->fetch_assoc(); ?>
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
    </section>
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
    <main class="p-3">
        <table class="table table-bordered">
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
                        <td class="text-center"><?= $row['nama']; ?></td>
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
    </main>
    <?php include_once('footer.php'); ?>
</body>

</html>