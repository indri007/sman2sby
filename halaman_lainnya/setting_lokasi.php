<?php
// Ensure table exists
$mysqli->query("
    CREATE TABLE IF NOT EXISTS `school_location` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `nama_titik` VARCHAR(100) NOT NULL DEFAULT 'SMAN 2 Surabaya',
      `latitude` DECIMAL(10,7) NOT NULL DEFAULT -7.265554,
      `longitude` DECIMAL(10,7) NOT NULL DEFAULT 112.750389,
      `radius_meter` INT NOT NULL DEFAULT 200,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Handle Form Submission
$message = '';
$message_type = '';

if (isset($_POST['submit_lokasi'])) {
    $nama_titik = $mysqli->real_escape_string($_POST['nama_titik']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);
    $radius_meter = intval($_POST['radius_meter']);

    $check = $mysqli->query("SELECT * FROM school_location LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $id = $row['id'];
        $q = "UPDATE school_location SET 
                nama_titik='$nama_titik', 
                latitude='$latitude', 
                longitude='$longitude', 
                radius_meter='$radius_meter' 
              WHERE id=$id";
    } else {
        $q = "INSERT INTO school_location (nama_titik, latitude, longitude, radius_meter) 
              VALUES ('$nama_titik', '$latitude', '$longitude', '$radius_meter')";
    }

    if ($mysqli->query($q)) {
        $message = "Pengaturan titik lokasi dan radius presensi berhasil disimpan!";
        $message_type = "success";
    } else {
        $message = "Gagal menyimpan pengaturan: " . $mysqli->error;
        $message_type = "danger";
    }
}

// Fetch current setting
$res = $mysqli->query("SELECT * FROM school_location LIMIT 1");
$lokasi = [
    'nama_titik' => 'SMAN 2 Surabaya',
    'latitude' => -7.265554,
    'longitude' => 112.750389,
    'radius_meter' => 200
];

if ($res && $res->num_rows > 0) {
    $lokasi = $res->fetch_assoc();
}
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pengaturan Lokasi Presensi GPS</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Setting Lokasi</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <?php if (!empty($message)) : ?>
            <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show" role="alert">
                <?= $message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map-marked-alt mr-2"></i> Koordinat & Radius Sekolah</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_titik">Nama Titik / Sekolah</label>
                                <input type="text" class="form-control" name="nama_titik" id="nama_titik" value="<?= htmlspecialchars($lokasi['nama_titik']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="latitude" value="<?= $lokasi['latitude']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="longitude" value="<?= $lokasi['longitude']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="radius_meter">Batas Radius Maksimal Presensi (Meter)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="radius_meter" id="radius_meter" value="<?= $lokasi['radius_meter']; ?>" min="10" max="10000" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Meter</span>
                                    </div>
                                </div>
                                <small class="text-muted">Contoh: <strong>200</strong> meter dari titik pusat SMAN 2 Surabaya.</small>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="getCurrentLocation()">
                                    <i class="fas fa-crosshairs mr-1"></i> Deteksi Posisi Saya Saat Ini
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPresetSMAN2()">
                                    <i class="fas fa-undo mr-1"></i> Reset ke SMAN 2 Surabaya
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="submit_lokasi" class="btn btn-success float-right">
                                <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map mr-2"></i> Peta Lokasi SMAN 2 Surabaya</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="embed-responsive embed-responsive-4by3" style="min-height: 380px;">
                            <iframe 
                                id="mapFrame" 
                                class="embed-responsive-item" 
                                src="https://maps.google.com/maps?q=<?= $lokasi['latitude']; ?>,<?= $lokasi['longitude']; ?>&hl=id&z=17&output=embed" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a id="btnMapsLink" href="https://www.google.com/maps?q=<?= $lokasi['latitude']; ?>,<?= $lokasi['longitude']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function updateMapPreview(lat, lng) {
        document.getElementById('mapFrame').src = `https://maps.google.com/maps?q=${lat},${lng}&hl=id&z=17&output=embed`;
        document.getElementById('btnMapsLink').href = `https://www.google.com/maps?q=${lat},${lng}`;
    }

    document.getElementById('latitude').addEventListener('change', function() {
        updateMapPreview(this.value, document.getElementById('longitude').value);
    });

    document.getElementById('longitude').addEventListener('change', function() {
        updateMapPreview(document.getElementById('latitude').value, this.value);
    });

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                document.getElementById('latitude').value = pos.coords.latitude;
                document.getElementById('longitude').value = pos.coords.longitude;
                updateMapPreview(pos.coords.latitude, pos.coords.longitude);
                alert("Koordinat GPS berhasil diperbarui sesuai lokasi Anda saat ini!");
            }, function(err) {
                alert("Gagal mengambil lokasi GPS: " + err.message);
            }, { enableHighAccuracy: true });
        } else {
            alert("Browser tidak mendukung Geolocation.");
        }
    }

    function setPresetSMAN2() {
        document.getElementById('nama_titik').value = "SMAN 2 Surabaya";
        document.getElementById('latitude').value = "-7.265554";
        document.getElementById('longitude').value = "112.750389";
        document.getElementById('radius_meter').value = "200";
        updateMapPreview("-7.265554", "112.750389");
    }
</script>
