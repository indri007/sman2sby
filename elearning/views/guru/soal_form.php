<?php
$isEdit = !empty($soal);
$pageTitle = $isEdit ? "Edit Butir Soal - Bank Soal Guru" : "Buat Soal Manual - Bank Soal Guru";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";

$currentTipe = $isEdit ? ($soal["tipe"] ?? "pg") : "pg";
$currentSubject = $isEdit ? ($soal["subject_id"] ?? 0) : 0;
$currentBab = $isEdit ? ($soal["bab"] ?? "") : "";
$currentKesulitan = $isEdit ? ($soal["kesulitan"] ?? "medium") : "medium";
$currentKunci = $isEdit ? ($soal["kunci_jawaban"] ?? "A") : "A";

$opsiMap = [];
if ($isEdit && !empty($soal["opsi"])) {
    foreach ($soal["opsi"] as $op) {
        $opsiMap[$op["label"]] = $op;
    }
}
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
    font-size: 14px;
}
.ql-editor {
    min-height: 180px;
    line-height: 1.6;
}
.type-selector-btn {
    flex: 1;
    padding: 12px;
    text-align: center;
    border: 1px solid var(--border-color);
    background: #ffffff;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-muted);
    transition: all 0.15s;
}
.type-selector-btn.active {
    background-color: #eff6ff;
    border-color: #3b82f6;
    color: #1d4ed8;
}
</style>

<div class="card card-white" style="max-width:960px;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <div class="card-title"><?= $isEdit ? "Edit Butir Soal" : "Form Pembuatan Soal Manual" ?></div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">
                <?= $isEdit ? "Perbarui teks soal, gambar, opsi jawaban, atau pembahasan." : "Buat butir soal pilihan ganda atau essay mandiri tanpa menggunakan AI." ?>
            </p>
        </div>
        <a href="index.php?page=guru_bank_soal" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" style="width:14px;"></i> Kembali ke Bank Soal
        </a>
    </div>

    <form id="formSoal" action="<?= $isEdit ? "index.php?page=guru_soal_edit&id=" . $soal["id"] : "index.php?page=guru_soal_create" ?>" method="POST" enctype="multipart/form-data">
        <!-- Parameter Dasar (Grid 3 Kolom) -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;margin-bottom:20px;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                <select name="subject_id" class="form-control" required>
                    <?php foreach ($mapelList as $map): ?>
                        <option value="<?= $map["id"] ?>" <?= $currentSubject == $map["id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($map["nama_mapel"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Bab / Topik <span style="color:#ef4444;">*</span></label>
                <input type="text" name="bab" class="form-control" placeholder="Contoh: Bab 3 - SPLTV" value="<?= htmlspecialchars($currentBab) ?>" required>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tingkat Kesulitan</label>
                <select name="kesulitan" class="form-control">
                    <option value="easy" <?= $currentKesulitan === "easy" ? "selected" : "" ?>>Mudah (Easy)</option>
                    <option value="medium" <?= $currentKesulitan === "medium" ? "selected" : "" ?>>Sedang (Medium)</option>
                    <option value="hard" <?= $currentKesulitan === "hard" ? "selected" : "" ?>>Sulit (Hard)</option>
                    <option value="hots" <?= $currentKesulitan === "hots" ? "selected" : "" ?>>HOTS (Berpikir Kritis)</option>
                </select>
            </div>
        </div>

        <!-- Pemilih Tipe Soal (Flat Tabs) -->
        <div class="form-group">
            <label class="form-label">Pilih Tipe Soal</label>
            <div style="display:flex;gap:12px;">
                <div class="type-selector-btn <?= $currentTipe === "pg" ? "active" : "" ?>" id="btnTypePg" onclick="switchType('pg')">
                    <i data-lucide="check-square" style="width:16px;vertical-align:middle;margin-right:6px;"></i> Pilihan Ganda (PG)
                </div>
                <div class="type-selector-btn <?= $currentTipe === "essay" ? "active" : "" ?>" id="btnTypeEssay" onclick="switchType('essay')">
                    <i data-lucide="edit-3" style="width:16px;vertical-align:middle;margin-right:6px;"></i> Soal Essay / Uraian
                </div>
            </div>
            <input type="hidden" name="tipe" id="tipeInput" value="<?= htmlspecialchars($currentTipe) ?>">
        </div>

        <!-- ============================================== -->
        <!-- PANEL PILIHAN GANDA (PG) -->
        <!-- ============================================== -->
        <div id="panelPg" style="<?= $currentTipe === "essay" ? "display:none;" : "display:block;" ?>">
            <div class="form-group">
                <label class="form-label">Pertanyaan Soal</label>
                <textarea name="pertanyaan_pg" id="pertanyaanPg" class="form-control" rows="4" placeholder="Tuliskan teks pertanyaan soal pilihan ganda di sini..."><?= ($isEdit && $currentTipe === "pg") ? htmlspecialchars($soal["pertanyaan"]) : "" ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i data-lucide="image" style="width:14px;vertical-align:middle;"></i> Unggah Gambar Pendukung Soal (Opsional)
                </label>
                <?php if ($isEdit && !empty($soal["gambar"])): ?>
                    <div style="margin-bottom:8px;">
                        <img src="<?= htmlspecialchars($soal["gambar"]) ?>" alt="Gambar Soal Eksisting" style="max-height:100px;border-radius:6px;border:1px solid #cbd5e1;">
                        <div style="font-size:11px;color:#64748b;">Gambar saat ini. Pilih file baru untuk menggantinya.</div>
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar_soal" class="form-control" accept="image/*">
                <small style="color:#64748b;font-size:11px;">Gunakan jika soal memiliki grafik, diagram, atau tabel visual.</small>
            </div>

            <!-- Opsi Jawaban A s/d E -->
            <div style="margin:20px 0;">
                <label class="form-label" style="margin-bottom:10px;">
                    Opsi Jawaban & Kunci Jawaban <span style="font-size:12px;color:#059669;font-weight:normal;">(Pilih radio pada opsi yang benar)</span>
                </label>

                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php foreach (["A", "B", "C", "D", "E"] as $idx => $label): ?>
                        <?php 
                            $optData = $opsiMap[$label] ?? null;
                            $isChecked = ($isEdit ? ($currentKunci === $label) : ($idx === 0));
                            $optTeks = $optData["teks_opsi"] ?? "";
                            $optGbr = $optData["gambar"] ?? null;
                        ?>
                        <div style="padding:14px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                                <label style="display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:14px;color:#1e293b;cursor:pointer;">
                                    <input type="radio" name="kunci_jawaban_pg" value="<?= $label ?>" <?= $isChecked ? "checked" : "" ?>>
                                    <span>Opsi <?= $label ?> (Kunci)</span>
                                </label>
                            </div>
                            <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;">
                                <input type="text" name="opsi_teks_<?= $label ?>" class="form-control" placeholder="Teks pilihan <?= $label ?>" value="<?= htmlspecialchars($optTeks) ?>" <?= $currentTipe === "pg" ? "required" : "" ?>>
                                <div>
                                    <?php if (!empty($optGbr)): ?>
                                        <div style="margin-bottom:4px;">
                                            <img src="<?= htmlspecialchars($optGbr) ?>" alt="Gambar Opsi <?= $label ?>" style="max-height:40px;border-radius:4px;border:1px solid #cbd5e1;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="opsi_gambar_<?= $label ?>" class="form-control" accept="image/*" title="Upload gambar opsi <?= $label ?> (opsional)">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- PANEL ESSAY / URAIAN (Dengan Quill.js) -->
        <!-- ============================================== -->
        <div id="panelEssay" style="<?= $currentTipe === "essay" ? "display:block;" : "display:none;" ?>">
            <div class="form-group">
                <label class="form-label" style="display:flex;justify-content:space-between;align-items:center;">
                    <span>Pertanyaan Soal Essay</span>
                </label>
                <div id="essay-pertanyaan-editor" style="background:#ffffff;"></div>
                <textarea name="pertanyaan_essay" id="pertanyaanEssayInput" style="display:none;"></textarea>
            </div>

            <div class="form-group" style="margin-top:20px;">
                <label class="form-label" style="display:flex;justify-content:space-between;align-items:center;">
                    <span>Kunci Jawaban / Rubrik Penilaian Essay</span>
                    <span style="font-size:12px;color:#059669;"><i data-lucide="check-circle" style="width:13px;vertical-align:middle;"></i> Poin Kunci & Penilaian</span>
                </label>
                <div id="essay-kunci-editor" style="background:#ffffff;"></div>
                <textarea name="kunci_jawaban_essay" id="kunciEssayInput" style="display:none;"></textarea>
            </div>
        </div>

        <!-- Pembahasan Bersama (Dengan Quill.js) -->
        <div class="form-group" style="margin-top:20px;">
            <label class="form-label" style="display:flex;justify-content:space-between;align-items:center;">
                <span>Langkah Pembahasan Soal (Opsional)</span>
                <span style="font-size:12px;color:#d97706;"><i data-lucide="help-circle" style="width:13px;vertical-align:middle;"></i> Solusi Langkah Demi Langkah & Unggah Gambar</span>
            </label>
            <div id="pembahasan-editor" style="background:#ffffff;min-height:120px;"></div>
            <textarea name="pembahasan" id="pembahasanInput" style="display:none;"></textarea>
        </div>

        <!-- Hidden Global Pertanyaan for Backend -->
        <input type="hidden" name="pertanyaan" id="finalPertanyaan">

        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
            <a href="index.php?page=guru_bank_soal" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:16px;"></i> <?= $isEdit ? "Perbarui Butir Soal" : "Simpan ke Bank Soal" ?>
            </button>
        </div>
    </form>
</div>

<!-- Hidden File Input for Quill Editor Image Upload -->
<input type="file" id="quillEssayImageInput" style="display:none;" accept="image/*">

<!-- Quill.js Library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
let currentQuillInstance = null;
let quillPertanyaan = null;
let quillKunci = null;
let quillPembahasan = null;

const initialSoal = <?= json_encode($isEdit ? $soal : null) ?>;

document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi Quill untuk Pertanyaan Essay
    quillPertanyaan = new Quill('#essay-pertanyaan-editor', {
        theme: 'snow',
        placeholder: 'Tuliskan teks pertanyaan soal essay di sini. Anda dapat menyisipkan rumus, tabel, dan mengunggah gambar...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Inisialisasi Quill untuk Kunci Jawaban Essay
    quillKunci = new Quill('#essay-kunci-editor', {
        theme: 'snow',
        placeholder: 'Tuliskan poin-poin kunci jawaban dan rubrik penilaian di sini...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['code-block', 'link', 'image'],
                ['clean']
            ]
        }
    });

    // Inisialisasi Quill untuk Pembahasan Soal
    quillPembahasan = new Quill('#pembahasan-editor', {
        theme: 'snow',
        placeholder: 'Tuliskan langkah penyelesaian rinci, konsep rumus, atau trik pembahasan di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Prefill data jika mode edit
    if (initialSoal) {
        if (initialSoal.tipe === 'essay') {
            quillPertanyaan.root.innerHTML = initialSoal.pertanyaan || '';
            quillKunci.root.innerHTML = initialSoal.kunci_jawaban || '';
        }
        if (initialSoal.pembahasan) {
            quillPembahasan.root.innerHTML = initialSoal.pembahasan;
        }
    }

    // Image upload handler untuk Quill
    const imageInput = document.getElementById('quillEssayImageInput');

    [quillPertanyaan, quillKunci, quillPembahasan].forEach(qInstance => {
        const toolbar = qInstance.getModule('toolbar');
        toolbar.addHandler('image', () => {
            currentQuillInstance = qInstance;
            imageInput.value = '';
            imageInput.click();
        });
    });

    imageInput.addEventListener('change', async () => {
        if (!imageInput.files || !imageInput.files[0] || !currentQuillInstance) return;
        const file = imageInput.files[0];
        const formData = new FormData();
        formData.append('image', file);

        try {
            const res = await fetch('index.php?page=api_upload_image&type=soal', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success && data.url) {
                const range = currentQuillInstance.getSelection(true);
                currentQuillInstance.insertEmbed(range.index, 'image', data.url);
                currentQuillInstance.setSelection(range.index + 1);
            } else {
                alert('Gagal mengunggah gambar: ' + (data.message || 'Error server'));
            }
        } catch (err) {
            alert('Terjadi kesalahan: ' + err.message);
        }
    });

    // Submit form handler
    document.getElementById('formSoal').addEventListener('submit', (e) => {
        const tipe = document.getElementById('tipeInput').value;
        const finalInput = document.getElementById('finalPertanyaan');

        if (tipe === 'pg') {
            const teksPg = document.getElementById('pertanyaanPg').value.trim();
            if (!teksPg) {
                alert('Teks pertanyaan pilihan ganda wajib diisi!');
                e.preventDefault();
                return;
            }
            finalInput.value = teksPg;
        } else {
            const htmlEssay = quillPertanyaan.root.innerHTML;
            if (quillPertanyaan.getText().trim().length === 0 && !htmlEssay.includes('<img')) {
                alert('Teks pertanyaan essay tidak boleh kosong!');
                e.preventDefault();
                return;
            }
            finalInput.value = htmlEssay;
            document.getElementById('kunciEssayInput').value = quillKunci.root.innerHTML;
        }

        // Simpan konten pembahasan dari Quill ke textarea input
        if (quillPembahasan) {
            const htmlPemb = quillPembahasan.root.innerHTML;
            if (quillPembahasan.getText().trim().length > 0 || htmlPemb.includes('<img')) {
                document.getElementById('pembahasanInput').value = htmlPemb;
            } else {
                document.getElementById('pembahasanInput').value = '';
            }
        }
    });
});

function switchType(type) {
    const btnPg = document.getElementById('btnTypePg');
    const btnEssay = document.getElementById('btnTypeEssay');
    const panelPg = document.getElementById('panelPg');
    const panelEssay = document.getElementById('panelEssay');
    const tipeInput = document.getElementById('tipeInput');

    tipeInput.value = type;

    if (type === 'pg') {
        btnPg.classList.add('active');
        btnEssay.classList.remove('active');
        panelPg.style.display = 'block';
        panelEssay.style.display = 'none';
        document.querySelectorAll('#panelPg input[required]').forEach(el => el.required = true);
    } else {
        btnEssay.classList.add('active');
        btnPg.classList.remove('active');
        panelPg.style.display = 'none';
        panelEssay.style.display = 'block';
        document.querySelectorAll('#panelPg input[required]').forEach(el => el.required = false);
    }
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
