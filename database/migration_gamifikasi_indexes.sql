-- ====================================================================
-- MIGRATION SQL: Sistem Gamifikasi Absensi Siswa — SMAN 2 Surabaya
-- Dokumen Acuan: PRD gamifikasi.md
-- ====================================================================

-- CATATAN PENTING:
-- Sesuai prinsip desain pada PRD gamifikasi.md (Bab 1 & 4), sistem gamifikasi 
-- (Poin, Streak, Badges, Leaderboard, dan Hall of Fame) dirancang murni 
-- memanfaatkan data tabel `presensi_pegawai` dan `pegawai` yang sudah ada 
-- secara dinamis tanpa perlu mengubah struktur kolom (Zero Table Alteration).

-- Migration di bawah ini bersifat OPSIONAL untuk menambahkan INDEX 
-- guna meningkatkan kecepatan komputasi query presensi dan leaderboard siswa:

-- 1. Index pada tabel presensi_pegawai untuk query gamifikasi per siswa & tanggal
CREATE INDEX IF NOT EXISTS `idx_presensi_gamifikasi` 
ON `presensi_pegawai` (`id_pegawai`, `tanggal_waktu`, `status`);

-- 2. Index pada tabel presensi_pegawai untuk query tanggal & jenis
CREATE INDEX IF NOT EXISTS `idx_presensi_tanggal_status` 
ON `presensi_pegawai` (`tanggal_waktu`, `status`);
