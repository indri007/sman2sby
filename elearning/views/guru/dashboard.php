<?php
$pageTitle = "Dashboard Guru - SMAN 2 Surabaya";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Solid Colored Stat Cards (No Gradients) -->
<div class="stat-grid">
    <div class="stat-card card-blue">
        <div class="stat-header">
            <div>
                <div class="stat-label">Kelas Diampu</div>
                <div class="stat-val"><?= count($kelasList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="school"></i>
            </div>
        </div>
        <div class="stat-sub">Rombel aktif mengajar</div>
    </div>

    <div class="stat-card card-emerald">
        <div class="stat-header">
            <div>
                <div class="stat-label">Materi Terbit</div>
                <div class="stat-val"><?= count($materiList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="file-text"></i>
            </div>
        </div>
        <div class="stat-sub">Bahan ajar kelas 10</div>
    </div>

    <div class="stat-card card-purple">
        <div class="stat-header">
            <div>
                <div class="stat-label">Tugas Diberikan</div>
                <div class="stat-val"><?= count($tugasList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="clipboard-check"></i>
            </div>
        </div>
        <div class="stat-sub">Penugasan siswa</div>
    </div>

    <div class="stat-card card-amber">
        <div class="stat-header">
            <div>
                <div class="stat-label">Ujian / Kuis</div>
                <div class="stat-val"><?= count($ujianList) ?></div>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="timer"></i>
            </div>
        </div>
        <div class="stat-sub">Asesmen aktif</div>
    </div>

    <div class="stat-card" style="border-left:4px solid #05682b;">
        <div class="stat-header">
            <div>
                <div class="stat-label">Perpus Digital</div>
                <div class="stat-val"><?= $totalBuku ?></div>
            </div>
            <div class="stat-icon-wrapper" style="background-color:rgba(5, 104, 43, 0.1);color:#05682b;">
                <i data-lucide="library"></i>
            </div>
        </div>
        <div class="stat-sub">Buku PDF (Cuman Baca)</div>
    </div>
</div>

<!-- Perpustakaan Digital / Buku Pelajaran PDF (Cuman Baca) -->
<div class="card card-white" style="margin-bottom:28px;">
    <div class="card-header" style="flex-wrap:wrap;gap:10px;">
        <div class="card-title">
            <i data-lucide="library" style="color:#05682b;width:20px;height:20px;"></i>
            Perpustakaan Digital / Buku Pelajaran PDF
            <span class="badge badge-success" style="font-size:11px;background-color:#05682b;color:#fff;margin-left:6px;">
                Mode Cuman Baca
            </span>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="index.php?page=guru_buku_create" class="btn btn-primary btn-sm" style="background-color:#05682b;border-color:#05682b;">
                <i data-lucide="plus" style="width:14px;height:14px;"></i> Terbitkan Buku PDF
            </a>
            <a href="index.php?page=guru_buku" class="btn btn-outline btn-sm">
                Lihat Semua (<?= $totalBuku ?>) &rarr;
            </a>
        </div>
    </div>

    <?php if (empty($recentBuku)): ?>
        <div style="text-align:center;padding:32px;color:#64748b;">
            <i data-lucide="book-open" style="width:36px;height:36px;color:#94a3b8;margin-bottom:8px;"></i>
            <p style="margin:0 0 10px;">Belum ada buku pelajaran digital yang diterbitkan.</p>
            <a href="index.php?page=guru_buku_create" class="btn btn-primary btn-sm" style="background-color:#05682b;border-color:#05682b;">
                + Terbitkan Buku Sekarang
            </a>
        </div>
    <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;">
            <?php foreach ($recentBuku as $b): ?>
                <div style="border:1.5px solid var(--border-subtle);border-radius:8px;padding:14px;display:flex;flex-direction:column;justify-content:space-between;background-color:#ffffff;">
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

<!-- Quick AI Assistant Action Cards (Solid Flat Colors, No Gradients) -->
<div style="margin-bottom:28px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <span style="display:inline-flex;padding:6px;background-color:#eff6ff;color:#2563eb;border-radius:8px;">
            <i data-lucide="sparkles" style="width:20px;height:20px;"></i>
        </span>
        <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin:0;">
            Asisten AI Pendidik Pintar
        </h3>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:18px;">
        <!-- Card 1: Generate Soal (Solid Blue Card) -->
        <div class="card card-blue" style="margin-bottom:0;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <span style="background-color:#2563eb;color:#ffffff;padding:6px;border-radius:8px;display:inline-flex;">
                        <i data-lucide="bot" style="width:18px;height:18px;"></i>
                    </span>
                    <h4 style="font-size:16px;font-weight:800;color:#1e3a8a;">AI Generator Bank Soal</h4>
                </div>
                <p style="font-size:13px;line-height:1.6;color:#1e40af;margin:0;">
                    Otomatisasi pembuatan butir soal Pilihan Ganda & Essay per bab lengkap dengan opsi, kunci, dan pembahasan rinci.
                </p>
            </div>
            <a href="index.php?page=guru_ai_soal" class="btn btn-primary btn-sm" style="margin-top:16px;align-self:flex-start;">
                Buka Generator Soal &rarr;
            </a>
        </div>

        <!-- Card 2: Generate RPP (Solid Emerald Card) -->
        <div class="card card-emerald" style="margin-bottom:0;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <span style="background-color:#059669;color:#ffffff;padding:6px;border-radius:8px;display:inline-flex;">
                        <i data-lucide="file-signature" style="width:18px;height:18px;"></i>
                    </span>
                    <h4 style="font-size:16px;font-weight:800;color:#065f46;">AI Modul Ajar / RPP</h4>
                </div>
                <p style="font-size:13px;line-height:1.6;color:#047857;margin:0;">
                    Susun draft RPP Kurikulum Merdeka (Tujuan Pembelajaran, Kegiatan Inti, Asesmen) selaras Capaian Pembelajaran.
                </p>
            </div>
            <a href="index.php?page=guru_ai_rpp" class="btn btn-success btn-sm" style="margin-top:16px;align-self:flex-start;">
                Buka Generator RPP &rarr;
            </a>
        </div>

        <!-- Card 3: Generate Materi (Solid Purple Card) -->
        <div class="card card-purple" style="margin-bottom:0;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <span style="background-color:#7c3aed;color:#ffffff;padding:6px;border-radius:8px;display:inline-flex;">
                        <i data-lucide="book-marked" style="width:18px;height:18px;"></i>
                    </span>
                    <h4 style="font-size:16px;font-weight:800;color:#5b21b6;">AI Ringkasan Materi</h4>
                </div>
                <p style="font-size:13px;line-height:1.6;color:#6d28d9;margin:0;">
                    Ekstrak dan rangkum materi ajar dari silabus resmi untuk langsung dibagikan ke siswa dalam hitungan detik.
                </p>
            </div>
            <a href="index.php?page=guru_ai_materi" class="btn btn-purple btn-sm" style="margin-top:16px;align-self:flex-start;">
                Buka Generator Materi &rarr;
            </a>
        </div>
    </div>
</div>

<!-- Materi Terbaru yang Dibuat Guru -->
<div class="card card-white">
    <div class="card-header">
        <div class="card-title">
            <i data-lucide="book-open" style="color:var(--c-blue);width:20px;height:20px;"></i>
            Materi Pembelajaran Terbitan Saya
        </div>
        <a href="index.php?page=guru_materi_create" class="btn btn-primary btn-sm">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Tambah Materi Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Judul Materi</th>
                    <th>Bab</th>
                    <th>Kelas Terbagi</th>
                    <th>Tanggal Terbit</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($materiList)): ?>
                    <tr><td colspan="5" style="text-align:center;color:#64748b;padding:30px;">Belum ada materi yang diterbitkan.</td></tr>
                <?php else: ?>
                    <?php foreach (array_slice($materiList, 0, 5) as $m): ?>
                        <tr>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($m['nama_mapel']) ?></span></td>
                            <td><b style="color:var(--text-primary);"><?= htmlspecialchars($m['judul']) ?></b></td>
                            <td style="color:#64748b;font-weight:600;"><?= htmlspecialchars($m['bab']) ?></td>
                            <td><span class="badge badge-secondary"><?= htmlspecialchars($m['kelas_terbagi'] ?? 'Semua') ?></span></td>
                            <td style="font-size:12.5px;color:#64748b;"><?= Helper::formatTanggal($m['created_at'], false) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
