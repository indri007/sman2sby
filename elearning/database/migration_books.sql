-- Migration for LMS Books / Perpustakaan Digital
CREATE TABLE IF NOT EXISTS `lms_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `penerbit` varchar(150) DEFAULT NULL,
  `tahun_terbit` varchar(10) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT 'Kelas X',
  `deskripsi` text DEFAULT NULL,
  `file_pdf` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `total_views` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample digital curriculum books for SMAN 2 Surabaya
INSERT INTO `lms_books` (`judul`, `penulis`, `penerbit`, `tahun_terbit`, `subject_id`, `kelas`, `deskripsi`, `file_pdf`, `cover_image`, `uploaded_by`, `total_views`) VALUES
('Buku Siswa Matematika SMA/SMK Kelas X', 'Dick Susanto, dkk.', 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi', '2022', 1, 'Kelas X', 'Buku teks utama mata pelajaran Matematika Kurikulum Merdeka Fase E Kelas 10. Mencakup bab Eksponen dan Logaritma, Barisan dan Deret, Sistem Persamaan Linear, Trigonometri, dan Vektor.', 'sample_matematika_x.pdf', '', 1, 42),
('Cerdas Cergas Berbahasa dan Bersastra Indonesia Kelas X', 'Fadillah Tri Aulia & Sefi Indra Gumilar', 'Pusat Perbukuan Kemendikbudristek', '2021', 2, 'Kelas X', 'Buku panduan siswa Bahasa Indonesia Kurikulum Merdeka. Mempelajari teks Laporan Hasil Observasi (LHO), teks Anekdot, Hikayat, Negosiasi, Debat, dan Puisi.', 'sample_bahasa_indonesia_x.pdf', '', 1, 28),
('Ilmu Pengetahuan Alam (IPA) Siswa SMA Kelas X', 'Ayuk Ratna Puspaningsih, dkk.', 'Pusat Kurikulum dan Perbukuan Kemendikbudristek', '2021', 3, 'Kelas X', 'Buku teks gabungan IPA SMA Fase E meliputi materi Pengukuran Ilmiah (Fisika), Virus dan Peranannya (Biologi), serta Kimia Hijau dalam Pembangunan Berkelanjutan (Kimia).', 'sample_ipa_x.pdf', '', 1, 35),
('English for Change: Bahasa Inggris untuk SMA/MA Kelas X', 'Basuki, dkk.', 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi', '2022', 6, 'Kelas X', 'Buku pembelajaran Bahasa Inggris kontekstual Fase E. Mengembangkan kemampuan mendengarkan, berbicara, membaca, dan menulis dalam berbagai tema global.', 'sample_bahasa_inggris_x.pdf', '', 1, 19);
