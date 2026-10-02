<?php
$pageTitle = "Buat Jadwal Ujian / Kuis Baru";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";

$defaultMulai = date("Y-m-d\TH:i");
$defaultSelesai = date("Y-m-d\TH:i", strtotime("+7 days 23:59"));
$currentUserId = Auth::user()["id"];
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Buat Jadwal Ujian / Kuis CBT</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Atur parameter ujian dan pilih butir soal dari Bank Soal Kurikulum Merdeka.</p>
        </div>
        <a href="index.php?page=guru_ujian" class="btn btn-outline btn-sm">
            <i data-lucide="arrow-left" style="width:14px;"></i> Kembali ke Daftar Ujian
        </a>
    </div>

    <form action="index.php?page=guru_ujian_create" method="POST" id="formUjian">
        <!-- Informasi Dasar Ujian -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:16px;">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Judul Ujian / Kuis <span style="color:#ef4444;">*</span></label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Penilaian Harian 1 - Bahasa Indonesia" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                <select name="subject_id" id="filterMapel" class="form-control" required onchange="performFilterSoal()">
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

            <div class="form-group">
                <label class="form-label">Durasi Pengerjaan (Menit)</label>
                <input type="number" name="durasi_menit" class="form-control" value="60" min="5" max="300" required>
            </div>

            <div class="form-group">
                <label class="form-label">Standar KKM Kelulusan</label>
                <input type="number" name="kkm" class="form-control" value="75" min="0" max="100" step="0.5" required>
            </div>

            <div class="form-group">
                <label class="form-label">Waktu Mulai Dapat Diakses <span style="color:#ef4444;">*</span></label>
                <input type="datetime-local" name="tgl_mulai" class="form-control" value="<?= $defaultMulai ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Batas Akhir Pengerjaan <span style="color:#ef4444;">*</span></label>
                <input type="datetime-local" name="tgl_selesai" class="form-control" value="<?= $defaultSelesai ?>" required>
            </div>
        </div>

        <div class="form-group" style="margin-top:10px;">
            <label class="form-label">Petunjuk & Tata Tertib Ujian (Opsional)</label>
            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Petunjuk pengerjaan soal untuk siswa..."></textarea>
        </div>

        <div style="margin:16px 0;padding:12px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;display:flex;align-items:center;gap:10px;">
            <input type="checkbox" name="acak_soal" id="acakSoal" value="1" checked style="width:16px;height:16px;cursor:pointer;">
            <label for="acakSoal" style="cursor:pointer;font-size:14px;color:#1e293b;font-weight:600;margin-bottom:0;">
                Acak Urutan Butir Soal untuk Tiap Siswa (Anti-Mencontek)
            </label>
        </div>

        <!-- ============================================== -->
        <!-- PILIH BUTIR SOAL DARI BANK SOAL -->
        <!-- ============================================== -->
        <div style="margin-top:28px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
                <div>
                    <h3 style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:2px;">
                        Pilih Butir Soal Dari Bank Soal
                    </h3>
                    <p style="font-size:13px;color:#64748b;">
                        Centang butir soal yang ingin diujikan. Bobot nilai tiap soal akan dihitung otomatis proporsional (total 100%).
                    </p>
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <span id="selectedCounter" class="badge badge-emerald" style="font-size:13px;padding:6px 12px;">
                        Dipilih: 0 Butir Soal
                    </span>
                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleSelectAll(true)">Pilih Semua</button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleSelectAll(false)">Batal Semua</button>
                </div>
            </div>

            <!-- Toolbar Pencarian Debounce 1 Detik & Filter -->
            <div style="background:#f8fafc;padding:14px 16px;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:14px;">
                <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                    <!-- Input Search Debounce 1 Detik -->
                    <div style="flex:1;min-width:280px;position:relative;">
                        <input type="text" id="searchSoalInput" class="form-control" 
                               placeholder="Cari pertanyaan, bab, atau materi... (debounce 1 detik)" 
                               style="padding-left:36px;font-size:13px;">
                        <i data-lucide="search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;color:#94a3b8;"></i>
                    </div>

                    <!-- Filter Tipe Soal -->
                    <div style="min-width:160px;">
                        <select id="filterTipeSoal" class="form-control" style="font-size:13px;" onchange="performFilterSoal()">
                            <option value="">-- Semua Tipe Soal --</option>
                            <option value="pg">Pilihan Ganda (PG)</option>
                            <option value="essay">Essay / Uraian</option>
                        </select>
                    </div>

                    <!-- Filter Kepemilikan Soal -->
                    <div style="min-width:190px;">
                        <select id="filterKepemilikan" class="form-control" style="font-size:13px;" onchange="performFilterSoal()">
                            <option value="all" selected>Semua Bank Soal Sekolah</option>
                            <option value="mine">Hanya Soal Buatan Saya</option>
                        </select>
                    </div>

                    <!-- Indikator Debounce 1 Detik -->
                    <div id="searchDebounceIndicator" style="display:none;font-size:12px;color:#2563eb;align-items:center;gap:6px;font-weight:600;">
                        <span class="spinner spinner-sm"></span> Mencari (jeda 1s)...
                    </div>

                    <div style="font-size:12px;color:#64748b;margin-left:auto;">
                        <span id="visibleSoalCount"><?= count($bankSoal) ?></span> soal ditampilkan
                    </div>
                </div>
            </div>

            <?php if (empty($bankSoal)): ?>
                <div style="text-align:center;padding:40px 20px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:8px;">
                    <i data-lucide="help-circle" style="width:40px;height:40px;margin-bottom:8px;opacity:0.5;"></i>
                    <p style="color:#64748b;margin-bottom:14px;">Bank Soal masih kosong. Silakan buat soal atau generate dengan AI terlebih dahulu.</p>
                    <div style="display:flex;gap:10px;justify-content:center;">
                        <a href="index.php?page=guru_soal_create" target="_blank" class="btn btn-primary btn-sm">+ Buat Soal Manual</a>
                        <a href="index.php?page=guru_ai_soal" target="_blank" class="btn btn-outline btn-sm">Generate dengan AI</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive" style="max-height:480px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:8px;">
                    <table class="table" style="margin-bottom:0;vertical-align:middle;">
                        <thead style="position:sticky;top:0;background:#f8fafc;z-index:2;">
                            <tr>
                                <th style="width:40px;text-align:center;">Pilih</th>
                                <th>Mata Pelajaran & Bab</th>
                                <th>Tipe</th>
                                <th>Teks Pertanyaan</th>
                                <th>Pembuat Soal</th>
                                <th>Kesulitan</th>
                            </tr>
                        </thead>
                        <tbody id="bankSoalBody">
                            <?php foreach ($bankSoal as $q): ?>
                                <?php 
                                    $searchBlob = strtolower($q["nama_mapel"] . " " . $q["bab"] . " " . strip_tags($q["pertanyaan"]) . " " . ($q["nama_pembuat"] ?? ""));
                                    $isMyQuestion = ($q["created_by"] == $currentUserId);
                                ?>
                                <tr class="soal-row" 
                                    data-subject="<?= $q["subject_id"] ?>" 
                                    data-tipe="<?= $q["tipe"] ?>"
                                    data-creator="<?= $q["created_by"] ?>"
                                    data-search="<?= htmlspecialchars($searchBlob) ?>"
                                    style="cursor:pointer;" 
                                    onclick="toggleRow(event, this)">
                                    <td style="text-align:center;" onclick="event.stopPropagation();">
                                        <input type="checkbox" name="soal_ids[]" value="<?= $q["id"] ?>" class="soal-checkbox" onchange="updateCounter()" style="width:16px;height:16px;cursor:pointer;">
                                    </td>
                                    <td style="font-size:13px;white-space:nowrap;">
                                        <b><?= htmlspecialchars($q["nama_mapel"]) ?></b>
                                        <div style="color:#64748b;font-size:12px;"><?= htmlspecialchars($q["bab"]) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary"><?= strtoupper($q["tipe"]) ?></span>
                                    </td>
                                    <td style="font-size:13px;color:#1e293b;max-width:380px;">
                                        <?= htmlspecialchars(mb_strimwidth(strip_tags($q["pertanyaan"]), 0, 110, "...")) ?>
                                        <?php if (!empty($q["gambar"])): ?>
                                            <span style="color:#2563eb;font-size:11px;margin-left:4px;">[Ada Gambar]</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:12px;color:#64748b;white-space:nowrap;">
                                        <span class="badge <?= $isMyQuestion ? "badge-emerald" : "badge-slate" ?>" style="font-size:11px;">
                                            <?= $isMyQuestion ? "Saya" : htmlspecialchars($q["nama_pembuat"] ?: "Guru") ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= Helper::badgeKesulitan($q["kesulitan"]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:28px;padding-top:18px;border-top:1px solid #e2e8f0;">
            <a href="index.php?page=guru_ujian" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="check-circle-2" style="width:16px;"></i> Terbitkan Jadwal Ujian
            </button>
        </div>
    </form>
</div>

<script>
const currentUserId = <?= (int)$currentUserId ?>;
let debounceTimer = null;

// Event Listener Search dengan Debounce 1 Detik (1000ms)
const searchInput = document.getElementById('searchSoalInput');
const debounceIndicator = document.getElementById('searchDebounceIndicator');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        if (debounceIndicator) {
            debounceIndicator.style.display = 'inline-flex';
        }

        // Tepat 1000ms jeda ketik (debounce 1 detik)
        debounceTimer = setTimeout(() => {
            performFilterSoal();
            if (debounceIndicator) {
                debounceIndicator.style.display = 'none';
            }
        }, 1000);
    });
}

function performFilterSoal() {
    const q = (document.getElementById('searchSoalInput')?.value || '').toLowerCase().trim();
    const mapelId = document.getElementById('filterMapel')?.value || '';
    const tipe = document.getElementById('filterTipeSoal')?.value || '';
    const kepemilikan = document.getElementById('filterKepemilikan')?.value || 'all';

    const rows = document.querySelectorAll('.soal-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowSubject = row.getAttribute('data-subject') || '';
        const rowTipe = row.getAttribute('data-tipe') || '';
        const rowCreator = parseInt(row.getAttribute('data-creator') || '0');
        const rowText = (row.getAttribute('data-search') || '').toLowerCase();

        let matchMapel = (!mapelId || rowSubject === mapelId);
        let matchTipe = (!tipe || rowTipe === tipe);
        let matchOwner = (kepemilikan === 'all' || rowCreator === currentUserId);
        let matchQuery = (!q || rowText.includes(q));

        if (matchMapel && matchTipe && matchOwner && matchQuery) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('visibleSoalCount');
    if (countEl) countEl.textContent = visibleCount;
}

function updateCounter() {
    const checked = document.querySelectorAll('.soal-checkbox:checked');
    const counter = document.getElementById('selectedCounter');
    if (counter) {
        counter.textContent = `Dipilih: ${checked.length} Butir Soal`;
    }
}

function toggleSelectAll(status) {
    const visibleCheckboxes = document.querySelectorAll('.soal-row:not([style*="display: none"]) .soal-checkbox');
    visibleCheckboxes.forEach(cb => cb.checked = status);
    updateCounter();
}

function toggleRow(e, row) {
    if (e.target.tagName.toLowerCase() === 'input') return;
    const cb = row.querySelector('.soal-checkbox');
    if (cb) {
        cb.checked = !cb.checked;
        updateCounter();
    }
}

document.getElementById('formUjian').addEventListener('submit', (e) => {
    const checked = document.querySelectorAll('.soal-checkbox:checked');
    if (checked.length === 0) {
        alert('Pilih minimal 1 butir soal dari daftar Bank Soal di bawah!');
        e.preventDefault();
        return;
    }
});
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
