<?php
$role = Auth::role();
$isEdit = !empty($buku);
$actionUrl = $isEdit ? 'index.php?page=guru_buku_update' : 'index.php?page=guru_buku_store';
if ($role === 'ADMIN') {
    $actionUrl = $isEdit ? 'index.php?page=admin_buku_update' : 'index.php?page=admin_buku_store';
}
$backUrl = $role === 'ADMIN' ? 'index.php?page=admin_buku' : 'index.php?page=guru_buku';

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div style="max-width:850px;margin:0 auto;">
    <!-- Breadcrumb / Back Button -->
    <div style="margin-bottom:18px;">
        <a href="<?= $backUrl ?>" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> &larr; Kembali ke Katalog Perpustakaan
        </a>
    </div>

    <div class="card card-white" style="border-top:5px solid #05682b;">
        <div class="card-header">
            <div>
                <div class="card-title" style="font-size:18px;">
                    <i data-lucide="<?= $isEdit ? 'edit' : 'plus-circle' ?>" style="color:#05682b;width:22px;height:22px;"></i>
                    <?= $isEdit ? 'Edit Data Buku Perpustakaan' : 'Terbitkan Buku Pelajaran PDF Baru' ?>
                </div>
                <p style="font-size:13px;color:#64748b;margin-top:2px;">
                    Unggah modul teks digital atau buku kurikulum untuk dibaca siswa secara online tanpa opsi unduh.
                </p>
            </div>
            <span class="badge badge-emerald">Perpus Digital SMAN 2</span>
        </div>

        <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $buku['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="judul">
                    Judul Lengkap Buku <span style="color:#e11d48;">*</span>
                </label>
                <input type="text" id="judul" name="judul" class="form-control" placeholder="Contoh: Buku Siswa Matematika SMA/SMK Kelas X" value="<?= htmlspecialchars($buku['judul'] ?? '') ?>" required autofocus>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label" for="penulis">Nama Penulis / Penyusun</label>
                    <input type="text" id="penulis" name="penulis" class="form-control" placeholder="Contoh: Dick Susanto, dkk." value="<?= htmlspecialchars($buku['penulis'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="penerbit">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" class="form-control" placeholder="Contoh: Pusat Perbukuan Kemendikbudristek" value="<?= htmlspecialchars($buku['penerbit'] ?? '') ?>">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label" for="subject_id">Mata Pelajaran Terkait</label>
                    <select id="subject_id" name="subject_id" class="form-control">
                        <option value="">-- Umum / Referensi --</option>
                        <?php foreach ($mapelList as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= (($buku['subject_id'] ?? '') == $m['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nama_mapel']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kelas">Tingkat Kelas</label>
                    <select id="kelas" name="kelas" class="form-control">
                        <option value="Kelas X" <?= (($buku['kelas'] ?? '') === 'Kelas X') ? 'selected' : '' ?>>Kelas X (Fase E)</option>
                        <option value="Kelas XI" <?= (($buku['kelas'] ?? '') === 'Kelas XI') ? 'selected' : '' ?>>Kelas XI (Fase F)</option>
                        <option value="Kelas XII" <?= (($buku['kelas'] ?? '') === 'Kelas XII') ? 'selected' : '' ?>>Kelas XII</option>
                        <option value="Semua Kelas" <?= (($buku['kelas'] ?? '') === 'Semua Kelas') ? 'selected' : '' ?>>Semua Jenjang / Umum</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tahun_terbit">Tahun Terbit</label>
                    <input type="text" id="tahun_terbit" name="tahun_terbit" class="form-control" placeholder="2022" value="<?= htmlspecialchars($buku['tahun_terbit'] ?? date('Y')) ?>">
                </div>
            </div>

            <!-- Upload File PDF -->
            <div class="form-group" style="background:#f8fafc;padding:18px;border-radius:10px;border:1.5px dashed #cbd5e1;margin-bottom:20px;">
                <label class="form-label" for="file_pdf" style="font-size:14px;color:#0f172a;">
                    <i data-lucide="file-text" style="width:17px;height:17px;vertical-align:middle;color:#05682b;"></i>
                    Berkas Dokumen PDF Buku <?= $isEdit ? '(Biarkan kosong jika tidak diganti)' : '<span style="color:#e11d48;">*</span>' ?>
                </label>
                <p style="font-size:12.5px;color:#64748b;margin-bottom:10px;">
                    Format berkas <b>.pdf</b>. Siswa hanya dapat membaca online di aplikasi tanpa tombol unduh langsung.
                </p>
                <input type="file" id="file_pdf" name="file_pdf" accept="application/pdf" class="form-control" <?= $isEdit ? '' : 'required' ?>>

                <?php if ($isEdit && !empty($buku['file_pdf'])): ?>
                    <div style="font-size:12.5px;color:#059669;margin-top:8px;display:flex;align-items:center;gap:6px;">
                        <i data-lucide="check" style="width:14px;height:14px;"></i>
                        File saat ini tersimpan: <code><?= htmlspecialchars($buku['file_pdf']) ?></code>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Upload Cover Buku -->
            <div class="form-group" style="background:#f8fafc;padding:18px;border-radius:10px;border:1.5px dashed #cbd5e1;margin-bottom:20px;">
                <label class="form-label" for="cover_image" style="font-size:14px;color:#0f172a;">
                    <i data-lucide="image" style="width:17px;height:17px;vertical-align:middle;color:#2563eb;"></i>
                    Gambar Sampul / Cover Buku (Opsional)
                </label>
                <p style="font-size:12.5px;color:#64748b;margin-bottom:10px;">
                    Format <b>.jpg, .png, .webp</b>. Jika tidak diunggah, sistem akan membuatkan visual sampul otomatis.
                </p>
                <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-control">

                <?php if ($isEdit && !empty($buku['cover_image']) && file_exists(__DIR__ . '/../../uploads/buku/covers/' . $buku['cover_image'])): ?>
                    <div style="margin-top:12px;display:flex;align-items:center;gap:12px;">
                        <img src="uploads/buku/covers/<?= htmlspecialchars($buku['cover_image']) ?>" style="width:60px;height:80px;object-fit:cover;border-radius:4px;border:1px solid #cbd5e1;">
                        <span style="font-size:12.5px;color:#64748b;">Cover saat ini aktif</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="deskripsi">Deskripsi / Sinopsis & Daftar Bab</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" placeholder="Ringkasan isi buku, capaian pembelajaran, atau daftar bab materi..."><?= htmlspecialchars($buku['deskripsi'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:26px;padding-top:18px;border-top:1px solid #e2e8f0;">
                <a href="<?= $backUrl ?>" class="btn btn-outline" style="padding:10px 20px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding:10px 24px;font-weight:800;background-color:#05682b;border-color:#05682b;">
                    <i data-lucide="save" style="width:16px;height:16px;"></i> <?= $isEdit ? 'Simpan Perubahan Buku' : 'Terbitkan Buku Sekarang' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
