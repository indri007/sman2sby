<?php
$pageTitle = "Penugasan Siswa - Guru";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Daftar Penugasan Siswa</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Kelola tugas, periksa hasil pengumpulan siswa, dan berikan nilai & evaluasi.</p>
        </div>
        <a href="index.php?page=guru_tugas_create" class="btn btn-primary">
            <i data-lucide="plus-circle" style="width:16px;"></i> + Buat Tugas Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Judul Tugas</th>
                    <th>Kelas</th>
                    <th>Batas Waktu (Deadline)</th>
                    <th>Status Pengumpulan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tugasList)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;color:#64748b;padding:40px 20px;">
                            <i data-lucide="clipboard-x" style="width:40px;height:40px;margin-bottom:8px;opacity:0.4;"></i>
                            <p style="font-size:14px;margin-bottom:12px;">Belum ada penugasan yang dibuat.</p>
                            <a href="index.php?page=guru_tugas_create" class="btn btn-primary btn-sm">+ Buat Tugas Pertama</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tugasList as $t): ?>
                        <?php 
                            $isOverdue = (strtotime($t["deadline"]) < time());
                            $totalSiswa = $t["total_siswa_kelas"] ?? 0;
                            $totalKumpul = (int)$t["total_mengumpulkan"];
                        ?>
                        <tr>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($t["nama_mapel"]) ?></span></td>
                            <td>
                                <b><?= htmlspecialchars($t["judul"]) ?></b>
                                <?php if (!empty($t["file_lampiran"])): ?>
                                    <span style="font-size:11px;color:#2563eb;margin-left:4px;">[Ada Lampiran File]</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-purple"><?= htmlspecialchars($t["nama_kelas"]) ?></span></td>
                            <td style="font-size:13px;">
                                <span style="color:<?= $isOverdue ? "#dc2626" : "#0f172a" ?>;font-weight:<?= $isOverdue ? "700" : "500" ?>;">
                                    <?= date("d/m/Y H:i", strtotime($t["deadline"])) ?>
                                </span>
                                <?php if ($isOverdue): ?>
                                    <span class="badge badge-rose" style="font-size:10px;margin-left:4px;">Lewat Deadline</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $totalKumpul > 0 ? "badge-emerald" : "badge-secondary" ?>" style="font-size:12px;">
                                    <?= $totalKumpul ?> <?= $totalSiswa > 0 ? "/ " . $totalSiswa : "" ?> Siswa Mengumpulkan
                                </span>
                            </td>
                            <td style="text-align:center;white-space:nowrap;">
                                <div style="display:inline-flex;gap:6px;">
                                    <a href="index.php?page=guru_tugas_submissions&id=<?= $t["id"] ?>" 
                                       class="btn btn-primary btn-sm" 
                                       style="padding:6px 12px;font-size:12px;" 
                                       title="Lihat pengumpulan tugas siswa & input nilai">
                                        <i data-lucide="check-circle" style="width:13px;vertical-align:middle;"></i> Periksa Tugas (<?= $totalKumpul ?>)
                                    </a>
                                    <a href="index.php?page=guru_tugas_delete&id=<?= $t["id"] ?>" 
                                       onclick="return confirm('Hapus penugasan ini beserta riwayat jawaban siswa?')" 
                                       class="btn btn-danger btn-sm" 
                                       style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:6px 9px;" 
                                       title="Hapus Tugas">
                                        <i data-lucide="trash-2" style="width:13px;vertical-align:middle;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
