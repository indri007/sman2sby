<?php
$pageTitle = "Perpustakaan Digital - SMAN 2 Surabaya";
$role = Auth::role();
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Header Banner Perpustakaan (Solid Blue Card, No Gradient) -->
<div class="card card-blue" style="margin-bottom:24px;padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:inline-flex;align-items:center;gap:6px;background-color:var(--c-blue);color:#ffffff;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:700;margin-bottom:8px;">
                <i data-lucide="library" style="width:14px;height:14px;"></i> Perpustakaan Online SMAN 2 Surabaya
            </div>
            <h2 style="font-size:22px;font-weight:800;color:var(--c-blue-text);margin-bottom:6px;">
                Perpustakaan Digital & Buku Pelajaran PDF
            </h2>
            <p style="font-size:13.5px;color:var(--c-blue-text);margin:0;opacity:0.9;">
                Koleksi buku kurikulum, modul pengayaan, dan bahan bacaan mandiri siswa kelas 10, 11, dan 12. Mode baca online terproteksi.
            </p>
        </div>
        <?php if (in_array($role, ['ADMIN', 'GURU'])): ?>
            <a href="index.php?page=<?= $role === 'ADMIN' ? 'admin_buku_create' : 'guru_buku_create' ?>" class="btn btn-primary" style="background-color:#05682b;border-color:#05682b;">
                <i data-lucide="plus-circle" style="width:17px;height:17px;"></i> + Terbitkan Buku PDF Baru
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filter & Search Toolbar (Card White) -->
<div class="card card-white" style="margin-bottom:24px;padding:18px 22px;">
    <form method="GET" action="index.php" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin:0;">
        <input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page'] ?? 'siswa_buku') ?>">

        <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:260px;">
            <div style="position:relative;flex:1;">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Cari judul buku, penulis, atau penerbit..." style="padding-left:38px;height:42px;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;">
                    <i data-lucide="search" style="width:16px;height:16px;"></i>
                </span>
            </div>
            <?php if (!empty($_GET['q']) || !empty($_GET['subject_id'])): ?>
                <a href="index.php?page=<?= htmlspecialchars($_GET['page'] ?? 'siswa_buku') ?>" class="btn btn-outline btn-sm" title="Reset Pencarian">
                    &times; Reset
                </a>
            <?php endif; ?>
        </div>

        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <label style="font-size:13px;font-weight:700;color:#475569;margin:0;">Mata Pelajaran:</label>
            <select name="subject_id" class="form-control" style="width:auto;height:42px;font-size:13.5px;" onchange="this.form.submit()">
                <option value="">-- Semua Mata Pelajaran --</option>
                <?php foreach ($mapelList as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= (($subjectId ?? '') == $m['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nama_mapel']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary" style="height:42px;padding:0 18px;">
                Terapkan
            </button>
        </div>
    </form>
</div>

<!-- Header Info Hasil Pencarian -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;padding:0 4px;">
    <div style="font-size:14px;font-weight:700;color:#334155;">
        Daftar Buku Digital (<?= $totalBuku ?> Judul Tersedia)
    </div>
    <span style="font-size:12.5px;color:#64748b;">
        <i data-lucide="shield-check" style="width:14px;height:14px;vertical-align:middle;color:#059669;"></i> Mode Baca Terproteksi (Read-Only)
    </span>
</div>

<!-- Grid Kartu Koleksi Buku -->
<?php if (empty($bukuList)): ?>
    <div class="card card-white" style="text-align:center;padding:60px 20px;">
        <div style="width:64px;height:64px;background:#eff6ff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;color:#2563eb;">
            <i data-lucide="book-open" style="width:32px;height:32px;"></i>
        </div>
        <h3 style="font-size:18px;font-weight:800;color:#0f172a;margin-bottom:6px;">Belum Ada Buku Ditemukan</h3>
        <p style="font-size:13.5px;color:#64748b;max-width:420px;margin:0 auto 18px;">
            Tidak ada buku pelajaran yang cocok dengan filter atau kata kunci pencarian Anda.
        </p>
        <a href="index.php?page=<?= htmlspecialchars($_GET['page'] ?? 'siswa_buku') ?>" class="btn btn-outline btn-sm">
            Lihat Semua Koleksi Buku
        </a>
    </div>
<?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:22px;margin-bottom:28px;">
        <?php foreach ($bukuList as $b): ?>
            <div class="card card-white" style="display:flex;flex-direction:column;padding:0;overflow:hidden;border:1.5px solid var(--border-subtle);transition:transform 0.18s ease, box-shadow 0.18s ease;">
                <!-- Cover Area -->
                <div style="position:relative;height:190px;background-color:#f1f5f9;display:flex;align-items:center;justify-content:center;border-bottom:1px solid #e2e8f0;overflow:hidden;">
                    <?php if (!empty($b['cover_image']) && file_exists(__DIR__ . '/../../uploads/buku/covers/' . $b['cover_image'])): ?>
                        <img src="uploads/buku/covers/<?= htmlspecialchars($b['cover_image']) ?>" alt="Cover <?= htmlspecialchars($b['judul']) ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <!-- Fallback Visual Stylized Book Spine -->
                        <div style="width:115px;height:155px;background-color:#05682b;border-radius:4px;box-shadow:3px 4px 12px rgba(0,0,0,0.18);display:flex;flex-direction:column;justify-content:space-between;padding:12px 10px;text-align:center;border-left:5px solid #045222;">
                            <div style="font-size:9px;font-weight:800;color:#86efac;text-transform:uppercase;letter-spacing:0.5px;">SMAN 2 SBY</div>
                            <div style="color:#ffffff;">
                                <i data-lucide="book-open" style="width:28px;height:28px;margin:0 auto 4px;"></i>
                                <div style="font-size:10px;font-weight:700;line-height:1.2;color:#ffffff;overflow:hidden;max-height:36px;">
                                    <?= htmlspecialchars($b['nama_mapel'] ?? 'Buku Siswa') ?>
                                </div>
                            </div>
                            <div style="font-size:9px;color:rgba(255,255,255,0.7);font-weight:600;"><?= htmlspecialchars($b['kelas']) ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Badges on top of cover -->
                    <div style="position:absolute;top:10px;left:10px;display:flex;gap:6px;flex-wrap:wrap;">
                        <span class="badge badge-purple" style="font-size:11px;font-weight:800;padding:4px 8px;box-shadow:0 2px 4px rgba(0,0,0,0.12);">
                            <?= htmlspecialchars($b['nama_mapel'] ?? 'Umum') ?>
                        </span>
                    </div>
                    <div style="position:absolute;top:10px;right:10px;">
                        <span class="badge badge-blue" style="font-size:10.5px;font-weight:800;padding:3px 7px;">
                            <?= htmlspecialchars($b['kelas']) ?>
                        </span>
                    </div>
                </div>

                <!-- Content Info Area -->
                <div style="padding:18px;flex:1;display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <h3 style="font-size:15px;font-weight:800;color:#0f172a;margin:0 0 6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" title="<?= htmlspecialchars($b['judul']) ?>">
                            <?= htmlspecialchars($b['judul']) ?>
                        </h3>

                        <div style="font-size:12.5px;color:#64748b;margin-bottom:8px;display:flex;align-items:center;gap:5px;">
                            <i data-lucide="user" style="width:14px;height:14px;color:#94a3b8;flex-shrink:0;"></i>
                            <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?= htmlspecialchars($b['penulis'] ?: 'Penulis Tim Kemendikbud') ?>">
                                <?= htmlspecialchars($b['penulis'] ?: 'Kemendikbud') ?>
                            </span>
                        </div>

                        <?php if (!empty($b['deskripsi'])): ?>
                            <p style="font-size:12.5px;color:#475569;margin:0 0 12px;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                <?= htmlspecialchars($b['deskripsi']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Actions & Stats -->
                    <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:11.5px;color:#94a3b8;">
                            <span><i data-lucide="eye" style="width:13px;height:13px;vertical-align:middle;color:#2563eb;"></i> <?= $b['total_views'] ?> kali dibaca</span>
                            <span><?= htmlspecialchars($b['tahun_terbit'] ?: date('Y')) ?></span>
                        </div>

                        <!-- Main Action: Baca Online -->
                        <a href="index.php?page=buku_baca&id=<?= $b['id'] ?>" class="btn btn-primary" style="width:100%;justify-content:center;padding:9px;font-size:13.5px;font-weight:800;background-color:#05682b;border-color:#05682b;">
                            <i data-lucide="book-open" style="width:16px;height:16px;"></i> Baca Online (Read-Only) &rarr;
                        </a>

                        <!-- Management Buttons for Guru/Admin -->
                        <?php if (in_array($role, ['ADMIN', 'GURU'])): ?>
                            <div style="display:flex;gap:6px;margin-top:2px;">
                                <a href="index.php?page=<?= $role === 'ADMIN' ? 'admin_buku_edit' : 'guru_buku_edit' ?>&id=<?= $b['id'] ?>" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;padding:6px;font-size:12px;">
                                    <i data-lucide="edit" style="width:13px;height:13px;"></i> Edit
                                </a>
                                <form method="POST" action="index.php?page=<?= $role === 'ADMIN' ? 'admin_buku_delete' : 'guru_buku_delete' ?>" style="flex:1;margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku <?= htmlspecialchars(addslashes($b['judul'])) ?>?');">
                                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;padding:6px;font-size:12px;">
                                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Paginasi Standar -->
    <?= Helper::renderPagination($totalBuku, $perPage, $page, $role === 'SISWA' ? 'siswa_buku' : ($role === 'ADMIN' ? 'admin_buku' : 'guru_buku'), ['subject_id' => $subjectId ?? '', 'q' => $search]) ?>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
