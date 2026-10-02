<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1>Selamat Datang Admin</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <?php $pegawai = $mysqli->query("SELECT * FROM pegawai"); ?>
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $pegawai->num_rows; ?></h3>
                        <p>Siswa</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <?php $admin = $mysqli->query("SELECT * FROM user WHERE status='ADMIN'"); ?>
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $admin->num_rows; ?></h3>
                        <p>Admin</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <?php $jabatan = $mysqli->query("SELECT * FROM jabatan"); ?>
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $jabatan->num_rows; ?></h3>
                        <p>Jabatan</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <?php 
                $adminLeaderboard = getGamifikasiLeaderboard($mysqli);
                $adminTop3 = array_slice($adminLeaderboard, 0, 3);
                ?>
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= count($adminLeaderboard); ?></h3>
                        <p>Leaderboard Siswa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <a href="?page=leaderboard" class="small-box-footer">Lihat Leaderboard <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Widget Top 3 Siswa Teraktif Bulan Ini -->
        <div class="card shadow-sm border mt-2">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-crown text-warning mr-2"></i> Top 3 Presensi Siswa Kelas X-8 (Bulan <?= MONTH_IN_INDONESIA[intval(date('m'))-1] . ' ' . date('Y'); ?>)
                </h5>
                <a href="?page=leaderboard" class="btn btn-xs btn-primary font-weight-bold">
                    Buka Leaderboard Lengkap <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                    $medals = ['🥇 Juara 1', '🥈 Juara 2', '🥉 Juara 3'];
                    $borderColors = ['border-warning', 'border-secondary', 'border-dark'];
                    for ($i = 0; $i < 3; $i++) :
                        $item = $adminTop3[$i] ?? null;
                    ?>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="card border <?= $borderColors[$i]; ?> shadow-sm h-100">
                                <div class="card-body text-center p-3">
                                    <h6 class="font-weight-bold text-muted"><?= $medals[$i]; ?></h6>
                                    <h5 class="font-weight-bold text-dark text-truncate mb-1" title="<?= htmlspecialchars($item['nama'] ?? '-'); ?>">
                                        <?= htmlspecialchars($item['nama'] ?? 'Belum ada data'); ?>
                                    </h5>
                                    <p class="text-primary font-weight-bold mb-1" style="font-size: 1.1rem;">
                                        <?= $item['poin_bulan_ini'] ?? 0; ?> Poin
                                    </p>
                                    <small class="text-muted d-block">
                                        🔥 Streak: <strong><?= $item['current_streak'] ?? 0; ?> Hari</strong> | Tepat Waktu: <?= $item['hadir_tepat'] ?? 0; ?>x
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

    </div>
</section>