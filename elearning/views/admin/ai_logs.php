<?php
$pageTitle = "AI Usage & Audit Logs - SMAN 2 Surabaya";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div class="card card-white">
    <div class="card-header">
        <div>
            <div class="card-title">Riwayat Interaksi & Penggunaan AI</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Monitoring audit penggunaan kuota Gemini AI.</p>
        </div>
        <span class="badge badge-purple"><?= $totalLogs ?> Total Entri Log</span>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Fitur AI</th>
                    <th>Prompt Cuplikan</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Respon Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="7" style="text-align:center;color:#64748b;">Belum ada log penggunaan.</td></tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td><?= Helper::formatTanggal($l['created_at']) ?></td>
                            <td><b><?= htmlspecialchars($l['nama_user']) ?></b></td>
                            <td><span class="badge badge-purple"><?= strtoupper($l['feature']) ?></span></td>
                            <td style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?= htmlspecialchars($l['prompt']) ?>">
                                <?= htmlspecialchars($l['prompt']) ?>
                            </td>
                            <td><code><?= htmlspecialchars($l['model_used']) ?></code></td>
                            <td>
                                <?php if ($l['status'] === 'success'): ?>
                                    <span class="badge badge-success">Sukses</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Gagal</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $l['execution_time_ms'] ?> ms</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Component -->
    <?= Helper::renderPagination($totalLogs, $perPage, $page, 'admin_ai_logs') ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
