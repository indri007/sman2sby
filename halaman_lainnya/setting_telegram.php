<?php
// Pengaturan Bot Telegram SMAN 2 Surabaya
$message = '';
$message_type = '';

// Pastikan tabel telegram_config ada
$mysqli->query("CREATE TABLE IF NOT EXISTS `telegram_config` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `bot_token` VARCHAR(255) NOT NULL DEFAULT '8982081168:AAGMRgzqFbsExn8QQsGxTVrI_piJmp_uT1M',
  `chat_id` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Ambil config saat ini
$cfg = getTelegramConfig($mysqli);
$bot_token = $cfg['bot_token'];
$chat_id = $cfg['chat_id'];
$is_active = $cfg['is_active'];

// Handle Save Configuration
if (isset($_POST['submit_save'])) {
    $bot_token = $mysqli->real_escape_string(trim($_POST['bot_token']));
    $chat_id = $mysqli->real_escape_string(trim($_POST['chat_id']));
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $check = $mysqli->query("SELECT id FROM telegram_config LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $id = $row['id'];
        $q = "UPDATE telegram_config SET bot_token = '$bot_token', chat_id = '$chat_id', is_active = '$is_active' WHERE id = '$id'";
    } else {
        $q = "INSERT INTO telegram_config (id, bot_token, chat_id, is_active) VALUES (1, '$bot_token', '$chat_id', '$is_active')";
    }

    if ($mysqli->query($q)) {
        $message = "Pengaturan Bot Telegram berhasil disimpan!";
        $message_type = "success";
        $cfg = getTelegramConfig($mysqli);
    } else {
        $message = "Gagal menyimpan database: " . $mysqli->error;
        $message_type = "danger";
    }
}

// Handle Test Send Message
if (isset($_POST['submit_test'])) {
    $test_token = trim($_POST['bot_token'] ?? $bot_token);
    $test_chat = trim($_POST['chat_id'] ?? $chat_id);

    if (empty($test_token) || empty($test_chat)) {
        $message = "Harap isi Bot Token dan Target Chat ID terlebih dahulu untuk uji coba.";
        $message_type = "warning";
    } else {
        $testMsg = "🔔 <b>TEST NOTIFIKASI BOT TELEGRAM</b>\n";
        $testMsg .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $testMsg .= "✅ <i>Koneksi ke Bot @Sman2sby_bot Berhasil!</i>\n";
        $testMsg .= "🏫 <b>Aplikasi:</b> Presensi Digital SMAN 2 Surabaya\n";
        $testMsg .= "⏰ <b>Waktu Uji Coba:</b> " . date('d-m-Y H:i:s') . " WIB\n";
        $testMsg .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $testMsg .= "Sistem siap mengirimkan notifikasi saat siswa melakukan presensi Masuk & Pulang.";

        $resp = sendTelegramMessage($test_token, $test_chat, $testMsg, 'HTML');
        if ($resp && !empty($resp['ok'])) {
            $message = "<b>Berhasil Terkirim!</b> Pesan uji coba telah sampai ke Chat ID ($test_chat).";
            $message_type = "success";
        } else {
            $desc = $resp['description'] ?? 'Gagal menghubungi Telegram API';
            $message = "<b>Gagal Kirim:</b> " . htmlspecialchars($desc) . " (Periksa kembali Chat ID / pastikan Bot sudah dimasukkan ke grup).";
            $message_type = "danger";
        }
    }
}

// Fetch Updates from Telegram for auto-detecting Chat ID
$recent_updates = [];
$update_error = '';
if (isset($_GET['action']) && $_GET['action'] === 'fetch_updates') {
    $updRes = getTelegramUpdates($bot_token);
    if ($updRes && !empty($updRes['ok'])) {
        $recent_updates = $updRes['result'] ?? [];
    } else {
        $update_error = $updRes['description'] ?? 'Gagal mengambil data update dari Telegram.';
    }
}
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pengaturan Notifikasi Bot Telegram</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Setting Telegram</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <?php if (!empty($message)) : ?>
            <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show" role="alert">
                <?= $message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Form Setting -->
            <div class="col-md-7">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fab fa-telegram-plane mr-2"></i> Konfigurasi Bot Telegram</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="bot_token">Bot Token API <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="bot_token" id="bot_token" value="<?= htmlspecialchars($bot_token); ?>" required>
                                    <div class="input-group-append">
                                        <a href="https://t.me/Sman2sby_bot" target="_blank" class="btn btn-outline-info" title="Buka Bot di Telegram">
                                            <i class="fab fa-telegram"></i> @Sman2sby_bot
                                        </a>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Token resmi yang digenerate oleh BotFather.</small>
                            </div>

                            <div class="form-group">
                                <label for="chat_id">Target Chat ID / Group ID <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="chat_id" id="chat_id" placeholder="Contoh: -1001234567890 atau 123456789" value="<?= htmlspecialchars($chat_id); ?>" required>
                                    <div class="input-group-append">
                                        <a href="?page=setting_telegram&action=fetch_updates" class="btn btn-outline-success" title="Cek ID Otomatis dari pesan Telegram">
                                            <i class="fas fa-magic mr-1"></i> Deteksi ID Otomatis
                                        </a>
                                    </div>
                                </div>
                                <small class="form-text text-muted">ID Group Telegram / Channel / Chat Admin tujuan notifikasi.</small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" <?= $is_active ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">Aktifkan Notifikasi Telegram Otomatis saat Presensi</label>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <button type="submit" name="submit_save" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                                </button>
                                <button type="submit" name="submit_test" class="btn btn-info">
                                    <i class="fas fa-paper-plane mr-1"></i> Test Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Deteksi Chat ID Otomatis Box -->
                <?php if (isset($_GET['action']) && $_GET['action'] === 'fetch_updates') : ?>
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-radar mr-2"></i> Hasil Deteksi Chat ID dari Telegram</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($update_error)) : ?>
                                <div class="alert alert-warning"><?= htmlspecialchars($update_error); ?></div>
                            <?php elseif (empty($recent_updates)) : ?>
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-1"></i> Belum ada pesan baru ke bot.
                                    <ol class="mt-2 mb-0">
                                        <li>Ketik <code>/start</code> atau kirim pesan ke bot <a href="https://t.me/Sman2sby_bot" target="_blank">@Sman2sby_bot</a>.</li>
                                        <li>Atau tambahkan bot ke Grup Telegram Anda lalu kirim pesan halo di grup.</li>
                                        <li>Lalu klik tombol <strong>Deteksi ID Otomatis</strong> kembali.</li>
                                    </ol>
                                </div>
                            <?php else : ?>
                                <p>Pilih salah satu Chat ID di bawah ini untuk digunakan:</p>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm table-hover">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Nama / Grup</th>
                                                <th>Tipe</th>
                                                <th>Chat ID</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $seen = [];
                                            foreach (array_reverse($recent_updates) as $upd) : 
                                                $chat = $upd['message']['chat'] ?? ($upd['my_chat_member']['chat'] ?? null);
                                                if (!$chat) continue;
                                                $cId = $chat['id'];
                                                if (isset($seen[$cId])) continue;
                                                $seen[$cId] = true;
                                                $title = $chat['title'] ?? (($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? ''));
                                                $type = $chat['type'] ?? 'private';
                                            ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars(trim($title) ?: 'User'); ?></strong></td>
                                                    <td><span class="badge badge-<?= $type === 'private' ? 'secondary' : 'info'; ?>"><?= $type; ?></span></td>
                                                    <td><code><?= $cId; ?></code></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-success" onclick="selectChatId('<?= $cId; ?>')">
                                                            <i class="fas fa-check"></i> Gunakan ID Ini
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Petunjuk & Panduan -->
            <div class="col-md-5">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-question-circle mr-2"></i> Panduan Penggunaan</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light border">
                            <h5><i class="fas fa-robot text-info mr-1"></i> Informasi Bot</h5>
                            <p class="mb-1"><strong>Nama Bot:</strong> Sman2_bot</p>
                            <p class="mb-1"><strong>Username:</strong> <a href="https://t.me/Sman2sby_bot" target="_blank">@Sman2sby_bot</a></p>
                            <p class="mb-0"><strong>Status:</strong> <span class="badge badge-success">Online & Aktif</span></p>
                        </div>

                        <h6><strong>Langkah Kirim Notifikasi ke Grup Telegram:</strong></h6>
                        <ol style="padding-left: 1.2rem; font-size: 0.95rem;">
                            <li class="mb-2">Buka aplikasi Telegram dan buat grup baru (misal: <i>"Presensi SMAN 2 Surabaya"</i>) atau buka grup yang sudah ada.</li>
                            <li class="mb-2">Tambahkan <strong>@Sman2sby_bot</strong> sebagai anggota/admin grup.</li>
                            <li class="mb-2">Kirim sembarang pesan di grup tersebut (misal: <code>Halo Bot</code>).</li>
                            <li class="mb-2">Kembali ke halaman ini, klik tombol <strong><i class="fas fa-magic"></i> Deteksi ID Otomatis</strong>.</li>
                            <li class="mb-2">Pilih nama grup Anda dan klik <strong>Gunakan ID Ini</strong>.</li>
                            <li class="mb-2">Klik <strong>Simpan Pengaturan</strong>, lalu tekan tombol <strong>Test Kirim Pesan</strong> untuk memastikan bot dapat mengirim notifikasi ke grup.</li>
                        </ol>

                        <div class="callout callout-info mt-3">
                            <small><i class="fas fa-info-circle mr-1"></i> Setiap kali ada siswa yang melakukan Absen Masuk, Pulang, Izin, atau Sakit, bot akan otomatis mengirimkan pesan rekap secara instan.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function selectChatId(id) {
    document.getElementById('chat_id').value = id;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
