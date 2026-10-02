<?php
class AiController
{
    private GeminiService $gemini;

    public function __construct()
    {
        Auth::requireLogin();
        $this->gemini = new GeminiService();
    }

    /**
     * Endpoint JSON API untuk AI Tutor
     */
    public function askTutor(): void
    {
        header('Content-Type: application/json');
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $pertanyaan = trim($data['pertanyaan'] ?? '');
        $subjectId = (int)($data['subject_id'] ?? 1);
        $topik = trim($data['topik'] ?? '');
        $userId = Auth::user()['id'];

        if (empty($pertanyaan)) {
            echo json_encode(['success' => false, 'message' => 'Pertanyaan wajib diisi.']);
            exit;
        }

        $result = $this->gemini->askTutor($pertanyaan, $topik, $subjectId, $userId);
        echo json_encode($result);
        exit;
    }

    /**
     * Endpoint JSON API untuk Generate Soal
     */
    public function generateSoal(): void
    {
        Auth::requireRole(['GURU', 'ADMIN']);
        header('Content-Type: application/json');

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $mapel = trim($data['mapel'] ?? 'Matematika');
        $bab = trim($data['bab'] ?? 'SPLTV');
        $jumlah = min(max((int)($data['jumlah'] ?? 5), 1), 20);
        $tipe = $data['tipe'] === 'essay' ? 'essay' : 'pg';
        $kesulitan = $data['kesulitan'] ?? 'medium';
        $userId = Auth::user()['id'];

        $result = $this->gemini->generateSoal($mapel, $bab, $jumlah, $tipe, $kesulitan, $userId);
        echo json_encode($result);
        exit;
    }

    /**
     * Simpan hasil generate soal ke Bank Soal
     */
    public function saveGeneratedSoal(): void
    {
        Auth::requireRole(['GURU', 'ADMIN']);
        header('Content-Type: application/json');

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (empty($data['soal_list']) || !is_array($data['soal_list'])) {
            echo json_encode(['success' => false, 'message' => 'Data soal tidak valid.']);
            exit;
        }

        $ujianModel = new UjianModel();
        $subjectId = (int)($data['subject_id'] ?? 1);
        $bab = trim($data['bab'] ?? 'Materi Kurikulum Merdeka');
        $userId = Auth::user()['id'];

        $savedCount = 0;
        foreach ($data['soal_list'] as $s) {
            $pertanyaan = $s['pertanyaan'] ?? $s['soal'] ?? $s['teks_soal'] ?? '';
            $kunci = $s['kunci'] ?? $s['kunci_jawaban'] ?? 'A';
            $qData = [
                'subject_id'    => $subjectId,
                'bab'           => $bab,
                'tipe'          => $s['tipe'] ?? 'pg',
                'pertanyaan'    => $pertanyaan,
                'kesulitan'     => $s['kesulitan'] ?? 'medium',
                'kunci_jawaban' => $kunci,
                'pembahasan'    => $s['pembahasan'] ?? '',
                'created_by'    => $userId
            ];
            $opsi = $s['opsi'] ?? [];
            if (!empty($opsi) && !isset($opsi[0]) && is_array($opsi)) {
                $convertedOpsi = [];
                foreach ($opsi as $lbl => $txt) {
                    $convertedOpsi[] = [
                        'label' => strtoupper((string)$lbl),
                        'teks' => (string)$txt,
                        'is_benar' => (strtoupper((string)$lbl) === strtoupper((string)$kunci))
                    ];
                }
                $opsi = $convertedOpsi;
            }
            $ujianModel->saveQuestion($qData, $opsi);
            $savedCount++;
        }

        echo json_encode(['success' => true, 'message' => "Berhasil menyimpan {$savedCount} butir soal ke Bank Soal!"]);
        exit;
    }

    /**
     * Endpoint JSON API untuk Generate RPP
     */
    public function generateRpp(): void
    {
        Auth::requireRole(['GURU', 'ADMIN']);
        header('Content-Type: application/json');

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $mapel = trim($data['mapel'] ?? 'Matematika');
        $bab = trim($data['bab'] ?? 'SPLTV');
        $alokasiWaktu = trim($data['alokasi_waktu'] ?? '2 x 45 Menit');
        $userId = Auth::user()['id'];

        $result = $this->gemini->generateRPP($mapel, $bab, $alokasiWaktu, $userId);
        echo json_encode($result);
        exit;
    }

    /**
     * Endpoint JSON API untuk Generate Roadmap
     */
    public function generateRoadmap(): void
    {
        header('Content-Type: application/json');
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $mapel = trim($data['mapel'] ?? 'Matematika');
        $topik = trim($data['topik'] ?? 'SPLTV');
        $targetHari = (int)($data['target_hari'] ?? 7);
        $userId = Auth::user()['id'];

        $result = $this->gemini->generateRoadmap($mapel, $topik, $targetHari, $userId);
        echo json_encode($result);
        exit;
    }
}
