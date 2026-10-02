<?php
/**
 * GeminiService - Resilient AI Engine with Multi-Key Rotation Pool
 * Supports:
 * - AI Tutor (with citations & strict anti-hallucination)
 * - AI Question Generator (PG & Essay with key & discussion)
 * - AI RPP / Modul Ajar Generator (Kurikulum Merdeka)
 * - AI Learning Roadmap Generator
 */
class GeminiService
{
    private PDO $db;
    private array $aiConfig;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->aiConfig = require __DIR__ . "/../config/ai_config.php";
    }

    /**
     * Ambil key yang aktif dari pool
     */
    public function getActiveKey(): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `ai_api_keys` WHERE `status` = 'active' AND `provider` = 'gemini' ORDER BY `total_requests` ASC LIMIT 1");
        $stmt->execute();
        $key = $stmt->fetch();
        return $key ?: null;
    }

    /**
     * Tandai key sebagai rate limited / error
     */
    public function markKeyRateLimited(int $keyId): void
    {
        $stmt = $this->db->prepare("UPDATE `ai_api_keys` SET `status` = 'rate_limited\, `error_count` = `error_count` + 1 WHERE `id` = ?");
        $stmt->execute([$keyId]);
    }

    /**
     * Catat penggunaan key
     */
    public function incrementUsage(int $keyId): void
    {
        $stmt = $this->db->prepare("UPDATE `ai_api_keys` SET `total_requests` = `total_requests` + 1, `last_used_at` = NOW() WHERE `id` = ?");
        $stmt->execute([$keyId]);
    }

    /**
     * Log interaksi AI
     */
    public function log(int $userId, string $feature, string $prompt, string $response, string $model, string $status, int $ms): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO `ai_logs` (`user_id`, `feature`, `prompt`, `response`, `model_used`, `status`, `execution_time_ms`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $feature, mb_substr($prompt, 0, 1000), $response, $model, $status, $ms]);
        } catch (Exception $e) {
            // fail silently on log error
        }
    }

    /**
     * Panggil Gemini API dengan mekanisme failover antar key pool dan model fallback
     */
    public function callGemini(string $systemPrompt, string $userPrompt, int $userId, string $feature = "general", bool $isJson = false): array
    {
        $startTime = microtime(true);
        $candidateModels = [
            $this->aiConfig["default_model"] ?? "gemini-3.5-flash-lite",
            "gemini-3.5-flash-lite",
            "gemini-3.5-flash",
            "gemini-3.6-flash"
        ];
        $candidateModels = array_values(array_unique($candidateModels));

        // Coba sampai 3 key berbeda di pool
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $keyRow = $this->getActiveKey();
            if (!$keyRow || empty($keyRow["api_key"]) || $keyRow["api_key"] === "YOUR_GEMINI_API_KEY_HERE") {
                return [
                    "success" => false,
                    "message" => "API Key Gemini belum disetting atau belum aktif di Admin Panel. Silakan buka menu Gemini Key Pool di dashboard Admin untuk memasukkan API Key valid."
                ];
            }

            $apiKey = trim($keyRow["api_key"]);

            foreach ($candidateModels as $model) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $genConfig = [
                    "temperature"     => (float)($this->aiConfig["temperature"] ?? 0.4),
                    "maxOutputTokens" => (int)($this->aiConfig["max_output_tokens"] ?? 8192)
                ];

                if ($isJson) {
                    $genConfig["responseMimeType"] = "application/json";
                }

                $payload = [
                    "contents" => [
                        [
                            "role" => "user",
                            "parts" => [
                                ["text" => "{$systemPrompt}\n\n--- PERMINTAAN PENGGUNA ---\n{$userPrompt}"]
                            ]
                        ]
                    ],
                    "generationConfig" => $genConfig
                ];

                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST           => true,
                    CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
                    CURLOPT_POSTFIELDS     => json_encode($payload),
                    CURLOPT_TIMEOUT        => 45,
                    CURLOPT_SSL_VERIFYPEER => false
                ]);

                $raw = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $executionTimeMs = (int)((microtime(true) - $startTime) * 1000);

                if ($raw === false) {
                    $this->markKeyRateLimited($keyRow["id"]);
                    break; // Coba key berikutnya
                }

                $json = json_decode($raw, true);

                // Jika Rate Limit (429)
                if ($httpCode === 429 || (isset($json["error"]["code"]) && $json["error"]["code"] == 429)) {
                    $this->markKeyRateLimited($keyRow["id"]);
                    break; // Coba key berikutnya di pool
                }

                // Jika model overloaded (503), not found (404), atau invalid model/params (400), coba model berikutnya
                if ($httpCode === 503 || $httpCode === 500 || $httpCode === 404 || $httpCode === 400) {
                    continue; // failover ke model kandidat berikutnya
                }

                // Jika berhasil (200 OK)
                if ($httpCode === 200 && !empty($json["candidates"][0]["content"]["parts"])) {
                    $this->incrementUsage($keyRow["id"]);
                    
                    // Kumpulkan semua parts teks jika ada beberapa chunk
                    $parts = $json["candidates"][0]["content"]["parts"];
                    $replyText = "";
                    foreach ($parts as $p) {
                        if (isset($p["text"])) {
                            $replyText .= $p["text"];
                        }
                    }

                    $this->log($userId, $feature, $userPrompt, $replyText, $model, "success", $executionTimeMs);

                    return [
                        "success" => true,
                        "text"    => $replyText,
                        "model"   => $model,
                        "time_ms" => $executionTimeMs
                    ];
                }

                $errMsg = $json["error"]["message"] ?? "HTTP error {$httpCode}";
                $this->log($userId, $feature, $userPrompt, $errMsg, $model, "error", $executionTimeMs);
            }
        }

        return [
            "success" => false,
            "message" => "Semua API Key Gemini sedang sibuk atau mengalami kendala kuota. Silakan coba sesaat lagi atau cek Gemini Key Pool di Admin Panel."
        ];
    }

    /**
     * AI Tutor untuk Siswa (Grounding & Sitasi Bab/Halaman & Anti-Halusinasi)
     */
    public function askTutor(string $pertanyaan, ?string $topik, int $subjectId, int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM `ai_curriculum_books` WHERE `subject_id` = ?");
        $stmt->execute([$subjectId]);
        $books = $stmt->fetchAll();

        $contextText = "";
        foreach ($books as $b) {
            $contextText .= "- [{$b['judul_buku']} | {$b['bab']} | {$b['halaman']}]: {$b['konten_ringkasan']}\n";
        }

        $systemPrompt = <<<SYS
Anda adalah "EduRAG AI Tutor SMAN 2 Surabaya" yang mendampingi siswa SMA/SMK Kelas 10 belajar berdasarkan Kurikulum Merdeka.

PRINSIP WAJIB:
1. ANTI-HALUSINASI: Anda hanya boleh menjawab pertanyaan seputar materi pelajaran sekolah Kurikulum Merdeka. Jika pertanyaan di luar konteks materi sekolah, tolaklah dengan sopan: "Maaf, saya adalah AI Tutor pendamping belajar SMAN 2 Surabaya. Pertanyaan Anda di luar materi pembelajaran sekolah. Silakan tanyakan materi terkait pelajaran Anda."
2. FORMAT SITASI: Setiap jawaban WAJIB menyertakan rujukan di akhir jawaban dengan format:
   📚 Sumber: [Buku Siswa Kurikulum Merdeka Kelas 10 - Bab X, Halaman Y]
3. BAHASA: Gunakan bahasa Indonesia yang ramah, jelas, terstruktur, mendidik, dan mudah dipahami siswa SMA.
4. RUMUS & MATEMATIKA: Jika ada rumus matematika atau sains, gunakan format LaTeX yang rapi (misal: \$x^2 + 2x + 1 = 0\$ atau \$\$y = mx + c\$\$).

KONTEKS REFERENSI RESMI BUKU KURIKULUM MERDEKA KELAS 10:
{$contextText}
SYS;

        return $this->callGemini($systemPrompt, $pertanyaan, $userId, "tutor", false);
    }

    /**
     * AI Generator Soal untuk Guru (Pilihan Ganda & Essay)
     */
    public function generateSoal(string $mapel, string $bab, int $jumlah, string $tipe, string $kesulitan, int $userId): array
    {
        $systemPrompt = <<<SYS
Anda adalah asisten pembuatan soal profesional untuk Guru SMAN 2 Surabaya (Kurikulum Merdeka Kelas 10).
Tugas Anda adalah menghasilkan {$jumlah} butir soal {$tipe} berkualitas tinggi dengan tingkat kesulitan {$kesulitan} untuk mata pelajaran "{$mapel}", topik "{$bab}".

WAJIB HASILKAN DALAM FORMAT JSON ARRAY MURNI dengan struktur persis berikut:
[
  {
    "nomor": 1,
    "tipe": "{$tipe}",
    "pertanyaan": "Teks soal lengkap...",
    "kesulitan": "{$kesulitan}",
    "opsi": [
      {"label": "A", "teks": "Pilihan A", "is_benar": true},
      {"label": "B", "teks": "Pilihan B", "is_benar": false},
      {"label": "C", "teks": "Pilihan C", "is_benar": false},
      {"label": "D", "teks": "Pilihan D", "is_benar": false},
      {"label": "E", "teks": "Pilihan E", "is_benar": false}
    ],
    "kunci": "A",
    "pembahasan": "Langkah pembahasan terperinci..."
  }
]
Jika tipe adalah "essay", buat array "opsi" bernilai kosong [] dan "kunci" berisi poin-poin rubrik jawaban.
SYS;

        $userPrompt = "Tolong buatkan {$jumlah} butir soal {$tipe} untuk {$mapel} Bab: {$bab} dengan tingkat kesulitan {$kesulitan}. Pastikan output HANYA JSON array valid.";
        $res = $this->callGemini($systemPrompt, $userPrompt, $userId, "soal", true);

        if ($res["success"]) {
            $rawText = trim($res["text"]);
            
            // Ekstrak JSON array jika ada karakter luar
            $cleaned = $rawText;
            if (preg_match("/\[\s*\{.*\}\s*\]/s", $rawText, $match)) {
                $cleaned = $match[0];
            } else {
                $cleaned = preg_replace("/^```json\s*/i", "", $cleaned);
                $cleaned = preg_replace("/```$/", "", trim($cleaned));
            }

            $decoded = json_decode($cleaned, true);

            // Normalisasi struktur soal jika model menggunakan variasi key
            if (is_array($decoded)) {
                $normalized = [];
                foreach ($decoded as $idx => $item) {
                    if (!is_array($item)) continue;

                    $pertanyaan = $item["pertanyaan"] ?? $item["soal"] ?? $item["teks_soal"] ?? "";
                    $kunci = $item["kunci"] ?? $item["kunci_jawaban"] ?? "";
                    $pembahasan = $item["pembahasan"] ?? $item["penjelasan"] ?? "";
                    $itemKesulitan = $item["kesulitan"] ?? $kesulitan;
                    $itemTipe = $item["tipe"] ?? $tipe;

                    $opsiList = [];
                    $rawOpsi = $item["opsi"] ?? $item["pilihan"] ?? [];
                    if (is_array($rawOpsi)) {
                        // Cek jika bentuknya associative array ["A" => "...", "B" => "..."]
                        if (!empty($rawOpsi) && !isset($rawOpsi[0])) {
                            foreach ($rawOpsi as $lbl => $txt) {
                                $opsiList[] = [
                                    "label" => strtoupper((string)$lbl),
                                    "teks"  => (string)$txt,
                                    "is_benar" => (strtoupper((string)$lbl) === strtoupper((string)$kunci))
                                ];
                            }
                        } else {
                            foreach ($rawOpsi as $op) {
                                if (is_array($op)) {
                                    $lbl = strtoupper((string)($op["label"] ?? ""));
                                    $opsiList[] = [
                                        "label" => $lbl,
                                        "teks"  => (string)($op["teks"] ?? $op["text"] ?? ""),
                                        "is_benar" => !empty($op["is_benar"]) || ($lbl === strtoupper((string)$kunci))
                                    ];
                                }
                            }
                        }
                    }

                    $normalized[] = [
                        "nomor"      => $idx + 1,
                        "tipe"       => $itemTipe,
                        "pertanyaan" => $pertanyaan,
                        "kesulitan"  => $itemKesulitan,
                        "opsi"       => $opsiList,
                        "kunci"      => $kunci,
                        "pembahasan" => $pembahasan
                    ];
                }

                $res["parsed_soal"] = $normalized;
            } else {
                $res["parsed_soal"] = null;
            }
        }

        return $res;
    }

    /**
     * AI Generator RPP / Modul Ajar Kurikulum Merdeka
     */
    public function generateRPP(string $mapel, string $bab, string $alokasiWaktu, int $userId): array
    {
        $systemPrompt = <<<SYS
Anda adalah pakar kurikulum yang bertugas membantu Guru SMAN 2 Surabaya menyusun Draf RPP / Modul Ajar Kurikulum Merdeka Fase E (Kelas 10).
Format draf harus mencakup:
1. IDENTITAS MODUL (Mata Pelajaran, Fase/Kelas, Alokasi Waktu, Elemen/Materi)
2. CAPAIAN PEMBELAJARAN (CP) & TUJUAN PEMBELAJARAN (TP)
3. PROFIL PELAJAR PANCASILA (Bernalar Kritis, Mandiri, Gotong Royong, dll.)
4. PEMAHAMAN BERMAKNA & PERTANYAAN PEMANTIK
5. MODEL & METODE PEMBELAJARAN (misal: Problem Based Learning / Discovery Learning)
6. KEGIATAN PEMBELAJARAN TERPERINCI:
   - Pendahuluan (15 menit)
   - Kegiatan Inti (Sesuai Sintaks Model Pembelajaran)
   - Penutup (15 menit)
7. ASESMEN PEMBELAJARAN (Formatif & Sumatif)
8. PENGAYAAN & REMEDIAL

Gunakan format Markdown yang rapi dengan heading, bullet points, dan tabel bila diperlukan.
SYS;

        $userPrompt = "Tolong buatkan draf Modul Ajar Kurikulum Merdeka untuk mata pelajaran {$mapel}, topik: {$bab}, alokasi waktu: {$alokasiWaktu}.";
        return $this->callGemini($systemPrompt, $userPrompt, $userId, "rpp", false);
    }

    /**
     * AI Generator Roadmap Belajar Mandiri untuk Siswa
     */
    public function generateRoadmap(string $mapel, string $target, int $userId): array
    {
        $systemPrompt = <<<SYS
Anda adalah konselor akademik AI SMAN 2 Surabaya.
Tugas Anda adalah merancang rencana roadmap belajar mandiri terstruktur (30 hari / 4 minggu) untuk siswa Kelas 10 Kurikulum Merdeka mata pelajaran "{$mapel}". Target siswa: "{$target}".

WAJIB HASILKAN DALAM FORMAT JSON BERSIH DENGAN STRUKTUR:
{
  "judul": "Roadmap {$mapel} - 30 Hari Menuju {$target}",
  "deskripsi": "Deskripsi motivatif...",
  "minggu": [
    {
      "minggu_ke": 1,
      "fokus": "Fondasi Konsep...",
      "hari": [
        {"hari_ke": 1, "topik": "...", "aktivitas": "...", "durasi_menit": 45},
        {"hari_ke": 2, "topik": "...", "aktivitas": "...", "durasi_menit": 45}
      ]
    }
  ]
}
SYS;

        $userPrompt = "Buatkan roadmap belajar 30 hari untuk mapel {$mapel} dengan target: {$target}.";
        $res = $this->callGemini($systemPrompt, $userPrompt, $userId, "roadmap", true);

        if ($res["success"]) {
            $rawText = trim($res["text"]);
            $cleaned = $rawText;
            if (preg_match("/\{.*\}/s", $rawText, $match)) {
                $cleaned = $match[0];
            } else {
                $cleaned = preg_replace("/^```json\s*/i", "", $cleaned);
                $cleaned = preg_replace("/```$/", "", trim($cleaned));
            }
            $res["parsed_roadmap"] = json_decode($cleaned, true);
        }

        return $res;
    }
}
