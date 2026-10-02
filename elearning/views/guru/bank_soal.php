<?php
$pageTitle = "Bank Soal Guru - SMAN 2 Surabaya";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div class="card card-white">
    <div class="card-header">
        <div>
            <div class="card-title">Koleksi Bank Soal Saya</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Kumpulan butir soal pilihan ganda & essay siap pakai untuk ujian/kuis.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <select class="form-control" style="width:auto;font-size:13px;" onchange="location.href='index.php?page=guru_bank_soal&subject_id='+this.value">
                <option value="">-- Semua Mata Pelajaran --</option>
                <?php foreach ($mapelList as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($subjectId ?? '') == $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nama_mapel']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a href="index.php?page=guru_soal_create" class="btn btn-primary">
                <i data-lucide="plus-circle" style="width:16px;"></i> + Buat Soal Manual
            </a>
            <a href="index.php?page=guru_ai_soal" class="btn btn-outline" style="border-color:#bfdbfe;color:#1e3a8a;background:#eff6ff;">
                <i data-lucide="sparkles" style="width:16px;"></i> Generate dengan AI
            </a>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:18px;">
        <?php if (empty($soalList)): ?>
            <div style="text-align:center;padding:50px 20px;color:#64748b;">
                <i data-lucide="help-circle" style="width:48px;height:48px;margin-bottom:12px;opacity:0.5;"></i>
                <p>Belum ada butir soal di Bank Soal<?= $subjectId ? " untuk mata pelajaran ini" : "" ?>.</p>
                <div style="margin-top:14px;display:flex;gap:10px;justify-content:center;">
                    <a href="index.php?page=guru_soal_create" class="btn btn-primary btn-sm">+ Buat Soal Manual</a>
                    <a href="index.php?page=guru_ai_soal" class="btn btn-outline btn-sm">Generate dengan AI</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($soalList as $idx => $q): ?>
                <?php $itemNo = (($page - 1) * $perPage) + $idx + 1; ?>
                <div class="card card-slate" style="margin-bottom:0;padding:20px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                        <span style="font-size:14px;font-weight:700;color:#1e293b;">
                            Soal #<?= $itemNo ?> &bull; <?= htmlspecialchars($q['nama_mapel']) ?> (<?= htmlspecialchars($q['bab']) ?>)
                        </span>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <?= Helper::badgeKesulitan($q['kesulitan']) ?>
                            <span class="badge badge-secondary"><?= strtoupper($q['tipe']) ?></span>
                            
                            <a href="index.php?page=guru_soal_edit&id=<?= $q['id'] ?>" class="btn btn-outline btn-sm" style="padding:4px 8px;font-size:12px;" title="Edit Butir Soal">
                                <i data-lucide="edit-3" style="width:13px;vertical-align:middle;"></i> Edit
                            </a>
                            <a href="index.php?page=guru_soal_delete&id=<?= $q['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus butir soal ini dari Bank Soal?')" class="btn btn-danger btn-sm" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:4px 8px;font-size:12px;" title="Hapus Soal">
                                <i data-lucide="trash-2" style="width:13px;vertical-align:middle;"></i> Hapus
                            </a>
                        </div>
                    </div>

                    <!-- Teks Pertanyaan (HTML render jika essay, nl2br jika PG biasa) -->
                    <div style="font-size:15px;line-height:1.7;margin-bottom:14px;color:#0f172a;">
                        <?= ($q['tipe'] === 'essay' && str_contains($q['pertanyaan'], '<')) ? $q['pertanyaan'] : nl2br(htmlspecialchars($q['pertanyaan'])) ?>
                    </div>

                    <!-- Gambar Pendukung Pertanyaan jika ada -->
                    <?php if (!empty($q['gambar'])): ?>
                        <div style="margin-bottom:14px;">
                            <img src="<?= htmlspecialchars($q['gambar']) ?>" alt="Gambar Soal" style="max-width:380px;border-radius:8px;border:1px solid #cbd5e1;display:block;">
                        </div>
                    <?php endif; ?>

                    <!-- Opsi Jawaban untuk PG -->
                    <?php if ($q['tipe'] === 'pg' && !empty($q['opsi'])): ?>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:10px;margin-bottom:14px;">
                            <?php foreach ($q['opsi'] as $opt): ?>
                                <div style="padding:10px 14px;border-radius:8px;font-size:14px;background:<?= $opt['is_benar'] ? '#dcfce7' : '#ffffff' ?>;border:1px solid <?= $opt['is_benar'] ? '#86efac' : '#e2e8f0' ?>;">
                                    <div>
                                        <b><?= $opt['label'] ?>.</b> <?= htmlspecialchars($opt['teks_opsi']) ?>
                                        <?php if ($opt['is_benar']): ?>
                                            <span style="color:#15803d;font-weight:700;margin-left:6px;font-size:12px;">&check; Kunci Jawaban</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($opt['gambar'])): ?>
                                        <img src="<?= htmlspecialchars($opt['gambar']) ?>" alt="Opsi <?= $opt['label'] ?>" style="max-height:80px;margin-top:6px;border-radius:4px;border:1px solid #cbd5e1;">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($q['tipe'] === 'essay'): ?>
                        <div style="padding:12px 16px;background:#f1f5f9;border-radius:8px;font-size:13px;margin-bottom:14px;color:#334155;">
                            <b>Rubrik / Kunci Jawaban Essay:</b><br>
                            <?= str_contains($q['kunci_jawaban'], '<') ? $q['kunci_jawaban'] : nl2br(htmlspecialchars($q['kunci_jawaban'])) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Pembahasan -->
                    <?php if (!empty($q['pembahasan'])): ?>
                        <div style="padding:10px 14px;background-color:#fffbeb;border:1px solid #fde68a;border-radius:6px;font-size:13px;color:#92400e;line-height:1.6;">
                            <b>Pembahasan:</b> <?= str_contains($q['pembahasan'], '<') ? $q['pembahasan'] : nl2br(htmlspecialchars($q['pembahasan'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination Component -->
    <?= Helper::renderPagination($totalSoal, $perPage, $page, 'guru_bank_soal', ['subject_id' => $subjectId ?? '']) ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
