<?php
$pageTitle = "AI Tutor EduRAG - SMAN 2 Surabaya";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';
?>

<div style="max-width:1000px;margin:0 auto;">
    <!-- Banner Fitur (Flat Emerald Card, No Gradient) -->
    <div class="card card-emerald" style="margin-bottom:20px;padding:18px 24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="font-size:18px;font-weight:800;color:#064e3b;margin-bottom:4px;">
                    <i data-lucide="bot" style="width:22px;vertical-align:middle;"></i> EduRAG AI Tutor SMAN 2 Surabaya
                </h2>
                <p style="font-size:13px;color:#047857;line-height:1.5;">
                    AI Tutor grounded pada <b>Buku Siswa Kurikulum Merdeka Kelas 10</b>. Dilengkapi sitasi bab/halaman dan proteksi anti-halusinasi.
                </p>
            </div>
            <!-- Pilih Mapel -->
            <div style="display:flex;align-items:center;gap:8px;">
                <label style="font-size:13px;font-weight:600;color:#064e3b;white-space:nowrap;">Pilih Mapel:</label>
                <select id="chatSubjectId" class="form-control" style="width:auto;padding:6px 12px;font-size:13px;" onchange="updateSubjectBadge()">
                    <?php foreach ($mapelList as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= ($selectedSubjectId == $m['id']) ? 'selected' : '' ?>><?= htmlspecialchars($m['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Chat Box Component -->
    <div class="chat-container">
        <div class="chat-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="user-avatar" style="background:#ffffff;color:#059669;font-weight:bold;border:none;">AI</span>
                <div>
                    <b style="font-size:14px;color:#ffffff;">EduRAG AI Assistant</b>
                    <div style="font-size:11.5px;color:#d1fae5;">Aktif &bull; Terhubung ke Gemini 2.5 Flash</div>
                </div>
            </div>
            <span id="activeMapelBadge" class="badge" style="background:#ffffff;color:#059669;font-weight:800;border:none;">Matematika</span>
        </div>

        <!-- Chat Messages Scroll Area -->
        <div id="chatMessages" class="chat-messages">
            <!-- Pesan Sambutan AI -->
            <div class="chat-bubble bubble-ai">
                Halo, <b><?= htmlspecialchars(Auth::user()['nama']) ?></b>! Saya adalah AI Tutor pendamping belajar mandiri Anda di SMAN 2 Surabaya.<br><br>
                Anda bisa menanyakan konsep materi pelajaran kelas 10, meminta contoh soal, ringkasan materi, atau penjelasan bertahap. Jawaban saya akan menyertakan sitasi halaman buku Kurikulum Merdeka resmi! 📖
            </div>
        </div>

        <!-- Quick Action Chips -->
        <div class="chat-quick-actions">
            <button class="quick-btn" onclick="sendQuickPrompt('Jelaskan dengan bahasa sederhana konsep materi ini')">
                💡 Jelaskan Bahasa Sederhana
            </button>
            <button class="quick-btn" onclick="sendQuickPrompt('Berikan 1 contoh soal kontekstual beserta langkah penyelesaian lengkapnya')">
                📝 Berikan Contoh Soal
            </button>
            <button class="quick-btn" onclick="sendQuickPrompt('Buatkan ringkasan 5 poin kunci dari materi ini')">
                📌 Ringkas Materi
            </button>
            <button class="quick-btn" onclick="sendQuickPrompt('Berikan saya 3 kuis latihan mandiri untuk menguji pemahaman saya')">
                🎯 Latihan Kuis Singkat
            </button>
        </div>

        <!-- Input Area -->
        <form id="chatForm" class="chat-input-area" onsubmit="handleChatSubmit(event)">
            <input type="text" id="chatInput" class="form-control" placeholder="Ketik pertanyaan materi pelajaran Anda di sini... (contoh: Bagaimana cara menyelesaikan SPLTV dengan substitusi?)" autocomplete="off" required>
            <button type="submit" id="chatSendBtn" class="btn btn-primary" style="padding:0 24px;">
                <i data-lucide="send" style="width:16px;"></i> Kirim
            </button>
        </form>
    </div>
</div>

<script>
function updateSubjectBadge() {
    const sel = document.getElementById('chatSubjectId');
    const badge = document.getElementById('activeMapelBadge');
    badge.textContent = sel.options[sel.selectedIndex].text;
}

function sendQuickPrompt(promptText) {
    document.getElementById('chatInput').value = promptText;
    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
}

async function handleChatSubmit(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const sendBtn = document.getElementById('chatSendBtn');
    const text = input.value.trim();
    if (!text) return;

    const subjectId = document.getElementById('chatSubjectId').value;
    const container = document.getElementById('chatMessages');

    // 1. Tampilkan bubble siswa
    const userDiv = document.createElement('div');
    userDiv.className = 'chat-bubble bubble-user';
    userDiv.textContent = text;
    container.appendChild(userDiv);

    // 2. Tampilkan bubble loading AI
    const aiLoadingDiv = document.createElement('div');
    aiLoadingDiv.className = 'chat-bubble bubble-ai';
    aiLoadingDiv.innerHTML = '<span class="spinner spinner-sm"></span> Sedang membaca buku Kurikulum Merdeka & merumuskan jawaban...';
    container.appendChild(aiLoadingDiv);
    lucide.createIcons();
    container.scrollTop = container.scrollHeight;

    input.value = '';
    sendBtn.disabled = true;

    try {
        const data = await EduRagAI.askTutor(text, subjectId);
        sendBtn.disabled = false;

        if (data.success) {
            aiLoadingDiv.innerHTML = formatAiResponse(data.text);
        } else {
            aiLoadingDiv.innerHTML = `<span style="color:#dc2626;">Maaf, terjadi kendala: ${data.message}</span>`;
        }
    } catch (err) {
        sendBtn.disabled = false;
        aiLoadingDiv.innerHTML = `<span style="color:#dc2626;">Error koneksi: ${err.message}</span>`;
    }

    container.scrollTop = container.scrollHeight;
}

function formatAiResponse(rawText) {
    // Render text with line breaks
    let formatted = rawText.replace(/\n/g, '<br>');
    return formatted;
}

updateSubjectBadge();
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
