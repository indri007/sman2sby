<?php
$pageTitle = "Terbitkan Materi Belajar - Guru";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Quill.js Theme Stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow {
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    background-color: #f8fafc;
}
.ql-container.ql-snow {
    border: 1px solid var(--border-color);
    border-top: none;
    border-radius: 0 0 var(--radius-sm) var(--radius-sm);
    font-family: inherit;
    font-size: 15px;
}
.ql-editor {
    min-height: 260px;
    line-height: 1.7;
}
</style>

<div class="card card-white" style="max-width:960px;">
    <div class="card-header">
        <div>
            <div class="card-title">Form Tambah Materi Pembelajaran</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Tuliskan materi pembelajaran dan bagikan ke kelas pilihan Anda.</p>
        </div>
        <a href="index.php?page=guru_materi" class="btn btn-outline btn-sm">&larr; Kembali</a>
    </div>

    <form id="formMateri" action="index.php?page=guru_materi_create" method="POST">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="form-group">
                <label class="form-label">Mata Pelajaran</label>
                <select name="subject_id" class="form-control" required>
                    <?php foreach ($mapelList as $map): ?>
                        <option value="<?= $map['id'] ?>"><?= htmlspecialchars($map['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Bab / Topik Kurikulum Merdeka</label>
                <input type="text" name="bab" class="form-control" placeholder="Contoh: Bab 3 - Sistem Persamaan Linear" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Judul Materi</label>
            <input type="text" name="judul" class="form-control" placeholder="Contoh: Metode Campuran Eliminasi & Substitusi" required>
        </div>

        <div class="form-group">
            <label class="form-label">Bagikan ke Kelas (Dapat Pilih Lebih dari 1)</label>
            <div style="display:flex;gap:16px;flex-wrap:wrap;padding:12px;background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
                <?php foreach ($kelasList as $c): ?>
                    <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="class_ids[]" value="<?= $c['id'] ?>" checked>
                        <span><?= htmlspecialchars($c['nama_kelas']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quill.js Rich Text Editor with Image Upload -->
        <div class="form-group">
            <label class="form-label" style="display:flex;justify-content:space-between;align-items:center;">
                <span>Isi / Konten Materi Pembelajaran</span>
                <span style="font-size:12px;color:#059669;font-weight:500;">
                    <i data-lucide="image" style="width:13px;vertical-align:middle;"></i> Mendukung Format & Upload Gambar
                </span>
            </label>
            <div id="editor-container" style="background:#ffffff;"></div>
            <textarea name="konten" id="kontenInput" style="display:none;" required></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">URL Video Pembelajaran YouTube (Opsional)</label>
            <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
            <a href="index.php?page=guru_materi" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="send" style="width:16px;"></i> Terbitkan Materi
            </button>
        </div>
    </form>
</div>

<!-- Hidden File Input for Quill Image Upload -->
<input type="file" id="quillImageInput" style="display:none;" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif">

<!-- Quill.js Library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi Quill Editor dengan Toolbar Lengkap
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tuliskan materi pembelajaran secara rinci di sini. Anda dapat memformat teks, menambahkan daftar, tabel, rumus, dan mengunggah gambar...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Custom Image Handler untuk Upload Gambar ke Server
    const toolbar = quill.getModule('toolbar');
    const imageInput = document.getElementById('quillImageInput');

    toolbar.addHandler('image', () => {
        imageInput.value = '';
        imageInput.click();
    });

    imageInput.addEventListener('change', async () => {
        if (!imageInput.files || !imageInput.files[0]) return;
        const file = imageInput.files[0];
        const formData = new FormData();
        formData.append('image', file);

        try {
            const res = await fetch('index.php?page=api_upload_image&type=materi', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success && data.url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', data.url);
                quill.setSelection(range.index + 1);
            } else {
                alert('Gagal mengunggah gambar: ' + (data.message || 'Error server'));
            }
        } catch (err) {
            alert('Terjadi kesalahan saat mengunggah: ' + err.message);
        }
    });

    // Sync konten Quill ke input textarea sebelum submit form
    const form = document.getElementById('formMateri');
    form.addEventListener('submit', (e) => {
        const html = quill.root.innerHTML;
        if (quill.getText().trim().length === 0 && !html.includes('<img')) {
            alert('Isi materi pembelajaran tidak boleh kosong!');
            e.preventDefault();
            return;
        }
        document.getElementById('kontenInput').value = html;
    });
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
