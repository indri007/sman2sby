<?php
class SiswaController
{
    private MateriModel $materiModel;
    private TugasModel $tugasModel;
    private UjianModel $ujianModel;
    private KelasModel $kelasModel;
    private MapelModel $mapelModel;

    public function __construct()
    {
        Auth::requireRole(['SISWA', 'ADMIN']);
        $this->materiModel = new MateriModel();
        $this->tugasModel = new TugasModel();
        $this->ujianModel = new UjianModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
    }

    public function dashboard(): void
    {
        $studentId = Auth::user()['id'];
        $kelas = $this->kelasModel->getKelasByStudent($studentId);
        $classId = $kelas ? (int)$kelas['id'] : 1;

        $materiList = $this->materiModel->getByClass($classId);
        $tugasList = $this->tugasModel->getByClassAndStudent($classId, $studentId);
        $ujianList = $this->ujianModel->getByClassAndStudent($classId, $studentId);

        $bukuModel = new BukuModel();
        $bukuList = $bukuModel->getRecent(4);
        $totalBuku = $bukuModel->countAll();

        require __DIR__ . '/../views/siswa/dashboard.php';
    }

    /**
     * Halaman Khusus Materi Pelajaran untuk Siswa
     */
    public function materi(): void
    {
        $studentId = Auth::user()['id'];
        $kelas = $this->kelasModel->getKelasByStudent($studentId);
        $classId = $kelas ? (int)$kelas['id'] : 1;

        $selectedSubjectId = isset($_GET['subject_id']) && $_GET['subject_id'] !== '' ? (int)$_GET['subject_id'] : null;
        $materiList = $this->materiModel->getByClass($classId, $selectedSubjectId);
        $mapelList = $this->mapelModel->getAll();

        require __DIR__ . '/../views/siswa/materi.php';
    }

    public function materiDetail(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $materi = $this->materiModel->getDetail($id);
        if (!$materi) {
            Helper::flash('error', 'Materi tidak ditemukan.');
            Helper::redirect('siswa_materi');
        }

        require __DIR__ . '/../views/siswa/materi_detail.php';
    }

    public function aiTutor(): void
    {
        $mapelList = $this->mapelModel->getAll();
        $selectedSubjectId = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 1;
        require __DIR__ . '/../views/siswa/ai_tutor.php';
    }

    public function tugas(): void
    {
        $studentId = Auth::user()['id'];
        $kelas = $this->kelasModel->getKelasByStudent($studentId);
        $classId = $kelas ? (int)$kelas['id'] : 1;

        $tugasList = $this->tugasModel->getByClassAndStudent($classId, $studentId);
        require __DIR__ . '/../views/siswa/tugas.php';
    }

    public function tugasDetail(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $studentId = Auth::user()['id'];
        $tugas = $this->tugasModel->getDetail($id);
        $submission = $this->tugasModel->getSubmission($id, $studentId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $catatan = trim($_POST['catatan_siswa'] ?? '');
            $fileName = null;

            if (isset($_FILES['file_jawaban']) && $_FILES['file_jawaban']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['file_jawaban']['name'], PATHINFO_EXTENSION);
                $newName = 'tugas_' . $id . '_' . $studentId . '_' . time() . '.' . $ext;
                $targetDir = __DIR__ . '/../uploads/tugas/';
                if (move_uploaded_file($_FILES['file_jawaban']['tmp_name'], $targetDir . $newName)) {
                    $fileName = 'uploads/tugas/' . $newName;
                }
            }

            $this->tugasModel->submit($id, $studentId, $fileName, $catatan);
            Helper::flash('success', 'Tugas berhasil dikumpulkan.');
            Helper::redirect('siswa_tugas_detail', ['id' => $id]);
        }

        require __DIR__ . '/../views/siswa/tugas_detail.php';
    }

    public function ujian(): void
    {
        $studentId = Auth::user()['id'];
        $kelas = $this->kelasModel->getKelasByStudent($studentId);
        $classId = $kelas ? (int)$kelas['id'] : 1;

        $ujianList = $this->ujianModel->getByClassAndStudent($classId, $studentId);
        require __DIR__ . '/../views/siswa/ujian.php';
    }

    public function ujianKerjakan(): void
    {
        $examId = (int)($_GET['id'] ?? 0);
        $studentId = Auth::user()['id'];
        $ujian = $this->ujianModel->getDetail($examId);
        $questions = $this->ujianModel->getQuestions($examId, (bool)$ujian['acak_soal']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jawaban = $_POST['jawaban'] ?? [];
            $totalBenar = 0;
            $totalSalah = 0;
            $totalBobot = 0;
            $skorDidapat = 0;

            foreach ($questions as $q) {
                $pilih = $jawaban[$q['id']] ?? '';
                $isBenar = (strtoupper($pilih) === strtoupper($q['kunci_jawaban'])) ? 1 : 0;
                $bobot = (float)$q['bobot'];
                $totalBobot += $bobot;

                if ($isBenar) {
                    $totalBenar++;
                    $skorDidapat += $bobot;
                } else {
                    $totalSalah++;
                }
            }

            $nilaiAkhir = $totalBobot > 0 ? ($skorDidapat / $totalBobot) * 100 : 0;
            $nilaiAkhir = round($nilaiAkhir, 2);

            // Simpan attempt
            $db = Database::getConnection();
            $stmtAtt = $db->prepare("INSERT INTO `lms_exam_attempts` (`exam_id`, `student_user_id`, `waktu_mulai`, `waktu_selesai`, `total_benar`, `total_salah`, `nilai_akhir`, `status`) VALUES (?, ?, NOW(), NOW(), ?, ?, ?, 'completed')");
            $stmtAtt->execute([$examId, $studentId, $totalBenar, $totalSalah, $nilaiAkhir]);
            $attemptId = (int)$db->lastInsertId();

            Helper::flash('success', "Ujian selesai! Nilai Anda: {$nilaiAkhir}");
            Helper::redirect('siswa_ujian_hasil', ['id' => $attemptId]);
        }

        require __DIR__ . '/../views/siswa/ujian_kerjakan.php';
    }

    public function ujianHasil(): void
    {
        $attemptId = (int)($_GET['id'] ?? 0);
        $studentId = Auth::user()['id'];
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT att.*, e.judul, e.kkm, s.nama_mapel
                              FROM `lms_exam_attempts` att
                              JOIN `lms_exams` e ON att.exam_id = e.id
                              JOIN `lms_subjects` s ON e.subject_id = s.id
                              WHERE att.id = ? AND (att.student_user_id = ? OR 'ADMIN' = ?) LIMIT 1");
        $stmt->execute([$attemptId, $studentId, Auth::role()]);
        $hasil = $stmt->fetch();

        if (!$hasil) {
            Helper::flash('error', 'Hasil ujian tidak ditemukan.');
            Helper::redirect('siswa_ujian');
        }

        require __DIR__ . '/../views/siswa/ujian_hasil.php';
    }

    public function roadmap(): void
    {
        $mapelList = $this->mapelModel->getAll();
        require __DIR__ . '/../views/siswa/roadmap.php';
    }
}
