<?php
if (isset($_POST['submit'])) {
    $nip = $mysqli->real_escape_string($_POST['nip']);
    $nama = $mysqli->real_escape_string($_POST['nama']);
    $nomor_telepon = $mysqli->real_escape_string($_POST['nomor_telepon']);
    $tanggal_lahir = $mysqli->real_escape_string($_POST['tanggal_lahir']);
    $tempat_lahir = $mysqli->real_escape_string($_POST['tempat_lahir']);
    $id_jabatan = $mysqli->real_escape_string($_POST['id_jabatan']);
    $tmt = $mysqli->real_escape_string($_POST['tmt']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $id_tunjangan = $_POST['id_tunjangan'] ?? [];

    try {
        $mysqli->begin_transaction();

        $target_dir = "uploads/";
        $gambar = $target_dir . Date("YmdHis") . '.' . strtolower(pathinfo(basename($_FILES["gambar"]["name"]), PATHINFO_EXTENSION));
        if (!is_dir($target_dir)) mkdir($target_dir, 0700, true);
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $gambar);

        $q = "
            INSERT INTO user (
                username,
                password,
                status  
            ) VALUES (
                '$nip',
                '$password',
                'PEGAWAI' 
            )
        ";
        $mysqli->query($q);


        $q = "
            INSERT INTO pegawai (
                id_jabatan,
                id_user,
                nip,
                nama,
                nomor_telepon,
                tanggal_lahir,
                tempat_lahir,
                tmt,
                gambar 
            ) VALUES (
                '$id_jabatan',
                '" . $mysqli->insert_id . "',
                '$nip',
                '$nama',
                '$nomor_telepon',
                '$tanggal_lahir',
                '$tempat_lahir',
                '$tmt',
                '$gambar'
            )
        ";
        $mysqli->query($q);

        $id_pegawai = $mysqli->insert_id;

        foreach ($id_tunjangan as $id) {
            $q = "
                INSERT INTO tunjangan_pegawai (
                    id_pegawai,
                    id_tunjangan 
                ) VALUES (
                    $id_pegawai,
                    $id
                )
            ";
            $mysqli->query($q);
        }


        $mysqli->commit();
        echo "<script>alert('Berhasil menambah data siswa');</script>";
        echo "<script>window.location.href = '?page=" . $_GET['page'] . "';</script>";
    } catch (\Throwable $e) {
        $mysqli->rollback();
        throw $e;
    };
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Form Pegawai</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Form Pegawai</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<form action="" method="POST" enctype="multipart/form-data">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Identitas Pegawai</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" name="nip" id="nip" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="nomor_telepon">Nomor Telepon</label>
                                <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
                                        <label class="custom-file-label" for="gambar">Pilih Gambar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Jabatan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="id_jabatan">Jabatan</label>
                                <?php
                                if (!$result = $mysqli->query("SELECT * FROM jabatan"))
                                    echo "Error: " . $q . "<br>" . $mysqli->error;
                                ?>
                                <select class="form-control select2bs4" name="id_jabatan" id="id_jabatan" required>
                                    <option value="" disabled selected>Pilih Jabatan</option>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <option data-golongan="<?= $row['golongan']; ?>" data-gaji_pokok="<?= $row['gaji_pokok']; ?>" value="<?= $row['id']; ?>"><?= $row['nama']; ?> - <?= $row['golongan']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Golongan</label>
                                <input type="text" class="form-control" name="golongan" disabled>
                            </div>
                            <div class="form-group">
                                <label>Gaji Pokok</label>
                                <input type="text" class="form-control" name="gaji_pokok" disabled>
                            </div>
                            <div class="form-group">
                                <label for="tmt">TMT</label>
                                <input type="date" class="form-control" name="tmt" id="tmt" value="<?= Date("Y-m-d"); ?>">
                            </div>
                            <div class="form-group">
                                <label>Tunjangan yang diterima</label>
                                <?php $result = $mysqli->query("SELECT * FROM tunjangan"); ?>
                                <div class="d-flex">
                                    <?php if ($result->num_rows) : ?>
                                        <?php while ($row = $result->fetch_assoc()) : ?>
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" id="tunjangan-<?= $row['id']; ?>" value="<?= $row['id']; ?>" name="id_tunjangan[]">
                                                <label class="form-check-label" for="tunjangan-<?= $row['id']; ?>"><?= $row['nama']; ?></label>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        Data Tunjangan Belum Ditambahkan!
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Preview Gambar</h3>
                        </div>
                        <div class="card-body d-flex justify-content-center" style="height: 470.5px; padding: 20px;">
                            <img id="preview" class="w-50" style="object-fit: cover;">
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Akun Pegawai</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" disabled>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="?page=<?= $_GET['page']; ?>" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right" name="submit">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<script>
    function previewImage(input) {
        input.nextElementSibling.innerHTML = input.files[0].name;

        const oFReader = new FileReader();
        oFReader.readAsDataURL(input.files[0]);

        oFReader.onload = function(oFREvent) {
            document.querySelector('#preview').src = oFREvent.target.result;
        }
    }

    document.querySelector("input[name=nip]").addEventListener('input', function() {
        document.querySelector("input[name=username]").value = this.value;
    });

    document.querySelector("select[name=id_jabatan]").addEventListener('change', function() {
        document.querySelector("input[name=golongan]").value = this[this.selectedIndex].getAttribute('data-golongan');
        document.querySelector("input[name=gaji_pokok]").value = formatNumberWithDot.format(this[this.selectedIndex].getAttribute('data-gaji_pokok'));
    });
</script>