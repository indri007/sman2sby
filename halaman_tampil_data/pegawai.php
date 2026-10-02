<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1>Data Siswa</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="?page=pegawai&method=tambah" class="btn btn-primary float-right">Tambah</a>
        </div>
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center td-fit">No</th>
                        <th class="text-center">NIS / NIP</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Password</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">IP & Perangkat</th>
                        <th class="text-center">Login Terakhir</th>
                        <th class="text-center td-fit">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "
                        SELECT 
                            p.*,
                            j.nama jabatan,
                            u.password,
                            u.ip_address,
                            u.device_info,
                            u.browser_info,
                            u.last_login
                        FROM 
                            pegawai p
                        INNER JOIN 
                            jabatan j 
                        ON 
                            j.id=p.id_jabatan 
                        LEFT JOIN 
                            user u 
                        ON 
                            p.id_user=u.id 
                        ORDER BY 
                            p.nama";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $row['nip'] ?></td>
                            <td style="vertical-align: middle;"><strong><?= $row['nama'] ?></strong></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['password'] ?? '-' ?></td>
                            <td class="text-center" style="vertical-align: middle;"><?= $row['jabatan'] ?></td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['ip_address'])) : ?>
                                    <span class="badge badge-info mb-1" title="IP Address">
                                        <i class="fas fa-network-wired mr-1"></i> <?= $row['ip_address'] ?>
                                    </span>
                                    <br>
                                    <small class="text-muted font-weight-bold" title="Perangkat & Browser">
                                        <i class="fas fa-laptop mr-1 text-secondary"></i> <?= $row['device_info'] ?: ($row['browser_info'] ?: 'Perangkat') ?>
                                    </small>
                                <?php else : ?>
                                    <span class="badge badge-light border text-muted">
                                        <i class="fas fa-info-circle mr-1"></i> Belum Login
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['last_login'])) : ?>
                                    <small class="text-dark font-weight-bold">
                                        <i class="far fa-clock mr-1 text-success"></i> <?= date('d/m/Y H:i', strtotime($row['last_login'])) ?>
                                    </small>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center td-fit" style="vertical-align: middle;">
                                <a href="?page=pegawai&method=detail&id=<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i> Detail</a>
                                <a href="?page=pegawai&method=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                <form action="?page=pegawai&method=hapus&id=<?= $row['id'] ?>" method="POST" class="d-inline">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>