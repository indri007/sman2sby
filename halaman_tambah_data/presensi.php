<?php
date_default_timezone_set("Asia/Jakarta");
require '../database/koneksi.php';
require '../utils/utils.php';

try {
    $mysqli->begin_transaction();
    $data = json_decode(file_get_contents('php://input'), true);
    $id_pegawai = $mysqli->real_escape_string($data['id_pegawai']);
    $clientIP = getClientIP();
    $devInfo = getDeviceInfo();
    $deviceSummary = 'Scanner QR (' . $devInfo['summary'] . ')';

    if (Date('H') <= 12) {
        $jenis = 'Masuk';
        $check = $mysqli->query("SELECT * FROM presensi_pegawai WHERE id_pegawai=$id_pegawai AND DATE(tanggal_waktu)='" . Date("Y-m-d") . "' AND jenis='$jenis'");
    } else {
        $jenis = 'Pulang';
        $check = $mysqli->query("SELECT * FROM presensi_pegawai WHERE id_pegawai=$id_pegawai AND DATE(tanggal_waktu)='" . Date("Y-m-d") . "' AND jenis='$jenis'");
    }
    if (!$check->num_rows) {
        $waktuSekarang = Date("Y-m-d H:i:s");
        $q = "
        INSERT INTO presensi_pegawai ( 
            id_pegawai,
            tanggal_waktu,
            status,
            jenis,
            ip_address,
            device_info,
            keterangan
        ) VALUES (
            '$id_pegawai',
            '$waktuSekarang',
            'Hadir',
            '$jenis',
            '$clientIP',
            '$deviceSummary',
            'Presensi via QR Scanner'
        )
        ";
        $mysqli->query($q);

        // Ambil info pegawai untuk notifikasi Telegram
        $pegRes = $mysqli->query("SELECT nama, nip FROM pegawai WHERE id = '$id_pegawai' LIMIT 1");
        if ($pegRes && $pegRes->num_rows > 0) {
            $pegInfo = $pegRes->fetch_assoc();
            sendTelegramAttendanceNotification($mysqli, [
                'nama' => $pegInfo['nama'],
                'nip' => $pegInfo['nip'],
                'status' => 'Hadir',
                'jenis' => $jenis,
                'waktu' => date('d-m-Y H:i:s'),
                'ip_address' => $clientIP,
                'device_info' => $deviceSummary,
                'keterangan' => 'Presensi via QR Scanner'
            ]);
        }
    }

    $mysqli->commit();
    echo json_encode(['isSuccess' => true]);
} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(array(
        'error' => array(
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
        ),
    ));
};

