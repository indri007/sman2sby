<?php
$pageTitle = "Dashboard Administrator - SMAN 2 Surabaya";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Solid Colored Stat Cards (No Gradients) -->
<div class="stat-grid">
    <div class="stat-card card-blue">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Guru</div>
                <div class="stat-val"><?= count($guruList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="users"></i>
            </div>
        </div>
        <div class="stat-sub">Terdaftar dari data absensi</div>
    </div>

    <div class="stat-card card-emerald">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-val"><?= count($siswaList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="graduation-cap"></i>
            </div>
        </div>
        <div class="stat-sub">Siswa aktif SMAN 2</div>
    </div>

    <div class="stat-card card-purple">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Kelas</div>
                <div class="stat-val"><?= count($kelasList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="school"></i>
            </div>
        </div>
        <div class="stat-sub">Kurikulum Merdeka Kelas 10</div>
    </div>

    <div class="stat-card card-amber">
        <div class="stat-header">
            <div>
                <div class="stat-label">Permintaan AI</div>
                <div class="stat-val"><?= $aiStats['total_requests'] ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="sparkles"></i>
            </div>
        </div>
        <div class="stat-sub"><?= $aiStats['active_keys'] ?> Key Gemini Aktif</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <!-- Recent AI Usage -->
    <div class="card card-white">
        <div class="card-header">
            <div class="card-title">
                <i data-lucide="activity" style="color:var(--c-blue);width:20px;height:20px;"></i>
                Aktivitas Terkini AI EduRAG
            </div>
            <a href="index.php?page=admin_ai_logs" class="btn btn-outline btn-sm">
                Lihat Semua Log &rarr;
            </a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Fitur</th>
                        <th>Status</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($aiLogs)): ?>
                        <tr><td colspan="5" style="text-align:center;color:#64748b;padding:30px;">Belum ada log penggunaan AI.</td></tr>
                    <?php else: ?>
                        <?php foreach ($aiLogs as $log): ?>
                            <tr>
                                <td style="font-size:12.5px;color:#64748b;"><?= Helper::timeAgo($log['created_at']) ?></td>
                                <td><b><?= htmlspecialchars($log['nama_user']) ?></b></td>
                                <td><span class="badge badge-purple"><?= strtoupper($log['feature']) ?></span></td>
                                <td>
                                    <?php if ($log['status'] === 'success'): ?>
                                        <span class="badge badge-success">&check; Berhasil</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">&times; Gagal</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-family:monospace;font-size:12.5px;"><?= $log['execution_time_ms'] ?> ms</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Info Box (Solid Slate Card) -->
    <div class="card card-slate">
        <div class="card-header">
            <div class="card-title" style="color:var(--text-primary);">
                <i data-lucide="server" style="color:#475569;width:20px;height:20px;"></i>
                Status Database Absensi
            </div>
        </div>
        <p style="font-size:13.5px;line-height:1.6;margin-bottom:18px;color:#334155;">
            Sistem terintegrasi langsung dengan database absensi <code>u401911348_absen1</code>. Akun guru dan siswa dapat langsung login dengan NIP/NISN masing-masing.
        </p>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="index.php?page=admin_buku" class="btn btn-primary" style="font-size:13.5px;width:100%;justify-content:flex-start;background-color:#05682b;border-color:#05682b;">
                <i data-lucide="book-open" style="width:16px;height:16px;"></i> Kelola Perpus Digital (<?= $totalBuku ?> Buku)
            </a>
            <a href="index.php?page=admin_ai_keys" class="btn btn-primary" style="font-size:13.5px;width:100%;justify-content:flex-start;">
                <i data-lucide="key-round" style="width:16px;height:16px;"></i> Kelola Gemini API Keys
            </a>
            <a href="index.php?page=admin_guru" class="btn btn-outline" style="font-size:13.5px;width:100%;justify-content:flex-start;">
                <i data-lucide="users" style="width:16px;height:16px;"></i> Lihat Data Guru
            </a>
            <a href="index.php?page=admin_siswa" class="btn btn-outline" style="font-size:13.5px;width:100%;justify-content:flex-start;">
                <i data-lucide="graduation-cap" style="width:16px;height:16px;"></i> Lihat Data Siswa
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
