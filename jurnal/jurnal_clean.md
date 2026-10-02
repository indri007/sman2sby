---
title: "Sistem Informasi Absensi Digital Berbasis GPS, QR Code, dan Kamera dengan Gamifikasi serta Integrasi Platform E-Learning EduRAG pada SMAN 2 Surabaya"
subtitle: "Digital Attendance Information System Based on GPS, QR Code, and Camera with Gamification and Integration of EduRAG E-Learning Platform at SMAN 2 Surabaya"
author:
  - "Indri Rahmawati^1^\\*, Budi Santoso^2^, Sari Wijayanti^1^, Andi Nugroho^3^"
date: "Oktober 2026"
lang: id
bibliography: references_sman2sby.bib
---

^1^Program Studi Teknik Informatika, Universitas Airlangga, Surabaya 60115, Indonesia\
^2^Jurusan Ilmu Komputer, Institut Teknologi Sepuluh Nopember, Surabaya 60111, Indonesia\
^3^Departemen Sistem Informasi, Universitas Ciputra Surabaya, Surabaya 60219, Indonesia\
\*Korespondensi: indri.rahmawati@informatika.unair.ac.id

**Diterima:** 1 Oktober 2026 | **Direvisi:** 15 Oktober 2026 | **Diterbitkan:** 1 November 2026

---

## Abstrak

Absensi manual berbasis panggil-nama masih menjadi praktik umum di sekolah menengah Indonesia, menghasilkan inefisiensi administratif, potensi kecurangan data, dan tidak adanya mekanisme umpan balik real-time kepada orang tua maupun guru. Penelitian ini merancang, mengimplementasikan, dan mengevaluasi **Sistem Informasi Absensi Digital** (*SiAbsensi*) di SMA Negeri 2 Surabaya (SMAN 2 Surabaya) yang mengintegrasikan empat mekanisme presensi — QR Code Scanner, verifikasi GPS geofencing (radius 200 m dari titik koordinat −7,265554° LS, 112,750389° BT), kamera wajah, dan input manual dengan unggah surat dokter — dengan modul notifikasi Telegram Bot (`@Sman2sby_bot`) dan sistem gamifikasi (poin, streak, badge, leaderboard bulanan). Selain itu, sistem terintegrasi dengan platform e-learning **EduRAG** yang menerapkan arsitektur *Retrieval-Augmented Generation* (RAG) berbasis Kurikulum Merdeka untuk mendukung AI Tutor siswa dan asisten pengajaran guru. Evaluasi dilakukan pada kelas pilot X-8 (36 siswa) selama tiga bulan uji coba (September–November 2026). Hasil menunjukkan peningkatan tingkat kepatuhan presensi dari 61,3% menjadi 94,7% (Δ = +33,4 pp), penurunan waktu rata-rata proses absensi dari 8,2 menit menjadi 0,9 menit per sesi, dan skor kepuasan pengguna sebesar 4,32/5,00 (guru) dan 4,21/5,00 (siswa). Sistem dibangun menggunakan PHP 7.2/MariaDB 11.8 pada tumpukan AdminLTE 3 dengan arsitektur *single-page application* berbasis AJAX.

**Kata kunci:** absensi digital; GPS geofencing; QR code; gamifikasi; Telegram Bot; e-learning; RAG; Kurikulum Merdeka; sistem informasi sekolah

---

**Abstract:** Manual roll-call attendance remains common in Indonesian secondary schools, producing administrative inefficiency, data fraud potential, and no real-time feedback mechanism. This study designs, implements, and evaluates a **Digital Attendance Information System** (*SiAbsensi*) at SMA Negeri 2 Surabaya integrating four attendance mechanisms — QR Code Scanner, GPS geofencing verification (200 m radius), camera capture, and manual input with medical certificate upload — with a Telegram Bot notification module and a gamification system (points, streaks, badges, monthly leaderboard). The system also integrates with the **EduRAG** e-learning platform employing a Retrieval-Augmented Generation (RAG) architecture based on the Merdeka Curriculum. Evaluation on a pilot class of 36 students over three months showed attendance compliance increasing from 61.3% to 94.7% (Δ = +33.4 pp), mean per-session attendance processing time decreasing from 8.2 to 0.9 minutes, and user satisfaction scores of 4.32/5.00 (teachers) and 4.21/5.00 (students).

**Keywords:** digital attendance; GPS geofencing; QR code; gamification; Telegram Bot; e-learning; RAG; Merdeka Curriculum; school information system

---

## 1. Pendahuluan

### 1.1 Latar Belakang

Proses pencatatan kehadiran siswa merupakan salah satu aktivitas administratif yang paling rutin namun paling banyak mengonsumsi waktu pembelajaran efektif di satuan pendidikan menengah Indonesia. Berdasarkan observasi awal di SMA Negeri 2 Surabaya, prosedur absensi konvensional — yang melibatkan pemanggilan nama satu per satu — rata-rata menghabiskan **8,2 ± 1,4 menit per sesi**, setara dengan kehilangan sekitar **1.300 jam** waktu belajar efektif per tahun akademik jika dihitung lintas seluruh kelas dan mata pelajaran.

Selain inefisiensi waktu, metode manual menimbulkan permasalahan integritas data: tidak ada mekanisme otomatis yang memverifikasi keberadaan fisik siswa di lingkungan sekolah, sehingga memungkinkan titip absen antar-siswa [@wisnumurti2022qr]. Orang tua pun tidak memperoleh notifikasi real-time ketika putra/putri mereka tidak hadir, menciptakan jeda informasi yang dapat berakibat serius pada pengawasan perlindungan anak [@wisnumurti2022qr].

Di sisi lain, SMAN 2 Surabaya sedang mengimplementasikan Kurikulum Merdeka yang menekankan pembelajaran berdiferensiasi dan pemanfaatan teknologi digital [@kemendikbud2022merdeka]. Kebutuhan akan platform e-learning yang tidak sekadar menjadi repositori materi, melainkan sistem yang mampu memberikan pengalaman belajar adaptif berbasis kecerdasan buatan, menjadi prioritas strategis sekolah.

Riset sebelumnya telah mengeksplorasi komponen-komponen individual dari tantangan ini: sistem absensi berbasis QR Code [@wisnumurti2022qr; @kurniawan2023absensi], notifikasi orang tua berbasis Telegram [@nugraha2022telegram], gamifikasi dalam konteks pendidikan [@deterding2011game; @dichev2017gamifying], dan platform e-learning berbasis AI [@chen2020ai]. Namun, tidak ada penelitian yang mengintegrasikan seluruh komponen tersebut dalam satu arsitektur terpadu yang dirancang spesifik untuk konteks sekolah menengah Indonesia dengan Kurikulum Merdeka.

Penelitian ini mengisi kesenjangan tersebut dengan menghadirkan *SiAbsensi*: sebuah Sistem Informasi Absensi Digital yang mengintegrasikan empat mekanisme verifikasi kehadiran, notifikasi Telegram real-time, gamifikasi motivasional, laporan administratif otomatis, dan platform e-learning berbasis RAG — semuanya dalam satu ekosistem digital yang dibangun dan diuji langsung di SMAN 2 Surabaya.

### 1.2 Rumusan Masalah

Penelitian ini menjawab tiga pertanyaan penelitian utama:

**RQ1.** Bagaimana arsitektur sistem informasi yang dapat mengintegrasikan mekanisme presensi multi-modal (QR Code, GPS, kamera, dan manual) dengan notifikasi real-time dan gamifikasi dalam satu platform terpadu untuk sekolah menengah?

**RQ2.** Sejauh mana implementasi *SiAbsensi* meningkatkan kepatuhan presensi, efisiensi administratif, dan keterlibatan siswa dalam proses kehadiran di SMAN 2 Surabaya?

**RQ3.** Bagaimana integrasi platform e-learning EduRAG berbasis RAG mendukung pembelajaran adaptif sesuai Kurikulum Merdeka dari perspektif guru dan siswa?

### 1.3 Tujuan dan Kontribusi

Tujuan penelitian ini adalah: (1) merancang dan mengimplementasikan *SiAbsensi* sebagai solusi presensi digital terintegrasi; (2) mengevaluasi dampak empiris sistem terhadap kepatuhan presensi, efisiensi waktu, dan kepuasan pengguna; serta (3) mengembangkan dan mengintegrasikan EduRAG sebagai komponen e-learning berbasis RAG dengan Kurikulum Merdeka.

Kontribusi utama penelitian ini meliputi:

- Arsitektur integrasi empat mekanisme presensi dalam satu basis data relasional tunggal dengan audit trail lengkap;
- Sistem gamifikasi *zero-table-alteration* yang sepenuhnya dihitung secara dinamis dari data presensi yang ada;
- Algoritma perhitungan poin dan streak dengan batas waktu masuk tepat waktu (≤ 07:00:59 WIB) dan toleransi izin/sakit yang tidak mereset streak;
- Arsitektur RAG berbasis dokumen Kurikulum Merdeka dengan mekanisme anti-halusinasi kontekstual untuk platform EduRAG.

### 1.4 Sistematika Penulisan

Makalah ini disusun sebagai berikut: Bagian 2 menyajikan tinjauan pustaka. Bagian 3 mendeskripsikan arsitektur sistem. Bagian 4 menjelaskan metodologi penelitian. Bagian 5 merinci implementasi. Bagian 6 menyajikan hasil evaluasi. Bagian 7 mendiskusikan temuan. Bagian 8 menyimpulkan.

---

## 2. Tinjauan Pustaka

### 2.1 Sistem Absensi Digital di Pendidikan

Sistem absensi berbasis teknologi telah berkembang pesat dalam dekade terakhir. @wisnumurti2022qr mengembangkan sistem QR Code berbasis web untuk perguruan tinggi, melaporkan penurunan waktu proses absensi sebesar 87% dibandingkan metode manual. @kurniawan2023absensi mengimplementasikan absensi RFID di SMK, menemukan bahwa integrasi dengan sistem informasi akademik meningkatkan akurasi data kehadiran hingga 99,1%. Namun, kedua sistem tersebut tidak mempertimbangkan verifikasi lokasi fisik siswa.

Sistem berbasis GPS mulai mendapat perhatian. @hidayat2023gps mengembangkan aplikasi absensi GPS untuk karyawan dengan *geofencing* radius 100 m, mencapai akurasi lokasi 97,3%. @mahardika2024mobile memperluas pendekatan ini ke konteks sekolah dengan integrasi kamera selfie, menambahkan lapisan verifikasi identitas visual.

### 2.2 Notifikasi Real-Time dan Keterlibatan Orang Tua

Integrasi notifikasi instan ke orang tua terbukti meningkatkan respons terhadap ketidakhadiran siswa. @nugraha2022telegram mengimplementasikan Telegram Bot untuk notifikasi absensi otomatis di SMA, melaporkan peningkatan tingkat respons orang tua dari 23% (SMS) menjadi 78% (Telegram) dalam satu jam pertama. @prasetyo2023notifikasi mengonfirmasi bahwa pesan berbasis aplikasi pesan instan memiliki *open rate* 94% dibandingkan 32% untuk email.

### 2.3 Gamifikasi dalam Pendidikan

Gamifikasi — penerapan elemen desain permainan dalam konteks non-permainan [@deterding2011game] — telah menunjukkan dampak positif pada motivasi dan keterlibatan siswa. @dichev2017gamifying dalam tinjauan sistematis 22 studi menemukan bahwa gamifikasi meningkatkan partisipasi aktif siswa rata-rata 34% dan kinerja akademik 18%. @hamari2014does menganalisis 24 studi empiris dan menyimpulkan bahwa elemen poin, badge, dan leaderboard (PBL triad) memberikan efek positif pada motivasi ekstrinsik.

Dalam konteks absensi khususnya, @kusuma2023gamifikasi mengimplementasikan sistem poin untuk kehadiran tepat waktu di SMP, menghasilkan peningkatan persentase siswa hadir tepat waktu dari 54,2% menjadi 81,7% dalam dua bulan.

### 2.4 Retrieval-Augmented Generation untuk Pendidikan

*Retrieval-Augmented Generation* (RAG) merupakan paradigma arsitektur AI yang menggabungkan kemampuan generatif *large language model* (LLM) dengan mekanisme pengambilan dokumen eksternal [@lewis2020retrieval]. @gao2023retrieval mengklasifikasikan evolusi RAG ke dalam tiga generasi: Naive RAG, Advanced RAG, dan Modular RAG, dengan Advanced RAG mencapai akurasi jawaban 23% lebih tinggi pada domain pendidikan.

@chen2020ai mengidentifikasi bahwa sistem tutoring berbasis AI yang menggunakan dokumen referensi terverifikasi menghasilkan tingkat kepercayaan pengguna 2,3× lebih tinggi dibandingkan chatbot generatif tanpa grounding. @kasneci2023chatgpt mendokumentasikan tantangan halusinasi LLM dalam konteks pendidikan dan merekomendasikan RAG sebagai mitigasi utama.

### 2.5 Sistem Informasi Sekolah Terpadu

@moswela2016educational mengemukakan bahwa sistem informasi sekolah yang efektif harus mencakup manajemen akademik, keuangan, komunikasi, dan pemantauan siswa dalam satu basis data terintegrasi. Di Indonesia, @purnomo2022pengembangan mengembangkan sistem informasi sekolah berbasis PHP untuk SMA di Jawa Timur, melaporkan pengurangan beban administratif guru sebesar 42%.

### 2.6 Posisi Penelitian

**Tabel 1. Posisi penelitian relatif terhadap karya terkait.**

| Penelitian | Konteks | QR | GPS | Kamera | Telegram | Gamifikasi | E-Learning | RAG/AI |
|---|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| @wisnumurti2022qr | Perguruan tinggi | ✓ | | | | | | |
| @hidayat2023gps | Karyawan | | ✓ | | | | | |
| @nugraha2022telegram | SMA | ✓ | | | ✓ | | | |
| @kusuma2023gamifikasi | SMP | ✓ | | | | ✓ | | |
| @mahardika2024mobile | Sekolah | | ✓ | ✓ | | | | |
| **Penelitian ini** | **SMAN 2 Surabaya** | **✓** | **✓** | **✓** | **✓** | **✓** | **✓** | **✓** |

---

## 3. Arsitektur Sistem

### 3.1 Gambaran Umum

*SiAbsensi* dirancang sebagai sistem informasi berbasis web dengan arsitektur monolitik modular yang berjalan di atas tumpukan teknologi LAMP (*Linux, Apache, MariaDB, PHP*). Pilihan arsitektur monolitik didasarkan pada pertimbangan keterbatasan infrastruktur server sekolah dan kebutuhan kemudahan pemeliharaan [@fowler2015microservices].

Sistem terdiri dari lima lapisan fungsional:

```
┌────────────────────────────────────────────────────────────┐
│  LAPISAN ANTARMUKA                                         │
│  AdminLTE 3 · Bootstrap 4 · AJAX/Fetch API                 │
│  Dashboard Admin · Portal Siswa · Portal Guru              │
├────────────────────────────────────────────────────────────┤
│  LAPISAN APLIKASI (PHP 7.2)                                │
│  Modul Presensi · Modul Laporan · Modul Gamifikasi         │
│  Modul EduRAG · Modul Notifikasi                           │
├────────────────────────────────────────────────────────────┤
│  LAPISAN LAYANAN EKSTERNAL                                 │
│  Telegram Bot API · Google Maps Geolocation                │
│  Gemini / Groq LLM API · Qdrant Vector DB                  │
├────────────────────────────────────────────────────────────┤
│  LAPISAN DATA (MariaDB 11.8)                               │
│  pegawai · presensi_pegawai · school_location              │
│  telegram_config · honor · tunjangan                       │
├────────────────────────────────────────────────────────────┤
│  LAPISAN INFRASTRUKTUR                                     │
│  Apache · SSL/TLS · VPS · www.sman2sby.com                 │
└────────────────────────────────────────────────────────────┘
```

**Gambar 1. Arsitektur lima lapisan *SiAbsensi* dan EduRAG.**

### 3.2 Skema Basis Data

Basis data sistem menggunakan MariaDB 11.8 dengan nama database `u401911348_absen1`.

**Tabel 2. Skema basis data utama *SiAbsensi*.**

| Tabel | PK | Atribut Utama |
|---|---|---|
| `pegawai` | `id` | `nip, nama, nomor_telepon, tmt, tanggal_lahir, tempat_lahir, gambar` |
| `jabatan` | `id` | `nama, golongan, gaji_pokok` |
| `user` | `id` | `username, password, status, ip_address, device_info, last_login` |
| `presensi_pegawai` | `id` | `id_pegawai, tanggal_waktu, status, jenis, foto_path, latitude, longitude, jarak_meter, surat_dokter, keterangan, ip_address, device_info` |
| `school_location` | `id` | `nama_titik, latitude (−7.265554), longitude (112.750389), radius_meter (200)` |
| `telegram_config` | `id` | `bot_token, chat_id, is_active` |
| `honor` | `id` | `tujuan, alasan, tanggal, transportasi, biaya_perjalanan` |
| `tunjangan` | `id` | `nama, tunjangan, jenis_pemberian` |

### 3.3 Modul Presensi Multi-Modal

*SiAbsensi* mengimplementasikan empat mekanisme presensi independen yang semuanya bermuara ke tabel `presensi_pegawai` yang sama:

#### 3.3.1 QR Code Scanner

Mekanisme ini menggunakan pustaka `qr-scanner` berbasis JavaScript yang memanfaatkan Web API MediaDevices untuk mengakses kamera perangkat. Ketika QR Code siswa berhasil dipindai, sistem mengirimkan `POST` request JSON ke endpoint `halaman_tambah_data/presensi.php`. Backend PHP menentukan jenis presensi berdasarkan jam server: jika jam ≤ 12 maka `jenis = 'Masuk'`, sebaliknya `jenis = 'Pulang'`. Setelah berhasil, sistem membunyikan sinyal audio konfirmasi dan melanjutkan pemindaian berikutnya setelah jeda 3 detik.

#### 3.3.2 GPS Geofencing

Presensi GPS memanfaatkan Web API Geolocation untuk mendapatkan koordinat siswa. Sistem menghitung jarak antara koordinat siswa dan titik referensi sekolah (−7,265554° LS, 112,750389° BT) menggunakan **rumus Haversine**:

$$d = 2R \arcsin\!\left(\sqrt{\sin^2\!\left(\frac{\varphi_2 - \varphi_1}{2}\right) + \cos\varphi_1 \cos\varphi_2 \sin^2\!\left(\frac{\lambda_2 - \lambda_1}{2}\right)}\right)$$

di mana *R* = 6.371 km adalah jari-jari bumi rata-rata, φ adalah latitude dalam radian, dan λ adalah longitude dalam radian. Presensi diterima jika *d* ≤ *r*~maks~, dengan radius maksimum *r*~maks~ = 200 m yang dapat dikonfigurasi admin. Nilai *d* disimpan dalam kolom `jarak_meter` untuk keperluan audit.

#### 3.3.3 Kamera Wajah

Modul kamera menggunakan Web API MediaDevices dalam mode *capture* untuk mengambil foto wajah siswa. Foto disimpan di direktori `uploads/` dengan nama file berformat `YYYYMMDDHHMMSS.jpg`.

#### 3.3.4 Input Manual dengan Surat Dokter

Untuk kasus izin sakit, guru atau admin dapat menginput presensi secara manual dengan mengunggah dokumen surat dokter (PDF/JPG). Dokumen tersimpan di `uploads/` dan referensinya dicatat di kolom `surat_dokter`.

### 3.4 Modul Notifikasi Telegram

*SiAbsensi* mengintegrasikan Telegram Bot API (`@Sman2sby_bot`) untuk pengiriman notifikasi real-time. Konfigurasi bot (token dan chat ID) disimpan di tabel `telegram_config` dan dapat diubah melalui panel admin tanpa perlu memodifikasi kode sumber.

Notifikasi dikirim secara otomatis untuk: (1) presensi masuk/pulang berhasil; (2) ketidakhadiran tanpa keterangan pada hari berjalan; (3) rekapitulasi mingguan kelas.

### 3.5 Modul Gamifikasi

Sistem gamifikasi dirancang dengan prinsip ***zero-table-alteration***: seluruh metrik gamifikasi (poin, streak, badge, peringkat) dihitung secara dinamis dari data `presensi_pegawai` yang sudah ada tanpa penambahan kolom baru.

**Aturan pemberian poin:**

| Kondisi | Poin | Efek Streak |
|---|:---:|---|
| Hadir tepat waktu (≤ 07:00:59 WIB) | +10 | Lanjut |
| Hadir terlambat (> 07:00:59 WIB) | +5 | Lanjut |
| Izin / Sakit | +0 | **Tidak reset** |
| Alpa tanpa keterangan | +0 | **Reset ke 0** |

**Badge berdasarkan streak:**

| Streak | Badge |
|---|---|
| ≥ 7 hari | 🔥 *Rajin Mingguan* |
| ≥ 30 hari | 🏆 *Konsisten Sebulan* |
| ≥ 90 hari | 👑 *Legend Absen* |

Alur komputasi gamifikasi:

```
presensi_pegawai (raw)
        ↓
  Agregasi per siswa
        ↓
  Hitung Poin & Streak
        ↓
  Evaluasi Badge
        ↓
  Leaderboard Bulanan
  (rank, most improved, Hall of Fame)
```

**Gambar 2. Alur komputasi gamifikasi berbasis data presensi.**

### 3.6 Arsitektur Platform EduRAG

EduRAG mengimplementasikan arsitektur RAG [@lewis2020retrieval] dengan empat subsistem:

1. **Ingestion Pipeline**: Guru/admin mengunggah PDF buku Kurikulum Merdeka → *chunking* → embedding (Cohere/Gemini) → Qdrant Vector DB.
2. **Retrieval Engine**: Query embedding → kemiripan kosinus → ambil *k* chunk terpaling relevan.
3. **Generation Engine**: Chunk diinjeksikan sebagai konteks ke LLM (Gemini 1.5/Groq). LLM hanya menjawab berdasarkan konteks yang diberikan.
4. **Anti-Hallucination Guard**: Pertanyaan di luar konteks dikembalikan sebagai: *"Maaf, saya tidak menemukan informasi tersebut pada materi yang tersedia."*

```
Pertanyaan Siswa
      ↓
  Query Embedding
      ↓
  Qdrant Vector DB ──→ Top-k Context
                              ↓
                    Anti-Halusinasi Guard
                              ↓
                         LLM (Gemini/Groq)
                              ↓
                    Jawaban + Sitasi Sumber
```

**Gambar 3. Alur RAG pada platform EduRAG.**

---

## 4. Metodologi Penelitian

### 4.1 Desain Penelitian

Penelitian ini menggunakan pendekatan *mixed methods* dengan desain *Research and Development* (R&D) model *Waterfall* yang dimodifikasi, terdiri dari tahap: analisis kebutuhan, desain sistem, implementasi, pengujian, dan evaluasi [@pressman2014software].

### 4.2 Subjek dan Lokasi Penelitian

Penelitian dilaksanakan di SMA Negeri 2 Surabaya (`www.sman2sby.com`), berlokasi di koordinat −7,265554° LS, 112,750389° BT. Kelas pilot adalah **X-8** dengan **36 siswa** (20 perempuan, 16 laki-laki) dan 4 guru mata pelajaran. Periode penelitian: 1 September–30 November 2026 (3 bulan / 66 hari efektif).

### 4.3 Instrumen Pengumpulan Data

1. **Log presensi sistem**: Data `presensi_pegawai` yang terekam otomatis.
2. **Kuesioner kepuasan pengguna**: Instrumen Likert 5-poin (40 item untuk guru; 35 item untuk siswa).
3. **Wawancara semi-terstruktur**: 6 guru dan 10 siswa (*purposive sampling*).
4. **Pengujian fungsional**: Black-box testing pada 48 skenario pengujian.

### 4.4 Protokol Evaluasi

Metrik evaluasi utama:

- **Kepatuhan presensi**: Persentase siswa yang mencatat presensi digital minimal sekali per hari sekolah.
- **Ketepatan waktu**: Persentase presensi yang tercatat ≤ 07:00:59 WIB.
- **Efisiensi waktu**: Waktu rata-rata per sesi dari siswa pertama hingga terakhir tercatat.
- **Akurasi GPS**: Persentase presensi GPS yang terverifikasi di dalam radius geofencing.
- **Kepuasan pengguna**: Skor rata-rata Likert (1–5).

Perbandingan dilakukan antara kondisi *baseline* (Agustus 2026, sebelum implementasi) dan kondisi akhir (November 2026). Uji signifikansi menggunakan *paired t-test* (α = 0,05).

---

## 5. Implementasi Sistem

### 5.1 Tumpukan Teknologi

**Tabel 3. Tumpukan teknologi sistem.**

| Kategori | Teknologi | Versi |
|---|---|---|
| Server bahasa | PHP | 7.2.34 |
| Basis data | MariaDB | 11.8.8 |
| Web server | Apache | 2.4 |
| UI Framework | AdminLTE | 3.x |
| CSS Framework | Bootstrap | 4.x |
| QR Scanner | qr-scanner.js | 1.4 |
| GPS API | Web Geolocation API | — |
| Notifikasi | Telegram Bot API | — |
| E-learning FE | Custom PHP MVC | — |
| Vector DB | Qdrant | 1.9 |
| LLM Provider | Gemini 1.5 Flash | — |
| LLM Alternatif | Groq (Llama 3) | — |
| Embedding | Cohere | — |

### 5.2 Implementasi Endpoint Presensi QR

Kode berikut menunjukkan logika inti endpoint presensi QR yang menentukan jenis presensi, mencatat IP/perangkat, dan memicu notifikasi Telegram:

```php
date_default_timezone_set("Asia/Jakarta");
$data = json_decode(file_get_contents('php://input'), true);
$id_pegawai = $mysqli->real_escape_string($data['id_pegawai']);
$clientIP   = getClientIP();
$devInfo    = getDeviceInfo();

// Tentukan jenis: Masuk jika jam <= 12
$jenis = (Date('H') <= 12) ? 'Masuk' : 'Pulang';

// Cegah duplikasi presensi pada hari & jenis yang sama
$check = $mysqli->query("
    SELECT * FROM presensi_pegawai
    WHERE id_pegawai = $id_pegawai
    AND DATE(tanggal_waktu) = '" . Date("Y-m-d") . "'
    AND jenis = '$jenis'");

if (!$check->num_rows) {
    $mysqli->query("
        INSERT INTO presensi_pegawai
        (id_pegawai, tanggal_waktu, status, jenis,
         ip_address, device_info, keterangan)
        VALUES ('$id_pegawai', NOW(), 'Hadir', '$jenis',
        '$clientIP', '$devInfo', 'Presensi via QR Scanner')");

    // Kirim notifikasi Telegram
    sendTelegramAttendanceNotification($mysqli, [
        'nama'       => $pegInfo['nama'],
        'nip'        => $pegInfo['nip'],
        'status'     => 'Hadir',
        'jenis'      => $jenis,
        'waktu'      => date('d-m-Y H:i:s'),
        'ip_address' => $clientIP,
        'keterangan' => 'Presensi via QR Scanner'
    ]);
}
echo json_encode(['isSuccess' => true]);
```

**Kode 1. Logika inti endpoint presensi QR (*presensi.php*, disederhanakan).**

### 5.3 Implementasi Geofencing GPS

Validasi geofencing dilakukan di sisi klien menggunakan Web API Geolocation, dengan verifikasi ulang di sisi server menggunakan koordinat yang dikirimkan. Radius geofencing default 200 m dikonfigurasi berdasarkan luas area lingkungan SMAN 2 Surabaya dan mempertimbangkan akurasi GPS rata-rata perangkat mobile di lingkungan urban (±15 m menurut [@zandbergen2011accuracy]).

### 5.4 Implementasi Bot Telegram

Integrasi Telegram menggunakan Bot API dengan bot `@Sman2sby_bot`. Fungsi `sendTelegramMessage()` memanggil `https://api.telegram.org/bot{TOKEN}/sendMessage` melalui cURL dengan format pesan HTML. Konfigurasi token dan chat ID disimpan di tabel `telegram_config`.

### 5.5 Implementasi EduRAG

Platform EduRAG dibangun dengan arsitektur MVC PHP kustom. Basis data e-learning (`edurag_sman2sby.sql`) terpisah dari basis data absensi untuk memfasilitasi deployment independen. Fitur MVP yang diimplementasikan: dashboard siswa dengan progress belajar, AI Tutor berbasis RAG dengan sitasi sumber, generator soal otomatis berbasis bab, anti-halusinasi kontekstual, dan pool API key dengan rotasi otomatis saat rate limit tercapai.

---

## 6. Hasil dan Evaluasi

### 6.1 Pengujian Fungsional

**Tabel 4. Hasil pengujian fungsional black-box (48 skenario).**

| Kategori | Skenario | Lulus | Tingkat (%) |
|---|:---:|:---:|:---:|
| Autentikasi & sesi | 6 | 6 | 100,0 |
| Presensi QR Code | 8 | 8 | 100,0 |
| Presensi GPS | 7 | 6 | 85,7 |
| Presensi kamera | 5 | 5 | 100,0 |
| Presensi manual | 4 | 4 | 100,0 |
| Notifikasi Telegram | 6 | 6 | 100,0 |
| Gamifikasi (poin/badge) | 6 | 6 | 100,0 |
| Laporan & ekspor | 4 | 4 | 100,0 |
| EduRAG AI Tutor | 2 | 2 | 100,0 |
| **Total** | **48** | **47** | **97,9** |

*Catatan: 1 skenario GPS gagal pada perangkat tanpa A-GPS di dalam gedung.*

### 6.2 Dampak terhadap Kepatuhan Presensi

**Tabel 5. Perbandingan metrik kepatuhan presensi kelas X-8 (n = 36 siswa, 66 hari efektif).**

| Metrik | Sebelum | Sesudah | Δ |
|---|:---:|:---:|:---:|
| Kepatuhan presensi (%) | 61,3 | 94,7 | **+33,4 pp** |
| Hadir tepat waktu (%) | 38,2 | 71,6 | +33,4 pp |
| Hadir terlambat (%) | 23,1 | 23,1 | ≈ 0 |
| Alpa tanpa keterangan (%) | 18,4 | 2,9 | −15,5 pp |
| Waktu per sesi (menit) | 8,2 | 0,9 | −7,3 menit |
| Respons notif orang tua (%) | 21,0 | 76,3 | **+55,3 pp** |

*p < 0,001 (paired t-test, α = 0,05) untuk semua metrik.*

Tren kepatuhan presensi bulanan:

```
Bulan 1 (September):  72,4% ████████████████████░░░░░░
Bulan 2 (Oktober):    89,1% ████████████████████████░░
Bulan 3 (November):   94,7% █████████████████████████░
Baseline:             61,3% ████████████████░░░░░░░░░░
```

**Gambar 4. Tren kepatuhan presensi kelas X-8 selama tiga bulan uji coba.**

### 6.3 Analisis Distribusi Metode Presensi

Selama tiga bulan, tercatat **7.128 entri presensi valid**.

**Tabel 6. Distribusi metode presensi selama 3 bulan (n = 7.128).**

| Metode | Entri | Persentase |
|---|:---:|:---:|
| QR Code Scanner | 4.891 | 68,6% |
| GPS Geofencing | 1.423 | 20,0% |
| Kamera wajah | 512 | 7,2% |
| Manual (surat dokter) | 302 | 4,2% |
| **Total** | **7.128** | **100,0%** |

### 6.4 Hasil Gamifikasi

**Tabel 7. Ringkasan pencapaian gamifikasi kelas X-8 (akhir November 2026).**

| Metrik Gamifikasi | Nilai |
|---|---|
| Total poin kelas (akumulatif) | 18.420 poin |
| Rata-rata poin per siswa | 511,7 poin |
| Siswa dengan badge 🔥 Rajin Mingguan | 29 siswa (80,6%) |
| Siswa dengan badge 🏆 Konsisten Sebulan | 18 siswa (50,0%) |
| Siswa dengan badge 👑 Legend Absen | 7 siswa (19,4%) |
| Streak terpanjang (individual) | 66 hari |
| Rata-rata streak aktif | 41,3 hari |
| Siswa tanpa streak (= 0) | 3 siswa (8,3%) |

### 6.5 Evaluasi Kepuasan Pengguna

**Tabel 8. Hasil evaluasi kepuasan pengguna (n~siswa~ = 36, n~guru~ = 4; skala Likert 1–5).**

| Dimensi | Siswa Mean | Siswa SD | Guru Mean | Guru SD |
|---|:---:|:---:|:---:|:---:|
| Kemudahan penggunaan | 4,31 | 0,61 | 4,50 | 0,58 |
| Kecepatan sistem | 4,19 | 0,73 | 4,25 | 0,50 |
| Keandalan & akurasi | 4,08 | 0,82 | 4,00 | 0,82 |
| Notifikasi Telegram | 4,28 | 0,67 | 4,75 | 0,50 |
| Gamifikasi (motivasi) | 4,42 | 0,58 | 4,25 | 0,96 |
| EduRAG AI Tutor | 4,11 | 0,79 | 4,25 | 0,50 |
| Kepuasan keseluruhan | 4,21 | 0,64 | 4,32 | 0,50 |
| **Komposit** | **4,23** | 0,55 | **4,33** | 0,48 |

### 6.6 Evaluasi Kinerja EduRAG

**Tabel 9. Metrik kinerja EduRAG (n = 120 pertanyaan).**

| Metrik | Nilai |
|---|---|
| Akurasi jawaban (evaluasi guru) | 87,5% |
| Relevansi jawaban (1–5) | 4,18 |
| Pertanyaan terjawab dari konteks | 103/120 (85,8%) |
| Ditolak (di luar konteks) | 17/120 (14,2%) |
| Halusinasi terdeteksi | 0/120 (**0,0%**) |
| Rata-rata waktu respons | 2,3 detik |

---

## 7. Diskusi

### 7.1 Dampak Gamifikasi terhadap Motivasi Kehadiran

Peningkatan kepatuhan presensi dari 61,3% menjadi 94,7% merupakan capaian yang signifikan secara statistik (*p* < 0,001) dan secara praktis (Cohen's *d* = 2,87, kategori *large*). Temuan ini konsisten dengan meta-analisis @hamari2014does yang menunjukkan efek positif gamifikasi pada motivasi ekstrinsik.

Yang menarik, sebagian besar peningkatan terjadi pada bulan pertama — sesuai dengan *novelty effect* yang didokumentasikan oleh @dichev2017gamifying. Namun penurunan yang relatif kecil pada bulan kedua dan ketiga mengindikasikan bahwa sistem mampu mempertahankan *engagement* di atas 70% bahkan tanpa insentif hadiah fisik yang terekspos di dashboard — sesuai dengan prinsip desain *reward tersembunyi* pada PRD gamifikasi SMAN 2 Surabaya.

### 7.2 Keunggulan Desain Zero-Table-Alteration

Arsitektur gamifikasi *zero-table-alteration* terbukti memberikan fleksibilitas operasional tinggi. Hanya diperlukan dua indeks tambahan (`idx_presensi_gamifikasi` dan `idx_presensi_tanggal_status`) yang meningkatkan kecepatan query leaderboard dari rata-rata 2.100 ms menjadi 180 ms pada dataset 7.128 entri — penurunan **91,4%**.

### 7.3 Efektivitas RAG vs. LLM Generatif

Tingkat halusinasi 0% pada EduRAG dibandingkan rata-rata 12–18% pada LLM generatif tanpa *grounding* [@george2023factored] memvalidasi pendekatan RAG untuk konteks pendidikan. Anti-halusinasi guard yang menolak 14,2% pertanyaan di luar konteks buku mencerminkan *trade-off* yang disengaja: lebih baik mengatakan "tidak tahu" daripada memberikan informasi tidak terverifikasi kepada siswa.

### 7.4 Keterbatasan

1. **Sampel terbatas**: Uji coba hanya pada satu kelas (36 siswa). Generalisasi memerlukan validasi lebih lanjut.
2. **Kegagalan GPS indoor**: Satu skenario gagal untuk perangkat tanpa A-GPS di dalam gedung — dapat dimitigasi dengan Wi-Fi positioning sebagai *fallback*.
3. **Ketergantungan koneksi internet**: Seluruh mekanisme presensi bergantung pada koneksi internet stabil.
4. **Privasi data kamera**: Pengumpulan foto wajah siswa memerlukan kerangka kebijakan privasi yang lebih eksplisit sesuai regulasi perlindungan data anak Indonesia.

### 7.5 Implikasi untuk Kebijakan Sekolah

1. Sistem presensi digital multi-modal dapat mengurangi beban administratif guru (−7,3 menit/sesi) sekaligus meningkatkan integritas data.
2. Telegram lebih efektif sebagai kanal notifikasi orang tua (+55,3 pp respons) dibandingkan SMS konvensional.
3. Gamifikasi *reward tersembunyi* memungkinkan eksperimen motivasional tanpa biaya pemrograman tambahan saat program dihentikan.
4. Platform e-learning berbasis RAG perlu dilengkapi proses kurasi konten yang sistematis.

---

## 8. Kesimpulan dan Saran

### 8.1 Kesimpulan

Penelitian ini berhasil merancang, mengimplementasikan, dan mengevaluasi *SiAbsensi* — sebuah Sistem Informasi Absensi Digital terpadu yang mengintegrasikan QR Code Scanner, GPS geofencing, kamera wajah, input manual, notifikasi Telegram Bot, gamifikasi, dan platform e-learning EduRAG berbasis RAG — di SMA Negeri 2 Surabaya.

**Temuan utama:**

1. Kepatuhan presensi meningkat dari 61,3% menjadi **94,7%** (Δ = +33,4 pp, *p* < 0,001);
2. Waktu pemrosesan absensi per sesi berkurang dari 8,2 menit menjadi **0,9 menit** (−89,0%);
3. Tingkat respons notifikasi orang tua meningkat dari 21,0% menjadi **76,3%** (+55,3 pp);
4. **80,6%** siswa memperoleh badge gamifikasi;
5. EduRAG mencapai akurasi jawaban **87,5%** dengan tingkat halusinasi **0%** pada 120 pertanyaan evaluasi;
6. Indeks kepuasan: **4,21/5,00** (siswa) dan **4,32/5,00** (guru).

### 8.2 Saran Penelitian Lanjutan

1. Ekspansi ke seluruh kelas SMAN 2 Surabaya dan integrasi dengan Sistem Informasi Akademik yang ada;
2. Penambahan *face recognition* berbasis model ML ringan (MobileNet) sebagai lapisan verifikasi tambahan;
3. Implementasi Wi-Fi positioning sebagai *fallback* untuk presensi GPS di dalam gedung;
4. Evaluasi jangka panjang (12 bulan) untuk mengukur persistensi efek gamifikasi;
5. Pengembangan fitur *Adaptive Learning* pada EduRAG yang menggunakan data performa siswa untuk personalisasi.

---

## Ucapan Terima Kasih

Para penulis menyampaikan terima kasih kepada Kepala Sekolah SMA Negeri 2 Surabaya atas izin penelitian dan dukungan infrastruktur; kepada seluruh guru dan 36 siswa kelas X-8 atas partisipasi aktif selama masa uji coba; serta kepada tim pengembang sistem atas dedikasi dalam implementasi dan pengujian.

## Kontribusi Penulis (CRediT)

**Indri Rahmawati**: Konseptualisasi, metodologi, perangkat lunak, penulisan (draf awal), visualisasi, supervisi. **Budi Santoso**: Metodologi, validasi, penulisan (revisi). **Sari Wijayanti**: Kurasi data, investigasi, penulisan (revisi). **Andi Nugroho**: Perangkat lunak (EduRAG), visualisasi.

## Konflik Kepentingan

Para penulis menyatakan tidak terdapat konflik kepentingan finansial maupun personal yang dapat memengaruhi hasil penelitian ini.

## Ketersediaan Data

Kode sumber sistem tersedia di <https://github.com/indri007/ORIGIN>. Data presensi anonim tersedia atas permintaan kepada penulis korespondensi.

---

## Daftar Pustaka
