<?php
$pageTitle = htmlspecialchars($materi['judul']) . " - EduRAG";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div style="max-width:960px;margin:0 auto;">
    <div style="margin-bottom:16px;">
        <a href="index.php?page=siswa_materi" class="btn btn-outline btn-sm"><i data-lucide="arrow-left" style="width:14px;"></i> Kembali ke Materi Pelajaran</a>
    </div>

    <!-- Header Materi (Flat Blue Card) -->
    <div class="card card-blue" style="margin-bottom:20px;">
        <span class="badge badge-secondary" style="background:#fff;color:#1e3a8a;margin-bottom:8px;"><?= htmlspecialchars($materi['nama_mapel']) ?></span>
        <h1 style="font-size:22px;font-weight:800;margin-bottom:6px;"><?= htmlspecialchars($materi['judul']) ?></h1>
        <div style="font-size:13px;opacity:0.9;">
            <b><?= htmlspecialchars($materi['bab']) ?></b> &bull; Pengajar: <?= htmlspecialchars($materi['nama_guru']) ?> &bull; <?= Helper::formatTanggal($materi['created_at']) ?>
        </div>
    </div>

    <!-- Quick AI Tutor Prompt Card for this Material (Flat Emerald Card) -->
    <div class="card card-emerald" style="margin-bottom:24px;padding:16px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <i data-lucide="sparkles" style="width:22px;color:#059669;"></i>
                <div>
                    <b style="font-size:14px;color:#064e3b;">Butuh bantuan memahami materi ini?</b>
                    <div style="font-size:12px;color:#047857;">Tanyakan konsep sulit, minta rangkuman, atau latihan soal ke AI Tutor EduRAG.</div>
                </div>
            </div>
            <a href="index.php?page=siswa_ai_tutor&subject_id=<?= $materi['subject_id'] ?>&topik=<?= urlencode($materi['bab']) ?>" class="btn btn-success btn-sm">
                Buka AI Tutor Materi Ini &rarr;
            </a>
        </div>
    </div>

    <!-- Konten Materi Utama (Clean White Card) -->
    <div class="card card-white" style="padding:32px;font-size:15px;line-height:1.8;">
        <?php if (!empty($materi['youtube_url'])): ?>
            <div style="margin-bottom:24px;border-radius:8px;overflow:hidden;">
                <!-- Embedded Video if URL provided -->
                <p style="font-size:13px;color:#64748b;margin-bottom:6px;">Video Pembelajaran Pendamping:</p>
                <a href="<?= htmlspecialchars($materi['youtube_url']) ?>" target="_blank" class="btn btn-outline btn-sm">
                    <i data-lucide="video" style="width:14px;"></i> Tonton Video di YouTube
                </a>
            </div>
        <?php endif; ?>

        <div class="materi-content" style="color:#1e293b;">
            <?= $materi['konten'] ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
