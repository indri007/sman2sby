<?php
$pageTitle = "Ujian & Kuis Online - Siswa";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div class="card card-white">
    <div class="card-header">
        <div>
            <div class="card-title">
                <i data-lucide="award" style="color:var(--c-purple);width:20px;height:20px;"></i>
                Daftar Ujian & Asesmen Online
            </div>
            <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Ujian mandiri dan asesmen terjadwal dengan sistem CBT.</p>
        </div>
        <span class="badge badge-purple"><?= count($ujianList) ?> Asesmen Terbuka</span>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Judul Ujian</th>
                    <th>Durasi</th>
                    <th>KKM</th>
                    <th>Total Soal</th>
                    <th>Status Anda</th>
                    <th>Nilai Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ujianList)): ?>
                    <tr><td colspan="8" style="text-align:center;color:#64748b;padding:34px;">Belum ada ujian online yang dibuka.</td></tr>
                <?php else: ?>
                    <?php foreach ($ujianList as $u): ?>
                        <tr>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($u['nama_mapel']) ?></span></td>
                            <td><b style="color:var(--text-primary);"><?= htmlspecialchars($u['judul']) ?></b></td>
                            <td style="color:var(--text-secondary);"><?= $u['durasi_menit'] ?> Menit</td>
                            <td><span class="badge badge-secondary">KKM: <?= $u['kkm'] ?></span></td>
                            <td><?= $u['total_soal'] ?> Butir</td>
                            <td>
                                <?php if ($u['status_attempt'] === 'completed'): ?>
                                    <span class="badge badge-success">&check; Selesai</span>
                                <?php else: ?>
                                    <span class="badge badge-amber">Belum Dikerjakan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= ($u['status_attempt'] === 'completed') ? '<span class="badge badge-purple" style="font-size:13.5px;font-weight:800;">' . $u['nilai_akhir'] . '</span>' : '-' ?>
                            </td>
                            <td>
                                <?php if ($u['status_attempt'] === 'completed'): ?>
                                    <a href="index.php?page=siswa_ujian_hasil&id=<?= $u['attempt_id'] ?>" class="btn btn-outline btn-sm">
                                        Lihat Hasil
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?page=siswa_ujian_kerjakan&id=<?= $u['id'] ?>" class="btn btn-primary btn-sm" onclick="return confirm('Mulai mengerjakan ujian ini sekarang?')">
                                        Mulai Ujian &rarr;
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
