<?php
$pageTitle = "Ujian & Kuis Online - Guru";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Daftar Ujian & Kuis Online</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Jadwal asesmen formatif dan sumatif berbasis CBT Kurikulum Merdeka.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="index.php?page=guru_bank_soal" class="btn btn-outline">
                <i data-lucide="database" style="width:16px;"></i> Bank Soal
            </a>
            <a href="index.php?page=guru_ujian_create" class="btn btn-primary">
                <i data-lucide="plus-circle" style="width:16px;"></i> + Buat Jadwal Ujian
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Judul Ujian</th>
                    <th>Kelas</th>
                    <th>Jadwal / Waktu</th>
                    <th>Durasi</th>
                    <th>KKM</th>
                    <th>Soal</th>
                    <th>Selesai</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ujianList)): ?>
                    <tr>
                        <td colspan="9" style="text-align:center;color:#64748b;padding:40px 20px;">
                            <i data-lucide="file-text" style="width:40px;height:40px;margin-bottom:8px;opacity:0.4;"></i>
                            <p style="font-size:14px;margin-bottom:12px;">Belum ada jadwal ujian yang dibuat.</p>
                            <a href="index.php?page=guru_ujian_create" class="btn btn-primary btn-sm">+ Buat Ujian Pertama</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ujianList as $u): ?>
                        <?php 
                            $now = date("Y-m-d H:i:s");
                            $isEnded = ($u["tgl_selesai"] < $now);
                            $isStarted = ($u["tgl_mulai"] <= $now);
                        ?>
                        <tr>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($u["nama_mapel"]) ?></span></td>
                            <td>
                                <b><?= htmlspecialchars($u["judul"]) ?></b>
                                <?php if (!empty($u["deskripsi"])): ?>
                                    <div style="font-size:12px;color:#64748b;margin-top:2px;"><?= htmlspecialchars(mb_strimwidth($u["deskripsi"], 0, 50, "...")) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-purple"><?= htmlspecialchars($u["nama_kelas"]) ?></span></td>
                            <td style="font-size:12px;color:#334155;">
                                <div><b>Mulai:</b> <?= date("d/m/Y H:i", strtotime($u["tgl_mulai"])) ?></div>
                                <div><b>Batas:</b> <?= date("d/m/Y H:i", strtotime($u["tgl_selesai"])) ?></div>
                            </td>
                            <td><?= $u["durasi_menit"] ?> Menit</td>
                            <td><span style="font-weight:700;color:#0f172a;"><?= number_format($u["kkm"], 0) ?></span></td>
                            <td><span class="badge badge-secondary"><?= $u["total_soal"] ?> Soal</span></td>
                            <td><span class="badge badge-emerald"><?= $u["total_peserta"] ?> Siswa</span></td>
                            <td style="text-align:center;white-space:nowrap;">
                                <a href="index.php?page=guru_ujian_delete&id=<?= $u["id"] ?>" 
                                   onclick="return confirm('Hapus jadwal ujian ini beserta riwayat pengerjaannya?')" 
                                   class="btn btn-danger btn-sm" 
                                   title="Hapus Ujian"
                                   style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:5px 9px;">
                                    <i data-lucide="trash-2" style="width:14px;vertical-align:middle;"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
