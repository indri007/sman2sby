<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Tunjangan Pegawai</title>
    <link href="bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <h4 class="text-center my-3">Laporan Tunjangan Pegawai</h4>
    <section class="p-3">
        <strong>
            <span style="width: 178px; display: inline-block;">Filter</span>
        </strong>
        <?php if (isset($_POST['id_tunjangan'])) : ?>
            <br>
            <span style="width: 178px; display: inline-block;">Tunjangan yang diterima</span>
            <span>:
                <?php foreach ($_POST['tunjangan'] as $key => $value) : ?>
                    <?php if ($key) : ?>
                        <?= ", " . $value; ?>
                    <?php else : ?>
                        <?= $value; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
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
                    <th class="text-center align-middle">Tunjangan yang diterima</th>
                    <th class="text-center align-middle">Nominal Tunjangan</th>
                    <th class="text-center align-middle">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "
                        SELECT DISTINCT
                            p.*,
                            j.nama jabatan 
                        FROM 
                            pegawai p
                        INNER JOIN 
                            jabatan j 
                        ON 
                            j.id=p.id_jabatan
                        ";

                if (count($_POST['id_tunjangan'] ?? [])) {
                    $q .= " INNER JOIN tunjangan_pegawai tp ON tp.id_pegawai=p.id";
                    foreach ($_POST['id_tunjangan'] as $key => $value) {
                        if ($key)
                            $q .= " AND tp.id_tunjangan=$value";
                        else
                            $q .= " WHERE tp.id_tunjangan=$value";
                    }
                }

                $q .= " ORDER BY p.nama";
                $result = $mysqli->query($q);
                $pegawai = [];
                while ($row = $result->fetch_assoc()) {
                    $pegawai[] = array_merge($row, ['tunjangan' => [], 'total_tunjangan' => 0]);
                }
                $tunjangan = $mysqli->query("SELECT * FROM tunjangan")->fetch_all(MYSQLI_ASSOC);
                $q = "SELECT * FROM tunjangan_pegawai";

                if (count($_POST['id_tunjangan'] ?? [])) {
                    foreach ($_POST['id_tunjangan'] as $key => $value) {
                        if ($key)
                            $q .= " AND id_tunjangan=$value";
                        else
                            $q .= " WHERE id_tunjangan=$value";
                    }
                }
                $q .= " ORDER BY id_pegawai";
                $tunjangan_pegawai = $mysqli->query($q)->fetch_all(MYSQLI_ASSOC);
                foreach ($pegawai as $key_pegawai => $value_pegawai) {
                    foreach ($tunjangan_pegawai as $key_tunjangan_pegawai => $value_tunjangan_pegawai) {
                        if ($value_tunjangan_pegawai['id_pegawai'] == $value_pegawai['id']) {
                            foreach ($tunjangan as $key_tunjangan => $value_tunjangan) {
                                if ($value_tunjangan['id'] == $value_tunjangan_pegawai['id_tunjangan']) {
                                    $pegawai[$key_pegawai]['tunjangan'][] = $value_tunjangan;
                                    $pegawai[$key_pegawai]['total_tunjangan'] += $value_tunjangan['tunjangan'];
                                    break;
                                }
                            }
                        }
                    }
                }
                ?>
                <?php if (count($pegawai)) : ?>
                    <?php foreach ($pegawai as $index => $row) : ?>
                        <tr>
                            <td <?= count($row['tunjangan']) > 0 ? 'rowspan="' . count($row['tunjangan']) . '"' : ''; ?> class="text-center td-fit" style="vertical-align: middle;"><?= $index + 1; ?></td>
                            <td <?= count($row['tunjangan']) > 0 ? 'rowspan="' . count($row['tunjangan']) . '"' : ''; ?> class="text-center td-fit" style="vertical-align: middle;"><?= $row['nip'] ?></td>
                            <td <?= count($row['tunjangan']) > 0 ? 'rowspan="' . count($row['tunjangan']) . '"' : ''; ?> style="vertical-align: middle;"><?= $row['nama'] ?></td>
                            <td <?= count($row['tunjangan']) > 0 ? 'rowspan="' . count($row['tunjangan']) . '"' : ''; ?> class="text-center" style="vertical-align: middle;"><?= $row['jabatan'] ?></td>
                            <td class="text-center"><?= $row['tunjangan'][0]['nama'] ?? '-'; ?></td>
                            <td class="text-center"><?= isset($row['tunjangan'][0]['tunjangan']) ? ('Rp ' . number_format($row['tunjangan'][0]['tunjangan'], 0, ",", ".")) : '-'; ?></td>
                            <td <?= count($row['tunjangan']) > 0 ? 'rowspan="' . count($row['tunjangan']) . '"' : ''; ?> class="text-center" style="vertical-align: middle;">Rp <?= number_format($row['total_tunjangan'], 0, ",", "."); ?></td>
                        </tr>
                        <?php foreach ($row['tunjangan'] as $i => $value) : ?>
                            <?php if ($i) : ?>
                                <tr>
                                    <td class="text-center"><?= $value['nama']; ?></td>
                                    <td class="text-center">Rp <?= number_format($value['tunjangan'], 0, ",", "."); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
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