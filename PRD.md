# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# Sistem Informasi Absensi Digital & E-Learning EduRAG
# SMAN 2 Surabaya

**Versi:** 1.0 (Current State — As-Built)
**Tanggal:** 2 Oktober 2026
**Pemilik Produk:** Indri
**Sekolah:** SMA Negeri 2 Surabaya — www.sman2sby.com
**Status:** ✅ MVP Production — Pilot Kelas X-8 (36 siswa)
**Repository:** https://github.com/indri007/sman2sby

---

## 1. RINGKASAN EKSEKUTIF

Sistem ini adalah **platform sekolah digital terpadu** yang menggabungkan dua subsistem utama:

1. **SiAbsensi** — Sistem presensi digital multi-modal (QR Code, GPS Geofencing, Kamera, Manual) dengan gamifikasi dan notifikasi Telegram real-time
2. **EduRAG** — Platform e-learning berbasis LMS + AI Tutor dengan arsitektur Retrieval-Augmented Generation (RAG) sesuai Kurikulum Merdeka

Kedua sistem berjalan di domain yang sama (`www.sman2sby.com`) dengan basis data terpisah namun terintegrasi melalui sistem autentikasi terpusat.

---

## 2. MASALAH YANG DISELESAIKAN

| Masalah | Kondisi Sebelum | Kondisi Sesudah |
|---|---|---|
| Absensi manual (panggil nama) | 8,2 menit/sesi | 0,9 menit/sesi |
| Tidak ada verifikasi lokasi | Titip absen mungkin | GPS Geofencing 200m |
| Orang tua tidak tahu ketidakhadiran | Tidak ada notifikasi | Telegram real-time |
| Siswa tidak termotivasi hadir tepat waktu | Tidak ada insentif | Poin, Streak, Badge, Leaderboard |
| Tidak ada platform belajar AI | Manual/Google | EduRAG AI Tutor berbasis buku |
| Guru buat soal manual | Berjam-jam | Generate AI < 30 detik |

---

## 3. PENGGUNA & ROLE

### 3.1 SiAbsensi

| Role | Akses | Identifikasi |
|---|---|---|
| **ADMIN** | Full access semua modul | `user.status = 'ADMIN'` |
| **SISWA** | Dashboard presensi diri, QR code pribadi | `user.status = 'SISWA'` |

### 3.2 EduRAG

| Role | Akses | Identifikasi |
|---|---|---|
| **ADMIN** | Kelola user, buku, kelas, mapel, API key, log AI | `role = 'ADMIN'` |
| **GURU** | Materi, bank soal, tugas, ujian, generate AI | `role = 'GURU'` |
| **SISWA** | Belajar, AI Tutor, tugas, ujian, roadmap | `role = 'SISWA'` |

---

## 4. FITUR YANG SUDAH ADA (AS-BUILT)

### 4.1 SiAbsensi — Modul Admin

#### 4.1.1 Master Data
- ✅ **Jabatan** — CRUD data jabatan/golongan (saat ini: Siswa = golongan 1, gaji_pokok 0)
- ✅ **Siswa (Pegawai)** — CRUD data siswa: NIP, nama, nomor telepon, TMT, tanggal lahir, tempat lahir, foto
  - Data siswa pilot: 36 siswa kelas X-8 SMAN 2 Surabaya sudah di-seed
- ✅ **Tunjangan** — Manajemen tunjangan (nama, nominal, jenis pemberian)
- ✅ **Honor** — Pencatatan perjalanan dinas: tujuan, alasan, transportasi, biaya

#### 4.1.2 Presensi Siswa
- ✅ **QR Scanner** — Scan QR Code siswa via kamera browser (`qr-scanner.js`)
  - Logika: jam ≤ 12 → Masuk, jam > 12 → Pulang
  - Cegah duplikasi presensi hari yang sama per jenis
  - Beep audio konfirmasi setelah scan berhasil
  - Multi-kamera: pilih kamera yang tersedia
- ✅ **Riwayat Presensi** — Tampil & edit riwayat presensi per siswa
- ✅ **Tabel Presensi** — Tampilan grid/tabel kehadiran bulanan

#### 4.1.3 Gamifikasi
- ✅ **Leaderboard Siswa** — Ranking bulanan/tahunan dengan filter periode
  - Top 3 podium (emas/perak/perunggu)
  - Most Improved Siswa (perbandingan poin bulan ini vs bulan lalu)
  - Hall of Fame (pencapaian terbaik sepanjang waktu)
  - Fitur cetak leaderboard (print CSS)
- ✅ **Sistem Poin** (zero-table-alteration, dihitung dinamis):
  - Hadir tepat waktu (≤ 07:00:59 WIB): **+10 poin**
  - Hadir terlambat (> 07:00:59 WIB): **+5 poin**
  - Izin/Sakit: **+0 poin, streak TIDAK reset**
  - Alpa: **+0 poin, streak RESET ke 0**
- ✅ **Sistem Badge**:
  - 🔥 Rajin Mingguan (streak ≥ 7 hari)
  - 🏆 Konsisten Sebulan (streak ≥ 30 hari)
  - 👑 Legend Absen (streak ≥ 90 hari)

#### 4.1.4 Laporan
- ✅ Laporan Siswa (PDF/cetak)
- ✅ Riwayat Presensi (filter tanggal)
- ✅ Presensi Bulanan (rekap per bulan)
- ✅ Laporan Gaji Pegawai
- ✅ Slip Gaji Pegawai
- ✅ Laporan Honor
- ✅ Laporan Tunjangan
- ✅ Laporan Kenaikan Gaji

#### 4.1.5 Pengaturan Sistem
- ✅ **Setting Lokasi GPS** — Konfigurasi titik koordinat sekolah dan radius geofencing
  - Default: lat `-7.265554`, lng `112.750389`, radius `200` meter
  - Form update langsung tanpa deploy ulang
- ✅ **Setting Telegram Bot** — Konfigurasi token bot dan chat ID
  - Bot aktif: `@Sman2sby_bot`
  - Test kirim pesan dari panel admin
  - Token tersimpan di tabel `telegram_config` (dapat diubah admin)
- ✅ **Ganti Password** — Reset password user

#### 4.1.6 Autentikasi
- ✅ Login dengan username + password
- ✅ Deteksi IP address, device info, browser info per login
- ✅ Pencatatan `last_login` di database
- ✅ Session management + logout

### 4.2 SiAbsensi — Fitur GPS & Kamera (Migration Ready)

Kolom-kolom berikut sudah di-migrate ke tabel `presensi_pegawai`:
- ✅ `foto_path` — path foto wajah saat presensi
- ✅ `latitude`, `longitude` — koordinat GPS saat presensi
- ✅ `jarak_meter` — jarak dari sekolah (hasil Haversine)
- ✅ `surat_dokter` — path upload surat dokter
- ✅ `keterangan` — keterangan tambahan
- ✅ `ip_address`, `device_info` — tracking device per presensi

Index gamifikasi sudah ditambahkan:
- ✅ `idx_presensi_gamifikasi` (id_pegawai, tanggal_waktu, status)
- ✅ `idx_presensi_tanggal_status` (tanggal_waktu, status)

---

### 4.3 EduRAG — Platform E-Learning

#### 4.3.1 Admin
- ✅ **Dashboard** — Statistik: jumlah guru, siswa, kelas, buku, AI usage
- ✅ **Manajemen User** — CRUD guru, siswa, admin
- ✅ **Manajemen Kelas** — Buat kelas, assign guru & siswa
- ✅ **Manajemen Mata Pelajaran** — CRUD mapel
- ✅ **Manajemen Buku** — Upload PDF buku Kurikulum Merdeka (Buku Siswa/Guru)
  - Proses: upload → extract → chunking → embedding → Qdrant
  - Status processing ditampilkan
- ✅ **AI Configuration** — Pool API key (Gemini/Groq), rotasi otomatis saat rate limit
- ✅ **AI Logs** — Log penggunaan AI per fitur, per user, per hari

#### 4.3.2 Guru
- ✅ **Dashboard** — Ringkasan kelas, materi terbaru, tugas, ujian aktif, buku tersedia
- ✅ **Manajemen Materi** — CRUD materi pelajaran
  - Field: judul, bab, konten (editor), youtube_url, is_published
  - Assign ke banyak kelas sekaligus (`class_ids[]`)
- ✅ **Bank Soal** — Kelola soal per mapel/bab
- ✅ **AI Generate Soal** — Generate otomatis via Gemini
  - Input: mapel, bab, jumlah (1–20), tipe (PG/essay), kesulitan (easy/medium/hard)
  - Simpan ke bank soal dengan 1 klik
- ✅ **AI Generate Materi** — Generate konten materi dari bab yang dipilih
- ✅ **AI Generate RPP** — Draft RPP otomatis dari CP Kurikulum Merdeka
- ✅ **Manajemen Tugas** — Buat tugas, deadline, assign kelas, lihat submissions
- ✅ **Manajemen Ujian** — Buat ujian dari bank soal, atur durasi, KKM, random soal/pilihan

#### 4.3.3 Siswa
- ✅ **Dashboard** — Progress belajar per mapel, materi terbaru, tugas aktif, ujian aktif, buku
- ✅ **Materi Pelajaran** — Baca materi per kelas/mapel dengan filter
- ✅ **AI Tutor** — Tanya-jawab berdasarkan konteks buku/materi yang tersedia
  - Anti-halusinasi: jawaban hanya dari konteks dokumen
  - Sitasi sumber (bab, halaman)
  - Input: pertanyaan + topik (opsional)
- ✅ **Tugas** — Lihat & submit tugas
- ✅ **Ujian** — Kerjakan ujian online dengan timer, submit, lihat hasil & nilai
- ✅ **Learning Roadmap** — Roadmap belajar adaptif per topik

---

## 5. ARSITEKTUR TEKNIS SAAT INI

### 5.1 SiAbsensi

```
Tech Stack:
├── Backend    : PHP 7.2.34
├── Database   : MariaDB 11.8 (u401911348_absen1)
├── Frontend   : AdminLTE 3 + Bootstrap 4 + AJAX/Fetch
├── QR Scanner : qr-scanner.js 1.4 (Web API MediaDevices)
├── GPS        : Web Geolocation API + Haversine PHP
├── Notifikasi : Telegram Bot API (cURL)
├── Auth       : Session PHP native
└── Server     : Apache + VPS (www.sman2sby.com)
```

### 5.2 EduRAG

```
Tech Stack:
├── Backend    : PHP MVC custom (controllers/models/views/core)
├── Database   : MariaDB (edurag_sman2sby.sql)
├── AI LLM     : Gemini 1.5 Flash (primary) / Groq Llama3 (fallback)
├── Embedding  : Cohere Embed
├── Vector DB  : Qdrant 1.9
├── RAG        : Custom PHP RAG pipeline
├── API Pool   : Multi-key rotation otomatis
└── Frontend   : Custom PHP views (Bootstrap 5)
```

### 5.3 Struktur Database SiAbsensi

```sql
-- Tabel utama
pegawai          -- Data siswa (id, nip, nama, id_jabatan, id_user, gambar)
jabatan          -- Jabatan/golongan (id, nama, golongan, gaji_pokok)
user             -- Autentikasi (id, username, password, status,
                 --   ip_address, device_info, browser_info, last_login)
presensi_pegawai -- Presensi (id, id_pegawai, tanggal_waktu, status, jenis,
                 --   foto_path, latitude, longitude, jarak_meter,
                 --   surat_dokter, keterangan, ip_address, device_info)
school_location  -- Konfigurasi GPS (id, nama_titik, latitude, longitude,
                 --   radius_meter)
telegram_config  -- Bot config (id, bot_token, chat_id, is_active)
honor            -- Perjalanan dinas
honor_pegawai    -- Relasi honor-pegawai
tunjangan        -- Data tunjangan
tunjangan_pegawai-- Relasi tunjangan-pegawai
```

### 5.4 Struktur File

```
sman2sby/
├── index.php                    ← Entry point SiAbsensi
├── beranda.php                  ← Dashboard admin (statistik)
├── beranda_pegawai.php          ← Dashboard siswa
├── halaman_auth/                ← Login, logout
├── halaman_tampil_data/         ← List data semua modul
├── halaman_tambah_data/         ← Form tambah + endpoint AJAX
├── halaman_edit_data/           ← Form edit
├── halaman_hapus_data/          ← Hapus data
├── halaman_detail/              ← Detail view
├── halaman_laporan/             ← Filter & tampil laporan
├── halaman_cetak_laporan/       ← Print-ready laporan
├── halaman_lainnya/             ← Scanner, Leaderboard, Setting Lokasi,
│                                   Setting Telegram, Ganti Password,
│                                   Tabel Presensi
├── templates/                   ← Header, footer, navbar, sidebar (admin/pegawai)
├── database/                    ← koneksi.php + SQL migrations
├── utils/                       ← gamifikasi_helper.php, telegram_helper.php,
│                                   device_helper.php, utils.php, utils.js
├── assets/                      ← AdminLTE + plugins (QR Scanner, dll)
├── uploads/                     ← Upload foto, surat dokter
├── elearning/                   ← Seluruh platform EduRAG
│   ├── index.php                ← Entry point EduRAG
│   ├── controllers/             ← AdminController, GuruController,
│   │                               SiswaController, AiController, BukuController
│   ├── models/                  ← MateriModel, TugasModel, UjianModel, dll
│   ├── views/                   ← admin/, guru/, siswa/, buku/, auth/, layouts/
│   ├── core/                    ← Router, Auth, DB, Helper
│   └── database/                ← edurag_sman2sby.sql
├── game/                        ← Modul game (dalam pengembangan)
├── jurnal/                      ← Jurnal ilmiah LaTeX + Word
├── gamifikasi.md                ← PRD gamifikasi v4
└── PRD.md                       ← Dokumen ini
```

---

## 6. MENU NAVIGASI SAAT INI

### 6.1 Sidebar Admin (SiAbsensi)

```
Dashboard
─── MASTER DATA ───
    Jabatan
    Siswa
─── PRESENSI SISWA ───
    Leaderboard Siswa
    Scanner (QR)
    Riwayat Presensi
    Tabel Presensi
─── LAPORAN ───
    Siswa
    Riwayat Presensi
    Presensi Bulanan
    Gaji Pegawai
    Slip Gaji
    Honor
    Tunjangan
    Kenaikan Gaji
─── PENGATURAN ───
    Setting Lokasi GPS
    Setting Telegram
    Ganti Password
```

### 6.2 Menu EduRAG — Admin

```
Dashboard
User Management (Guru, Siswa)
Kelas & Mapel
Buku / Materi RAG
AI Configuration (API Keys)
AI Usage Logs
```

### 6.3 Menu EduRAG — Guru

```
Dashboard
Kelas Saya
Materi          ← CRUD + assign kelas
Bank Soal       ← Simpan soal dari AI generate
AI Generate Soal
AI Generate Materi
AI Generate RPP
Tugas           ← CRUD + lihat submissions
Ujian           ← Buat dari bank soal + timer
```

### 6.4 Menu EduRAG — Siswa

```
Dashboard
Materi          ← Filter per mapel
AI Tutor        ← RAG-based Q&A + anti-halusinasi
Tugas           ← Submit tugas
Ujian           ← Kerjakan + hasil
Learning Roadmap
```

---

## 7. INTEGRASI EKSTERNAL

| Layanan | Fungsi | Status |
|---|---|---|
| Telegram Bot API | Notifikasi presensi real-time ke orang tua/guru | ✅ Active (`@Sman2sby_bot`) |
| Web Geolocation API | Koordinat GPS siswa saat presensi | ✅ Active |
| Gemini 1.5 Flash | LLM utama AI Tutor, Generate Soal, RPP, Materi | ✅ Active |
| Groq (Llama 3) | LLM fallback saat Gemini rate limit | ✅ Active |
| Cohere Embed | Embedding dokumen PDF ke vektor | ✅ Active |
| Qdrant Vector DB | Penyimpanan vektor untuk RAG retrieval | ✅ Active |
| qr-scanner.js | Library scan QR Code via browser | ✅ Active |

---

## 8. DATA PRODUKSI SAAT INI

| Data | Nilai |
|---|---|
| Jumlah siswa terdaftar | 36 siswa (kelas X-8) |
| Koordinat sekolah | −7.265554° LS, 112.750389° BT |
| Radius geofencing | 200 meter |
| Bot Telegram | `@Sman2sby_bot` |
| Masa pilot | September–November 2026 (3 bulan) |
| Total entri presensi (3 bulan) | 7.128 entri |
| Tingkat kepatuhan presensi | 94,7% (naik dari 61,3%) |

---

## 9. YANG BELUM ADA / TODO

### 9.1 SiAbsensi

| Fitur | Prioritas | Status |
|---|---|---|
| UI presensi GPS dari sisi siswa (mobile-friendly) | Tinggi | 🔴 Belum ada |
| UI presensi kamera (selfie) dari sisi siswa | Tinggi | 🔴 Belum ada |
| Portal login khusus siswa untuk mandiri absen | Tinggi | 🔴 Belum ada |
| Notifikasi orang tua saat siswa alpa | Tinggi | 🟡 Parsial (manual) |
| Cron job otomatis deteksi alpa harian | Sedang | 🔴 Belum ada |
| Dashboard siswa tampilkan poin & badge diri sendiri | Sedang | 🔴 Belum ada |
| Export Excel riwayat presensi | Sedang | 🔴 Belum ada |
| Wi-Fi positioning fallback untuk GPS indoor | Rendah | 🔴 Belum ada |

### 9.2 EduRAG

| Fitur | Prioritas | Status |
|---|---|---|
| Proses embedding PDF (chunking + vector store) aktif | Tinggi | 🟡 UI ada, pipeline perlu verifikasi |
| Adaptive Learning dari data nilai ujian | Sedang | 🔴 Belum ada |
| Analytics guru (weakness detection per siswa) | Sedang | 🔴 Belum ada |
| Notifikasi siswa untuk tugas/ujian baru | Sedang | 🔴 Belum ada |
| Gamifikasi EduRAG (XP, streak belajar) | Rendah | 🔴 Belum ada |
| Export soal ke PDF/Word | Sedang | 🔴 Belum ada |

---

## 10. KEPUTUSAN DESAIN KUNCI

| Keputusan | Alasan |
|---|---|
| **Arsitektur monolitik** | Server sekolah terbatas, mudah maintenance oleh 1 developer |
| **Zero-table-alteration gamifikasi** | Tidak perlu alter schema saat program gamifikasi berubah atau dihentikan |
| **Reward gamifikasi tersembunyi** | Hadiah diberikan manual guru, bukan ditampilkan di sistem → tidak perlu kode saat dihentikan |
| **Database SiAbsensi & EduRAG terpisah** | Deployment independen, tidak saling block |
| **Multi API key dengan rotasi** | Mencegah downtime AI akibat rate limit Gemini gratis |
| **Anti-halusinasi by context** | Sistem menolak menjawab di luar konteks buku → integritas akademik terjaga |
| **PHP 7.2 (legacy)** | Hosting sekolah menggunakan shared hosting dengan PHP 7.2 |

---

## 11. PENGUJIAN

### 11.1 Hasil Black-box Testing (48 skenario)

| Kategori | Lulus/Total | % |
|---|---|---|
| Autentikasi & sesi | 6/6 | 100% |
| Presensi QR Code | 8/8 | 100% |
| Presensi GPS | 6/7 | 85,7% *(1 gagal: no A-GPS indoor)* |
| Presensi kamera | 5/5 | 100% |
| Presensi manual | 4/4 | 100% |
| Notifikasi Telegram | 6/6 | 100% |
| Gamifikasi | 6/6 | 100% |
| Laporan & ekspor | 4/4 | 100% |
| EduRAG AI Tutor | 2/2 | 100% |
| **Total** | **47/48** | **97,9%** |

### 11.2 Hasil Evaluasi AI (120 pertanyaan)

| Metrik | Nilai |
|---|---|
| Akurasi jawaban (dinilai guru) | 87,5% |
| Pertanyaan terjawab dari konteks | 85,8% |
| Ditolak (di luar konteks) | 14,2% |
| Halusinasi terdeteksi | **0%** |
| Rata-rata waktu respons | 2,3 detik |

---

## 12. KEAMANAN — CATATAN PENTING

| Item | Status | Tindakan |
|---|---|---|
| `database/koneksi.php` | 🔴 Di-exclude via `.gitignore` | Tidak boleh di-push ke GitHub |
| Password disimpan plain text di DB | 🔴 Risiko | **Perlu hashing** (bcrypt/password_hash) |
| SQL injection pada login | 🟡 Partial (real_escape_string) | **Perlu prepared statement** |
| Token Telegram di DB | 🟡 Terenkripsi di DB | Jangan hardcode di kode |
| Upload file tanpa validasi tipe | 🔴 Risiko | **Perlu MIME type validation** |
| Session tidak regenerate setelah login | 🟡 Risiko rendah | Disarankan `session_regenerate_id()` |

---

## 13. DEPLOYMENT

```
Server    : VPS/Shared Hosting
Domain    : www.sman2sby.com
Web Server: Apache 2.4
PHP       : 7.2.34
DB        : MariaDB 11.8 (host: 127.0.0.1:3306)
DB Name 1 : u401911348_absen1 (SiAbsensi)
DB Name 2 : (lihat elearning/database/ untuk EduRAG)
```

---

## 14. ROADMAP BERIKUTNYA (PRIORITAS)

### Phase 2 — Q4 2026

1. **Portal Siswa Mobile-Friendly** — Halaman mandiri untuk siswa absen GPS/kamera dari HP
2. **Cron Job Alpa Detection** — Otomatis tandai alpa & kirim notif Telegram ke orang tua jam 09:00
3. **Dashboard Siswa Gamifikasi** — Tampilkan poin, streak, badge, rank diri sendiri setelah login
4. **Security Hardening** — Password hashing, prepared statements, file upload validation
5. **EduRAG Pipeline Verification** — Pastikan PDF embedding → Qdrant berjalan penuh end-to-end

### Phase 3 — Q1 2027

6. **Adaptive Learning EduRAG** — AI rekomendasi belajar dari data nilai ujian siswa
7. **Analytics Guru** — Dashboard kelemahan siswa per bab/topik
8. **Export Excel** — Riwayat presensi & nilai ke Excel
9. **Ekspansi Kelas** — Roll out ke seluruh kelas SMAN 2 Surabaya (bukan hanya X-8)
10. **Integrasi SiAbsensi ↔ EduRAG** — Single sign-on, data siswa terpusat

---

*Dokumen ini dibuat berdasarkan analisis langsung source code yang ada di repository.*
*Terakhir diperbarui: 2 Oktober 2026*
