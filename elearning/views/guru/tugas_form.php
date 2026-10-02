<?php
$pageTitle = "Buat Penugasan Baru - Guru";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";

$defaultDeadline = date("Y-m-d\TH:i", strtotime("+7 days 23:59"));
?>

<div class="card card-white" style="max-width:880px;margin:0 auto;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <div class="card-title">Form Pembuatan Tugas Baru</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Terbitkan tugas mandiri atau kelompok untuk kelas yang diampu.</p>
        </div>
        <a href="index.php?page=guru_tugas" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" style="width:14px;"></i> Kembali ke Daftar Tugas
        </a>
    </div>

    <form action="index.php?page=guru_tugas_create" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Judul Penugasan <span style="color:#ef4444;">*</span></label>
            <input type="text" name="judul" class="form-control" placeholder="Contoh: Proyek Analisis Teks Laporan Hasil Observasi" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    <?php foreach ($mapelList as $m): ?>
                        <option value="<?= $m["id"] ?>"><?= htmlspecialchars($m["nama_mapel"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kelas Sasaran <span style="color:#ef4444;">*</span></label>
                <select name="class_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $c): ?>
                        <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["nama_kelas"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Batas Akhir Pengumpulan (Deadline) <span style="color:#ef4444;">*</span></label>
            <input type="datetime-local" name="deadline" class="form-control" value="<?= $defaultDeadline ?>" required style="max-width:320px;">
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi / Petunjuk Pengerjaan Tugas</label>
            <textarea name="deskripsi" class="form-control" rows="5" placeholder="Tuliskan instruksi langkah demi langkah, kriteria penilaian, dan format file pengumpulan yang diharapkan..."></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="paperclip" style="width:14px;vertical-align:middle;"></i> Unggah File Lampiran / Lembar Soal (Opsional)
            </label>
            <input type="file" name="file_lampiran" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.jpg,.png">
            <small style="color:#64748b;font-size:11px;">Format yang didukung: PDF, Word, Excel, PowerPoint, ZIP, atau Gambar (Maks 20MB).</small>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;padding-top:18px;border-top:1px solid #e2e8f0;">
            <a href="index.php?page=guru_tugas" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="check-circle" style="width:16px;"></i> Terbitkan Tugas ke Kelas
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
