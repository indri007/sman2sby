<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1>Data Riwayat Presensi Siswa</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="?page=riwayat_presensi&method=tambah" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Tambah Manual</a>
        </div>
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center td-fit">No</th>
                        <th class="text-center">Tanggal & Waktu</th>
                        <th class="text-center">NIS</th>
                        <th class="text-center">Nama Siswa</th>
                        <th class="text-center">Jenis & Status</th>
                        <th class="text-center">Foto Selfie</th>
                        <th class="text-center">Lokasi & Jarak GPS</th>
                        <th class="text-center">Perangkat & IP</th>
                        <th class="text-center">Surat / Ket</th>
                        <th class="text-center td-fit">Aksi</th>
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
                            pp.jenis,
                            pp.foto_path,
                            pp.latitude,
                            pp.longitude,
                            pp.jarak_meter,
                            pp.surat_dokter,
                            pp.keterangan,
                            pp.ip_address,
                            pp.device_info  
                        FROM 
                            presensi_pegawai pp
                        INNER JOIN 
                            pegawai p 
                        ON 
                            p.id=pp.id_pegawai 
                        ORDER BY 
                            pp.id DESC";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;">
                                <strong><?= indonesiaDate($row['tanggal']) ?></strong><br>
                                <small class="text-muted"><i class="far fa-clock"></i> <?= $row['waktu'] ?></small>
                            </td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $row['nip'] ?></td>
                            <td style="vertical-align: middle;"><strong><?= $row['nama'] ?></strong></td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="badge badge-<?= $row['status'] == 'Hadir' ? 'success' : ($row['status'] == 'Izin' ? 'info' : 'warning'); ?>">
                                    <?= $row['status']; ?>
                                </span>
                                <?php if ($row['status'] == 'Hadir') : ?>
                                    <br><small class="badge badge-light border mt-1"><?= (!empty($row['jenis']) && $row['jenis'] != '-') ? $row['jenis'] : 'Masuk'; ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['foto_path']) && file_exists($row['foto_path'])) : ?>
                                    <a href="<?= $row['foto_path']; ?>" target="_blank" title="Klik untuk melihat foto selfie">
                                        <img src="<?= $row['foto_path']; ?>" width="55" height="55" class="rounded border shadow-sm" style="object-fit: cover;">
                                    </a>
                                <?php elseif (!empty($row['foto_path'])) : ?>
                                    <a href="<?= $row['foto_path']; ?>" target="_blank" class="btn btn-xs btn-outline-info">
                                        <i class="fas fa-image"></i> Foto
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['latitude']) && !empty($row['longitude'])) : ?>
                                    <a href="https://www.google.com/maps?q=<?= $row['latitude']; ?>,<?= $row['longitude']; ?>" target="_blank" class="btn btn-xs btn-outline-danger mb-1">
                                        <i class="fas fa-map-marker-alt"></i> Lihat Peta
                                    </a>
                                    <br>
                                    <small class="badge badge-<?= ($row['jarak_meter'] <= SMAN2_RADIUS_METER) ? 'light' : 'danger'; ?>">
                                        Jarak: <?= number_format($row['jarak_meter'], 1); ?> m
                                    </small>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['ip_address'])) : ?>
                                    <span class="badge badge-info mb-1"><i class="fas fa-network-wired mr-1"></i> <?= $row['ip_address'] ?></span><br>
                                    <small class="text-muted"><i class="fas fa-laptop mr-1"></i> <?= $row['device_info'] ?: '-' ?></small>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['surat_dokter']) && file_exists($row['surat_dokter'])) : ?>
                                    <a href="<?= $row['surat_dokter']; ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-download"></i> Surat Dokter
                                    </a>
                                <?php elseif (!empty($row['keterangan'])) : ?>
                                    <small><?= htmlspecialchars($row['keterangan']); ?></small>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center td-fit" style="vertical-align: middle;">
                                <a href="?page=riwayat_presensi&method=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                <form action="?page=riwayat_presensi&method=hapus&id=<?= $row['id'] ?>" method="POST" class="d-inline">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>