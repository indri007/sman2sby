<?php

/**
 * Helper untuk Integrasi Telegram Bot API
 * SMAN 2 Surabaya
 * Bot: t.me/Sman2sby_bot
 */

const DEFAULT_TELEGRAM_BOT_TOKEN = '8982081168:AAGMRgzqFbsExn8QQsGxTVrI_piJmp_uT1M';

function getTelegramConfig($mysqli = null)
{
    $config = [
        'bot_token' => DEFAULT_TELEGRAM_BOT_TOKEN,
        'chat_id' => '',
        'is_active' => 1
    ];

    if ($mysqli) {
        $result = $mysqli->query("SELECT * FROM telegram_config LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $config['bot_token'] = !empty($row['bot_token']) ? $row['bot_token'] : DEFAULT_TELEGRAM_BOT_TOKEN;
            $config['chat_id'] = $row['chat_id'] ?? '';
            $config['is_active'] = isset($row['is_active']) ? intval($row['is_active']) : 1;
        }
    }

    return $config;
}

function sendTelegramMessage($botToken, $chatId, $messageText, $parseMode = 'HTML')
{
    if (empty($botToken) || empty($chatId)) {
        return ['ok' => false, 'description' => 'Bot Token atau Chat ID masih kosong.'];
    }

    $url = "https://api.telegram.org/bot" . urlencode($botToken) . "/sendMessage";
    $payload = [
        'chat_id' => $chatId,
        'text' => $messageText,
        'parse_mode' => $parseMode,
        'disable_web_page_preview' => true
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['ok' => false, 'description' => 'cURL Error: ' . $error];
    }

    $json = json_decode($response, true);
    return $json ?: ['ok' => false, 'description' => 'Invalid response from Telegram API'];
}

function getTelegramUpdates($botToken)
{
    if (empty($botToken)) {
        $botToken = DEFAULT_TELEGRAM_BOT_TOKEN;
    }

    $url = "https://api.telegram.org/bot" . urlencode($botToken) . "/getUpdates";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);
    return $json ?: ['ok' => false, 'result' => []];
}

function sendTelegramAttendanceNotification($mysqli, $dataAbsen)
{
    $config = getTelegramConfig($mysqli);

    if (empty($config['is_active']) || empty($config['chat_id'])) {
        return false;
    }

    $nama = htmlspecialchars($dataAbsen['nama'] ?? 'Siswa/Pegawai');
    $nip = htmlspecialchars($dataAbsen['nip'] ?? '-');
    $status = htmlspecialchars($dataAbsen['status'] ?? 'Hadir');
    $jenis = htmlspecialchars($dataAbsen['jenis'] ?? '-');
    $waktu = htmlspecialchars($dataAbsen['waktu'] ?? date('d-m-Y H:i:s'));
    $jarak = isset($dataAbsen['jarak_meter']) ? number_format(floatval($dataAbsen['jarak_meter']), 1) : '-';
    $lat = $dataAbsen['latitude'] ?? '';
    $lng = $dataAbsen['longitude'] ?? '';
    $ip = htmlspecialchars($dataAbsen['ip_address'] ?? getClientIP());
    $device = htmlspecialchars($dataAbsen['device_info'] ?? getDeviceInfo()['summary']);
    $keterangan = htmlspecialchars($dataAbsen['keterangan'] ?? '');

    $statusIcon = '✅';
    if ($status === 'Izin') $statusIcon = 'ℹ️';
    elseif ($status === 'Sakit') $statusIcon = '🏥';

    $jenisText = ($status === 'Hadir') ? "Absen $jenis" : $status;

    $msg = "🔔 <b>NOTIFIKASI PRESENSI SISWA - SMAN 2 SURABAYA</b>\n";
    $msg .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "👤 <b>Nama:</b> {$nama}\n";
    $msg .= "🆔 <b>NIS / NIP:</b> {$nip}\n";
    $msg .= "{$statusIcon} <b>Status:</b> <b>{$jenisText}</b> ({$status})\n";
    $msg .= "⏰ <b>Waktu:</b> {$waktu} WIB\n";

    if (!empty($lat) && !empty($lng)) {
        $msg .= "📍 <b>Lokasi GPS:</b> {$lat}, {$lng} (±{$jarak} m)\n";
    }

    $msg .= "🌐 <b>IP Address:</b> <code>{$ip}</code>\n";
    $msg .= "📱 <b>Perangkat:</b> {$device}\n";

    if (!empty($keterangan)) {
        $msg .= "📝 <b>Keterangan:</b> {$keterangan}\n";
    }

    $msg .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $msg .= "<i>Sistem Presensi Digital SMAN 2 Surabaya</i>";

    return sendTelegramMessage($config['bot_token'], $config['chat_id'], $msg, 'HTML');
}
