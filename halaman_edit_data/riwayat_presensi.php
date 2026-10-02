<?php

if (isset($_GET['id'])) {
    $q = "
        SELECT 
            pp.*,
            DATE(pp.tanggal_waktu) tanggal,
            DATE_FORMAT(pp.tanggal_waktu, '%H:%i') waktu,
            p.nip,
            p.nama,
            j.nama jabatan 
        FROM 
            presensi_pegawai pp 
        INNER JOIN 
            pegawai p 
        ON 
            p.id=pp.id_pegawai 
        INNER JOIN 
            jabatan j 
        ON 
            j.id=p.id_jabatan 
        WHERE 
            pp.id=" . $_GET['id'] . "
    ";
    $result = $mysqli->query($q);
    $data = $result->fetch_assoc();
} else {
    echo "<script>alert('id tidak ditemukan');</script>";
    echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
}

if (isset($_POST['submit'])) {
    $id_pegawai = $mysqli->real_escape_string($_POST['id_pegawai']);
    $status = $mysqli->real_escape_string($_POST['status']);
    $jenis = ($status == 'Hadir') ? (!empty($_POST['jenis']) ? $mysqli->real_escape_string($_POST['jenis']) : 'Masuk') : '-';
    $tanggal = !empty($_POST['tanggal']) ? $mysqli->real_escape_string($_POST['tanggal']) : date('Y-m-d');
    $waktu = !empty($_POST['waktu']) ? $mysqli->real_escape_string($_POST['waktu']) : '00:00:00';
    $keterangan = $mysqli->real_escape_string($_POST['keterangan'] ?? '');

    $surat_dokter_path = $data['surat_dokter'];
    if (isset($_FILES['surat_dokter']) && $_FILES['surat_dokter']['error'] == UPLOAD_ERR_OK) {
        $dir = "uploads/presensi/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['surat_dokter']['name'], PATHINFO_EXTENSION));
        $filename = "surat_" . date("YmdHis") . "_" . $id_pegawai . "." . $ext;
        $surat_dokter_path = $dir . $filename;
        move_uploaded_file($_FILES['surat_dokter']['tmp_name'], $surat_dokter_path);
    }

    $foto_path = $data['foto_path'];
    if (isset($_FILES['foto_path']) && $_FILES['foto_path']['error'] == UPLOAD_ERR_OK) {
        $dir = "uploads/presensi/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['foto_path']['name'], PATHINFO_EXTENSION));
        $filename = "foto_" . date("YmdHis") . "_" . $id_pegawai . "." . $ext;
        $foto_path = $dir . $filename;
        move_uploaded_file($_FILES['foto_path']['tmp_name'], $foto_path);
    }

    $tanggal_waktu = "$tanggal $waktu";

    $q = "
        UPDATE presensi_pegawai SET  
            id_pegawai='$id_pegawai',
            tanggal_waktu='$tanggal_waktu',
            status='$status',
            jenis='$jenis',
            foto_path='$foto_path',
            surat_dokter='$surat_dokter_path',
            keterangan='$keterangan'
        WHERE 
            id=" . $_GET['id'] . "
    ";

    if ($mysqli->query($q)) {
        echo "<script>alert('Berhasil memperbaharui data presensi');</script>";
        echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
    } else echo "Error: " . $q . "<br>" . $mysqli->error;
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row justify-content-center mb-2">
            <div class="col-sm-6">
                <h1 class="text-center">Form Edit Presensi</h1>
            </div>
        </div>
    </div>
</section>
<form action="" method="POST" enctype="multipart/form-data">
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="id_pegawai">Nama Siswa / Pegawai</label>
                                <?php
                                $q = "
                                    SELECT 
                                        p.id,
                                        p.nip,
                                        p.nama,
                                        j.nama jabatan 
                                    FROM 
                                        pegawai p
                                    INNER JOIN 
                                        jabatan j 
                                    ON 
                                        j.id=p.id_jabatan
                                ";
                                if (!$result = $mysqli->query($q))
                                    echo "Error: " . $q . "<br>" . $mysqli->error;
                                ?>
                                <select class="form-control select2bs4" name="id_pegawai" id="id_pegawai" required>
                                    <option value="" disabled selected>Pilih Siswa / Pegawai</option>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <?php if ($row['id'] == $data['id_pegawai']) : ?>
                                            <option selected data-jabatan="<?= $row['jabatan']; ?>" data-nip="<?= $row['nip']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                                        <?php else : ?>
                                            <option data-jabatan="<?= $row['jabatan']; ?>" data-nip="<?= $row['nip']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>NIS / NIP</label>
                                <input type="text" class="form-control" name="nip" disabled value="<?= $data['nip']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" disabled value="<?= $data['jabatan']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="status">Status Kehadiran</label>
                                <select class="form-control select2bs4" name="status" id="status" required>
                                    <option value="" disabled selected>Pilih Status Kehadiran</option>
                                    <option <?= $data['status'] == 'Hadir' ? 'selected' : ''; ?> value="Hadir">Hadir</option>
                                    <option <?= $data['status'] == 'Sakit' ? 'selected' : ''; ?> value="Sakit">Sakit</option>
                                    <option <?= $data['status'] == 'Izin' ? 'selected' : ''; ?> value="Izin">Izin</option>
                                </select>
                            </div>
                            <div class="form-group" id="groupJenis">
                                <label for="jenis">Jenis Presensi</label>
                                <select class="form-control select2bs4" name="jenis" id="jenis" required <?= $data['status'] != 'Hadir' ? 'disabled' : ''; ?>>
                                    <option value="" disabled>Pilih Jenis Presensi</option>
                                    <option <?= ($data['jenis'] == 'Masuk' || empty($data['jenis'])) ? 'selected' : ''; ?> value="Masuk">Masuk</option>
                                    <option <?= $data['jenis'] == 'Pulang' ? 'selected' : ''; ?> value="Pulang">Pulang</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= $data['tanggal']; ?>" required>
                            </div>
                            <div class="form-group" id="groupWaktu">
                                <label for="waktu">Waktu</label>
                                <input type="time" class="form-control" name="waktu" id="waktu" value="<?= $data['waktu']; ?>" required <?= $data['status'] != 'Hadir' ? 'disabled' : ''; ?>>
                            </div>

                            <!-- Upload / Preview Foto Selfie untuk Status Hadir -->
                            <div class="form-group" id="groupFoto" style="display: <?= ($data['status'] == 'Hadir') ? 'block' : 'none'; ?>;">
                                <label for="foto_path">Foto Selfie Presensi</label>
                                <?php if (!empty($data['foto_path']) && file_exists($data['foto_path'])) : ?>
                                    <div class="mb-2">
                                        <a href="<?= $data['foto_path']; ?>" target="_blank">
                                            <img src="<?= $data['foto_path']; ?>" width="90" height="90" class="rounded border shadow-sm" style="object-fit: cover;">
                                        </a>
                                        <small class="d-block text-muted mt-1">Klik foto di atas untuk memperbesar</small>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control-file" name="foto_path" id="foto_path" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file foto.</small>
                            </div>

                            <!-- Upload Surat Dokter & Keterangan untuk Izin / Sakit -->
                            <div id="groupSurat" style="display: <?= ($data['status'] == 'Izin' || $data['status'] == 'Sakit') ? 'block' : 'none'; ?>;">
                                <div class="form-group">
                                    <label for="surat_dokter">Upload Surat Dokter / Bukti Surat Izin</label>
                                    <?php if (!empty($data['surat_dokter']) && file_exists($data['surat_dokter'])) : ?>
                                        <div class="mb-2">
                                            <a href="<?= $data['surat_dokter']; ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-file"></i> Lihat File Surat Dokter Saat Ini
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control-file" name="surat_dokter" id="surat_dokter" accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file surat.</small>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan / Alasan</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Keterangan izin atau sakit..."><?= htmlspecialchars($data['keterangan'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<script>
    document.querySelector("select[name=id_pegawai]").addEventListener('change', function() {
        document.querySelector("input[name=nip]").value = this[this.selectedIndex].getAttribute('data-nip');
        document.querySelector("input[name=jabatan]").value = this[this.selectedIndex].getAttribute('data-jabatan');
    });
    document.querySelector("select[name=status]").addEventListener('change', function() {
        if (this[this.selectedIndex].value == 'Hadir') {
            document.querySelector("select[name=jenis]").removeAttribute('disabled');
            document.querySelector('input[name=waktu]').removeAttribute('disabled');
            document.getElementById('groupSurat').style.display = 'none';
            if (document.getElementById('groupFoto')) document.getElementById('groupFoto').style.display = 'block';
        } else {
            document.querySelector("select[name=jenis]").setAttribute('disabled', '');
            document.querySelector('input[name=waktu]').setAttribute('disabled', '');
            document.getElementById('groupSurat').style.display = 'block';
            if (document.getElementById('groupFoto')) document.getElementById('groupFoto').style.display = 'none';
        }
    });
</script>