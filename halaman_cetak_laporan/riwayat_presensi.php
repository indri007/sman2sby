<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Riwayat Presensi</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Riwayat Presensi</h4>
    <section class="p-3">
        <strong>
            <span style="width: 150px; display: inline-block;">Filter</span>
        </strong>
        <?php if (isset($_POST['dari_tanggal'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Dari Tanggal</span>
            <span>: <?= indonesiaDate($_POST['dari_tanggal']); ?></span>
        <?php endif; ?>
        <?php if (isset($_POST['dari_tanggal'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Sampai Tanggal</span>
            <span>: <?= indonesiaDate($_POST['sampai_tanggal']); ?></span>
        <?php endif; ?>
        <?php if (isset($_POST['status'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Status Kehadiran</span>
            <span>: <?= $_POST['status']; ?></span>
        <?php endif; ?>
        <?php if (isset($_POST['jenis'])) : ?>
            <br>
            <span style="width: 150px; display: inline-block;">Jenis Presensi</span>
            <span>: <?= $_POST['jenis']; ?></span>
        <?php endif; ?>
    </section>
    <main class="p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle td-fit">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">Waktu</th>
                    <th class="text-center align-middle">NIP</th>
                    <th class="text-center align-middle">Nama</th>
                    <th class="text-center align-middle">Jenis Presensi</th>
                    <th class="text-center align-middle">Status</th>
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
                <?php if ($result->num_rows) : ?>
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