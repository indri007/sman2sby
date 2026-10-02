<?php
$pageTitle = "Hasil Ujian: " . htmlspecialchars($hasil['judul']);
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div style="max-width:700px;margin:0 auto;text-align:center;">
    <!-- Score Card (Flat Colored Card, No Gradient) -->
    <?php $isLulus = $hasil['nilai_akhir'] >= $hasil['kkm']; ?>
    <div class="card <?= $isLulus ? 'card-emerald' : 'card-rose' ?>" style="padding:40px 24px;margin-bottom:24px;">
        <span class="badge badge-secondary" style="background:#fff;margin-bottom:12px;"><?= htmlspecialchars($hasil['nama_mapel']) ?></span>
        <h2 style="font-size:22px;font-weight:800;margin-bottom:6px;"><?= htmlspecialchars($hasil['judul']) ?></h2>
        
        <div style="margin:24px 0;">
            <div style="font-size:13px;letter-spacing:1px;text-transform:uppercase;font-weight:700;opacity:0.8;">NILAI AKHIR ANDA</div>
            <div style="font-size:64px;font-weight:900;line-height:1;margin:8px 0;"><?= $hasil['nilai_akhir'] ?></div>
            <span class="badge <?= $isLulus ? 'badge-success' : 'badge-danger' ?>" style="font-size:14px;padding:6px 16px;">
                <?= $isLulus ? 'LULUS (Di Atas KKM)' : 'REMEDIAL (Di Bawah KKM)' ?>
            </span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-top:24px;background:#ffffff;padding:16px;border-radius:10px;border:1px solid #e2e8f0;color:#0f172a;">
            <div>
                <div style="font-size:11px;color:#64748b;">Benar</div>
                <div style="font-size:20px;font-weight:800;color:#15803d;"><?= $hasil['total_benar'] ?></div>
            </div>
            <div>
                <div style="font-size:11px;color:#64748b;">Salah</div>
                <div style="font-size:20px;font-weight:800;color:#b91c1c;"><?= $hasil['total_salah'] ?></div>
            </div>
            <div>
                <div style="font-size:11px;color:#64748b;">KKM</div>
                <div style="font-size:20px;font-weight:800;color:#1e293b;"><?= $hasil['kkm'] ?></div>
            </div>
        </div>
    </div>

    <!-- AI Adaptive Recommendation Card (Flat Blue Card) -->
    <div class="card card-blue" style="text-align:left;padding:24px;">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:6px;">
            <i data-lucide="bot" style="width:18px;vertical-align:middle;"></i> Rekomendasi Belajar AI Adaptif
        </h3>
        <p style="font-size:13px;line-height:1.6;margin-bottom:16px;">
            <?= $isLulus ? 'Performa Anda sangat baik! Lanjutkan pemahaman Anda dengan materi pengayaan.' : 'Jangan berkecil hati. Buka AI Tutor untuk mendiskusikan kembali bab ini atau minta roadmap belajar perbaikan.' ?>
        </p>
        <div style="display:flex;gap:12px;">
            <a href="index.php?page=siswa_ai_tutor" class="btn btn-primary btn-sm">
                Buka AI Tutor
            </a>
            <a href="index.php?page=siswa_roadmap" class="btn btn-outline btn-sm">
                Lihat Roadmap Belajar
            </a>
        </div>
    </div>

    <a href="index.php?page=siswa_ujian" class="btn btn-outline">&larr; Kembali ke Daftar Ujian</a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
