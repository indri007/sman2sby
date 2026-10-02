<?php
$pageTitle = "AI Ringkas / Buat Materi - EduRAG";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div class="card card-purple" style="margin-bottom:24px;">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:6px;">
        <i data-lucide="book-marked" style="width:18px;vertical-align:middle;"></i> AI Materi Generator
    </h3>
    <p style="font-size:13px;line-height:1.6;">
        Buat bahan ajar terstruktur dari buku teks Kurikulum Merdeka. Anda dapat menyalin hasil ringkasan ke form penerbitan materi dan membagikannya ke kelas binaan.
    </p>
</div>

<div class="card card-white">
    <div class="card-header">
        <div class="card-title">Generator Materi Terstruktur</div>
    </div>
    <div style="text-align:center;padding:40px;color:#64748b;">
        <p style="margin-bottom:16px;">Gunakan generator ini untuk membuat draft bahan ajar baru dengan cepat.</p>
        <a href="index.php?page=guru_materi_create" class="btn btn-primary">
            <i data-lucide="edit" style="width:16px;"></i> Tulis Materi Sekarang
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
