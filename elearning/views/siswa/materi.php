<?php
$pageTitle = "Materi Pembelajaran - Siswa";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";

$selectedSub = isset($_GET["subject_id"]) ? (int)$_GET["subject_id"] : 0;
?>

<!-- Header Section (Solid Blue Card, No Gradient) -->
<div class="card card-blue" style="margin-bottom:24px;padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:#ffffff;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:700;margin-bottom:8px;">
                <i data-lucide="book-open" style="width:14px;height:14px;"></i> Kurikulum Merdeka Fase E
            </div>
            <h1 style="font-size:22px;font-weight:800;color:#1e3a8a;margin-bottom:4px;">
                Materi Pelajaran Kelas <?= htmlspecialchars($kelas["nama_kelas"] ?? "X-1") ?>
            </h1>
            <p style="font-size:13.5px;color:#1d4ed8;margin:0;">
                Pelajari modul pembelajaran, rangkuman, dan materi multimedia dari bapak/ibu guru.
            </p>
        </div>
        <a href="index.php?page=siswa_ai_tutor" class="btn btn-primary" style="box-shadow:0 4px 10px rgba(37, 99, 235, 0.3);">
            <i data-lucide="bot" style="width:16px;height:16px;"></i> Tanya AI Tutor
        </a>
    </div>
</div>

<!-- Filter & Search Toolbar (Solid White Card) -->
<div class="card card-white" style="margin-bottom:20px;padding:18px 22px;">
    <form method="GET" action="index.php" style="display:flex;gap:14px;flex-wrap:wrap;align-items:center;justify-content:space-between;">
        <input type="hidden" name="page" value="siswa_materi">

        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;flex:1;min-width:280px;">
            <div style="min-width:220px;">
                <select name="subject_id" class="form-control" onchange="this.form.submit()" style="font-size:13.5px;font-weight:600;">
                    <option value="">-- Semua Mata Pelajaran --</option>
                    <?php foreach ($mapelList as $m): ?>
                        <option value="<?= $m["id"] ?>" <?= $selectedSub == $m["id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($m["nama_mapel"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="flex:1;min-width:220px;">
                <input type="text" id="searchMateri" class="form-control" placeholder="Cari judul materi, bab, atau guru..." style="font-size:13.5px;" onkeyup="filterCards()">
            </div>
        </div>

        <div style="font-size:13px;font-weight:600;color:var(--text-secondary);white-space:nowrap;background:#f8fafc;padding:6px 14px;border-radius:var(--radius-md);border:1px solid var(--border-subtle);">
            Total: <b><span id="materiCount" style="color:var(--c-blue);"><?= count($materiList) ?></span></b> materi
        </div>
    </form>
</div>

<!-- Grid Daftar Materi -->
<?php if (empty($materiList)): ?>
    <div class="card card-white" style="text-align:center;padding:50px 20px;">
        <i data-lucide="book-x" style="width:48px;height:48px;color:#94a3b8;margin-bottom:12px;"></i>
        <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin-bottom:4px;">Belum Ada Materi Terbit</h3>
        <p style="font-size:13px;color:#64748b;max-width:460px;margin:0 auto 16px;">
            Bapak/Ibu guru belum mempublikasikan bahan ajar untuk kelas atau mata pelajaran yang dipilih.
        </p>
        <a href="index.php?page=siswa_materi" class="btn btn-outline btn-sm">Reset Filter Mapel</a>
    </div>
<?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:20px;" id="materiGrid">
        <?php foreach ($materiList as $m): ?>
            <?php 
                $snippet = strip_tags($m["konten"]);
                $snippet = mb_strimwidth($snippet, 0, 130, "...");
            ?>
            <div class="card card-white materi-card" 
                 data-title="<?= strtolower(htmlspecialchars($m["judul"])) ?>" 
                 data-bab="<?= strtolower(htmlspecialchars($m["bab"])) ?>" 
                 data-guru="<?= strtolower(htmlspecialchars($m["nama_guru"])) ?>" 
                 data-mapel="<?= strtolower(htmlspecialchars($m["nama_mapel"])) ?>"
                 style="display:flex;flex-direction:column;justify-content:space-between;margin-bottom:0;padding:22px;border:1.5px solid var(--border-subtle);border-left:4px solid var(--c-blue);">
                
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:10px;">
                        <span class="badge badge-blue"><?= htmlspecialchars($m["nama_mapel"]) ?></span>
                        <span style="font-size:11.5px;color:#94a3b8;font-weight:500;"><?= Helper::formatTanggal($m["created_at"]) ?></span>
                    </div>

                    <h3 style="font-size:16.5px;font-weight:800;color:var(--text-primary);margin-bottom:6px;line-height:1.4;">
                        <?= htmlspecialchars($m["judul"]) ?>
                    </h3>

                    <div style="font-size:12.5px;color:var(--c-blue);font-weight:700;margin-bottom:10px;display:flex;align-items:center;gap:4px;">
                        <i data-lucide="bookmark" style="width:13px;height:13px;"></i>
                        <span><?= htmlspecialchars($m["bab"]) ?></span>
                    </div>

                    <p style="font-size:13px;line-height:1.6;color:var(--text-secondary);margin-bottom:16px;">
                        <?= htmlspecialchars($snippet) ?>
                    </p>
                </div>

                <div>
                    <!-- Indikator Media Pendukung -->
                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:14px;flex-wrap:wrap;">
                        <div style="font-size:12px;color:var(--text-muted);display:inline-flex;align-items:center;gap:4px;font-weight:600;">
                            <i data-lucide="user" style="width:13px;height:13px;"></i> <?= htmlspecialchars($m["nama_guru"]) ?>
                        </div>

                        <?php if (!empty($m["youtube_url"])): ?>
                            <span class="badge badge-rose" style="font-size:11px;">
                                <i data-lucide="video" style="width:11px;vertical-align:middle;"></i> Video
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($m["file_lampiran"])): ?>
                            <span class="badge badge-amber" style="font-size:11px;">
                                <i data-lucide="paperclip" style="width:11px;vertical-align:middle;"></i> Lampiran
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display:grid;grid-template-columns:1fr auto;gap:8px;padding-top:12px;border-top:1px solid var(--border-subtle);">
                        <a href="index.php?page=siswa_materi_detail&id=<?= $m["id"] ?>" class="btn btn-primary btn-sm" style="text-align:center;justify-content:center;">
                            Buka Materi &rarr;
                        </a>
                        <a href="index.php?page=siswa_ai_tutor&subject_id=<?= $m["subject_id"] ?>&topik=<?= urlencode($m["bab"]) ?>" class="btn btn-outline btn-sm" title="Tanya AI Tutor tentang materi ini" style="color:var(--c-green-text);background:var(--c-green-light);border-color:var(--c-green-border);padding:6px 12px;">
                            <i data-lucide="bot" style="width:15px;height:15px;"></i>
                        </a>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function filterCards() {
    const q = document.getElementById('searchMateri').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.materi-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const bab = card.getAttribute('data-bab') || '';
        const guru = card.getAttribute('data-guru') || '';
        const mapel = card.getAttribute('data-mapel') || '';

        if (!q || title.includes(q) || bab.includes(q) || guru.includes(q) || mapel.includes(q)) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const countEl = document.getElementById('materiCount');
    if (countEl) countEl.textContent = visibleCount;
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
