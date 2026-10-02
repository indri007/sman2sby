<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Kenaikan Gaji</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Kenaikan Gaji</h4>
    <section class="p-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter</span>
        </strong>
        <?php if (isset($_POST['dari_tmt'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Dari TMT</span>
            <span>: <?= indonesiaDate($_POST['dari_tmt']); ?></span>
        <?php endif; ?>
        <?php if (isset($_POST['sampai_tmt'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Sampai TMT</span>
            <span>: <?= indonesiaDate($_POST['sampai_tmt']); ?></span>
        <?php endif; ?>
    </section>
    <main class="p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle td-fit">No</th>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                    <th class="text-center align-middle">TMT</th>
                    <th class="text-center align-middle">Lama Kerja</th>
                    <th class="text-center align-middle">Gaji Pokok</th>
                    <th class="text-center align-middle">Kenaikan Gaji (5% / Tahun)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "
                    SELECT 
                        p.*,
                        j.nama jabatan,
                        j.gaji_pokok 
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
                <?php if ($result->num_rows) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center align-middle td-fit"><?= $no++; ?></td>
                            <td class="text-center align-middle td-fit"><?= $row['nip'] ?></td>
                            <td class="align-middle"><?= $row['nama'] ?></td>
                            <td class="text-center align-middle"><?= indonesiaDate($row['tmt']) ?></td>
                            <td class="text-center align-middle">
                                <?php
                                $interval = getYearMonthDayFromCompareDateWithToday($row['tmt']);
                                ?>
                                <?php if ($interval['year']) : ?>
                                    <?= $interval['year'] . ' Tahun'; ?>
                                <?php elseif ($interval['month']) : ?>
                                    <?= $interval['month'] . ' Bulan'; ?>
                                <?php elseif ($interval['day']) : ?>
                                    <?= $interval['day'] . ' Hari'; ?>
                                <?php else : ?>
                                    <?= 'Baru Saja'; ?>
                                <?php endif; ?>

                            </td>
                            <td class="text-center align-middle"><?= number_format($row['gaji_pokok'], 0, ",", "."); ?></td>
                            <td class="text-center align-middle">
                                <?php if ($interval['year']) : ?>
                                    <?= number_format(($row['gaji_pokok'] * 0.05) * $interval['year'], 0, ",", "."); ?>
                                <?php else : ?>
                                    <?= '0'; ?>
                                <?php endif; ?>
                            </td>
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