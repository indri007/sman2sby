<?php

$q = "
    SELECT 
        p.*, 
        j.golongan, 
        j.gaji_pokok, 
        u.password,
        u.ip_address,
        u.device_info,
        u.browser_info,
        u.last_login,
        j.nama jabatan
    FROM 
        pegawai p
    INNER JOIN 
        jabatan j  
    ON 
        p.id_jabatan=j.id 
    INNER JOIN 
        user u  
    ON 
        p.id_user=u.id 
    WHERE 
        p.id=" . ($_GET['id'] ?? $_SESSION['user']['id_pegawai']);
$result = $mysqli->query($q);
$data = $result->fetch_assoc();
$gambar = $data['gambar'];

$q = "
    SELECT 
        t.*  
    FROM 
        tunjangan_pegawai tp 
    INNER JOIN 
        tunjangan t 
    ON 
        t.id=tp.id_tunjangan 
    WHERE 
        tp.id_pegawai=" . ($_GET['id'] ?? $_SESSION['user']['id_pegawai']) . "
    ";
$tunjangan_pegawai = $mysqli->query($q);
$data['tunjangan'] = [];
while ($row = $tunjangan_pegawai->fetch_assoc()) {
    $data['tunjangan'][] = $row['nama'];
}
$gambar = $data['gambar'];
?>
<section class="content mt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Preview Gambar</h3>
                    </div>
                    <div class="card-body d-flex justify-content-center" style="height: 400px; padding: 20px;">
                        <img id="preview" src="<?= $gambar; ?>" class="w-100" style="object-fit: cover;">
                    </div>
                </div>

                <style>
                    .fade-scale {
                        transform: scale(0);
                        opacity: 0;
                        -webkit-transition: all .25s linear;
                        -o-transition: all .25s linear;
                        transition: all .25s linear;
                    }

                    .fade-scale.in {
                        opacity: 1;
                        transform: scale(1);
                    }

                    #qrcode canvas {
                        height: 100% !important;
                    }
                </style>
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title flex-grow-1">QR CODE</h3>
                        <button id="cetak" class="btn btn-sm btn-dark m-0">Cetak</a>
                    </div>
                    <div class="card-body">
                        <div style="aspect-ratio: 1 / 1;">
                            <div id="qrcode" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Identitas Pegawai</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" value="<?= $data['nip']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" value="<?= $data['nama']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nomor_telepon">Nomor Telepon</label>
                            <input type="text" class="form-control" value="<?= $data['nomor_telepon']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="text" class="form-control" value="<?= indonesiaDate($data['tanggal_lahir']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" value="<?= $data['tempat_lahir']; ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Jabatan</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" class="form-control" value="<?= $data['jabatan']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="golongan">Golongan</label>
                            <input type="text" class="form-control" value="<?= $data['golongan']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="gaji_pokok">Gaji Pokok</label>
                            <input type="text" class="form-control" value="<?= number_format($data['gaji_pokok'], 0, ",", "."); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="tmt">TMT</label>
                            <input type="text" class="form-control" value="<?= indonesiaDate($data['tmt']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Tunjangan yang diterima</label>
                            <ol style="padding-left: 1rem;">
                                <?php foreach ($data['tunjangan'] as $tunjangan) : ?>
                                    <li><?= $tunjangan; ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-desktop mr-2"></i>Informasi Perangkat & Akses Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label><i class="fas fa-network-wired text-info mr-1"></i> IP Address Terakhir</label>
                            <input type="text" class="form-control font-weight-bold" value="<?= $data['ip_address'] ? $data['ip_address'] : 'Belum pernah login'; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-laptop text-info mr-1"></i> Perangkat & Browser</label>
                            <input type="text" class="form-control" value="<?= $data['device_info'] ? $data['device_info'] : 'Belum tercatat'; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="far fa-clock text-info mr-1"></i> Waktu Login Terakhir</label>
                            <input type="text" class="form-control" value="<?= $data['last_login'] ? date('d-m-Y H:i:s', strtotime($data['last_login'])) . ' WIB' : 'Belum pernah login'; ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    const qrCode = new QRCodeStyling({
        width: 1000,
        height: 1000,
        data: JSON.stringify(<?= json_encode($data); ?>),
        // image: "assets/img/favicon.png", 
        backgroundOptions: {
            color: "#ffffff",
        },
        imageOptions: {
            crossOrigin: "anonymous",
            margin: 30
        },
        margin: 0
    });

    qrCode.append(document.getElementById("qrcode"));
    document.getElementById('cetak').addEventListener('click', () => {
        qrCode.download({});
    });
</script>