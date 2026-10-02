<?php
if (isset($_POST['submit'])) {
    $id_pegawai = $mysqli->real_escape_string($_POST['id_pegawai']);
    $status = $mysqli->real_escape_string($_POST['status']);
    $jenis = $mysqli->real_escape_string($_POST['jenis'] ?? '-');
    $tanggal = $mysqli->real_escape_string($_POST['tanggal'] ?? date('Y-m-d'));
    $waktu = $mysqli->real_escape_string($_POST['waktu'] ?? date('H:i:s'));
    $keterangan = $mysqli->real_escape_string($_POST['keterangan'] ?? '');
    $clientIP = getClientIP();
    $deviceSummary = 'Manual Input (Admin)';

    $surat_dokter_path = '';
    if (isset($_FILES['surat_dokter']) && $_FILES['surat_dokter']['error'] == UPLOAD_ERR_OK) {
        $dir = "uploads/presensi/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['surat_dokter']['name'], PATHINFO_EXTENSION));
        $filename = "surat_" . date("YmdHis") . "_" . $id_pegawai . "." . $ext;
        $surat_dokter_path = $dir . $filename;
        move_uploaded_file($_FILES['surat_dokter']['tmp_name'], $surat_dokter_path);
    }

    $foto_path = '';
    if (isset($_FILES['foto_path']) && $_FILES['foto_path']['error'] == UPLOAD_ERR_OK) {
        $dir = "uploads/presensi/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        
        $ext = strtolower(pathinfo($_FILES['foto_path']['name'], PATHINFO_EXTENSION));
        $filename = "foto_" . date("YmdHis") . "_" . $id_pegawai . "." . $ext;
        $foto_path = $dir . $filename;
        move_uploaded_file($_FILES['foto_path']['tmp_name'], $foto_path);
    }

    $q = "
        INSERT INTO presensi_pegawai ( 
            id_pegawai,
            tanggal_waktu,
            status,
            jenis,
            foto_path,
            surat_dokter,
            keterangan,
            ip_address,
            device_info
        ) VALUES (
            '$id_pegawai',
            '" . ("$tanggal $waktu") . "',
            '$status',
            '$jenis',
            '$foto_path',
            '$surat_dokter_path',
            '$keterangan',
            '$clientIP',
            '$deviceSummary'
        )
    ";

    if ($mysqli->query($q)) {
        // Ambil info pegawai untuk Telegram
        $pegRes = $mysqli->query("SELECT nama, nip FROM pegawai WHERE id = '$id_pegawai' LIMIT 1");
        if ($pegRes && $pegRes->num_rows > 0) {
            $pegInfo = $pegRes->fetch_assoc();
            sendTelegramAttendanceNotification($mysqli, [
                'nama' => $pegInfo['nama'],
                'nip' => $pegInfo['nip'],
                'status' => $status,
                'jenis' => $jenis,
                'waktu' => date('d-m-Y H:i:s', strtotime("$tanggal $waktu")),
                'ip_address' => $clientIP,
                'device_info' => $deviceSummary,
                'keterangan' => $keterangan ?: 'Input Manual Admin'
            ]);
        }
        echo "<script>alert('Berhasil menambah data presensi');</script>";
        echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
    } else echo "Error: " . $q . "<br>" . $mysqli->error;
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row justify-content-center mb-2">
            <div class="col-sm-6">
                <h1 class="text-center">Form Tambah Presensi</h1>
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
                                        <option data-jabatan="<?= $row['jabatan']; ?>" data-nip="<?= $row['nip']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>NIS / NIP</label>
                                <input type="text" class="form-control" name="nip" disabled>
                            </div>
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" disabled>
                            </div>
                            <div class="form-group">
                                <label for="status">Status Kehadiran</label>
                                <select class="form-control select2bs4" name="status" id="status" required>
                                    <option value="" disabled selected>Pilih Status Kehadiran</option>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                </select>
                            </div>
                            <div class="form-group" id="groupJenis">
                                <label for="jenis">Jenis Presensi</label>
                                <select class="form-control select2bs4" name="jenis" id="jenis" required disabled>
                                    <option value="" disabled selected>Pilih Jenis Presensi</option>
                                    <option value="Masuk">Masuk</option>
                                    <option value="Pulang">Pulang</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= Date("Y-m-d"); ?>" required>
                            </div>
                            <div class="form-group" id="groupWaktu">
                                <label for="waktu">Waktu</label>
                                <input type="time" class="form-control" name="waktu" id="waktu" value="<?= Date("H:i"); ?>" required disabled>
                            </div>

                            <!-- Upload Foto Selfie untuk Status Hadir -->
                            <div class="form-group" id="groupFoto" style="display: none;">
                                <label for="foto_path">Upload Foto Selfie</label>
                                <input type="file" class="form-control-file" name="foto_path" id="foto_path" accept="image/*">
                            </div>

                            <!-- Upload Surat Dokter & Keterangan untuk Izin / Sakit -->
                            <div id="groupSurat" style="display: none;">
                                <div class="form-group">
                                    <label for="surat_dokter">Upload Surat Dokter / Bukti Surat Izin</label>
                                    <input type="file" class="form-control-file" name="surat_dokter" id="surat_dokter" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan / Alasan</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Keterangan izin atau sakit..."></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Simpan Data</button>
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