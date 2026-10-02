-- Migration SQL untuk Fitur Absensi Kamera, GPS & Upload Surat Dokter
-- SMAN 2 Surabaya

-- 1. Menambahkan kolom pendukung di tabel presensi_pegawai jika belum ada
ALTER TABLE `presensi_pegawai` 
  ADD COLUMN `foto_path` VARCHAR(255) NULL AFTER `jenis`,
  ADD COLUMN `latitude` DECIMAL(10,7) NULL AFTER `foto_path`,
  ADD COLUMN `longitude` DECIMAL(10,7) NULL AFTER `latitude`,
  ADD COLUMN `jarak_meter` DECIMAL(8,2) NULL AFTER `longitude`,
  ADD COLUMN `surat_dokter` VARCHAR(255) NULL AFTER `jarak_meter`,
  ADD COLUMN `keterangan` TEXT NULL AFTER `surat_dokter`;

-- 2. Membuat tabel lokasi sekolah (SMAN 2 Surabaya)
CREATE TABLE IF NOT EXISTS `school_location` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_titik` VARCHAR(100) NOT NULL DEFAULT 'SMAN 2 Surabaya',
  `latitude` DECIMAL(10,7) NOT NULL DEFAULT -7.265554,
  `longitude` DECIMAL(10,7) NOT NULL DEFAULT 112.750389,
  `radius_meter` INT NOT NULL DEFAULT 200,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Inser data default titik lokasi SMAN 2 Surabaya
INSERT INTO `school_location` (`id`, `nama_titik`, `latitude`, `longitude`, `radius_meter`)
VALUES (1, 'SMAN 2 Surabaya', -7.265554, 112.750389, 200)
ON DUPLICATE KEY UPDATE 
  `latitude` = -7.265554, 
  `longitude` = 112.750389, 
  `radius_meter` = 200;
