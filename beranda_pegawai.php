<?php
// Processing Absensi Post Request
$message = '';
$message_type = '';

// Check School Location Target
$target_lat = SMAN2_LATITUDE;
$target_lng = SMAN2_LONGITUDE;
$max_radius = SMAN2_RADIUS_METER;

$loc_check = $mysqli->query("SELECT * FROM school_location LIMIT 1");
if ($loc_check && $loc_check->num_rows > 0) {
    $row_loc = $loc_check->fetch_assoc();
    $target_lat = floatval($row_loc['latitude']);
    $target_lng = floatval($row_loc['longitude']);
    $max_radius = intval($row_loc['radius_meter']);
}

if (isset($_POST['submit_absen'])) {
    $id_pegawai = $_SESSION['user']['id_pegawai'];
    $status = $mysqli->real_escape_string($_POST['status']);
    $jenis = !empty($_POST['jenis']) ? $mysqli->real_escape_string($_POST['jenis']) : (date('H') <= 12 ? 'Masuk' : 'Pulang');
    $keterangan = $mysqli->real_escape_string($_POST['keterangan'] ?? '');
    $latitude = floatval($_POST['latitude'] ?? 0);
    $longitude = floatval($_POST['longitude'] ?? 0);
    $tanggal_waktu = date('Y-m-d H:i:s');
    $clientIP = getClientIP();
    $devInfo = getDeviceInfo();
    $deviceSummary = $devInfo['summary'];

    // Hitung jarak Haversine
    $jarak = calculateDistance($latitude, $longitude, $target_lat, $target_lng);

    // Ambil data detail pegawai untuk notifikasi
    $pegawaiInfo = ['nama' => $_SESSION['user']['nama'] ?? 'Siswa', 'nip' => ''];
    $pegRes = $mysqli->query("SELECT nama, nip FROM pegawai WHERE id = '$id_pegawai' LIMIT 1");
    if ($pegRes && $pegRes->num_rows > 0) {
        $pegawaiInfo = $pegRes->fetch_assoc();
    }

    if ($status === 'Hadir') {
        if ($latitude == 0 && $longitude == 0) {
            $message = "Gagal mengambil koordinat GPS! Harap izinkan akses lokasi (GPS) pada browser Anda.";
            $message_type = "danger";
        } elseif ($jarak > $max_radius) {
            $message = "Absensi Ditolak! Posisi Anda saat ini berjarak " . number_format($jarak, 1) . " meter dari SMAN 2 Surabaya (Maksimal radius " . $max_radius . " meter).";
            $message_type = "danger";
        } else {
            // Save base64 photo or uploaded photo
            $foto_base64 = $_POST['foto_base64'] ?? '';
            $foto_path = '';
            $dir = "uploads/presensi/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            if (!empty($foto_base64) && strpos($foto_base64, 'data:image') === 0) {
                $foto_clean = preg_replace('#^data:image/\w+;base64,#i', '', $foto_base64);
                $foto_clean = str_replace(' ', '+', $foto_clean);
                $data = base64_decode($foto_clean);
                
                if ($data !== false && strlen($data) > 100) {
                    $filename = "foto_" . date("YmdHis") . "_" . $id_pegawai . ".png";
                    $target_file = $dir . $filename;
                    if (file_put_contents($target_file, $data)) {
                        $foto_path = $target_file;
                    }
                }
            }

            // Fallback: check uploaded file $_FILES['foto_file']
            if (empty($foto_path) && isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] == UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['foto_file']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $filename = "foto_" . date("YmdHis") . "_" . $id_pegawai . "." . $ext;
                    $target_file = $dir . $filename;
                    if (move_uploaded_file($_FILES['foto_file']['tmp_name'], $target_file)) {
                        $foto_path = $target_file;
                    }
                }
            }

            $q = "INSERT INTO presensi_pegawai (id_pegawai, tanggal_waktu, status, jenis, foto_path, latitude, longitude, jarak_meter, keterangan, ip_address, device_info) 
                  VALUES ('$id_pegawai', '$tanggal_waktu', '$status', '$jenis', '$foto_path', '$latitude', '$longitude', '$jarak', '$keterangan', '$clientIP', '$deviceSummary')";
            
            if ($mysqli->query($q)) {
                $message = "Berhasil melakukan Absensi $jenis! Jarak lokasi: " . number_format($jarak, 1) . " meter.";
                $message_type = "success";

                // Kirim notifikasi ke Bot Telegram
                sendTelegramAttendanceNotification($mysqli, [
                    'nama' => $pegawaiInfo['nama'],
                    'nip' => $pegawaiInfo['nip'],
                    'status' => $status,
                    'jenis' => $jenis,
                    'waktu' => date('d-m-Y H:i:s'),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'jarak_meter' => $jarak,
                    'ip_address' => $clientIP,
                    'device_info' => $deviceSummary,
                    'keterangan' => $keterangan
                ]);
            } else {
                $message = "Error Database: " . $mysqli->error;
                $message_type = "danger";
            }
        }
    } else {
        // Status Izin / Sakit
        $surat_dokter_path = '';
        if (isset($_FILES['surat_dokter']) && $_FILES['surat_dokter']['error'] == UPLOAD_ERR_OK) {
            $dir = "uploads/presensi/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            
            $ext = strtolower(pathinfo($_FILES['surat_dokter']['name'], PATHINFO_EXTENSION));
            $filename = "surat_" . date("YmdHis") . "_" . $id_pegawai . "." . $ext;
            $surat_dokter_path = $dir . $filename;
            move_uploaded_file($_FILES['surat_dokter']['tmp_name'], $surat_dokter_path);
        }

        $q = "INSERT INTO presensi_pegawai (id_pegawai, tanggal_waktu, status, jenis, latitude, longitude, jarak_meter, surat_dokter, keterangan, ip_address, device_info) 
              VALUES ('$id_pegawai', '$tanggal_waktu', '$status', '-', '$latitude', '$longitude', '$jarak', '$surat_dokter_path', '$keterangan', '$clientIP', '$deviceSummary')";
        
        if ($mysqli->query($q)) {
            $message = "Berhasil mengajukan $status!";
            $message_type = "success";

            // Kirim notifikasi ke Bot Telegram
            sendTelegramAttendanceNotification($mysqli, [
                'nama' => $pegawaiInfo['nama'],
                'nip' => $pegawaiInfo['nip'],
                'status' => $status,
                'jenis' => '-',
                'waktu' => date('d-m-Y H:i:s'),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'jarak_meter' => $jarak,
                'ip_address' => $clientIP,
                'device_info' => $deviceSummary,
                'keterangan' => $keterangan
            ]);
        } else {
            $message = "Error Database: " . $mysqli->error;
            $message_type = "danger";
        }
    }
}
?>

<?php
// Ambil data gamifikasi siswa yang login & leaderboard kelas
$id_siswa_login = $_SESSION['user']['id_pegawai'];
$gamifikasiData = getGamifikasiSiswa($mysqli, $id_siswa_login);
$leaderboardKelas = getGamifikasiLeaderboard($mysqli);
$top3Kelas = array_slice($leaderboardKelas, 0, 3);
$hallOfFameData = getHallOfFameData($mysqli);
$mostImproved = getMostImprovedSiswa($leaderboardKelas);
?>

<style>
    .gamifikasi-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
    }
    .badge-streak-fire {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(255, 65, 108, 0.4);
    }
    .podium-box {
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        background: #ffffff;
        color: #333;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: transform 0.2s ease;
        border-top: 4px solid #ccc;
    }
    .podium-box:hover {
        transform: translateY(-3px);
    }
    .podium-1 {
        border-top-color: #f1c40f;
        background: linear-gradient(to bottom, #fffdf2, #ffffff);
    }
    .podium-2 {
        border-top-color: #95a5a6;
        background: linear-gradient(to bottom, #f8f9fa, #ffffff);
    }
    .podium-3 {
        border-top-color: #cd7f32;
        background: linear-gradient(to bottom, #fdf8f4, #ffffff);
    }
    .badge-pill-custom {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 3px;
    }
    .badge-unlocked {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    .badge-locked {
        background-color: #f5f5f5;
        color: #9e9e9e;
        border: 1px dashed #bdbdbd;
        filter: grayscale(1);
    }
    .table-responsive {
        width: 100%;
        margin-bottom: 1rem;
        overflow-y: hidden;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        -webkit-overflow-scrolling: touch;
    }
    @media (max-width: 767.98px) {
        .modal-dialog {
            margin: 0.5rem auto;
            max-width: 95vw;
        }
        .modal-title {
            font-size: 1.05rem !important;
        }
        .gamifikasi-card .badge-streak-fire {
            font-size: 0.85rem;
            padding: 4px 10px;
        }
    }
</style>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1 class="font-weight-bold"><i class="fas fa-graduation-cap text-success mr-2"></i>Dashboard Siswa - SMAN 2 Surabaya</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Widget Gamifikasi Siswa & Bar Personal -->
    <div class="card gamifikasi-card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge-streak-fire mr-3">
                            <i class="fas fa-fire mr-1"></i> Streak <?= $gamifikasiData['current_streak'] ?? 0; ?> Hari
                        </span>
                        <span class="badge badge-light text-dark font-weight-bold p-2 px-3" style="border-radius: 20px;">
                            <i class="fas fa-trophy text-warning mr-1"></i> Peringkat #<?= $gamifikasiData['rank'] ?? '-'; ?> dari <?= $gamifikasiData['total_siswa'] ?? 36; ?> Siswa
                        </span>
                    </div>
                    <h3 class="font-weight-bold mb-1">
                        Halo, <?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Siswa'); ?>!
                    </h3>
                    <p class="text-white-50 mb-3" style="font-size: 0.95rem;">
                        <?php if (($gamifikasiData['rank'] ?? 99) <= 3) : ?>
                            🌟 <strong>Luar biasa!</strong> Kamu berada di <strong>Top 3 Kelas</strong> bulan ini. Terus pertahankan kedisiplinanmu!
                        <?php else : ?>
                            🚀 <strong>Semangat!</strong> Total perolehanmu adalah <strong><?= $gamifikasiData['poin_bulan_ini'] ?? 0; ?> Poin</strong> bulan ini. Absen tepat waktu tiap hari untuk naik ke Top 3!
                        <?php endif; ?>
                    </p>

                    <!-- Progress Bar Menuju Badge Berikutnya -->
                    <div class="bg-white p-3 rounded text-dark shadow-sm" style="max-width: 520px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold" style="font-size: 0.85rem;">
                                <?= $gamifikasiData['next_badge']['icon'] ?? '🎯'; ?> Target Badge: <strong><?= $gamifikasiData['next_badge']['name'] ?? 'Rajin'; ?></strong> (<?= $gamifikasiData['next_badge']['target'] ?? 7; ?> Hari)
                            </span>
                            <span class="badge badge-info font-weight-bold">
                                <?= $gamifikasiData['current_streak'] ?? 0; ?> / <?= $gamifikasiData['next_badge']['target'] ?? 7; ?> Hari (<?= $gamifikasiData['progress_percent'] ?? 0; ?>%)
                            </span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $gamifikasiData['progress_percent'] ?? 0; ?>%"></div>
                        </div>
                    </div>

                    <!-- Badge Collection -->
                    <div class="mt-3">
                        <small class="text-white-50 d-block mb-1 font-weight-bold">KOLEKSI BADGE KEHADIRAN:</small>
                        <?php 
                        $streakVal = $gamifikasiData['current_streak'] ?? 0;
                        $badgeListDef = [
                            ['name' => 'Rajin Mingguan', 'icon' => '🔥', 'min' => 7],
                            ['name' => 'Konsisten Sebulan', 'icon' => '🏆', 'min' => 30],
                            ['name' => 'Legend Absen', 'icon' => '👑', 'min' => 90],
                        ];
                        foreach ($badgeListDef as $bDef) : 
                            $isUnlocked = $streakVal >= $bDef['min'];
                        ?>
                            <span class="badge-pill-custom <?= $isUnlocked ? 'badge-unlocked' : 'badge-locked'; ?>" title="<?= $isUnlocked ? 'Tercapai!' : 'Perlu streak ' . $bDef['min'] . ' hari'; ?>">
                                <?= $bDef['icon']; ?> <?= $bDef['name']; ?> <?= $isUnlocked ? '✓' : '🔒'; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-5 text-center text-lg-right">
                    <div class="bg-white text-dark p-3 rounded shadow-sm d-inline-block text-left" style="min-width: 250px;">
                        <h6 class="font-weight-bold text-muted border-bottom pb-2 mb-2"><i class="fas fa-chart-line mr-1 text-primary"></i> Statistik Anda Bulan Ini</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-star text-warning mr-1"></i> Total Poin:</span>
                            <strong class="text-primary" style="font-size: 1.1rem;"><?= $gamifikasiData['poin_bulan_ini'] ?? 0; ?> Poin</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-check-circle text-success mr-1"></i> Tepat Waktu (+10):</span>
                            <strong class="text-success"><?= $gamifikasiData['hadir_tepat'] ?? 0; ?>x</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-clock text-warning mr-1"></i> Terlambat (+5):</span>
                            <strong class="text-warning"><?= $gamifikasiData['hadir_terlambat'] ?? 0; ?>x</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-file-medical text-info mr-1"></i> Izin / Sakit (+0):</span>
                            <strong class="text-info"><?= ($gamifikasiData['izin'] ?? 0) + ($gamifikasiData['sakit'] ?? 0); ?>x</strong>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-light btn-sm mr-1 font-weight-bold" data-toggle="modal" data-target="#modalLeaderboard">
                            <i class="fas fa-list-ol mr-1"></i> Leaderboard Kelas
                        </button>
                        <button type="button" class="btn btn-warning btn-sm font-weight-bold text-dark" data-toggle="modal" data-target="#modalHallOfFame">
                            <i class="fas fa-award mr-1"></i> Hall of Fame
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Podium Top 3 Kelas Bulan Berjalan -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title font-weight-bold mb-0 text-dark">
                <i class="fas fa-crown text-warning mr-2"></i> Top 3 Presensi Kelas X-8 — Bulan <?= MONTH_IN_INDONESIA[intval(date('m'))-1] . ' ' . date('Y'); ?>
            </h5>
            <button class="btn btn-xs btn-outline-primary font-weight-bold" data-toggle="modal" data-target="#modalLeaderboard">
                Lihat Semua (36 Siswa) <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <!-- Juara 2 -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="podium-box podium-2">
                        <div class="display-4 font-weight-bold mb-1">🥈</div>
                        <h6 class="badge badge-secondary mb-2">Peringkat 2</h6>
                        <h5 class="font-weight-bold mb-1 text-truncate" title="<?= htmlspecialchars($top3Kelas[1]['nama'] ?? '-'); ?>">
                            <?= htmlspecialchars($top3Kelas[1]['nama'] ?? 'Belum ada data'); ?>
                        </h5>
                        <p class="text-muted mb-2 font-weight-bold" style="font-size: 0.9rem;">
                            <?= ($top3Kelas[1]['poin_bulan_ini'] ?? 0); ?> Poin
                        </p>
                        <span class="badge badge-pill badge-light border">
                            🔥 Streak <?= $top3Kelas[1]['current_streak'] ?? 0; ?> Hari
                        </span>
                    </div>
                </div>
                <!-- Juara 1 -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="podium-box podium-1" style="transform: scale(1.03);">
                        <div class="display-4 font-weight-bold mb-1">🥇</div>
                        <h6 class="badge badge-warning text-dark font-weight-bold mb-2">Juara 1</h6>
                        <h5 class="font-weight-bold mb-1 text-truncate text-primary" title="<?= htmlspecialchars($top3Kelas[0]['nama'] ?? '-'); ?>">
                            <?= htmlspecialchars($top3Kelas[0]['nama'] ?? 'Belum ada data'); ?>
                        </h5>
                        <p class="text-dark mb-2 font-weight-bold" style="font-size: 1.05rem;">
                            <?= ($top3Kelas[0]['poin_bulan_ini'] ?? 0); ?> Poin
                        </p>
                        <span class="badge badge-pill badge-warning border text-dark font-weight-bold">
                            🔥 Streak <?= $top3Kelas[0]['current_streak'] ?? 0; ?> Hari
                        </span>
                    </div>
                </div>
                <!-- Juara 3 -->
                <div class="col-md-4">
                    <div class="podium-box podium-3">
                        <div class="display-4 font-weight-bold mb-1">🥉</div>
                        <h6 class="badge badge-dark mb-2" style="background-color: #cd7f32;">Peringkat 3</h6>
                        <h5 class="font-weight-bold mb-1 text-truncate" title="<?= htmlspecialchars($top3Kelas[2]['nama'] ?? '-'); ?>">
                            <?= htmlspecialchars($top3Kelas[2]['nama'] ?? 'Belum ada data'); ?>
                        </h5>
                        <p class="text-muted mb-2 font-weight-bold" style="font-size: 0.9rem;">
                            <?= ($top3Kelas[2]['poin_bulan_ini'] ?? 0); ?> Poin
                        </p>
                        <span class="badge badge-pill badge-light border">
                            🔥 Streak <?= $top3Kelas[2]['current_streak'] ?? 0; ?> Hari
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Absensi Kamera & GPS -->
    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title font-weight-bold"><i class="fas fa-camera mr-2"></i> Form Absensi Mandiri</h3>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data" id="formAbsensi">
                <input type="hidden" name="latitude" id="inputLat" value="0">
                <input type="hidden" name="longitude" id="inputLng" value="0">
                <input type="hidden" name="foto_base64" id="inputFotoBase64" value="">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status Kehadiran <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="statusAbsen" required onchange="toggleFormMode()">
                                <option value="Hadir" selected>Hadir (Presensi Kamera Selfie)</option>
                                <option value="Izin">Izin (Lampirkan Surat/Keterangan)</option>
                                <option value="Sakit">Sakit (Lampirkan Surat Dokter)</option>
                            </select>
                        </div>

                        <div class="form-group" id="groupJenis">
                            <label for="jenis">Jenis Presensi <span class="text-danger">*</span></label>
                            <select class="form-control" name="jenis" id="jenisAbsen">
                                <option value="Masuk" <?= date('H') <= 12 ? 'selected' : ''; ?>>Absen Masuk</option>
                                <option value="Pulang" <?= date('H') > 12 ? 'selected' : ''; ?>>Absen Pulang</option>
                            </select>
                        </div>

                        <!-- Status Lokasi GPS (Hanya ditampilkan saat Hadir) -->
                        <div class="card bg-light mb-3" id="groupLokasiGps">
                            <div class="card-body">
                                <h5><i class="fas fa-map-marker-alt text-danger mr-1"></i> Lokasi & Deteksi GPS</h5>
                                <p class="mb-1"><strong>Status GPS:</strong> <span id="gpsStatus" class="badge badge-warning">Mengambil Lokasi...</span></p>
                                <p class="mb-1"><strong>Koordinat Anda:</strong> <span id="gpsKoord" class="text-muted">Mendeteksi...</span></p>
                                <p class="mb-1"><strong>Jarak ke Sekolah:</strong> <span id="gpsJarak" class="font-weight-bold">-</span></p>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-xs btn-outline-primary" onclick="getLocation()">
                                        <i class="fas fa-crosshairs"></i> Refresh Lokasi GPS
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">* Batas radius maksimum absensi adalah <strong><?= $max_radius; ?> meter</strong> dari SMAN 2 Surabaya.</small>
                            </div>
                        </div>

                        <!-- Form Surat Dokter / Keterangan jika Izin / Sakit -->
                        <div id="groupIzinSakit" style="display: none;">
                            <div class="form-group">
                                <label for="surat_dokter">Upload Surat Dokter / Surat Izin (JPG, PNG, PDF)</label>
                                <input type="file" class="form-control-file" name="surat_dokter" id="surat_dokter" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan / Alasan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Tuliskan keterangan/alasan izin atau sakit..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Area Live Web Camera & Snapshot -->
                    <div class="col-md-6 text-center" id="groupKamera">
                        <label class="d-block font-weight-bold"><i class="fas fa-camera text-primary mr-1"></i> Kamera Selfie Presensi</label>
                        
                        <div class="border rounded p-2 bg-dark d-inline-block position-relative shadow-sm" style="width: 100%; max-width: 380px; min-height: 250px;">
                            <video id="webcam" autoplay playsinline webkit-playsinline muted class="w-100 rounded" style="object-fit: cover; max-height: 250px;"></video>
                            <img id="photoPreview" class="w-100 rounded d-none" style="object-fit: cover; max-height: 250px;" alt="Preview Foto Selfie">
                            <canvas id="canvas" class="d-none"></canvas>
                        </div>

                        <div class="mt-2">
                            <span id="cameraStatus" class="badge badge-secondary mb-2 d-inline-block">Mengaktifkan kamera...</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" id="btnCapture" onclick="takeSnapshot()">
                                    <i class="fas fa-camera"></i> Ambil Foto Selfie
                                </button>
                                <button type="button" class="btn btn-sm btn-warning d-none" id="btnRetake" onclick="retakeSnapshot()">
                                    <i class="fas fa-redo"></i> Foto Ulang
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="startCamera()">
                                    <i class="fas fa-sync"></i> Refresh Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Fallback Manual Upload if camera error/blocked -->
                        <div class="mt-3 text-left border-top pt-2" id="groupManualFoto">
                            <small class="text-muted d-block font-weight-bold mb-1"><i class="fas fa-upload mr-1"></i> Atau Upload Foto Selfie (Manual):</small>
                            <input type="file" class="form-control-file" name="foto_file" id="fotoFile" accept="image/*" capture="user" onchange="previewManualFile(this)">
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" name="submit_absen" id="btnSubmitAbsen" class="btn btn-success btn-lg btn-block font-weight-bold">
                        <i class="fas fa-check-circle mr-1"></i> Kirim Presensi Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Presensi -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Presensi Saya</h3>
        </div>
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center td-fit">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Waktu</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Foto Selfie</th>
                        <th class="text-center">Jarak (GPS)</th>
                        <th class="text-center">Surat / Ket</th>
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
                            pp.jarak_meter,
                            pp.surat_dokter,
                            pp.keterangan  
                        FROM 
                            presensi_pegawai pp
                        INNER JOIN 
                            pegawai p 
                        ON 
                            p.id=pp.id_pegawai 
                        WHERE 
                            pp.id_pegawai=" . $_SESSION['user']['id_pegawai'] . "
                        ORDER BY 
                            pp.id DESC";
                    $no = 1;
                    if ($result = $mysqli->query($q)) {
                    } else echo "Error: " . $q . "<br>" . $mysqli->error;
                    ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= $no++; ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;"><?= indonesiaDate($row['tanggal']) ?></td>
                            <td class="text-center td-fit" style="vertical-align: middle;">
                                <i class="far fa-clock mr-1 text-muted"></i><?= $row['waktu']; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if ($row['status'] == 'Hadir') : ?>
                                    <?php 
                                        $jenisDisplay = (!empty($row['jenis']) && $row['jenis'] != '-') ? $row['jenis'] : 'Masuk';
                                        $badgeColor = ($jenisDisplay == 'Pulang') ? 'badge-info' : 'badge-primary';
                                    ?>
                                    <span class="badge <?= $badgeColor; ?>"><?= $jenisDisplay; ?></span>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <span class="badge badge-<?= $row['status'] == 'Hadir' ? 'success' : ($row['status'] == 'Izin' ? 'info' : 'warning'); ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['foto_path']) && file_exists($row['foto_path'])) : ?>
                                    <a href="<?= $row['foto_path']; ?>" target="_blank" title="Klik untuk melihat foto">
                                        <img src="<?= $row['foto_path']; ?>" width="50" height="50" class="rounded-circle shadow-sm border" style="object-fit: cover;">
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
                                <?= !empty($row['jarak_meter']) ? number_format($row['jarak_meter'], 1) . ' m' : '-'; ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?php if (!empty($row['surat_dokter']) && file_exists($row['surat_dokter'])) : ?>
                                    <a href="<?= $row['surat_dokter']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-download"></i> Surat
                                    </a>
                                <?php elseif (!empty($row['keterangan'])) : ?>
                                    <small><?= htmlspecialchars($row['keterangan']); ?></small>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Kehadiran Bulanan -->
    <?php
    $q = "
        SELECT 
            * 
        FROM 
            pegawai 
        WHERE 
            id=" . $_SESSION['user']['id_pegawai'];
    $result = $mysqli->query($q);
    $pegawai = [];
    while ($row = $result->fetch_assoc()) {
        $pegawai[] = array_merge($row, ['presensi' => []]);
    }

    $q = "
    SELECT 
        id_pegawai, 
        DAY(tanggal_waktu) tanggal,
        status 
    FROM 
        presensi_pegawai 
    WHERE 
        MONTH(tanggal_waktu)='" . Date('m') . "' 
        AND 
        YEAR(tanggal_waktu)='" . Date("Y") . "' 
        AND 
        id_pegawai=" . $_SESSION['user']['id_pegawai'] . "
    ORDER BY 
        id_pegawai";
    $presensi_pegawai = $mysqli->query($q)->fetch_all(MYSQLI_ASSOC);
    foreach ($pegawai as $index => $value_pegawai) {
        for ($i = 1; $i <= Date('t'); $i++) {
            if ((Date('Y-m-') . ($i < 10 ? ('0' . $i) : $i)) <= Date('Y-m-d')) {
                $ada = false;
                foreach ($presensi_pegawai as $value_presensi_pegawai) {
                    if ($value_pegawai['id'] == $value_presensi_pegawai['id_pegawai'] && $value_presensi_pegawai['tanggal'] == $i) {
                        if ($value_presensi_pegawai['status'] == 'Hadir') {
                            $pegawai[$index]['presensi'][] = 'H';
                        } elseif ($value_presensi_pegawai['status'] == 'Izin') {
                            $pegawai[$index]['presensi'][] = 'I';
                        } elseif ($value_presensi_pegawai['status'] == 'Sakit') {
                            $pegawai[$index]['presensi'][] = 'S';
                        }
                        $ada = !$ada;
                        break;
                    }
                }
                if (!$ada) {
                    $pegawai[$index]['presensi'][] = '-';
                }
            } else {
                $pegawai[$index]['presensi'][] = '';
            }
        }
    }
    ?>
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title font-weight-bold mb-0 text-dark">
                <i class="fas fa-calendar-alt text-success mr-2"></i> Tabel Presensi Bulan <?= MONTH_IN_INDONESIA[Date('m')-1]; ?> <?= Date("Y"); ?>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mb-0" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered table-hover text-center table-sm text-nowrap mb-0" style="min-width: 650px;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-middle" colspan="<?= Date('t'); ?>">Kehadiran Tanggal (1 - <?= Date('t'); ?>)</th>
                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= Date('t'); $i++) : ?>
                                <th class="text-center" style="width: 32px; font-size: 0.85rem;"><?= $i; ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pegawai as $value) : ?>
                            <tr>
                                <?php foreach ($value['presensi'] as $presensi) : 
                                    $badgeBg = 'text-muted';
                                    if ($presensi == 'H') $badgeBg = 'text-success font-weight-bold';
                                    elseif ($presensi == 'I') $badgeBg = 'text-info font-weight-bold';
                                    elseif ($presensi == 'S') $badgeBg = 'text-warning font-weight-bold';
                                    elseif ($presensi == '-') $badgeBg = 'text-danger font-weight-bold';
                                ?>
                                    <td class="text-center align-middle <?= $badgeBg; ?>" style="font-size: 0.9rem;"><?= $presensi; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top bg-light">
                <small class="text-muted d-block mb-2"><i class="fas fa-arrows-alt-h text-primary mr-1"></i> Geser tabel ke kanan untuk melihat seluruh tanggal</small>
                <strong>Keterangan:</strong> 
                <span class="badge badge-success mr-1">H: Hadir</span>
                <span class="badge badge-info mr-1">I: Izin</span>
                <span class="badge badge-warning mr-1">S: Sakit</span>
                <span class="badge badge-danger mr-1">-: Alpa / Tidak Hadir</span>
            </div>
        </div>
    </div>

    <!-- Modal Leaderboard Lengkap Kelas X-8 -->
    <div class="modal fade" id="modalLeaderboard" tabindex="-1" role="dialog" aria-labelledby="modalLeaderboardLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="modalLeaderboardLabel" style="font-size: 1.1rem; line-height: 1.3;">
                        <i class="fas fa-trophy text-warning mr-2"></i> Papan Peringkat (Leaderboard) Kelas X-8
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="p-3 bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="mb-1 mb-md-0">
                                <h6 class="font-weight-bold mb-0">Periode: <?= MONTH_IN_INDONESIA[intval(date('m'))-1] . ' ' . date('Y'); ?></h6>
                                <small class="text-muted">Total 36 Siswa terdaftar</small>
                            </div>
                            <div class="text-md-right">
                                <small class="text-muted d-block"><span class="badge badge-success">+10</span> Tepat Waktu (&le;07:00) | <span class="badge badge-warning">+5</span> Terlambat</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info py-2 px-3 m-2 text-center border-0" style="font-size: 0.82rem; background-color: #e3f2fd; color: #0d47a1; border-radius: 6px;">
                        <i class="fas fa-arrows-alt-h mr-1"></i> Geser tabel ke kanan untuk melihat rincian poin & badge lengkap
                    </div>

                    <div class="table-responsive mb-0" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-hover table-striped table-sm text-nowrap mb-0" style="min-width: 620px;">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center align-middle" style="width: 50px;">Rank</th>
                                    <th class="align-middle" style="min-width: 170px;">Nama Siswa</th>
                                    <th class="text-center align-middle">Tepat (+10)</th>
                                    <th class="text-center align-middle">Telat (+5)</th>
                                    <th class="text-center align-middle">Izin / Sakit</th>
                                    <th class="text-center align-middle">Streak</th>
                                    <th class="text-center align-middle">Badge</th>
                                    <th class="text-center align-middle font-weight-bold" style="min-width: 80px;">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaderboardKelas as $item) : 
                                    $isMe = ($item['id'] == $id_siswa_login);
                                    $rankDisplay = $item['rank'];
                                    if ($item['rank'] == 1) $rankDisplay = '🥇 1';
                                    elseif ($item['rank'] == 2) $rankDisplay = '🥈 2';
                                    elseif ($item['rank'] == 3) $rankDisplay = '🥉 3';
                                ?>
                                    <tr class="<?= $isMe ? 'table-success font-weight-bold' : ''; ?>">
                                        <td class="text-center align-middle font-weight-bold" style="font-size: 0.95rem;">
                                            <?= $rankDisplay; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?= htmlspecialchars($item['nama']); ?>
                                            <?php if ($isMe) : ?>
                                                <span class="badge badge-success ml-1">Kamu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle text-success font-weight-bold"><?= $item['hadir_tepat']; ?></td>
                                        <td class="text-center align-middle text-warning font-weight-bold"><?= $item['hadir_terlambat']; ?></td>
                                        <td class="text-center align-middle text-muted"><?= $item['izin'] + $item['sakit']; ?></td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-pill badge-light border">
                                                🔥 <?= $item['current_streak']; ?> Hari
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if (!empty($item['badges'])) : ?>
                                                <?php foreach ($item['badges'] as $b) : ?>
                                                    <span title="<?= $b['name']; ?>" style="font-size: 1.05rem;"><?= $b['icon']; ?></span>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle font-weight-bold text-primary" style="font-size: 1rem;">
                                            <?= $item['poin_bulan_ini']; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hall of Fame -->
    <div class="modal fade" id="modalHallOfFame" tabindex="-1" role="dialog" aria-labelledby="modalHallOfFameLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold" id="modalHallOfFameLabel">
                        <i class="fas fa-award text-dark mr-2"></i> Hall of Fame — SMAN 2 Surabaya
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted">Daftar siswa berprestasi dengan kedisiplinan dan perolehan poin kehadiran tertinggi di setiap periode bulan:</p>
                    
                    <?php if (empty($hallOfFameData)) : ?>
                        <div class="alert alert-info text-center">
                            Belum ada riwayat Hall of Fame tersimpan.
                        </div>
                    <?php else : ?>
                        <?php foreach ($hallOfFameData as $hf) : ?>
                            <div class="card mb-4 border shadow-sm">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold"><i class="far fa-calendar-alt mr-2 text-warning"></i> Periode: <?= $hf['nama_bulan']; ?></span>
                                    <?php if ($hf['is_current']) : ?>
                                        <span class="badge badge-success">Bulan Berjalan</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-medal mr-1"></i> Top 3 Presensi:</h6>
                                            <div class="list-group list-group-flush">
                                                <?php 
                                                $medals = ['🥇 Juara 1', '🥈 Juara 2', '🥉 Juara 3'];
                                                foreach ($hf['top3'] as $idx => $t3) : 
                                                ?>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                                                        <div>
                                                            <strong><?= $medals[$idx] ?? '#' . ($idx+1); ?>:</strong> <?= htmlspecialchars($t3['nama']); ?>
                                                        </div>
                                                        <span class="badge badge-primary badge-pill font-weight-bold"><?= $t3['poin_bulan_ini']; ?> Poin</span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4 border-left">
                                            <h6 class="font-weight-bold text-success mb-2"><i class="fas fa-chart-line mr-1"></i> Most Improved:</h6>
                                            <?php if (!empty($hf['most_improved'])) : ?>
                                                <div class="p-2 bg-light rounded text-center">
                                                    <div class="font-weight-bold text-dark"><?= htmlspecialchars($hf['most_improved']['nama']); ?></div>
                                                    <small class="text-success font-weight-bold d-block mt-1">
                                                        <i class="fas fa-arrow-up"></i> +<?= $hf['most_improved']['most_improved_score']; ?> Poin peningkatan
                                                    </small>
                                                </div>
                                            <?php else : ?>
                                                <small class="text-muted">Data perbandingan bulan lalu belum tersedia.</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript Kamera & GPS Haversine -->
<script>
    const targetLat = <?= $target_lat; ?>;
    const targetLng = <?= $target_lng; ?>;
    const maxRadius = <?= $max_radius; ?>;

    let userLat = 0;
    let userLng = 0;
    let isLocationValid = false;
    let mediaStream = null;

    let video = document.getElementById('webcam');
    let photoPreview = document.getElementById('photoPreview');
    let canvas = document.getElementById('canvas');
    let btnCapture = document.getElementById('btnCapture');
    let btnRetake = document.getElementById('btnRetake');
    let cameraStatus = document.getElementById('cameraStatus');

    function toggleFormMode() {
        let status = document.getElementById('statusAbsen').value;
        let groupLokasiGps = document.getElementById('groupLokasiGps');
        let groupKamera = document.getElementById('groupKamera');
        let groupJenis = document.getElementById('groupJenis');
        let groupIzinSakit = document.getElementById('groupIzinSakit');

        if (status === 'Hadir') {
            if (groupKamera) groupKamera.style.display = 'block';
            if (groupJenis) groupJenis.style.display = 'block';
            if (groupLokasiGps) groupLokasiGps.style.display = 'block';
            if (groupIzinSakit) groupIzinSakit.style.display = 'none';
        } else {
            if (groupKamera) groupKamera.style.display = 'none';
            if (groupJenis) groupJenis.style.display = 'none';
            if (groupLokasiGps) groupLokasiGps.style.display = 'none';
            if (groupIzinSakit) groupIzinSakit.style.display = 'block';
        }
    }

    // Hitung Jarak Haversine JS
    function getDistanceHaversine(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius Bumi dalam meter
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Geolocation API
    function getLocation() {
        const gpsStatus = document.getElementById('gpsStatus');
        const gpsKoord = document.getElementById('gpsKoord');
        const gpsJarak = document.getElementById('gpsJarak');

        gpsStatus.className = "badge badge-warning";
        gpsStatus.innerText = "Mengambil Lokasi...";

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;

                    document.getElementById('inputLat').value = userLat;
                    document.getElementById('inputLng').value = userLng;

                    gpsKoord.innerText = userLat.toFixed(6) + ", " + userLng.toFixed(6);

                    let jarak = getDistanceHaversine(userLat, userLng, targetLat, targetLng);
                    gpsJarak.innerText = jarak.toFixed(1) + " Meter";

                    if (jarak <= maxRadius) {
                        isLocationValid = true;
                        gpsStatus.className = "badge badge-success";
                        gpsStatus.innerText = "Dalam Radius SMAN 2 Surabaya (" + jarak.toFixed(1) + "m)";
                    } else {
                        isLocationValid = false;
                        gpsStatus.className = "badge badge-danger";
                        gpsStatus.innerText = "Di Luar Radius (" + jarak.toFixed(1) + "m / Maks " + maxRadius + "m)";
                    }
                },
                function (error) {
                    gpsStatus.className = "badge badge-danger";
                    gpsStatus.innerText = "GPS Ditolak/Tidak Aktif!";
                    gpsKoord.innerText = "Tidak dapat mendeteksi lokasi";
                    alert("Akses lokasi GPS diperlukan untuk verifikasi presensi. Mohon aktifkan GPS browser Anda.");
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            gpsStatus.innerText = "Browser tidak mendukung Geolocation.";
        }
    }
    
    // Camera Access with Multi-Constraint Fallback
    function startCamera() {
        if (cameraStatus) {
            cameraStatus.className = "badge badge-warning mb-2 d-inline-block";
            cameraStatus.innerText = "Menghubungkan ke kamera...";
        }

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            const constraintsPrimary = {
                video: {
                    facingMode: "user",
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            };

            navigator.mediaDevices.getUserMedia(constraintsPrimary)
            .then(handleCameraSuccess)
            .catch(function (error) {
                console.log("Primary camera facingMode error, mencoba fallback: ", error);
                // Fallback 1: Simple video true
                navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(handleCameraSuccess)
                .catch(function (fallbackErr) {
                    console.log("Semua akses kamera gagal: ", fallbackErr);
                    if (cameraStatus) {
                        cameraStatus.className = "badge badge-danger mb-2 d-inline-block";
                        cameraStatus.innerText = "Kamera tidak aktif / izin ditolak. Silakan gunakan tombol upload foto di bawah.";
                    }
                });
            });
        } else {
            if (cameraStatus) {
                cameraStatus.className = "badge badge-danger mb-2 d-inline-block";
                cameraStatus.innerText = "Browser tidak mendukung akses kamera langsung (Gunakan upload file)";
            }
        }
    }

    function handleCameraSuccess(stream) {
        mediaStream = stream;
        video.srcObject = stream;
        video.onloadedmetadata = function() {
            video.play().catch(e => console.log("Video play error:", e));
        };
        video.classList.remove('d-none');
        photoPreview.classList.add('d-none');
        if (cameraStatus) {
            cameraStatus.className = "badge badge-success mb-2 d-inline-block";
            cameraStatus.innerText = "Kamera Aktif";
        }
    }

    // Ambil Foto Selfie (Manual Button)
    function takeSnapshot() {
        if (video.videoWidth > 0 && video.videoHeight > 0) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            let context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            let dataURL = canvas.toDataURL('image/png');
            document.getElementById('inputFotoBase64').value = dataURL;
            
            photoPreview.src = dataURL;
            photoPreview.classList.remove('d-none');
            video.classList.add('d-none');
            
            btnCapture.classList.add('d-none');
            btnRetake.classList.remove('d-none');
            if (cameraStatus) {
                cameraStatus.className = "badge badge-success mb-2 d-inline-block";
                cameraStatus.innerText = "Foto selfie berhasil diambil!";
            }
            return true;
        } else {
            alert("Kamera belum siap atau tidak aktif. Silakan tunggu atau gunakan upload foto selfie di bawah.");
            return false;
        }
    }

    // Foto Ulang
    function retakeSnapshot() {
        document.getElementById('inputFotoBase64').value = '';
        photoPreview.classList.add('d-none');
        video.classList.remove('d-none');
        btnCapture.classList.remove('d-none');
        btnRetake.classList.add('d-none');
        if (cameraStatus) {
            cameraStatus.className = "badge badge-success mb-2 d-inline-block";
            cameraStatus.innerText = "Kamera Aktif";
        }
    }

    // Preview File Manual
    function previewManualFile(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.classList.remove('d-none');
                video.classList.add('d-none');
                if (btnCapture) btnCapture.classList.add('d-none');
                if (btnRetake) btnRetake.classList.remove('d-none');
                if (cameraStatus) {
                    cameraStatus.className = "badge badge-info mb-2 d-inline-block";
                    cameraStatus.innerText = "File foto dipilih: " + input.files[0].name;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Capture Canvas Image before Submit
    document.getElementById('formAbsensi').addEventListener('submit', function (e) {
        let status = document.getElementById('statusAbsen').value;
        
        if (status === 'Hadir') {
            if (!isLocationValid && userLat !== 0) {
                if (!confirm("Posisi Anda berada di luar radius SMAN 2 Surabaya (Maks " + maxRadius + "m). Yakin ingin tetap mengirim? (Absensi mungkin ditolak oleh sistem)")) {
                    e.preventDefault();
                    return false;
                }
            }

            // Auto snapshot jika belum klik tombol jepret dan tidak upload file
            let fotoBase64 = document.getElementById('inputFotoBase64').value;
            let fotoFileInput = document.getElementById('fotoFile');
            let hasFile = fotoFileInput && fotoFileInput.files && fotoFileInput.files.length > 0;
            
            if (!fotoBase64 && !hasFile) {
                if (video.videoWidth > 0 && video.videoHeight > 0) {
                    takeSnapshot();
                }
            }
        }
    });

    window.addEventListener('load', function() {
        getLocation();
        startCamera();
    });
</script>