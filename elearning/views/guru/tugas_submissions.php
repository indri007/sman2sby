<?php
$pageTitle = "Pengumpulan Tugas: " . htmlspecialchars($tugas["judul"]) . " - Guru";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";

$totalSiswa = count($submissions);
$sudahKumpul = 0;
$sudahNilai = 0;
foreach ($submissions as $sub) {
    if (!empty($sub["submission_id"])) {
        $sudahKumpul++;
        if ($sub["status"] === "graded") $sudahNilai++;
    }
}
$belumKumpul = $totalSiswa - $sudahKumpul;
?>

<div style="margin-bottom:16px;">
    <a href="index.php?page=guru_tugas" class="btn btn-outline btn-sm">
        <i data-lucide="arrow-left" style="width:14px;"></i> Kembali ke Daftar Penugasan
    </a>
</div>

<!-- Header Detail Penugasan (Flat Blue Card, No Gradient) -->
<div class="card card-blue" style="margin-bottom:24px;padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;gap:8px;margin-bottom:8px;">
                <span class="badge badge-secondary" style="background:#fff;color:#1e40af;font-weight:700;">
                    <?= htmlspecialchars($tugas["nama_mapel"]) ?>
                </span>
                <span class="badge badge-secondary" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;">
                    Kelas <?= htmlspecialchars($tugas["nama_kelas"]) ?>
                </span>
            </div>

            <h1 style="font-size:22px;font-weight:800;color:#1e3a8a;margin-bottom:6px;">
                <?= htmlspecialchars($tugas["judul"]) ?>
            </h1>

            <div style="font-size:13px;color:#2563eb;">
                <b>Batas Pengumpulan:</b> <?= date("d F Y, H:i", strtotime($tugas["deadline"])) ?> WIB
            </div>

            <?php if (!empty($tugas["deskripsi"])): ?>
                <div style="margin-top:12px;font-size:14px;color:#1e293b;background:#ffffff;padding:12px 16px;border-radius:8px;border:1px solid #bfdbfe;line-height:1.6;">
                    <b>Petunjuk Tugas:</b><br>
                    <?= nl2br(htmlspecialchars($tugas["deskripsi"])) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($tugas["file_lampiran"])): ?>
                <div style="margin-top:10px;">
                    <a href="<?= htmlspecialchars($tugas["file_lampiran"]) ?>" target="_blank" class="btn btn-outline btn-sm" style="background:#ffffff;border-color:#bfdbfe;color:#1e3a8a;">
                        <i data-lucide="paperclip" style="width:14px;"></i> Unduh Lembar Soal / Lampiran
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ringkasan Statistik Siswa -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <div style="background:#ffffff;padding:12px 16px;border-radius:8px;border:1px solid #bfdbfe;text-align:center;min-width:100px;">
                <div style="font-size:11px;color:#64748b;font-weight:700;text-transform:uppercase;">Total Siswa</div>
                <div style="font-size:22px;font-weight:800;color:#1e293b;"><?= $totalSiswa ?></div>
            </div>
            <div style="background:#ffffff;padding:12px 16px;border-radius:8px;border:1px solid #86efac;text-align:center;min-width:100px;">
                <div style="font-size:11px;color:#15803d;font-weight:700;text-transform:uppercase;">Terkumpul</div>
                <div style="font-size:22px;font-weight:800;color:#15803d;"><?= $sudahKumpul ?></div>
            </div>
            <div style="background:#ffffff;padding:12px 16px;border-radius:8px;border:1px solid #fde68a;text-align:center;min-width:100px;">
                <div style="font-size:11px;color:#b45309;font-weight:700;text-transform:uppercase;">Belum Kumpul</div>
                <div style="font-size:22px;font-weight:800;color:#b45309;"><?= $belumKumpul ?></div>
            </div>
            <div style="background:#ffffff;padding:12px 16px;border-radius:8px;border:1px solid #c7d2fe;text-align:center;min-width:100px;">
                <div style="font-size:11px;color:#4338ca;font-weight:700;text-transform:uppercase;">Dinilai</div>
                <div style="font-size:22px;font-weight:800;color:#4338ca;"><?= $sudahNilai ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Pengumpulan Siswa & Form Penilaian (Clean White Card) -->
<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div class="card-title">Daftar Hasil Pengerjaan Siswa</div>
        <div style="display:flex;gap:10px;align-items:center;">
            <input type="text" id="searchSiswa" placeholder="Cari nama atau NISN siswa..." class="form-control" style="font-size:13px;width:240px;" onkeyup="filterSiswa()">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" style="vertical-align:middle;">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Siswa</th>
                    <th>Status & Waktu Pengumpulan</th>
                    <th>Jawaban / Berkas Siswa</th>
                    <th style="width:320px;">Penilaian & Evaluasi Guru</th>
                </tr>
            </thead>
            <tbody id="siswaTableBody">
                <?php if (empty($submissions)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:30px;color:#64748b;">
                            Tidak ada data siswa di kelas ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($submissions as $idx => $s): ?>
                        <?php 
                            $hasSubmitted = !empty($s["submission_id"]);
                            $isGraded = ($s["status"] === "graded");
                        ?>
                        <tr class="siswa-row" data-name="<?= strtolower(htmlspecialchars($s["nama_siswa"])) ?>" data-nisn="<?= htmlspecialchars($s["nisn"]) ?>">
                            <td style="text-align:center;font-weight:700;color:#64748b;"><?= $idx + 1 ?></td>
                            <td>
                                <b style="font-size:14px;color:#0f172a;"><?= htmlspecialchars($s["nama_siswa"]) ?></b>
                                <div style="font-size:12px;color:#64748b;">NISN: <code><?= htmlspecialchars($s["nisn"]) ?></code></div>
                            </td>
                            <td>
                                <?php if ($hasSubmitted): ?>
                                    <span class="badge badge-emerald" style="margin-bottom:4px;">
                                        &check; Sudah Mengumpulkan
                                    </span>
                                    <div style="font-size:11px;color:#64748b;">
                                        <?= date("d/m/Y H:i", strtotime($s["submitted_at"])) ?> WIB
                                    </div>
                                    <?php if ($isGraded): ?>
                                        <div style="margin-top:4px;">
                                            <span class="badge badge-purple">Nilai: <?= number_format($s["nilai"], 0) ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-amber">Belum Mengumpulkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($hasSubmitted): ?>
                                    <?php if (!empty($s["catatan_siswa"])): ?>
                                        <div style="font-size:13px;color:#1e293b;background:#f8fafc;padding:8px 12px;border-radius:6px;border:1px solid #e2e8f0;margin-bottom:6px;max-width:320px;line-height:1.5;">
                                            <b>Catatan:</b> <?= nl2br(htmlspecialchars($s["catatan_siswa"])) ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($s["file_jawaban"])): ?>
                                        <a href="<?= htmlspecialchars($s["file_jawaban"]) ?>" target="_blank" class="btn btn-outline btn-sm" style="font-size:12px;border-color:#bfdbfe;color:#1e3a8a;background:#eff6ff;">
                                            <i data-lucide="download" style="width:13px;vertical-align:middle;"></i> Unduh Berkas Tugas
                                        </a>
                                    <?php else: ?>
                                        <span style="font-size:12px;color:#64748b;font-style:italic;">Hanya teks catatan</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="font-size:12px;color:#94a3b8;">- Tidak ada berkas -</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="index.php?page=guru_tugas_grade" method="POST" style="background:#f8fafc;padding:10px 12px;border-radius:8px;border:1px solid #e2e8f0;">
                                    <input type="hidden" name="assignment_id" value="<?= $tugas["id"] ?>">
                                    <input type="hidden" name="student_user_id" value="<?= $s["student_user_id"] ?>">

                                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:6px;">
                                        <label style="font-size:12px;font-weight:700;color:#334155;margin:0;white-space:nowrap;">Nilai (0-100):</label>
                                        <input type="number" name="nilai" value="<?= $s["nilai"] !== null ? (float)$s["nilai"] : "" ?>" min="0" max="100" step="0.5" class="form-control" style="width:85px;padding:4px 8px;font-size:13px;font-weight:700;text-align:center;" placeholder="100" required>
                                        <button type="submit" class="btn btn-success btn-sm" style="padding:4px 10px;font-size:12px;white-space:nowrap;">
                                            <i data-lucide="save" style="width:12px;vertical-align:middle;"></i> Simpan
                                        </button>
                                    </div>

                                    <input type="text" name="catatan_guru" value="<?= htmlspecialchars($s["catatan_guru"] ?? "") ?>" placeholder="Komentar / masukan evaluasi guru..." class="form-control" style="font-size:12px;padding:4px 8px;">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterSiswa() {
    const q = document.getElementById('searchSiswa').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.siswa-row');
    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const nisn = row.getAttribute('data-nisn') || '';
        if (!q || name.includes(q) || nisn.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
