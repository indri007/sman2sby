<?php
$pageTitle = "AI Generator Bank Soal - EduRAG";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Info Banner (Flat Blue Card) -->
<div class="card card-blue" style="margin-bottom:24px;">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:6px;">
        <i data-lucide="bot" style="width:18px;vertical-align:middle;"></i> AI Question Bank Generator
    </h3>
    <p style="font-size:13px;line-height:1.6;">
        Hasilkan butir-butir soal Pilihan Ganda atau Essay beserta kunci jawaban dan pembahasan bertahap secara otomatis dengan Gemini AI. Hasil soal dapat Anda review dan simpan langsung ke Bank Soal SMAN 2 Surabaya.
    </p>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <!-- Form Input Generator (Flat Slate Card) -->
    <div class="card card-slate">
        <div class="card-header">
            <div class="card-title">Parameter Soal</div>
        </div>
        <form id="formGenSoal">
            <div class="form-group">
                <label class="form-label">Mata Pelajaran</label>
                <select id="genMapel" class="form-control" required>
                    <?php foreach ($mapelList as $map): ?>
                        <option value="<?= htmlspecialchars($map['nama_mapel']) ?>" data-id="<?= $map['id'] ?>"><?= htmlspecialchars($map['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Bab / Topik Kurikulum Merdeka</label>
                <input type="text" id="genBab" class="form-control" placeholder="Contoh: Sistem Persamaan Linear Tiga Variabel" value="Sistem Persamaan Linear Tiga Variabel" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Soal</label>
                <select id="genTipe" class="form-control">
                    <option value="pg">Pilihan Ganda (5 Opsi A-E)</option>
                    <option value="essay">Essay / Uraian</option>
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Jumlah Soal</label>
                    <input type="number" id="genJumlah" class="form-control" value="5" min="1" max="20" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kesulitan</label>
                    <select id="genKesulitan" class="form-control">
                        <option value="easy">Mudah</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="hard">Sulit</option>
                        <option value="hots">HOTS</option>
                    </select>
                </div>
            </div>

            <button type="submit" id="btnSubmitGen" class="btn btn-primary" style="width:100%;margin-top:10px;">
                <i data-lucide="sparkles" style="width:16px;"></i> Mulai Generate Soal
            </button>
        </form>
    </div>

    <!-- Output Preview (Clean White Card) -->
    <div class="card card-white">
        <div class="card-header">
            <div class="card-title">Hasil Soal AI</div>
            <button id="btnSaveToBank" class="btn btn-success btn-sm" style="display:none;">
                <i data-lucide="save" style="width:14px;"></i> Simpan ke Bank Soal
            </button>
        </div>

        <div id="genStatus" style="display:none;padding:16px;background-color:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;margin-bottom:16px;font-size:13px;color:#1e3a8a;">
            <span class="spinner"></span> Sedang menghasilkan soal dengan Gemini AI... Mohon tunggu beberapa detik.
        </div>

        <div id="genResults" style="min-height:300px;">
            <div style="text-align:center;padding:50px 20px;color:#94a3b8;">
                <i data-lucide="file-question" style="width:48px;height:48px;margin-bottom:12px;opacity:0.6;"></i>
                <p>Pilih parameter di sebelah kiri lalu klik tombol <b>Mulai Generate Soal</b>.</p>
            </div>
        </div>
    </div>
</div>

<script>
let lastGeneratedSoal = [];

document.getElementById('formGenSoal').addEventListener('submit', async (e) => {
    e.preventDefault();
    const mapel = document.getElementById('genMapel').value;
    const bab = document.getElementById('genBab').value;
    const jumlah = parseInt(document.getElementById('genJumlah').value);
    const tipe = document.getElementById('genTipe').value;
    const kesulitan = document.getElementById('genKesulitan').value;

    const btn = document.getElementById('btnSubmitGen');
    const statusBox = document.getElementById('genStatus');
    const resultsBox = document.getElementById('genResults');
    const saveBtn = document.getElementById('btnSaveToBank');

    btn.disabled = true;
    statusBox.style.display = 'block';
    resultsBox.innerHTML = '';
    saveBtn.style.display = 'none';

    try {
        const data = await EduRagAI.generateSoal(mapel, bab, jumlah, tipe, kesulitan);
        statusBox.style.display = 'none';
        btn.disabled = false;

        if (data.success && data.parsed_soal) {
            lastGeneratedSoal = data.parsed_soal;
            renderSoal(data.parsed_soal);
            saveBtn.style.display = 'inline-flex';
        } else {
            resultsBox.innerHTML = `<div class="alert alert-error">${data.message || 'Gagal menghasilkan soal'}</div><pre style="background:#f8fafc;padding:14px;border-radius:6px;font-size:12px;">${data.text || ''}</pre>`;
        }
    } catch (err) {
        statusBox.style.display = 'none';
        btn.disabled = false;
        resultsBox.innerHTML = `<div class="alert alert-error">Terjadi kesalahan: ${err.message}</div>`;
    }
});

function renderSoal(soalList) {
    const container = document.getElementById('genResults');
    let html = '<div style="display:flex;flex-direction:column;gap:18px;">';

    soalList.forEach((s, idx) => {
        const pertanyaan = s.pertanyaan || s.soal || s.teks_soal || '';
        const kunci = s.kunci || s.kunci_jawaban || '';
        const kesulitan = (s.kesulitan || 'sedang').toUpperCase();

        let opsi = [];
        if (Array.isArray(s.opsi)) {
            opsi = s.opsi;
        } else if (typeof s.opsi === 'object' && s.opsi !== null) {
            opsi = Object.entries(s.opsi).map(([lbl, txt]) => ({
                label: lbl,
                teks: txt,
                is_benar: (String(lbl).toUpperCase() === String(kunci).toUpperCase())
            }));
        }

        html += `
        <div class="card card-slate" style="margin-bottom:0;padding:18px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                <b style="font-size:14px;color:#1e293b;">Soal #${idx + 1}</b>
                <span class="badge badge-purple">${kesulitan}</span>
            </div>
            <div style="font-size:14px;margin-bottom:12px;line-height:1.6;color:#0f172a;">${pertanyaan.replace(/\n/g, '<br>')}</div>
        `;

        if (opsi && opsi.length > 0) {
            html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">';
            opsi.forEach(opt => {
                const isKunci = (opt.label === kunci) || opt.is_benar || (String(opt.label).toUpperCase() === String(kunci).toUpperCase());
                html += `
                <div style="padding:6px 12px;border-radius:6px;font-size:13px;background:${isKunci ? '#dcfce7' : '#ffffff'};border:1px solid ${isKunci ? '#86efac' : '#e2e8f0'};">
                    <b>${opt.label}.</b> ${opt.teks} ${isKunci ? '<span style="color:#15803d;font-weight:700;margin-left:4px;">&check; Kunci</span>' : ''}
                </div>`;
            });
            html += '</div>';
        } else {
            html += `<div style="padding:8px 12px;background:#f1f5f9;border-radius:6px;font-size:13px;margin-bottom:12px;"><b>Kunci Jawaban:</b> ${kunci}</div>`;
        }

        if (s.pembahasan) {
            html += `
            <div style="padding:10px 14px;background-color:#fffbeb;border:1px solid #fde68a;border-radius:6px;font-size:12px;color:#92400e;line-height:1.5;">
                <b>Pembahasan:</b> ${s.pembahasan}
            </div>`;
        }

        html += '</div>';
    });

    html += '</div>';
    container.innerHTML = html;
}

document.getElementById('btnSaveToBank').addEventListener('click', async () => {
    if (lastGeneratedSoal.length === 0) return;
    const mapelSelect = document.getElementById('genMapel');
    const subjectId = mapelSelect.options[mapelSelect.selectedIndex].getAttribute('data-id') || 1;
    const bab = document.getElementById('genBab').value;

    const btn = document.getElementById('btnSaveToBank');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    try {
        const res = await EduRagAI.saveSoalToBank(subjectId, bab, lastGeneratedSoal);
        if (res.success) {
            alert(res.message);
            window.location.href = 'index.php?page=guru_bank_soal';
        } else {
            alert('Gagal: ' + res.message);
            btn.disabled = false;
            btn.textContent = 'Simpan ke Bank Soal';
        }
    } catch (e) {
        alert('Terjadi kesalahan: ' + e.message);
        btn.disabled = false;
        btn.textContent = 'Simpan ke Bank Soal';
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
