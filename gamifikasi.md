PRODUCT REQUIREMENTS DOCUMENT — v4
Sistem Absensi Gamifikasi — Reward Tersembunyi
Masa Uji Coba 3 Bulan

Pemilik Produk: Indri
Sekolah / Mitra: SMA Negeri 2 Surabaya (www.sman2sby.com)
Kelas Pilot: X-8 — 36 siswa
Budget Reward: Rp 100.000/bulan, hanya Bulan 1–3 (masa uji coba), tidak ditampilkan di dashboard
Tanggal: 27 Agustus 2026
Status: Draft v4.0 — reward tersembunyi & transisi tanpa ubah kode


1. RINGKASAN EKSEKUTIF

Dokumen ini menggabungkan pendekatan dua versi sebelumnya: sistem tetap memakai poin, streak, dan leaderboard, dengan reward fisik bertujuan menciptakan antusiasme awal (buzz) selama masa uji coba 3 bulan pertama.

Perbedaan kunci pada versi ini: hadiah TIDAK PERNAH ditampilkan atau direferensikan di dashboard atau kode aplikasi — pemberiannya murni diumumkan manual oleh wali kelas di kelas.

Dengan begitu, saat masa uji coba berakhir dan hadiah dihentikan, tidak ada satu pun perubahan yang perlu dilakukan pada dashboard, database, atau logika sistem.

Yang berhenti hanyalah proses operasional di luar aplikasi (pembelian & pengumuman hadiah).


2. PRINSIP DESAIN KUNCI

• Pemisahan total antara sistem (poin/streak/leaderboard) dan reward (hadiah fisik) — keduanya tidak boleh saling bergantung secara teknis.

• Dashboard hanya menampilkan data gamifikasi murni: poin, peringkat, streak, badge. Tidak ada teks, ikon, atau notifikasi yang menyebut "hadiah", "reward", atau nominal apa pun.

• Pemberian hadiah dilakukan 100% di luar sistem — diumumkan lisan oleh wali kelas di kelas, berdasarkan data Top 3 yang dilihat manual dari dashboard.

• Karena hadiah tidak pernah "tertanam" di kode maupun tampilan, penghentiannya di bulan ke-4 tidak memerlukan deployment, update, atau perubahan UI apa pun — cukup wali kelas berhenti membelikan hadiah.


3. KONDISI SAAT INI (AS-IS)

• Data induk siswa & administrasi kelas dikelola manual di file Administrasi_Kelas_X8_2026-2027.xlsx oleh wali kelas.

• Sebagian siswa sudah punya akses aplikasi absen digital, namun adopsinya rendah — sebagian masih absen manual (dipanggil satu-satu / angkat tangan).

• Tidak ada mekanisme insentif maupun pengakuan; siswa yang rajin dan malas absen sama-sama tidak mendapat pembeda.

• Website sekolah www.sman2sby.com belum punya portal siswa yang menampilkan data partisipasi individu maupun kelas.


4. SKENARIO WAKTU: UJI COBA VS PASCA UJI COBA

FASE 1 — MASA UJI COBA (BULAN 1–3)

Langkah 1 — Sistem gamifikasi berjalan penuh

Poin, streak, badge, dan leaderboard aktif dan bisa dilihat semua siswa lewat dashboard login, persis seperti desain final — tidak ada elemen "versi uji coba" yang terlihat beda di UI.


Langkah 2 — Wali kelas cek Top 3 secara manual

Tiap akhir bulan, wali kelas melihat leaderboard di dashboard (bukan dari fitur khusus), lalu mencatat sendiri di luar sistem siapa Top 3 dan Most Improved.


Langkah 3 — Hadiah diberikan & diumumkan lisan

Hadiah dari budget Rp 100.000/bulan dibelikan wali kelas dan diumumkan langsung di kelas.

Contoh:
"Selamat untuk 3 besar bulan ini..."

Tanpa ada catatan digital, notifikasi app, atau tampilan apa pun di dashboard yang menyebut hadiah tersebut.


Langkah 4 — Tujuan fase ini

Menciptakan kebiasaan rutin cek leaderboard dan rasa kompetisi yang tinggi di awal, saat kebiasaan absen tepat waktu belum terbentuk.


FASE 2 — PASCA UJI COBA (BULAN 4 DAN SETERUSNYA)

Langkah 1 — Hadiah dihentikan tanpa pengumuman formal

Wali kelas berhenti membelikan hadiah bulanan.

Karena hadiah tidak pernah muncul di dashboard, tidak ada "fitur yang hilang" secara teknis — pengalaman aplikasi identik sebelum dan sesudah.


Langkah 2 — Transisi bertahap disarankan

Untuk memperhalus dampak psikologis, wali kelas bisa mengurangi frekuensi/nilai hadiah secara bertahap di bulan ke-3.

Misalnya hanya Juara 1 yang mendapatkan hadiah, bukan Top 3 penuh, sebelum benar-benar berhenti di bulan ke-4.


Langkah 3 — Motivasi beralih ke pengakuan sosial

Hall of Fame, badge streak, notifikasi kenaikan peringkat, dan pengumuman rutin wali kelas tiap Senin menjadi pendorong motivasi utama menggantikan hadiah fisik.


Langkah 4 — Tidak ada perubahan sistem

Tim teknis tidak perlu deploy ulang, ubah database, atau redesain dashboard.

Sistem yang dipakai di bulan ke-4 secara teknis 100% sama dengan yang dipakai di bulan ke-1.


5. SKENARIO PENGGABUNGAN DENGAN PROSES YANG SUDAH BERJALAN

MINGGU 1–2 — TRANSISI DATA GANDA

• Excel manual (Administrasi_Kelas_X8_2026-2027.xlsx) tetap diisi wali kelas sebagai arsip resmi.

• Data absen digital mulai ditarik otomatis ke sheet Leaderboard_X8, dihitung poin & streak-nya, berjalan paralel tanpa menggantikan Excel.

• Wali kelas cek selisih data 5 menit tiap pagi, koreksi di Leaderboard_X8 jika ada perbedaan (mis. siswa izin belum tercatat).


MINGGU 3–4 — DASHBOARD LOGIN AKTIF

• 36 siswa login memakai akun yang sama dengan aplikasi absen digital.

• Dashboard menampilkan Top 3, posisi pribadi, dan badge streak — tanpa elemen hadiah apa pun.


BULAN 2+ — INTEGRASI KE www.sman2sby.com

• Leaderboard & Hall of Fame di-embed sebagai portal resmi di website sekolah.

• Excel manual beralih fungsi menjadi arsip cadangan setelah data digital terbukti stabil selama satu bulan.


6. DASHBOARD LOGIN — SPESIFIKASI TAMPILAN
(BERLAKU SEPANJANG WAKTU, TIDAK BERUBAH)

• Widget utama:
  Nama + total poin bulan berjalan (Top 3 kelas).
  🥇 🥈 🥉

• Baris personal:
  Posisi siswa yang login + progress bar streak.

• Notifikasi kenaikan peringkat:
  "Kamu naik ke posisi #5!"

• Tab Hall of Fame:
  Riwayat siapa saja yang pernah Top 3 / Most Improved.

CATATAN PENTING:

Seluruh elemen di atas identik baik selama maupun setelah masa uji coba.

Ini yang memastikan penghentian hadiah tidak memerlukan perubahan kode.


7. SISTEM POIN & BADGE
(36 SISWA)

KONDISI POIN

Absen tepat waktu          +10
Absen terlambat             +5
Tidak absen tanpa keterangan  0
Izin/sakit dengan bukti      Tidak dipotong, streak tidak reset


BADGE STREAK

7 hari beruntun
🔥 Rajin Mingguan

30 hari beruntun
🏆 Konsisten Sebulan

90 hari beruntun
👑 Legend Absen


8. ALOKASI BUDGET HADIAH — RP 100.000/BULAN
(HANYA BULAN 1–3)

🥇 Juara 1
Snack/minuman
Rp 15.000
Diberikan langsung, tanpa dicatat di app.

🥈 Juara 2
Snack/minuman
Rp 10.000

🥉 Juara 3
Snack ringan
Rp 7.000

Most Improved
Kenaikan poin terbesar bulan ini
Rp 15.000

Streak 30 hari (jika ada)
Reward khusus konsistensi
Rp 15.000

Buffer / seri nilai
Cadangan kalau ada siswa nilai sama
Rp 20.000

Cadangan bulan ke-3 (transisi halus)
Dikurangi bertahap sebelum dihentikan bulan ke-4
Rp 18.000


TOTAL:
Rp 100.000/bulan

Berlaku hanya untuk Bulan 1–3.

Bulan ke-3 disarankan mulai dikurangi cakupannya, misalnya hanya Juara 1 & Most Improved, sebagai persiapan transisi ke Fase 2.


9. KATEGORI PENGAKUAN NON-MATERI
(BERLAKU PERMANEN, SEJAK AWAL)

Top 3 Bulanan
Kriteria:
Poin tertinggi bulan berjalan

Bentuk Pengakuan:
Nama di Hall of Fame + bebas piket 1 hari


Most Improved
Kriteria:
Kenaikan poin terbesar dibanding bulan lalu

Bentuk Pengakuan:
Nama di Hall of Fame + pilih posisi duduk 1 minggu


Comeback of the Month
Kriteria:
Sempat drop lalu bangkit kembali rajin absen

Bentuk Pengakuan:
Pengumuman khusus wali kelas di kelas


Streak 30 & 90 hari
Kriteria:
Konsistensi tanpa putus

Bentuk Pengakuan:
Badge permanen di profil + disebut di pengumuman mingguan


10. METRIK KEBERHASILAN

• 36 siswa memiliki akun aktif di dashboard dalam 4 minggu pertama.

• Tingkat partisipasi absen digital naik minimal 20% selama masa uji coba (Bulan 1–3).

• Partisipasi tetap bertahan (tidak turun drastis) di Bulan 4–5 setelah hadiah dihentikan — indikator utama keberhasilan pendekatan ini.

• Selisih data antara Excel manual dan sistem digital turun ke nol dalam 2 minggu transisi.


11. RISIKO & MITIGASI

RISIKO:
Partisipasi turun drastis begitu hadiah berhenti di bulan ke-4.

MITIGASI:
Transisi bertahap di bulan ke-3 (kurangi cakupan hadiah) + perkuat pengumuman & notifikasi peringkat sejak awal.


RISIKO:
Siswa mengetahui dari mulut ke mulut bahwa hadiah akan berhenti, menurunkan semangat lebih awal.

MITIGASI:
Wali kelas tidak mengumumkan tanggal pasti penghentian; cukup informasikan bahwa "periode ini" adalah bagian dari uji coba.


RISIKO:
Data ganda (Excel & digital) selisih saat transisi.

MITIGASI:
Verifikasi harian 5 menit oleh wali kelas selama minggu 1–2.


RISIKO:
Tim/pihak lain mengira hadiah adalah fitur sistem dan meminta ditampilkan di dashboard.

MITIGASI:
Tegaskan di PRD ini bahwa hadiah adalah proses operasional wali kelas, bukan fitur produk.


12. TIMELINE RINGKAS

Minggu 1–2
Fase:
Transisi data ganda

Output:
Leaderboard_X8 berjalan paralel dengan Excel.


Minggu 3–4
Fase:
Dashboard login aktif

Output:
36 siswa melihat Top 3 & posisi pribadi saat login.


Bulan 1–3
Fase:
Uji coba dengan hadiah tersembunyi

Output:
Hadiah diberikan manual di kelas, budget Rp 100.000/bulan.


Bulan 3 (akhir)
Fase:
Transisi halus

Output:
Cakupan hadiah dikurangi bertahap (mis. hanya Juara 1).


Bulan 4+
Fase:
Pasca uji coba, tanpa hadiah

Output:
Sistem berjalan identik secara teknis, motivasi 100% non-materi.