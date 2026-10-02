<?php
$pageTitle = "Materi Pembelajaran - Guru";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div class="card card-white">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Materi Belajar</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Materi yang Anda buat dan distribusikan ke kelas.</p>
        </div>
        <a href="index.php?page=guru_materi_create" class="btn btn-primary">
            <i data-lucide="plus" style="width:16px;"></i> Terbitkan Materi
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Judul</th>
                    <th>Bab</th>
                    <th>Kelas Terkait</th>
                    <th>Dibaca</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($materiList)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#64748b;padding:30px;">Belum ada materi pembelajaran. Silakan klik tombol 'Terbitkan Materi'.</td></tr>
                <?php else: ?>
                    <?php foreach ($materiList as $m): ?>
                        <tr>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($m['nama_mapel']) ?></span></td>
                            <td><b><?= htmlspecialchars($m['judul']) ?></b></td>
                            <td><?= htmlspecialchars($m['bab']) ?></td>
                            <td><span class="badge badge-purple"><?= htmlspecialchars($m['kelas_terbagi'] ?? '-') ?></span></td>
                            <td><?= $m['views'] ?>x</td>
                            <td><?= Helper::formatTanggal($m['created_at'], false) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
