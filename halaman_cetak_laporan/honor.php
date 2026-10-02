<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Honor</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Honor</h4>
    <section class="p-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter</span>
        </strong>
        <?php if (isset($_POST['dari_tanggal'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Dari Tanggal</span>
            <span>: <?= indonesiaDate($_POST['dari_tanggal']); ?></span>
        <?php endif; ?>
        <?php if (isset($_POST['sampai_tanggal'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Sampai Tanggal</span>
            <span>: <?= indonesiaDate($_POST['sampai_tanggal']); ?></span>
        <?php endif; ?>
    </section>
    <main class="p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle td-fit">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">Tujuan</th>
                    <th class="text-center align-middle">Transportasi</th>
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
                <?php if ($result->num_rows) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no++; ?></td>
                            <td class="text-center align-middle"><?= indonesiaDate($row['tanggal']); ?></td>
                            <td class="text-center align-middle"><?= $row['tujuan']; ?></td>
                            <td class="text-center align-middle"><?= $row['transportasi']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td class="text-center" colspan="3">Tidak Ada Data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    <?php include_once('footer.php'); ?>
</body>

</html>