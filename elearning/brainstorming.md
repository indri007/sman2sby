Saya sudah pelajari PPT **EduRAG Merdeka** dan saya gabungkan dengan hasil chat Anda dengan client. Secara konsep, aplikasi ini sebenarnya bisa dikembangkan jauh lebih lengkap dari sekadar “chat AI + generate soal”.

Di PPT, konsep awalnya adalah **platform belajar mandiri siswa + asisten mengajar guru berbasis AI**, dengan sumber utama Buku Siswa dan Buku Guru Kurikulum Merdeka. Fitur inti yang disebut adalah tanya-jawab materi, sitasi bab/halaman, generator bank soal, draft RPP, riwayat/ekspor, serta mekanisme anti-halusinasi. 

# Brainstorming Fitur Aplikasi EduRAG

## 1. Konsep Besar Aplikasi

Saya sarankan aplikasi dibagi menjadi **3 sisi pengguna**:

### A. Admin

Mengelola seluruh sistem dan sumber AI.

### B. Guru

Mengelola materi, kelas, soal, ujian, dan menggunakan AI sebagai asisten.

### C. Siswa

Belajar berdasarkan materi yang diberikan guru dan bisa menggunakan AI untuk memahami materi.

Jadi jangan membuat aplikasi hanya seperti ChatGPT.

Lebih bagus konsepnya:

> **Learning Management System + AI Tutor + AI Teacher Assistant**

---

# 2. Struktur Utama Aplikasi

Kurang lebih dashboard bisa seperti:

```text
EDURAG
│
├── Dashboard
│
├── Kelas
│   ├── Kelas Saya
│   ├── Materi
│   ├── Tugas
│   ├── Ujian
│   └── Siswa
│
├── AI Assistant
│   ├── Tanya Materi
│   ├── Generate Soal
│   ├── Generate Materi
│   └── Generate RPP / Modul
│
├── Bank Soal
│
├── Ujian
│
├── Tugas
│
├── Progress Belajar
│
├── Riwayat
│
└── Profile
```

Untuk admin:

```text
ADMIN
│
├── Dashboard
├── User
├── Guru
├── Siswa
├── Kelas
├── Mata Pelajaran
├── Buku / Materi
├── Bank Soal
├── AI Configuration
├── API Key
├── Usage AI
├── Logs
└── Settings
```

---

# 3. MODE GURU

Ini menurut saya bagian paling penting karena dari chat Anda, client memang tertarik dengan **otomatisasi pekerjaan guru**.

PPT sendiri menyebut masalah guru adalah menyusun soal dan RPP secara manual, sehingga AI diarahkan untuk membantu menghasilkan soal dan draft RPP dari buku resmi. 

## Dashboard Guru

Contoh:

```text
Selamat datang, Bu Indri

Kelas Saya
[ X IPA 1 ] [ X IPA 2 ] [ X IPA 3 ]

Quick Action

[ + Buat Materi ]
[ + Buat Soal ]
[ + Buat Ujian ]
[ 🤖 Generate dengan AI ]

Aktivitas Terbaru
- Ujian Matematika
- Materi Persamaan Linear
- Tugas Bab 3
```

---

# 4. Manajemen Kelas

Guru dapat membuat:

```text
Kelas X IPA 1

Mata Pelajaran:
Matematika

Jumlah Siswa:
32 siswa
```

Guru dapat:

* menambahkan siswa
* menghapus siswa
* melihat daftar siswa
* memberikan materi
* memberikan tugas
* membuat ujian
* melihat nilai
* melihat progress siswa

---

# 5. MATERI PEMBELAJARAN

Guru dapat membuat materi secara manual.

Contoh:

```text
Buat Materi

Judul:
Sistem Persamaan Linear

Mata Pelajaran:
Matematika

Bab:
Bab 3

Materi:
[Editor]

Lampiran:
PDF
Video
Gambar

Assign ke:
☑ X IPA 1
☑ X IPA 2
☐ X IPA 3

[ Simpan & Publish ]
```

Menariknya, materi bisa **di-assign ke beberapa kelas sekaligus**, seperti yang sudah Anda tawarkan ke client.

---

# 6. AI GENERATE MATERI

Selain manual, bisa ada:

**"Generate Materi dengan AI"**

Guru memilih:

```text
Mata Pelajaran
↓
Matematika

Bab
↓
Sistem Persamaan Linear

Target
↓
Kelas 10

Panjang materi
○ Singkat
● Sedang
○ Lengkap

[ Generate ]
```

AI menghasilkan:

```text
# Sistem Persamaan Linear

## Pengertian

...

## Konsep Dasar

...

## Contoh

...

## Latihan

...
```

Guru **tetap harus bisa edit sebelum publish**.

Ini penting karena AI jangan langsung menjadi sumber final.

---

# 7. AI GENERATE SOAL

Ini merupakan fitur yang paling jelas diminta client.

PPT juga secara eksplisit mencantumkan **Generator Bank Soal: soal pilihan ganda & esai otomatis per bab**. 

Flow:

```text
Generate Soal

Mata Pelajaran:
Matematika

Kelas:
10

Bab:
Sistem Persamaan Linear

Jumlah Soal:
20

Jenis:
☑ Pilihan Ganda
☑ Essay

Tingkat Kesulitan:
☐ Mudah
☑ Sedang
☐ Sulit

[ Generate ]
```

AI kemudian menghasilkan:

```text
1. Diketahui ...

A. ...
B. ...
C. ...
D. ...

Jawaban:
B

Pembahasan:
...
```

Guru bisa:

```text
[ Edit ]
[ Hapus ]
[ Generate Ulang ]
[ Simpan ke Bank Soal ]
```

---

# 8. Jangan Langsung Generate Ujian

Saya sarankan dibuat dua tahap:

### Tahap 1

AI → **Generate Bank Soal**

### Tahap 2

Guru → memilih soal → membuat ujian

Contohnya:

```text
BANK SOAL

☑ Soal 1
☑ Soal 2
☐ Soal 3
☑ Soal 4
...

[ Buat Ujian ]
```

Dengan begitu guru tetap punya kontrol.

---

# 9. AI GENERATE ROADMAP

Nah, ini menurut saya bisa menjadi fitur yang **lebih menarik daripada sekadar generate soal**.

Client menyebut:

> "bikin soal otomatis / roadmap mirip kita pakai NotebookLM"

Konsepnya bisa dibuat sebagai:

## AI Learning Roadmap

Guru/siswa memilih:

```text
Saya ingin belajar:

Matematika Kelas 10

Topik:
Persamaan Linear

Target:
Memahami materi untuk ujian

Waktu belajar:
7 hari
```

AI membuat:

```text
LEARNING ROADMAP

Hari 1
✓ Konsep dasar
✓ Istilah penting
○ Latihan 5 soal

Hari 2
✓ Persamaan linear satu variabel
○ Latihan

Hari 3
✓ Sistem persamaan
○ Latihan

Hari 4
✓ Metode substitusi
○ Latihan

Hari 5
✓ Metode eliminasi
○ Latihan

Hari 6
✓ Soal campuran

Hari 7
🎯 Simulasi Ujian
```

Jadi siswa tidak hanya bertanya kepada AI.

AI bisa menjadi **learning planner**.

---

# 10. AI Tutor

Ini berasal langsung dari konsep RAG di PPT.

Siswa bisa bertanya:

> "Apa itu sistem persamaan linear?"

AI menjawab berdasarkan buku/materi yang tersedia.

Yang menarik adalah jawaban bisa diberikan:

```text
Jawaban

Sistem persamaan linear adalah ...

📚 Sumber:
Matematika Kelas 10
Bab 3
Halaman 87
```

PPT memang menekankan jawaban dengan **sitasi bab & halaman** dan AI harus dibatasi agar tidak menjawab di luar konteks buku. 

---

# 11. AI Tutor Jangan Hanya Chat

Bisa dibuat beberapa tombol:

```text
Apa yang ingin kamu lakukan?

[ Jelaskan Materi ]
[ Buat Ringkasan ]
[ Berikan Contoh ]
[ Buat Latihan ]
[ Jelaskan dengan Bahasa Sederhana ]
[ Buat Quiz ]
```

Misalnya siswa sedang membaca:

**Bab 3 — Sistem Persamaan Linear**

Klik:

> "Jelaskan dengan bahasa sederhana"

AI memberikan penjelasan yang lebih mudah.

---

# 12. AI Quiz

Siswa bisa:

```text
Saya ingin latihan

Topik:
Sistem Persamaan Linear

Jumlah:
10 soal

[ Mulai Quiz ]
```

AI mengambil/generate soal berdasarkan materi.

Setelah selesai:

```text
Nilai Kamu

80 / 100

Benar:
8

Salah:
2

Materi yang perlu dipelajari:
- Substitusi
- Eliminasi
```

Ini kemudian bisa dikaitkan ke roadmap.

---

# 13. Adaptive Learning

Ini pengembangan yang menurut saya sangat bagus.

Misalnya:

```text
Siswa sering salah:
Persamaan Linear

AI mendeteksi:
⚠ Perlu penguatan

Rekomendasi:

1. Pelajari ulang konsep dasar
2. Lihat contoh
3. Kerjakan 5 soal mudah
4. Kerjakan 5 soal sedang
5. Coba quiz kembali
```

Jadi sistem tidak hanya memberikan nilai.

Tetapi memberikan **rekomendasi belajar berikutnya**.

---

# 14. TUGAS

Guru dapat membuat tugas:

```text
+ Buat Tugas

Judul:
Latihan Bab 3

Deskripsi:
Kerjakan soal halaman 95

Deadline:
20 September 2026

Assign:
X IPA 1
X IPA 2
```

Siswa:

```text
Tugas Saya

Latihan Bab 3
Deadline: 20 September

[ Kerjakan ]
```

---

# 15. UJIAN

Guru dapat membuat ujian dari bank soal.

```text
Buat Ujian

Nama:
Ujian Bab 3

Kelas:
X IPA 1
X IPA 2

Soal:
20

Durasi:
60 menit

Random Soal:
ON

Random Pilihan:
ON

KKM:
75
```

Siswa mengerjakan melalui sistem.

Setelah submit:

```text
Nilai: 85

Benar: 17
Salah: 3

Status:
LULUS
```

---

# 16. ANALYTICS GURU

Ini fitur yang menurut saya penting untuk aplikasi production.

Guru dapat melihat:

```text
PERFORMA KELAS

Jumlah siswa      32

Rata-rata nilai   78.5

Nilai tertinggi   98
Nilai terendah    45
```

Kemudian:

```text
Materi paling sulit:

1. Persamaan Linear      48%
2. Fungsi                62%
3. Sistem Persamaan      71%
```

Sehingga guru tahu bagian mana yang harus dijelaskan ulang.

---

# 17. RPP / MODUL AJAR GENERATOR

PPT menyebut **Draft RPP otomatis**, dengan tujuan, kegiatan, dan asesmen yang disusun dari CP. 

Flow:

```text
Generate RPP

Mata Pelajaran:
Matematika

Kelas:
X

Materi:
Sistem Persamaan Linear

Alokasi:
2 x 45 menit

CP:
[ pilih / generate ]

[ Generate RPP ]
```

Output:

```text
Tujuan Pembelajaran

...

Kegiatan Pembelajaran

1. Pendahuluan
2. Kegiatan inti
3. Penutup

Asesmen

...

Media Pembelajaran

...

Sumber Belajar

...
```

Kemudian:

```text
[ Edit ]
[ Simpan ]
[ Export PDF ]
[ Export DOCX ]
```

---

# 18. BANK SOAL

Struktur:

```text
Bank Soal
│
├── Matematika
│   ├── Bab 1
│   ├── Bab 2
│   └── Bab 3
│
├── Bahasa Indonesia
│   ├── Bab 1
│   └── Bab 2
│
└── IPA
```

Soal memiliki metadata:

```text
Pertanyaan
Jenis
Bab
Materi
Kesulitan
Jawaban
Pembahasan
Sumber
Pembuat
```

Ini akan sangat berguna untuk AI nantinya.

---

# 19. SISTEM AI / GEMINI

Nah ini bagian yang perlu Anda desain dari awal.

Client sudah setuju bahwa API Gemini bisa dimasukkan dari Admin:

> Kalau API habis → admin tinggal mengganti API key.

Saya setuju dengan konsep ini.

Tetapi jangan hardcode satu API key.

Buat:

## AI Provider Configuration

```text
AI Configuration

Provider:
● Gemini
○ Groq
○ OpenAI

API Keys

Gemini Key #1
● Active

Gemini Key #2
● Active

Gemini Key #3
○ Inactive

[ + Add API Key ]
```

---

# 20. API KEY ROTATION

Daripada hanya:

```text
API KEY
```

lebih baik:

```text
API Key Pool

KEY 1
Status: Active
Usage: 75%

KEY 2
Status: Active
Usage: 34%

KEY 3
Status: Active
Usage: 10%
```

Ketika key error / limit:

```text
KEY 1
↓
Limit reached
↓
Disable sementara
↓
Gunakan KEY 2
```

Ini jauh lebih aman untuk operasional.

**Catatan:** penggunaan banyak akun/key tetap harus mengikuti batas, kebijakan, dan ketentuan layanan provider AI yang digunakan; jangan mengandalkan rotasi key sebagai cara mengakali limit.

---

# 21. NOTIFIKASI TOKEN / API

Admin:

```text
⚠ AI Warning

Gemini API Key #1 mengalami rate limit.

System automatically switched
to API Key #2.
```

Kalau semuanya gagal:

```text
🔴 AI Temporarily Unavailable

Fitur AI sedang tidak tersedia.

Silakan hubungi administrator.
```

Jadi user tidak melihat error teknis seperti:

```text
429 RESOURCE_EXHAUSTED
```

---

# 22. AI USAGE MONITORING

Admin dashboard:

```text
AI USAGE

Today
2,431 requests

This Month
48,920 requests

Gemini
42,120

Groq
6,800
```

Kemudian:

```text
Feature Usage

AI Tutor          21,300
Generate Soal     15,200
Generate RPP       4,300
Roadmap            3,200
Quiz               5,000
```

Ini penting supaya client bisa tahu fitur AI mana yang paling banyak menghabiskan quota.

---

# 23. SUMBER MATERI / RAG

Dari PPT, arsitektur awalnya menggunakan:

```text
PDF Buku
    ↓
Chunking
    ↓
Embedding
    ↓
Qdrant
    ↓
LLM
    ↓
Jawaban
```

PPT menyebut PDF Buku Siswa & Buku Guru, Cohere untuk embedding, Qdrant sebagai vector DB, Groq/Gemini sebagai LLM, MySQL untuk log/hasil generate, dan n8n untuk orkestrasi ingestion. 

Untuk versi aplikasi client, saya akan pisahkan:

```text
Mata Pelajaran
      ↓
Buku
      ↓
Bab
      ↓
Materi
      ↓
Chunk
      ↓
Embedding
      ↓
Vector DB
```

---

# 24. ADMIN UPLOAD BUKU

Admin:

```text
+ Upload Buku

Nama:
Matematika Kelas 10

Jenis:
● Buku Siswa
○ Buku Guru

Tahun:
2026

File:
matematika-kelas-10.pdf

[ Upload & Process ]
```

System:

```text
Upload
 ↓
Extract PDF
 ↓
Chunking
 ↓
Embedding
 ↓
Vector DB
 ↓
Ready
```

Status:

```text
Processing 65%

████████████░░░░░
```

---

# 25. ANTI-HALLUCINATION

Ini salah satu fitur penting dari konsep asli EduRAG.

Kalau siswa bertanya:

> "Siapa presiden pertama Amerika?"

Padahal tidak ada di buku/materi yang digunakan.

System jangan mengarang.

Jawab:

```text
Maaf, saya tidak menemukan informasi
tersebut pada materi yang tersedia.

Silakan tanyakan sesuatu yang berkaitan
dengan materi pembelajaran.
```

PPT memang menyebut **"Cek Anti-Halusinasi — sistem menahan jawaban di luar konteks buku."** 

---

# 26. ROLE & PERMISSION

Saya sarankan minimal:

### Admin

Full access.

### Guru

```text
Dashboard
Kelas
Materi
Bank Soal
Tugas
Ujian
AI
Nilai
RPP
```

### Siswa

```text
Dashboard
Kelas
Materi
AI Tutor
Tugas
Ujian
Nilai
Roadmap
Progress
```

---

# 27. DASHBOARD SISWA

Konsepnya bisa dibuat seperti:

```text
Halo, Ahmad 👋

Progress Belajar
██████████████░░ 78%

Kelas Saya

Matematika
Progress 80%

Bahasa Indonesia
Progress 72%

IPA
Progress 81%

────────────────

🎯 Learning Roadmap

Hari ini:
✓ Pelajari Bab 3
○ Kerjakan 5 latihan
○ Quiz

[ Lanjutkan Belajar ]
```

Ini akan membuat aplikasi terasa seperti **platform belajar**, bukan sekadar sistem ujian.

---

# 28. GAMIFIKASI — OPSIONAL

Kalau client ingin aplikasi lebih menarik:

```text
XP
🔥 Streak
🏆 Badge
📊 Level
```

Misalnya:

```text
+10 XP
Menyelesaikan materi

+20 XP
Menyelesaikan quiz

+50 XP
Lulus ujian
```

Namun saya akan taruh ini sebagai **fase 2**, bukan MVP.

---

# 29. NOTIFICATION

Guru:

```text
🔔 5 siswa belum mengerjakan tugas.

🔔 Ujian X IPA 1 besok.

🔔 AI Generate Soal selesai.
```

Siswa:

```text
🔔 Tugas baru dari Bu Indri.

🔔 Besok ada ujian Matematika.

🔔 Learning roadmap baru tersedia.
```

---

# 30. FITUR "ASK AI" DI SETIAP MATERI

Ini menurut saya UX yang bagus.

Saat siswa membuka materi:

```text
Sistem Persamaan Linear

[ Materi ]

────────────────

🤖 Bingung dengan materi ini?

[ Tanya AI ]

[ Ringkas Materi ]

[ Buat Contoh ]

[ Latihan Soal ]
```

AI otomatis mengetahui konteks halaman/materi yang sedang dibuka.

Jadi user tidak perlu menjelaskan ulang:

> "Saya sedang belajar bab 3..."

---

# 31. AI GENERATE SOAL BERDASARKAN LEVEL

Bisa dikembangkan:

```text
Easy
Medium
Hard
HOTS
```

Contoh:

```text
Generate 20 Soal

Easy       5
Medium     8
Hard       5
HOTS       2
```

Dan guru dapat menentukan:

```text
PG       15
Essay     5
```

---

# 32. PEMBAHASAN OTOMATIS

Jangan hanya generate:

```text
Jawaban: C
```

Tetapi:

```text
Jawaban: C

Pembahasan:
...

Langkah:
1. ...
2. ...
3. ...
```

Pembahasan juga bisa digunakan siswa setelah ujian.

---

# 33. ROADMAP + HASIL UJIAN

Ini bisa menjadi salah satu selling point paling menarik.

Misalnya siswa mendapat:

```text
Nilai Matematika

Bab 1: 90%
Bab 2: 85%
Bab 3: 52%
Bab 4: 78%
```

AI otomatis:

```text
Rekomendasi Belajar

⚠ Bab 3 perlu diperkuat.

Roadmap berikutnya:

1. Review konsep Bab 3
2. Contoh soal dasar
3. 5 soal Easy
4. 5 soal Medium
5. Quiz
```

Jadi AI benar-benar menggunakan **data performa siswa**.

---

# 34. FITUR RIWAYAT AI

PPT juga menyebut **riwayat & ekspor**. 

Guru:

```text
Riwayat AI

12 Sep
Generate Soal Matematika
20 soal

11 Sep
Generate RPP
SPL

10 Sep
Generate Materi
Bab 3
```

Bisa:

```text
[ Buka ]
[ Edit ]
[ Export ]
```

---

# 35. EXPORT

Guru bisa export:

* PDF
* DOCX
* Excel untuk bank soal
* Print

Contoh:

```text
Bank Soal Matematika
↓
Export DOCX
```

atau:

```text
Ujian
↓
Print / PDF
```

---

# 36. STRUKTUR DATABASE KONSEPTUAL

Kalau Anda yang develop backend, saya kira minimal perlu:

```text
users
roles
teachers
students

classes
class_students
class_teachers

subjects
books
book_chapters
materials

assignments
assignment_submissions

question_banks
questions
question_options

exams
exam_questions
exam_attempts
exam_answers

learning_roadmaps
roadmap_items
student_progress

ai_conversations
ai_generations
ai_usage_logs

ai_providers
ai_api_keys

documents
document_chunks
```

Kalau menggunakan RAG:

```text
documents
document_chunks
embeddings/vector_db
```

Vector sebenarnya tetap bisa berada di Qdrant sesuai rancangan PPT.

---

# 37. ARSITEKTUR YANG SAYA SARANKAN

Konsepnya:

```text
                 ┌───────────────┐
                 │   ADMIN       │
                 └───────┬───────┘
                         │
                         ▼
┌──────────────┐   ┌───────────────┐   ┌──────────────┐
│    GURU      │──▶│   BACKEND     │◀──│    SISWA     │
└──────────────┘   └───────┬───────┘   └──────────────┘
                            │
             ┌──────────────┼──────────────┐
             ▼              ▼              ▼
          MySQL          Qdrant         AI Service
                                            │
                                  ┌─────────┼─────────┐
                                  ▼         ▼         ▼
                               Gemini     Groq      Other
```

---

# 38. AI SERVICE SEBAIKNYA DIPISAH

Saya sangat menyarankan jangan membuat setiap fitur langsung memanggil Gemini.

Buat satu service:

```text
AI SERVICE

generateQuestion()
generateMaterial()
generateRoadmap()
generateRPP()
askTutor()
generateQuiz()
```

Kemudian:

```text
AI Service
    ↓
API Key Manager
    ↓
Gemini
```

Dengan begitu nanti kalau client bilang:

> "Besok mau ganti Gemini ke provider lain."

Anda tidak perlu bongkar semua fitur.

---

# 39. PRIORITAS MVP

Kalau ini benar-benar mau dibuat sebagai aplikasi client, **jangan langsung bangun semua fitur di atas**.

Saya sarankan MVP:

### Phase 1 — Core

**Admin**

* Login
* User
* Guru
* Siswa
* Kelas
* Mapel
* Upload buku/materi
* API Key Gemini

**Guru**

* Dashboard
* Kelas
* Materi
* Bank soal
* Generate soal AI
* Ujian
* Nilai

**Siswa**

* Dashboard
* Materi
* AI Tutor
* Tugas
* Ujian
* Nilai

---

# 40. Phase 2 — AI Intelligence

Setelah MVP stabil:

```text
AI Generate Materi
AI Generate RPP
AI Generate Quiz
AI Learning Roadmap
AI Summary
AI Explanation
AI Recommendation
```

---

# 41. Phase 3 — Smart Learning

Baru kemudian:

```text
Adaptive Learning
Learning Analytics
Student Weakness Detection
Personalized Roadmap
Gamification
Achievement
Leaderboard
```

---

# 42. FITUR YANG PALING MENJUAL KE CLIENT

Kalau saya harus memilih **7 fitur utama untuk dipresentasikan ke client**, saya akan pilih:

### 1. 🤖 AI Tutor

Siswa bertanya berdasarkan buku/materi.

### 2. 📝 AI Question Generator

Guru generate soal berdasarkan bab.

### 3. 🗺️ AI Learning Roadmap

AI membuat jalur belajar siswa.

### 4. 📚 Smart Material

Materi terstruktur + AI summary/explanation.

### 5. 📊 Learning Analytics

Guru melihat kelemahan siswa.

### 6. 📋 AI RPP / Modul Generator

Mengurangi pekerjaan administratif guru.

### 7. 🔐 AI API Management

Admin dapat mengatur/mengganti API key dan melihat penggunaan AI.

---

# 43. FLOW UTAMA YANG PALING BAGUS

Saya membayangkan pengalaman aplikasinya seperti ini:

```text
                GURU
                  │
                  ▼
          Upload / Buat Materi
                  │
                  ▼
              Assign Kelas
                  │
                  ▼
                SISWA
                  │
         ┌────────┼─────────┐
         ▼        ▼         ▼
       Materi    AI       Tugas
         │       Tutor      │
         │        │         │
         ▼        ▼         ▼
       Quiz ←── AI ─────→ Ujian
         │
         ▼
       Nilai
         │
         ▼
   Learning Analytics
         │
         ▼
   AI Learning Roadmap
         │
         ▼
   Rekomendasi Belajar
```

Dengan konsep tersebut, **AI tidak berdiri sendiri**. AI terhubung dengan materi → soal → nilai → kelemahan siswa → roadmap.

---

## Kesimpulan konsep aplikasi

Menurut saya, jangan positioning aplikasi ini sebagai:

> **"Aplikasi generate soal menggunakan Gemini."**

Terlalu sederhana dan value-nya kecil.

Lebih bagus:

> **EduRAG — Platform Pembelajaran dan Asisten Guru Berbasis AI**

Dengan 2 mesin utama:

**AI Tutor untuk Siswa**
→ memahami materi, bertanya, latihan, quiz, roadmap.

**AI Assistant untuk Guru**
→ membuat materi, soal, RPP, quiz, dan menganalisis pembelajaran.

Sedangkan **RAG menjadi fondasi AI-nya**, sehingga AI mengambil konteks dari buku/materi yang tersedia, bukan sekadar chatbot umum. Ini konsisten dengan arsitektur dan fitur yang memang dijelaskan di PPT client. 

Untuk kebutuhan **estimasi harga/proyek**, saya akan membagi scope menjadi **MVP + Phase 2 + Phase 3** supaya fitur AI seperti roadmap, adaptive learning, analytics, dan gamifikasi tidak membuat scope awal membengkak.
