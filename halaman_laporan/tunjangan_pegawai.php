<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Tunjangan Pegawai</h1>
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
                    <form action="halaman_cetak_laporan/tunjangan_pegawai.php" method="POST" target="_blank">
                        <?php if (count($_POST['id_tunjangan'] ?? [])) : ?>
                            <?php $tunjangan = $mysqli->query("SELECT * FROM tunjangan")->fetch_all(MYSQLI_ASSOC); ?>
                            <?php foreach ($_POST['id_tunjangan'] as $id_tunjangan) : ?>
                                <input type="text" hidden name="id_tunjangan[]" value="<?= $id_tunjangan; ?>">
                                <?php foreach ($tunjangan as $value) : ?>
                                    <?php if ($id_tunjangan == $value['id']) : ?>
                                        <input type="text" hidden name="tunjangan[]" value="<?= $value['nama']; ?>">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <button class="btn btn-success">Cetak</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Tunjangan yang diterima</label>
                            <?php $result = $mysqli->query("SELECT * FROM tunjangan"); ?>
                            <div class="d-flex">
                                <?php if ($result->num_rows) : ?>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <?php if (in_array($row['id'], ($_POST['id_tunjangan'] ?? []))) : ?>
                                            <div class="form-check mr-3">
                                                <input checked class="form-check-input" type="checkbox" id="tunjangan-<?= $row['id']; ?>" value="<?= $row['id']; ?>" name="id_tunjangan[]">
                                                <label class="form-check-label" for="tunjangan-<?= $row['id']; ?>"><?= $row['nama']; ?></label>
                                            </div>
                                        <?php else : ?>
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" id="tunjangan-<?= $row['id']; ?>" value="<?= $row['id']; ?>" name="id_tunjangan[]">
                                                <label class="form-check-label" for="tunjangan-<?= $row['id']; ?>"><?= $row['nama']; ?></label>
                                            </div>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    Data Tunjangan Belum Ditambahkan!
                                <?php endif; ?>
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
    <div class="card">
        <div class="card-body">
            <table id="example3" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center td-fit">No</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Tunjangan yang diterima</th>
                        <th class="text-center">Nominal Tunjangan</th>
                        <th class="text-center">Total</th>
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
                </tbody>
            </table>
        </div>
    </div>
</section>