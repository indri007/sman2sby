<?php
$pageTitle = "AI Modul Ajar / RPP Kurikulum Merdeka - EduRAG";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Info Banner (Flat Emerald Card) -->
<div class="card card-emerald" style="margin-bottom:24px;">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:6px;">
        <i data-lucide="file-signature" style="width:18px;vertical-align:middle;"></i> Generator Modul Ajar / RPP Kurikulum Merdeka
    </h3>
    <p style="font-size:13px;line-height:1.6;">
        Bantu beban administrasi guru dengan menyusun otomatis draft Modul Ajar lengkap: Capaian Pembelajaran (CP), Tujuan Pembelajaran (TP), Pertanyaan Pemantik, Sintaks Kegiatan Pembelajaran, serta Rubrik Asesmen.
    </p>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <!-- Parameter RPP (Flat Slate Card) -->
    <div class="card card-slate">
        <div class="card-header">
            <div class="card-title">Parameter Modul Ajar</div>
        </div>
        <form id="formRpp">
            <div class="form-group">
                <label class="form-label">Mata Pelajaran</label>
                <select id="rppMapel" class="form-control" required>
                    <?php foreach ($mapelList as $map): ?>
                        <option value="<?= htmlspecialchars($map['nama_mapel']) ?>"><?= htmlspecialchars($map['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Topik / Bab</label>
                <input type="text" id="rppBab" class="form-control" placeholder="Contoh: Eksponen dan Logaritma" value="Eksponen dan Logaritma" required>
            </div>

            <div class="form-group">
                <label class="form-label">Alokasi Waktu</label>
                <input type="text" id="rppWaktu" class="form-control" value="2 x 45 Menit (1 Pertemuan)" required>
            </div>

            <button type="submit" id="btnSubmitRpp" class="btn btn-success" style="width:100%;margin-top:10px;">
                <i data-lucide="sparkles" style="width:16px;"></i> Generate Modul Ajar
            </button>
        </form>
    </div>

    <!-- Hasil RPP (Clean White Card) -->
    <div class="card card-white">
        <div class="card-header">
            <div class="card-title">Draf Modul Ajar Kurikulum Merdeka</div>
            <div style="display:flex;gap:8px;">
                <button id="btnPrintRpp" class="btn btn-outline btn-sm" onclick="window.print();" style="display:none;">
                    <i data-lucide="printer" style="width:14px;"></i> Cetak / PDF
                </button>
            </div>
        </div>

        <div id="rppStatus" style="display:none;padding:16px;background-color:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;margin-bottom:16px;font-size:13px;color:#064e3b;">
            <span class="spinner"></span> Sedang menyusun draf RPP dengan Gemini AI... Mohon tunggu.
        </div>

        <div id="rppResults" style="min-height:350px;font-size:14px;line-height:1.7;color:#1e293b;">
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <i data-lucide="file-text" style="width:48px;height:48px;margin-bottom:12px;opacity:0.6;"></i>
                <p>Isi parameter di kiri dan klik <b>Generate Modul Ajar</b> untuk menyusun RPP otomatis.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formRpp').addEventListener('submit', async (e) => {
    e.preventDefault();
    const mapel = document.getElementById('rppMapel').value;
    const bab = document.getElementById('rppBab').value;
    const waktu = document.getElementById('rppWaktu').value;

    const btn = document.getElementById('btnSubmitRpp');
    const statusBox = document.getElementById('rppStatus');
    const resultsBox = document.getElementById('rppResults');
    const printBtn = document.getElementById('btnPrintRpp');

    btn.disabled = true;
    statusBox.style.display = 'block';
    resultsBox.innerHTML = '';
    printBtn.style.display = 'none';

    try {
        const data = await EduRagAI.generateRpp(mapel, bab, waktu);
        statusBox.style.display = 'none';
        btn.disabled = false;

        if (data.success) {
            resultsBox.innerHTML = `<div style="white-space:pre-wrap;background:#f8fafc;padding:24px;border-radius:8px;border:1px solid #e2e8f0;font-family:inherit;">${data.text}</div>`;
            printBtn.style.display = 'inline-flex';
        } else {
            resultsBox.innerHTML = `<div class="alert alert-error">${data.message || 'Gagal menyusun RPP'}</div>`;
        }
    } catch (err) {
        statusBox.style.display = 'none';
        btn.disabled = false;
        resultsBox.innerHTML = `<div class="alert alert-error">Terjadi kesalahan: ${err.message}</div>`;
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
