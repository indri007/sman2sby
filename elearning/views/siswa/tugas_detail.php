<?php
$pageTitle = htmlspecialchars($tugas['judul']) . " - Pengumpulan Tugas";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div style="max-width:850px;margin:0 auto;">
    <div style="margin-bottom:16px;">
        <a href="index.php?page=siswa_tugas" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar Tugas</a>
    </div>

    <!-- Deskripsi Tugas (Clean White Card) -->
    <div class="card card-white" style="margin-bottom:24px;">
        <div class="card-header">
            <div>
                <span class="badge badge-blue" style="margin-bottom:6px;"><?= htmlspecialchars($tugas['nama_mapel']) ?></span>
                <h2 style="font-size:20px;font-weight:800;color:#0f172a;"><?= htmlspecialchars($tugas['judul']) ?></h2>
                <div style="font-size:13px;color:#64748b;margin-top:4px;">
                    Guru: <b><?= htmlspecialchars($tugas['nama_guru']) ?></b> &bull; Batas Waktu: <b style="color:#dc2626;"><?= Helper::formatTanggal($tugas['deadline']) ?></b>
                </div>
            </div>
        </div>
        <div style="font-size:14px;line-height:1.7;color:#334155;padding:10px 0;">
            <?= nl2br(htmlspecialchars($tugas['deskripsi'])) ?>
        </div>
    </div>

    <!-- Status Pengumpulan (Flat Colored Cards) -->
    <?php if ($submission): ?>
        <div class="card card-emerald" style="margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h3 style="font-size:16px;font-weight:700;color:#064e3b;margin-bottom:4px;">
                        &check; Tugas Sudah Anda Kumpulkan
                    </h3>
                    <p style="font-size:13px;color:#047857;">
                        Dikumpulkan pada: <?= Helper::formatTanggal($submission['submitted_at']) ?>
                    </p>
                </div>
                <div>
                    <?php if ($submission['nilai'] !== null): ?>
                        <div style="text-align:right;">
                            <div style="font-size:11px;color:#047857;">NILAI ANDA:</div>
                            <div style="font-size:28px;font-weight:800;color:#064e3b;"><?= $submission['nilai'] ?></div>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-warning">Menunggu Koreksi Guru</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($submission['catatan_siswa'])): ?>
                <div style="margin-top:12px;padding:10px;background:#ffffff;border-radius:6px;font-size:13px;color:#334155;">
                    <b>Catatan Anda:</b> <?= htmlspecialchars($submission['catatan_siswa']) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Form Submit / Edit Jawaban (Flat Slate Card) -->
    <div class="card card-slate">
        <div class="card-header">
            <div class="card-title"><?= $submission ? 'Kirim Ulang / Perbarui Tugas' : 'Form Pengumpulan Tugas' ?></div>
        </div>
        <form action="index.php?page=siswa_tugas_detail&id=<?= $tugas['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Unggah Berkas Jawaban (PDF / Gambar / Word)</label>
                <input type="file" name="file_jawaban" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" <?= $submission ? '' : 'required' ?>>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Tambahan untuk Guru (Opsional)</label>
                <textarea name="catatan_siswa" class="form-control" rows="3" placeholder="Tuliskan keterangan jika ada..."><?= htmlspecialchars($submission['catatan_siswa'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">
                <i data-lucide="upload" style="width:16px;"></i> Simpan & Kumpulkan Tugas
            </button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
