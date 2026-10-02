-- Migration SQL: Pencatatan IP, Perangkat, dan Konfigurasi Bot Telegram
-- SMAN 2 Surabaya

-- 1. Menambahkan kolom pelacakan IP, Device, dan Waktu Login pada tabel `user`
ALTER TABLE `user` 
  ADD COLUMN IF NOT EXISTS `ip_address` VARCHAR(45) NULL AFTER `status`,
  ADD COLUMN IF NOT EXISTS `device_info` VARCHAR(255) NULL AFTER `ip_address`,
  ADD COLUMN IF NOT EXISTS `browser_info` VARCHAR(100) NULL AFTER `device_info`,
  ADD COLUMN IF NOT EXISTS `last_login` DATETIME NULL AFTER `browser_info`;

-- 2. Menambahkan kolom IP dan Device pada tabel `presensi_pegawai`
ALTER TABLE `presensi_pegawai`
  ADD COLUMN IF NOT EXISTS `ip_address` VARCHAR(45) NULL AFTER `keterangan`,
  ADD COLUMN IF NOT EXISTS `device_info` VARCHAR(255) NULL AFTER `ip_address`;

-- 3. Membuat tabel konfigurasi bot Telegram jika belum ada
CREATE TABLE IF NOT EXISTS `telegram_config` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `bot_token` VARCHAR(255) NOT NULL DEFAULT '8982081168:AAGMRgzqFbsExn8QQsGxTVrI_piJmp_uT1M',
  `chat_id` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Inisialisasi token bot Telegram default
INSERT INTO `telegram_config` (`id`, `bot_token`, `chat_id`, `is_active`)
VALUES (1, '8982081168:AAGMRgzqFbsExn8QQsGxTVrI_piJmp_uT1M', '', 1)
ON DUPLICATE KEY UPDATE 
  `bot_token` = VALUES(`bot_token`);
