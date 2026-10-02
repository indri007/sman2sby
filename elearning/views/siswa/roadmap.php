<?php
$pageTitle = "AI Learning Roadmap - Siswa";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<!-- Banner (Flat Purple Card, No Gradient) -->
<div class="card card-purple" style="margin-bottom:24px;">
    <h2 style="font-size:18px;font-weight:800;color:#5b21b6;margin-bottom:4px;">
        <i data-lucide="map" style="width:20px;vertical-align:middle;"></i> AI Learning Roadmap Planner
    </h2>
    <p style="font-size:13px;color:#6d28d9;line-height:1.5;">
        Rancang jalur belajar mandiri bertahap selama 7 s/d 14 hari dengan bantuan AI untuk menguasai topik pembelajaran Kurikulum Merdeka secara terarah.
    </p>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <!-- Form Input (Flat Slate Card) -->
    <div class="card card-slate">
        <div class="card-header">
            <div class="card-title">Rencana Belajar Baru</div>
        </div>
        <form id="formRoadmap">
            <div class="form-group">
                <label class="form-label">Mata Pelajaran</label>
                <select id="roadMapel" class="form-control">
                    <?php foreach ($mapelList as $map): ?>
                        <option value="<?= htmlspecialchars($map['nama_mapel']) ?>"><?= htmlspecialchars($map['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Topik yang Ingin Dikuasai</label>
                <input type="text" id="roadTopik" class="form-control" placeholder="Contoh: Barisan dan Deret Aritmetika" value="Barisan dan Deret Aritmetika" required>
            </div>

            <div class="form-group">
                <label class="form-label">Target Waktu (Hari)</label>
                <select id="roadHari" class="form-control">
                    <option value="5">5 Hari</option>
                    <option value="7" selected>7 Hari (1 Minggu)</option>
                    <option value="10">10 Hari</option>
                    <option value="14">14 Hari (2 Minggu)</option>
                </select>
            </div>

            <button type="submit" id="btnSubmitRoadmap" class="btn btn-primary" style="width:100%;margin-top:10px;">
                <i data-lucide="sparkles" style="width:16px;"></i> Buat Jadwal Belajar AI
            </button>
        </form>
    </div>

    <!-- Output Roadmap (Clean White Card) -->
    <div class="card card-white">
        <div class="card-header">
            <div class="card-title">Jadwal Belajar Terstruktur Anda</div>
        </div>

        <div id="roadStatus" style="display:none;padding:16px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:8px;margin-bottom:16px;font-size:13px;color:#5b21b6;">
            <span class="spinner"></span> AI sedang merancang jadwal belajar harian Anda...
        </div>

        <div id="roadResults" style="min-height:300px;">
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <i data-lucide="calendar-check" style="width:48px;height:48px;margin-bottom:12px;opacity:0.6;"></i>
                <p>Pilih topik dan klik <b>Buat Jadwal Belajar AI</b> untuk memvisualisasikan roadmap harian Anda.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formRoadmap').addEventListener('submit', async (e) => {
    e.preventDefault();
    const mapel = document.getElementById('roadMapel').value;
    const topik = document.getElementById('roadTopik').value;
    const targetHari = parseInt(document.getElementById('roadHari').value);

    const btn = document.getElementById('btnSubmitRoadmap');
    const statusBox = document.getElementById('roadStatus');
    const resultsBox = document.getElementById('roadResults');

    btn.disabled = true;
    statusBox.style.display = 'block';
    resultsBox.innerHTML = '';

    try {
        const data = await EduRagAI.generateRoadmap(mapel, topik, targetHari);
        statusBox.style.display = 'none';
        btn.disabled = false;

        if (data.success && data.parsed_roadmap) {
            renderRoadmap(data.parsed_roadmap);
        } else {
            resultsBox.innerHTML = `<div style="white-space:pre-wrap;background:#f8fafc;padding:20px;border-radius:8px;">${data.text || data.message}</div>`;
        }
    } catch (err) {
        statusBox.style.display = 'none';
        btn.disabled = false;
        resultsBox.innerHTML = `<div class="alert alert-error">Terjadi kesalahan: ${err.message}</div>`;
    }
});

function renderRoadmap(r) {
    const container = document.getElementById('roadResults');
    let html = `
    <div style="margin-bottom:20px;padding:16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;">
        <h4 style="font-size:16px;font-weight:700;color:#1e3a8a;">🎯 ${r.topik} (${r.mapel})</h4>
        <p style="font-size:13px;color:#1e40af;margin-top:2px;">Target: Penguasaan komprehensif dalam ${r.total_hari} hari.</p>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px;">
    `;

    if (r.langkah && Array.isArray(r.langkah)) {
        r.langkah.forEach(step => {
            html += `
            <div class="card card-slate" style="margin-bottom:0;padding:16px;border-left:4px solid #2563eb;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <b style="color:#2563eb;font-size:13px;">HARI KE-${step.hari}</b>
                    <span style="font-size:12px;color:#64748b;">${step.target_latihan || ''}</span>
                </div>
                <div style="font-weight:700;color:#0f172a;font-size:14px;margin-bottom:8px;">${step.fokus}</div>
                <ul style="padding-left:18px;font-size:13px;color:#334155;">
            `;
            if (step.aktivitas && Array.isArray(step.aktivitas)) {
                step.aktivitas.forEach(act => {
                    html += `<li>${act}</li>`;
                });
            }
            html += `</ul></div>`;
        });
    }

    html += '</div>';
    container.innerHTML = html;
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
