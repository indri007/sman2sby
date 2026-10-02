<?php
$pageTitle = "Mengerjakan: " . htmlspecialchars($ujian['judul']);
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
$totalQuestions = count($questions);
?>

<div style="max-width:1180px;margin:0 auto;">
    <!-- Header & Timer Bar -->
    <div class="card card-amber" style="margin-bottom:20px;padding:16px 22px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div>
                <span class="badge badge-secondary" style="background:#ffffff;color:#78350f;margin-bottom:4px;font-weight:700;">
                    <?= htmlspecialchars($ujian['nama_mapel']) ?> &bull; KKM: <?= $ujian['kkm'] ?>
                </span>
                <h2 style="font-size:18px;font-weight:800;color:#92400e;margin:0;">
                    <?= htmlspecialchars($ujian['judul']) ?>
                </h2>
            </div>
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="text-align:right;">
                    <div style="font-size:11px;color:#92400e;font-weight:700;letter-spacing:0.5px;">SISA WAKTU</div>
                    <div id="examTimer" style="font-size:26px;font-weight:800;color:#92400e;line-height:1;">
                        <?= $ujian['durasi_menit'] ?>:00
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CBT Main Container -->
    <form id="examForm" action="index.php?page=siswa_ujian_kerjakan&id=<?= $ujian['id'] ?>" method="POST">
        <div class="cbt-container">
            <!-- Left Area: Active Question Card -->
            <div>
                <?php foreach ($questions as $idx => $q): ?>
                    <div class="card card-white cbt-question-item <?= $idx === 0 ? 'active' : '' ?>" id="qItem_<?= $idx ?>" style="padding:28px;border:1.5px solid var(--border-subtle);margin-bottom:18px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border-subtle);">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="badge badge-blue" style="font-size:13px;padding:6px 12px;font-weight:800;">
                                    Nomor <?= $idx + 1 ?> dari <?= $totalQuestions ?>
                                </span>
                                <span class="badge badge-secondary">Bobot: <?= $q['bobot'] ?></span>
                            </div>
                            <span style="font-size:12px;font-weight:700;color:var(--text-muted);">
                                <?= strtoupper($q['tipe']) ?>
                            </span>
                        </div>

                        <!-- Teks Pertanyaan -->
                        <div style="font-size:16px;line-height:1.75;color:var(--text-primary);margin-bottom:24px;">
                            <?= ($q['tipe'] === 'essay' && str_contains($q['pertanyaan'], '<')) ? $q['pertanyaan'] : nl2br(htmlspecialchars($q['pertanyaan'])) ?>
                        </div>

                        <!-- Gambar Soal Jika Ada -->
                        <?php if (!empty($q['gambar'])): ?>
                            <div style="margin-bottom:20px;">
                                <img src="<?= htmlspecialchars($q['gambar']) ?>" alt="Gambar Soal <?= $idx + 1 ?>" style="max-width:100%;max-height:360px;border-radius:10px;border:1px solid #cbd5e1;display:block;">
                            </div>
                        <?php endif; ?>

                        <!-- Opsi Jawaban untuk PG -->
                        <?php if ($q['tipe'] === 'pg' && !empty($q['opsi'])): ?>
                            <div style="display:flex;flex-direction:column;gap:12px;">
                                <?php foreach ($q['opsi'] as $opt): ?>
                                    <label style="display:flex;align-items:flex-start;gap:12px;padding:14px 18px;background:#f8fafc;border:1.5px solid var(--border-subtle);border-radius:10px;cursor:pointer;transition:all 0.15s;" onmouseover="this.style.borderColor='var(--c-blue)'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--border-subtle)'">
                                        <input type="radio" name="jawaban[<?= $q['id'] ?>]" value="<?= $opt['label'] ?>" style="margin-top:4px;accent-color:var(--c-blue);transform:scale(1.15);" onchange="markAnswered(<?= $idx ?>)">
                                        <span style="font-size:15px;color:var(--text-primary);line-height:1.5;">
                                            <b style="color:var(--c-blue);"><?= $opt['label'] ?>.</b> <?= htmlspecialchars($opt['teks_opsi']) ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($q['tipe'] === 'essay'): ?>
                            <div>
                                <label class="form-label">Jawaban Essay Anda:</label>
                                <textarea name="jawaban[<?= $q['id'] ?>]" class="form-control" rows="5" placeholder="Tuliskan jawaban essay Anda di sini..." oninput="markAnsweredText(<?= $idx ?>, this.value)"></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Bottom Navigation Buttons -->
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;padding:18px 24px;background:#ffffff;border-radius:12px;border:1.5px solid var(--border-subtle);box-shadow:var(--shadow-sm);">
                    <button type="button" class="btn btn-outline" id="btnPrev" onclick="navigateQuestion(-1)">
                        &larr; Soal Sebelumnya
                    </button>

                    <button type="button" class="btn btn-warning" id="btnRagu" onclick="toggleRagu()">
                        <i data-lucide="help-circle" style="width:16px;height:16px;"></i> Ragu-Ragu
                    </button>

                    <button type="button" class="btn btn-primary" id="btnNext" onclick="navigateQuestion(1)">
                        Soal Berikutnya &rarr;
                    </button>
                </div>
            </div>

            <!-- Right Area: Question Grid Navigation Card -->
            <div class="cbt-nav-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <h3 style="font-size:15px;font-weight:800;color:var(--text-primary);margin:0;">
                        Daftar Nomor Soal
                    </h3>
                    <span id="answeredCounter" class="badge badge-emerald" style="font-weight:700;">
                        0 / <?= $totalQuestions ?> Terjawab
                    </span>
                </div>

                <div class="cbt-grid">
                    <?php for ($i = 0; $i < $totalQuestions; $i++): ?>
                        <button type="button" class="cbt-num-btn <?= $i === 0 ? 'active' : '' ?>" id="gridBtn_<?= $i ?>" onclick="goToQuestion(<?= $i ?>)">
                            <?= $i + 1 ?>
                        </button>
                    <?php endfor; ?>
                </div>

                <!-- Legend -->
                <div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border-subtle);display:flex;flex-direction:column;gap:8px;font-size:12px;color:var(--text-secondary);">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:14px;height:14px;border-radius:3px;background:var(--c-blue);display:inline-block;"></span>
                        <span>Nomor Sedang Dilihat</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:14px;height:14px;border-radius:3px;background:var(--c-green);display:inline-block;"></span>
                        <span>Sudah Dijawab</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:14px;height:14px;border-radius:3px;background:var(--c-yellow);display:inline-block;"></span>
                        <span>Ragu-Ragu</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:14px;height:14px;border-radius:3px;background:#f8fafc;border:1px solid #cbd5e1;display:inline-block;"></span>
                        <span>Belum Dijawab</span>
                    </div>
                </div>

                <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border-subtle);">
                    <button type="button" class="btn btn-danger" style="width:100%;padding:12px;font-size:14px;" onclick="confirmFinishExam()">
                        <i data-lucide="check-circle" style="width:16px;height:16px;"></i> Selesaikan Ujian
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const totalQuestions = <?= $totalQuestions ?>;
let currentQuestionIndex = 0;
const raguSet = new Set();

function goToQuestion(idx) {
    if (idx < 0 || idx >= totalQuestions) return;

    // Hide old question
    document.getElementById(`qItem_${currentQuestionIndex}`).classList.remove('active');
    document.getElementById(`gridBtn_${currentQuestionIndex}`).classList.remove('active');

    // Show new question
    currentQuestionIndex = idx;
    document.getElementById(`qItem_${currentQuestionIndex}`).classList.add('active');
    document.getElementById(`gridBtn_${currentQuestionIndex}`).classList.add('active');

    // Button states
    document.getElementById('btnPrev').disabled = (currentQuestionIndex === 0);
    const btnNext = document.getElementById('btnNext');
    if (currentQuestionIndex === totalQuestions - 1) {
        btnNext.innerHTML = 'Selesaikan &rarr;';
        btnNext.className = 'btn btn-success';
        btnNext.onclick = confirmFinishExam;
    } else {
        btnNext.innerHTML = 'Soal Berikutnya &rarr;';
        btnNext.className = 'btn btn-primary';
        btnNext.onclick = () => navigateQuestion(1);
    }

    // Scroll smoothly to question top
    window.scrollTo({ top: 80, behavior: 'smooth' });
}

function navigateQuestion(step) {
    goToQuestion(currentQuestionIndex + step);
}

function markAnswered(idx) {
    const btn = document.getElementById(`gridBtn_${idx}`);
    if (!raguSet.has(idx)) {
        btn.classList.add('answered');
    }
    updateAnsweredCount();
}

function markAnsweredText(idx, val) {
    const btn = document.getElementById(`gridBtn_${idx}`);
    if (val.trim().length > 0) {
        if (!raguSet.has(idx)) btn.classList.add('answered');
    } else {
        btn.classList.remove('answered');
    }
    updateAnsweredCount();
}

function toggleRagu() {
    const btn = document.getElementById(`gridBtn_${currentQuestionIndex}`);
    if (raguSet.has(currentQuestionIndex)) {
        raguSet.delete(currentQuestionIndex);
        btn.classList.remove('ragu');
        // If it was already answered, restore green
        const hasChecked = document.querySelector(`#qItem_${currentQuestionIndex} input:checked, #qItem_${currentQuestionIndex} textarea`);
        if (hasChecked && (hasChecked.checked || (hasChecked.value && hasChecked.value.trim()))) {
            btn.classList.add('answered');
        }
    } else {
        raguSet.add(currentQuestionIndex);
        btn.classList.remove('answered');
        btn.classList.add('ragu');
    }
}

function updateAnsweredCount() {
    let answered = 0;
    for (let i = 0; i < totalQuestions; i++) {
        const item = document.getElementById(`qItem_${i}`);
        const radioChecked = item.querySelector('input[type="radio"]:checked');
        const textarea = item.querySelector('textarea');
        if (radioChecked || (textarea && textarea.value.trim().length > 0)) {
            answered++;
        }
    }
    const counter = document.getElementById('answeredCounter');
    if (counter) {
        counter.textContent = `${answered} / ${totalQuestions} Terjawab`;
    }
    return answered;
}

function confirmFinishExam() {
    const answered = updateAnsweredCount();
    const unanswered = totalQuestions - answered;
    let msg = 'Apakah Anda yakin ingin menyelesaikan dan mengirim jawaban ujian sekarang?';
    if (unanswered > 0) {
        msg = `PERHATIAN: Masih ada ${unanswered} nomor yang belum Anda jawab!\n\nApakah Anda tetap ingin menyelesaikan dan mengirim ujian?`;
    }
    if (confirm(msg)) {
        document.getElementById('examForm').submit();
    }
}

// Countdown Timer
let totalSeconds = <?= (int)$ujian['durasi_menit'] * 60 ?>;
const timerDisplay = document.getElementById('examTimer');

const timerInterval = setInterval(() => {
    totalSeconds--;
    if (totalSeconds <= 0) {
        clearInterval(timerInterval);
        alert('Waktu ujian telah habis! Jawaban Anda akan otomatis dikirimkan ke sistem.');
        document.getElementById('examForm').submit();
        return;
    }

    const m = Math.floor(totalSeconds / 60);
    const s = totalSeconds % 60;
    timerDisplay.textContent = `${m}:${s < 10 ? '0' : ''}${s}`;
}, 1000);

// Initialize initial button states
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnPrev').disabled = true;
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
