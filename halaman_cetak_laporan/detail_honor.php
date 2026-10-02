<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Detail Honor</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <?php $data = $mysqli->query("SELECT * FROM honor WHERE id=" . $_GET['id'])->fetch_assoc(); ?>
    <h4 class="text-center my-3">Laporan Detail Honor</h4>
    <section class="p-3">
        <span style="width: 250px; display: inline-block;">Tanggal</span>
        <span>: <?= indonesiaDate($data['tanggal']); ?></span>
        <br>
        <span style="width: 250px; display: inline-block;">Tujuan</span>
        <span>: <?= $data['tujuan']; ?></span>
        <br>
        <span style="width: 250px; display: inline-block;">Transportasi yang digunakan</span>
        <span>: <?= $data['transportasi']; ?></span>
        <br>
        <span style="width: 250px; display: inline-block;">Alasan Perjalanan</span>
        <span>: <?= $data['alasan']; ?></span>
    </section>
    <main class="p-3">
        <h5 class="text-center mb-5">Pegawai yang melakukan perjalanan</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle td-fit">No</th>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Pegawai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "
                    SELECT 
                        p.* 
                    FROM 
                        honor_pegawai hp 
                    INNER JOIN 
                        pegawai p 
                    ON 
                        p.id=hp.id_pegawai 
                    WHERE 
                        hp.id_honor=" . $data['id'];
                $no = 1;
                if ($result = $mysqli->query($q)) {
                } else echo "Error: " . $q . "<br>" . $mysqli->error;
                ?>
                <?php if ($result->num_rows) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no++; ?></td>
                            <td class="text-center align-middle"><?= $row['nip']; ?></td>
                            <td class="text-center"><?= $row['nama']; ?></td>
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