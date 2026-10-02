<?php
$pageTitle = "Gemini Key Pool - EduRAG Management";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Info Box (Flat Amber Card, No Gradient) -->
<div class="card card-amber" style="margin-bottom:24px;">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:6px;"><i data-lucide="shield-alert" style="width:18px;vertical-align:middle;"></i> Mekanisme AI Key Rotation & Failover</h3>
    <p style="font-size:13px;line-height:1.6;">
        Anda dapat memasukkan lebih dari satu Google Gemini API Key. Jika salah satu API key mengalami limit kuota (HTTP 429 Too Many Requests), sistem EduRAG akan secara otomatis beralih ke key berikutnya dalam pool tanpa memutus aktivitas belajar siswa atau guru.
    </p>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <!-- Form Tambah Key (Flat Slate Card) -->
    <div class="card card-slate">
        <div class="card-header">
            <div class="card-title">+ Tambah Gemini API Key</div>
        </div>
        <form action="index.php?page=admin_ai_keys" method="POST">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label class="form-label">Nama / Label Key</label>
                <input type="text" name="key_name" class="form-control" placeholder="Contoh: Key Cadangan Guru" required>
            </div>

            <div class="form-group">
                <label class="form-label">Google Gemini API Key</label>
                <input type="password" name="api_key" class="form-control" placeholder="AIzaSy..." required>
                <small style="color:#64748b;font-size:11px;margin-top:4px;display:block;">Dapatkan di Google AI Studio (aistudio.google.com)</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:10px;">
                <i data-lucide="plus-circle" style="width:16px;"></i> Tambah ke Pool
            </button>
        </form>
    </div>

    <!-- Table Key Pool (Clean White Card) -->
    <div class="card card-white">
        <div class="card-header">
            <div class="card-title">Daftar API Key dalam Pool</div>
            <span class="badge badge-emerald"><?= $stats['active_keys'] ?> Aktif</span>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>API Key (Sensor)</th>
                        <th>Status</th>
                        <th>Total Req</th>
                        <th>Error</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($keys as $k): ?>
                        <tr>
                            <td><b><?= htmlspecialchars($k['key_name']) ?></b></td>
                            <td><code><?= substr($k['api_key'], 0, 8) ?>••••••••</code></td>
                            <td>
                                <?php if ($k['status'] === 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php elseif ($k['status'] === 'rate_limited'): ?>
                                    <span class="badge badge-danger">Rate Limited</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $k['total_requests'] ?></td>
                            <td><?= $k['error_count'] ?></td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <form action="index.php?page=admin_ai_keys" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                        <input type="hidden" name="status" value="<?= $k['status'] ?>">
                                        <button type="submit" class="btn btn-outline btn-sm">
                                            <?= $k['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>

                                    <form action="index.php?page=admin_ai_keys" method="POST" style="display:inline;" onsubmit="return confirm('Hapus API key ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
