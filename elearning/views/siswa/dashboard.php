<?php
$pageTitle = "Dashboard Siswa - SMAN 2 Surabaya";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Welcome Banner (Solid Emerald Card, No Gradient) -->
<div class="card card-emerald" style="margin-bottom:24px;padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:inline-flex;align-items:center;gap:6px;background-color:#059669;color:#ffffff;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:700;margin-bottom:8px;">
                <i data-lucide="sparkles" style="width:14px;height:14px;"></i> Siswa Aktif SMAN 2
            </div>
            <h2 style="font-size:22px;font-weight:800;color:#064e3b;margin-bottom:6px;">
                Halo, <?= htmlspecialchars(Auth::user()['nama']) ?>! 👋
            </h2>
            <p style="font-size:13.5px;color:#047857;margin:0;">
                Kelas: <b><?= htmlspecialchars($kelas['nama_kelas'] ?? 'X-1') ?></b> &bull; NISN: <code style="background:#ffffff;padding:2px 6px;border-radius:4px;border:1px solid #a7f3d0;color:#065f46;"><?= htmlspecialchars(Auth::user()['username']) ?></code>
            </p>
        </div>
        <a href="index.php?page=siswa_ai_tutor" class="btn btn-success" style="box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);">
            <i data-lucide="bot" style="width:18px;height:18px;"></i> Tanya Tutor AI EduRAG
        </a>
    </div>
</div>

<!-- Stat Grid (Solid Colored Cards, No Gradients) -->
<div class="stat-grid">
    <div class="stat-card card-blue">
        <div class="stat-header">
            <div>
                <div class="stat-label">Materi Tersedia</div>
                <div class="stat-val"><?= count($materiList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="book-open"></i>
            </div>
        </div>
        <div class="stat-sub">Bahan ajar kelas <?= htmlspecialchars($kelas['nama_kelas'] ?? 'X-1') ?></div>
    </div>

    <div class="stat-card card-amber">
        <div class="stat-header">
            <div>
                <div class="stat-label">Tugas Kelas</div>
                <div class="stat-val"><?= count($tugasList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="clipboard-list"></i>
            </div>
        </div>
        <div class="stat-sub">Penugasan guru</div>
    </div>

    <div class="stat-card card-purple">
        <div class="stat-header">
            <div>
                <div class="stat-label">Ujian / Kuis</div>
                <div class="stat-val"><?= count($ujianList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="award"></i>
            </div>
        </div>
        <div class="stat-sub">Asesmen mandiri & kelas</div>
    </div>

    <div class="stat-card card-rose">
        <div class="stat-header">
            <div>
                <div class="stat-label">Roadmap Belajar</div>
                <div class="stat-val">Aktif</div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="map"></i>
            </div>
        </div>
        <div class="stat-sub">
            <a href="index.php?page=siswa_roadmap" style="color:var(--c-red-text);text-decoration:none;font-weight:700;">
                Lihat Jadwal &rarr;
            </a>
        </div>
    </div>
</div>

<!-- Rak Buku Perpustakaan Digital (Buku PDF Read-Only Langsung di Dashboard) -->
<div class="card card-white" style="margin-bottom:24px;border-top:4px solid #05682b;">
    <div class="card-header">
        <div>
            <div class="card-title">
                <i data-lucide="library" style="color:#05682b;width:20px;height:20px;"></i>
                Perpustakaan Digital / Buku Pelajaran PDF
            </div>
            <p style="font-size:12.5px;color:#64748b;margin-top:2px;">
                Buku kurikulum & bahan ajar sekolah. Tersedia mode baca langsung online tanpa opsi unduh.
            </p>
        </div>
        <a href="index.php?page=siswa_buku" class="btn btn-outline btn-sm">
            Buka Semua Buku (<?= $totalBuku ?? count($bukuList ?? []) ?>) &rarr;
        </a>
    </div>

    <?php if (empty($bukuList)): ?>
        <p style="color:#64748b;font-size:13px;text-align:center;padding:20px;">Belum ada buku pelajaran yang diterbitkan.</p>
    <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:16px;">
            <?php foreach ($bukuList as $b): ?>
                <div style="border:1.5px solid var(--border-subtle);border-radius:10px;padding:14px;background:#f8fafc;display:flex;flex-direction:column;justify-content:space-between;transition:box-shadow 0.15s ease;">
                    <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:12px;">
                        <div style="width:48px;height:66px;background-color:#05682b;border-radius:4px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#ffffff;flex-shrink:0;box-shadow:0 2px 6px rgba(0,0,0,0.15);border-left:3px solid #045222;">
                            <i data-lucide="book-open" style="width:20px;height:20px;"></i>
                            <span style="font-size:8.5px;font-weight:800;margin-top:2px;">PDF</span>
                        </div>
                        <div style="min-width:0;flex:1;">
                            <span class="badge badge-purple" style="font-size:10px;padding:2px 6px;margin-bottom:4px;display:inline-block;">
                                <?= htmlspecialchars($b['nama_mapel'] ?? 'Umum') ?>
                            </span>
                            <h5 style="font-size:13.5px;font-weight:800;color:#0f172a;margin:0 0 4px;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" title="<?= htmlspecialchars($b['judul']) ?>">
                                <?= htmlspecialchars($b['judul']) ?>
                            </h5>
                            <div style="font-size:11.5px;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <?= htmlspecialchars($b['penulis'] ?: 'Kemendikbud') ?>
                            </div>
                        </div>
                    </div>
                    <a href="index.php?page=buku_baca&id=<?= $b['id'] ?>" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;background-color:#05682b;border-color:#05682b;font-size:12.5px;font-weight:800;padding:8px;">
                        <i data-lucide="eye" style="width:14px;height:14px;"></i> Baca Buku (Online) &rarr;
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Daftar Materi Pelajaran Terkini -->
<div class="card card-white">
    <div class="card-header">
        <div class="card-title">
            <i data-lucide="book-marked" style="color:var(--c-blue);width:20px;height:20px;"></i>
            Materi Pembelajaran Kelas Anda
        </div>
        <span class="badge badge-emerald">Kurikulum Merdeka Fase E</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:18px;">
        <?php if (empty($materiList)): ?>
            <div style="text-align:center;padding:40px;color:#64748b;grid-column:1/-1;">
                <i data-lucide="inbox" style="width:40px;height:40px;color:#94a3b8;margin-bottom:10px;"></i>
                <p style="margin:0;">Belum ada materi pembelajaran yang dibagikan guru ke kelas Anda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($materiList as $m): ?>
                <div class="card card-white" style="margin-bottom:0;display:flex;flex-direction:column;justify-content:space-between;padding:20px;border:1.5px solid var(--border-subtle);border-left:4px solid var(--c-blue);">
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                            <span class="badge badge-blue"><?= htmlspecialchars($m['nama_mapel']) ?></span>
                            <span style="font-size:11.5px;color:#94a3b8;"><?= Helper::formatTanggal($m['created_at'], false) ?></span>
                        </div>
                        <h4 style="font-size:16px;font-weight:800;color:var(--text-primary);margin-bottom:6px;line-height:1.3;">
                            <?= htmlspecialchars($m['judul']) ?>
                        </h4>
                        <div style="font-size:12.5px;color:var(--text-secondary);margin-bottom:16px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="bookmark" style="width:14px;height:14px;color:var(--c-blue);"></i> 
                            <span><b><?= htmlspecialchars($m['bab']) ?></b> &bull; <?= htmlspecialchars($m['nama_guru']) ?></span>
                        </div>
                    </div>
                    <div style="display:flex;gap:8px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border-subtle);">
                        <a href="index.php?page=siswa_materi_detail&id=<?= $m['id'] ?>" class="btn btn-primary btn-sm" style="flex:1;">
                            Baca Materi &rarr;
                        </a>
                        <a href="index.php?page=siswa_ai_tutor&subject_id=<?= $m['subject_id'] ?>&topik=<?= urlencode($m['bab']) ?>" class="btn btn-outline btn-sm" title="Tanya AI tentang materi ini" style="color:var(--c-green-text);background:var(--c-green-light);border-color:var(--c-green-border);">
                            <i data-lucide="bot" style="width:14px;height:14px;"></i> Tanya AI
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
