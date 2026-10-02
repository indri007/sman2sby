<?php
$pageTitle = "Tugas Kelas - Siswa";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div class="card card-white">
    <div class="card-header">
        <div>
            <div class="card-title">
                <i data-lucide="clipboard-list" style="color:var(--c-yellow);width:20px;height:20px;"></i>
                Daftar Tugas Kelas Anda
            </div>
            <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Kumpulkan tugas sebelum batas waktu deadline berakhir.</p>
        </div>
        <span class="badge badge-amber"><?= count($tugasList) ?> Tugas Tersedia</span>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Judul Tugas</th>
                    <th>Guru Pengajar</th>
                    <th>Batas Pengumpulan</th>
                    <th>Status Anda</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tugasList)): ?>
                    <tr><td colspan="7" style="text-align:center;color:#64748b;padding:34px;">Tidak ada tugas kelas yang sedang aktif saat ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($tugasList as $t): ?>
                        <tr>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($t['nama_mapel']) ?></span></td>
                            <td><b style="color:var(--text-primary);"><?= htmlspecialchars($t['judul']) ?></b></td>
                            <td style="color:var(--text-secondary);"><?= htmlspecialchars($t['nama_guru']) ?></td>
                            <td style="font-size:12.5px;color:var(--text-muted);"><?= Helper::formatTanggal($t['deadline']) ?></td>
                            <td>
                                <?php if ($t['submission_id']): ?>
                                    <span class="badge badge-success">&check; Sudah Mengumpulkan</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Belum Mengumpulkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $t['nilai'] !== null ? '<span class="badge badge-purple" style="font-size:13px;">' . $t['nilai'] . '</span>' : '-' ?>
                            </td>
                            <td>
                                <a href="index.php?page=siswa_tugas_detail&id=<?= $t['id'] ?>" class="btn <?= $t['submission_id'] ? 'btn-outline' : 'btn-primary' ?> btn-sm">
                                    <?= $t['submission_id'] ? 'Lihat Tugas' : 'Kumpulkan &rarr;' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
