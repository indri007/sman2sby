<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Gaji Pegawai</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Gaji Pegawai</h4>
    <section class="p-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter</span>
        </strong>
        <?php if (isset($_POST['minimal_total_gaji']) && isset($_POST['maksimal_total_gaji'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Range Gaji</span>
            <span>:
                Rp <?= number_format($_POST['minimal_total_gaji'], 0, ",", "."); ?>
                -
                Rp <?= number_format($_POST['maksimal_total_gaji'], 0, ",", "."); ?>
            </span>
        <?php endif; ?>
    </section>
    <main class="p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle td-fit">No</th>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                    <th class="text-center align-middle">Jabatan</th>
                    <th class="text-center align-middle">Gaji Pokok</th>
                    <th class="text-center align-middle">Tunjangan</th>
                    <th class="text-center align-middle">Total</th>
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
                <?php if ($result->num_rows) : ?>
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
                <?php else : ?>
                    <tr>
                        <td class="text-center" colspan="7">Tidak Ada Data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    <?php include_once('footer.php'); ?>
</body>

</html>