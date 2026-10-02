<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1>Data Honor</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="?page=honor&method=tambah" class="btn btn-primary float-right">Tambah</a>
        </div>
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center td-fit">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Tujuan</th>
                        <th class="text-center">Transportasi</th>
                        <th class="text-center td-fit">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "SELECT * FROM honor ORDER BY tanggal DESC, id DESC";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= indonesiaDate($row['tanggal']) ?></td>
                            <td style="vertical-align: middle;"><?= $row['tujuan'] ?></td>
                            <td style="vertical-align: middle;"><?= $row['transportasi'] ?></td>
                            <td class="text-center td-fit">
                                <a href="?page=honor&method=detail&id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Detail</a>
                                <a href="?page=honor&method=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">Edit</a>
                                <form action="?page=honor&method=hapus&id=<?= $row['id'] ?>" method="POST" class="d-inline">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>