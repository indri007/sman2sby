<?php
$bulanPilihan = isset($_GET['bulan']) ? intval($_GET['bulan']) : intval(date('m'));
$tahunPilihan = isset($_GET['tahun']) ? intval($_GET['tahun']) : intval(date('Y'));

$leaderboard = getGamifikasiLeaderboard($mysqli, $bulanPilihan, $tahunPilihan);
$top3 = array_slice($leaderboard, 0, 3);
$mostImproved = getMostImprovedSiswa($leaderboard);
$hallOfFame = getHallOfFameData($mysqli);

// Summary metrics
$totalSiswa = count($leaderboard);
$totalPoinSemua = array_sum(array_column($leaderboard, 'poin_bulan_ini'));
$rataRataPoin = $totalSiswa > 0 ? round($totalPoinSemua / $totalSiswa, 1) : 0;
$totalTepatWaktu = array_sum(array_column($leaderboard, 'hadir_tepat'));
$totalTerlambat = array_sum(array_column($leaderboard, 'hadir_terlambat'));
?>

<style>
    .podium-admin-card {
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        transition: transform 0.2s ease;
        border-top: 5px solid #ccc;
    }
    .podium-admin-card:hover {
        transform: translateY(-3px);
    }
    .podium-1 {
        border-top-color: #f1c40f;
        background: linear-gradient(180deg, #fffcf0 0%, #ffffff 100%);
    }
    .podium-2 {
        border-top-color: #95a5a6;
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    }
    .podium-3 {
        border-top-color: #cd7f32;
        background: linear-gradient(180deg, #fdf8f5 0%, #ffffff 100%);
    }
    .badge-pill-table {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 2px;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }
    }
</style>

<section class="content-header no-print">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fas fa-trophy text-warning mr-2"></i>Leaderboard & Gamifikasi Siswa</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button onclick="window.print()" class="btn btn-sm btn-outline-dark font-weight-bold">
                    <i class="fas fa-print mr-1"></i> Cetak Leaderboard
                </button>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <!-- Filter Periode Bulan & Tahun -->
    <div class="card shadow-sm no-print mb-4">
        <div class="card-body py-3">
            <form action="" method="GET" class="form-inline d-flex justify-content-between flex-wrap">
                <input type="hidden" name="page" value="leaderboard">
                <div class="form-group mb-2 mb-md-0">
                    <label class="mr-2 font-weight-bold text-muted"><i class="far fa-calendar-alt mr-1 text-primary"></i> Pilih Periode:</label>
                    <select name="bulan" class="form-control mr-2">
                        <?php foreach (MONTH_IN_INDONESIA as $idx => $mName) : ?>
                            <option value="<?= $idx + 1; ?>" <?= ($bulanPilihan == ($idx + 1)) ? 'selected' : ''; ?>>
                                <?= $mName; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="tahun" class="form-control mr-2">
                        <?php for ($y = 2024; $y <= intval(date('Y')) + 1; $y++) : ?>
                            <option value="<?= $y; ?>" <?= ($tahunPilihan == $y) ? 'selected' : ''; ?>>
                                <?= $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Tampilkan
                    </button>
                </div>
                <div class="text-muted font-weight-bold">
                    Periode Aktif: <span class="badge badge-primary px-2 py-1" style="font-size: 0.9rem;"><?= MONTH_IN_INDONESIA[$bulanPilihan - 1] . ' ' . $tahunPilihan; ?></span>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row no-print mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3><?= $totalSiswa; ?></h3>
                    <p>Total Siswa (Kelas X-8)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3><?= $totalTepatWaktu; ?>x</h3>
                    <p>Absen Tepat Waktu (+10)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3><?= $totalTerlambat; ?>x</h3>
                    <p>Absen Terlambat (+5)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple shadow-sm" style="background-color: #6f42c1; color: white;">
                <div class="inner">
                    <h3><?= $rataRataPoin; ?></h3>
                    <p>Rata-rata Poin / Siswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Podium Top 3 Kelas & Most Improved -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="podium-admin-card podium-2 h-100">
                <div class="display-4 mb-1">🥈</div>
                <span class="badge badge-secondary mb-2">Juara 2</span>
                <h5 class="font-weight-bold mb-1 text-truncate" title="<?= htmlspecialchars($top3[1]['nama'] ?? '-'); ?>">
                    <?= htmlspecialchars($top3[1]['nama'] ?? 'Belum ada data'); ?>
                </h5>
                <p class="text-muted mb-2 font-weight-bold" style="font-size: 0.95rem;">
                    <?= ($top3[1]['poin_bulan_ini'] ?? 0); ?> Poin
                </p>
                <span class="badge badge-pill badge-light border">
                    🔥 Streak <?= $top3[1]['current_streak'] ?? 0; ?> Hari
                </span>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="podium-admin-card podium-1 h-100" style="transform: scale(1.02);">
                <div class="display-4 mb-1">🥇</div>
                <span class="badge badge-warning text-dark font-weight-bold mb-2">Juara 1</span>
                <h5 class="font-weight-bold mb-1 text-truncate text-primary" title="<?= htmlspecialchars($top3[0]['nama'] ?? '-'); ?>">
                    <?= htmlspecialchars($top3[0]['nama'] ?? 'Belum ada data'); ?>
                </h5>
                <p class="text-dark mb-2 font-weight-bold" style="font-size: 1.1rem;">
                    <?= ($top3[0]['poin_bulan_ini'] ?? 0); ?> Poin
                </p>
                <span class="badge badge-pill badge-warning border text-dark font-weight-bold">
                    🔥 Streak <?= $top3[0]['current_streak'] ?? 0; ?> Hari
                </span>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="podium-admin-card podium-3 h-100">
                <div class="display-4 mb-1">🥉</div>
                <span class="badge badge-dark mb-2" style="background-color: #cd7f32;">Juara 3</span>
                <h5 class="font-weight-bold mb-1 text-truncate" title="<?= htmlspecialchars($top3[2]['nama'] ?? '-'); ?>">
                    <?= htmlspecialchars($top3[2]['nama'] ?? 'Belum ada data'); ?>
                </h5>
                <p class="text-muted mb-2 font-weight-bold" style="font-size: 0.95rem;">
                    <?= ($top3[2]['poin_bulan_ini'] ?? 0); ?> Poin
                </p>
                <span class="badge badge-pill badge-light border">
                    🔥 Streak <?= $top3[2]['current_streak'] ?? 0; ?> Hari
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border shadow-sm" style="background: linear-gradient(135deg, #e0f2fe 0%, #ffffff 100%);">
                <div class="card-body text-center p-3 d-flex flex-column justify-content-center">
                    <div class="font-weight-bold text-info mb-1" style="font-size: 1.8rem;">🚀</div>
                    <span class="badge badge-info font-weight-bold mb-2">Most Improved</span>
                    <?php if (!empty($mostImproved)) : ?>
                        <h5 class="font-weight-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($mostImproved['nama']); ?>">
                            <?= htmlspecialchars($mostImproved['nama']); ?>
                        </h5>
                        <div class="text-success font-weight-bold mt-1">
                            <i class="fas fa-arrow-up"></i> +<?= $mostImproved['most_improved_score']; ?> Poin
                        </div>
                        <small class="text-muted">Peningkatan dari bulan lalu</small>
                    <?php else : ?>
                        <p class="text-muted mb-0">Belum ada data pembanding dari bulan sebelumnya.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Lengkap Leaderboard Siswa -->
    <div class="card shadow-sm border">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0 text-dark">
                <i class="fas fa-list-ol mr-2 text-primary"></i> Tabel Peringkat Siswa — Kelas X-8 (<?= MONTH_IN_INDONESIA[$bulanPilihan - 1] . ' ' . $tahunPilihan; ?>)
            </h5>
            <small class="text-muted">Diurutkan berdasarkan Total Poin & Streak</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered table-striped table-hover text-nowrap mb-0" id="tableLeaderboard">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">Rank</th>
                            <th class="text-center" style="width: 110px;">NISN</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Tepat Waktu (+10)</th>
                            <th class="text-center">Terlambat (+5)</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Streak Aktif</th>
                            <th class="text-center">Badges</th>
                            <th class="text-center font-weight-bold" style="width: 110px;">Total Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $row) : 
                            $rankText = $row['rank'];
                            $rowClass = '';
                            if ($row['rank'] == 1) {
                                $rankText = '🥇 1';
                                $rowClass = 'table-warning';
                            } elseif ($row['rank'] == 2) {
                                $rankText = '🥈 2';
                            } elseif ($row['rank'] == 3) {
                                $rankText = '🥉 3';
                            }
                        ?>
                            <tr class="<?= $rowClass; ?>">
                                <td class="text-center align-middle font-weight-bold" style="font-size: 1rem;">
                                    <?= $rankText; ?>
                                </td>
                                <td class="text-center align-middle text-muted"><?= htmlspecialchars($row['nip']); ?></td>
                                <td class="align-middle font-weight-bold"><?= htmlspecialchars($row['nama']); ?></td>
                                <td class="text-center align-middle text-success font-weight-bold"><?= $row['hadir_tepat']; ?></td>
                                <td class="text-center align-middle text-warning font-weight-bold"><?= $row['hadir_terlambat']; ?></td>
                                <td class="text-center align-middle text-info"><?= $row['izin']; ?></td>
                                <td class="text-center align-middle text-secondary"><?= $row['sakit']; ?></td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-pill badge-light border">
                                        🔥 <?= $row['current_streak']; ?> Hari
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if (!empty($row['badges'])) : ?>
                                        <?php foreach ($row['badges'] as $b) : ?>
                                            <span title="<?= $b['name']; ?> (<?= $b['desc']; ?>)" style="font-size: 1.1rem;"><?= $b['icon']; ?></span>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle font-weight-bold text-primary" style="font-size: 1.1rem;">
                                    <?= $row['poin_bulan_ini']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Hall of Fame -->
    <div class="card shadow-sm border mt-4">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-award text-warning mr-2"></i> Hall of Fame Historis (Arsip Bulanan)
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($hallOfFame)) : ?>
                <p class="text-muted mb-0">Belum ada histori bulanan tersimpan.</p>
            <?php else : ?>
                <div class="row">
                    <?php foreach ($hallOfFame as $h) : ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                    <strong class="text-dark"><i class="far fa-calendar-alt text-primary mr-1"></i> <?= $h['nama_bulan']; ?></strong>
                                    <?php if ($h['is_current']) : ?>
                                        <span class="badge badge-success">Bulan Berjalan</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <small class="text-muted font-weight-bold d-block mb-1">TOP 3 PRESENSI:</small>
                                        <?php 
                                        $icons = ['🥇', '🥈', '🥉'];
                                        foreach ($h['top3'] as $k => $t) : 
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span><?= $icons[$k] ?? '#' . ($k+1); ?> <?= htmlspecialchars($t['nama']); ?></span>
                                                <strong class="text-primary"><?= $t['poin_bulan_ini']; ?> Poin</strong>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (!empty($h['most_improved'])) : ?>
                                        <div class="mt-2 pt-1">
                                            <small class="text-muted font-weight-bold d-block">MOST IMPROVED:</small>
                                            <span class="text-success font-weight-bold"><i class="fas fa-rocket mr-1"></i> <?= htmlspecialchars($h['most_improved']['nama']); ?></span>
                                            <small class="text-muted">(+<?= $h['most_improved']['most_improved_score']; ?> poin)</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
