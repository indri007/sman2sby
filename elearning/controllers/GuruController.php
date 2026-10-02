<?php
class GuruController
{
    private MateriModel $materiModel;
    private TugasModel $tugasModel;
    private UjianModel $ujianModel;
    private KelasModel $kelasModel;
    private MapelModel $mapelModel;

    public function __construct()
    {
        Auth::requireRole(['GURU', 'ADMIN']);
        $this->materiModel = new MateriModel();
        $this->tugasModel = new TugasModel();
        $this->ujianModel = new UjianModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
    }

    public function dashboard(): void
    {
        $teacherId = Auth::user()['id'];
        $kelasList = $this->kelasModel->getKelasByTeacher($teacherId);
        $materiList = $this->materiModel->getByTeacher($teacherId);
        $tugasList = $this->tugasModel->getByTeacher($teacherId);
        $ujianList = $this->ujianModel->getByTeacher($teacherId);
        $bukuModel = new BukuModel();
        $totalBuku = $bukuModel->countAll();
        $recentBuku = $bukuModel->getRecent(4);

        require __DIR__ . '/../views/guru/dashboard.php';
    }

    public function materi(): void
    {
        $teacherId = Auth::user()['id'];
        $materiList = $this->materiModel->getByTeacher($teacherId);
        require __DIR__ . '/../views/guru/materi.php';
    }

    public function materiCreate(): void
    {
        $teacherId = Auth::user()['id'];
        $kelasList = $this->kelasModel->getAll();
        $mapelList = $this->mapelModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'subject_id'      => (int)$_POST['subject_id'],
                'teacher_user_id' => $teacherId,
                'judul'           => trim($_POST['judul']),
                'bab'             => trim($_POST['bab']),
                'konten'          => trim($_POST['konten']),
                'youtube_url'     => trim($_POST['youtube_url'] ?? ''),
                'is_published'    => isset($_POST['is_published']) ? 1 : 0
            ];
            $classIds = $_POST['class_ids'] ?? [];

            $this->materiModel->create($data, $classIds);
            Helper::flash('success', 'Materi berhasil diterbitkan ke kelas tujuan.');
            Helper::redirect('guru_materi');
        }

        require __DIR__ . '/../views/guru/materi_form.php';
    }

    public function tugas(): void
    {
        $teacherId = Auth::user()['id'];
        $tugasList = $this->tugasModel->getByTeacher($teacherId);
        require __DIR__ . '/../views/guru/tugas.php';
    }

    /**
     * Buat Penugasan Baru untuk Siswa
     */
    public function tugasCreate(): void
    {
        $teacherId = Auth::user()['id'];
        $mapelList = $this->mapelModel->getAll();
        $kelasList = $this->kelasModel->getKelasByTeacher($teacherId);
        if (empty($kelasList)) {
            $kelasList = $this->kelasModel->getAll();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $subjectId = (int)($_POST['subject_id'] ?? 0);
            $classId = (int)($_POST['class_id'] ?? 0);
            $deadline = $_POST['deadline'] ?? date('Y-m-d H:i:s', strtotime('+7 days'));

            // File lampiran jika ada
            $fileLampiran = null;
            if (isset($_FILES['file_lampiran']) && $_FILES['file_lampiran']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['file_lampiran']['name'], PATHINFO_EXTENSION));
                $targetDir = __DIR__ . '/../uploads/tugas/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = 'tugas_' . time() . '_' . rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($_FILES['file_lampiran']['tmp_name'], $targetDir . $fileName)) {
                    $fileLampiran = 'uploads/tugas/' . $fileName;
                }
            }

            if (empty($judul) || $subjectId === 0 || $classId === 0) {
                Helper::flash('error', 'Judul tugas, mata pelajaran, dan kelas wajib diisi.');
                Helper::redirect('guru_tugas_create');
            }

            $this->tugasModel->createAssignment([
                'subject_id'      => $subjectId,
                'class_id'        => $classId,
                'teacher_user_id' => $teacherId,
                'judul'           => $judul,
                'deskripsi'       => $deskripsi,
                'deadline'        => $deadline,
                'file_lampiran'   => $fileLampiran
            ]);

            Helper::flash('success', 'Penugasan baru berhasil dibuat dan diterbitkan untuk kelas.');
            Helper::redirect('guru_tugas');
        }

        require __DIR__ . '/../views/guru/tugas_form.php';
    }

    /**
     * Lihat Seluruh Pengumpulan Tugas Siswa & Penilaian
     */
    public function tugasSubmissions(): void
    {
        $teacherId = Auth::user()['id'];
        $assignmentId = (int)($_GET['id'] ?? 0);
        $tugas = $this->tugasModel->getDetail($assignmentId);

        if (!$tugas) {
            Helper::flash('error', 'Penugasan tidak ditemukan.');
            Helper::redirect('guru_tugas');
        }

        $submissions = $this->tugasModel->getSubmissionsByAssignment($assignmentId);
        require __DIR__ . '/../views/guru/tugas_submissions.php';
    }

    /**
     * Simpan Nilai & Feedback Tugas Siswa
     */
    public function tugasGrade(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignmentId = (int)($_POST['assignment_id'] ?? 0);
            $studentUserId = (int)($_POST['student_user_id'] ?? 0);
            $nilai = (float)($_POST['nilai'] ?? 0);
            $catatanGuru = trim($_POST['catatan_guru'] ?? '');

            if ($assignmentId > 0 && $studentUserId > 0) {
                $this->tugasModel->gradeSubmission($assignmentId, $studentUserId, $nilai, $catatanGuru);
                Helper::flash('success', 'Nilai dan evaluasi tugas siswa berhasil disimpan!');
            }
            Helper::redirect('guru_tugas_submissions&id=' . $assignmentId);
        }
    }

    /**
     * Hapus Penugasan
     */
    public function tugasDelete(): void
    {
        $teacherId = Auth::user()['id'];
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->tugasModel->deleteAssignment($id, $teacherId);
            Helper::flash('success', 'Penugasan beserta seluruh pengumpulan tugas siswa berhasil dihapus.');
        }
        Helper::redirect('guru_tugas');
    }

    public function ujian(): void
    {
        $teacherId = Auth::user()['id'];
        $ujianList = $this->ujianModel->getByTeacher($teacherId);
        require __DIR__ . '/../views/guru/ujian.php';
    }

    /**
     * Buat Jadwal Ujian / Kuis Baru & Hubungkan dengan Bank Soal
     */
    public function ujianCreate(): void
    {
        $teacherId = Auth::user()['id'];
        $mapelList = $this->mapelModel->getAll();
        $kelasList = $this->kelasModel->getKelasByTeacher($teacherId);
        if (empty($kelasList)) {
            $kelasList = $this->kelasModel->getAll();
        }
        $bankSoal = $this->ujianModel->getBankSoal($teacherId, false);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $subjectId = (int)($_POST['subject_id'] ?? 0);
            $classId = (int)($_POST['class_id'] ?? 0);
            $durasi = (int)($_POST['durasi_menit'] ?? 60);
            $kkm = (float)($_POST['kkm'] ?? 75.00);
            $tglMulai = $_POST['tgl_mulai'] ?? date('Y-m-d H:i:s');
            $tglSelesai = $_POST['tgl_selesai'] ?? date('Y-m-d H:i:s', strtotime('+7 days'));
            $acakSoal = isset($_POST['acak_soal']) ? 1 : 0;
            $soalIds = $_POST['soal_ids'] ?? [];

            if (empty($judul) || $subjectId === 0 || $classId === 0) {
                Helper::flash('error', 'Judul ujian, mata pelajaran, dan kelas sasaran wajib diisi.');
                Helper::redirect('guru_ujian_create');
            }

            if (empty($soalIds)) {
                Helper::flash('error', 'Pilih minimal 1 butir soal dari Bank Soal untuk ujian ini.');
                Helper::redirect('guru_ujian_create');
            }

            $examData = [
                'subject_id'      => $subjectId,
                'class_id'        => $classId,
                'teacher_user_id' => $teacherId,
                'judul'           => $judul,
                'deskripsi'       => $deskripsi,
                'durasi_menit'    => $durasi,
                'tgl_mulai'       => $tglMulai,
                'tgl_selesai'     => $tglSelesai,
                'kkm'             => $kkm,
                'acak_soal'       => $acakSoal,
                'is_active'       => 1
            ];

            $examId = $this->ujianModel->createExam($examData, $soalIds);
            Helper::flash('success', 'Jadwal Ujian "' . htmlspecialchars($judul) . '" berhasil dibuat dengan ' . count($soalIds) . ' butir soal!');
            Helper::redirect('guru_ujian');
        }

        require __DIR__ . '/../views/guru/ujian_form.php';
    }

    /**
     * Hapus Ujian
     */
    public function ujianDelete(): void
    {
        $teacherId = Auth::user()['id'];
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->ujianModel->deleteExam($id, $teacherId);
            Helper::flash('success', 'Jadwal ujian beserta riwayat pengerjaan berhasil dihapus.');
        }
        Helper::redirect('guru_ujian');
    }

    /**
     * Edit Butir Soal di Bank Soal
     */
    public function soalEdit(): void
    {
        $teacherId = Auth::user()['id'];
        $id = (int)($_GET['id'] ?? 0);
        $soal = $this->ujianModel->getQuestionById($id);

        if (!$soal) {
            Helper::flash('error', 'Butir soal tidak ditemukan.');
            Helper::redirect('guru_bank_soal');
        }

        $mapelList = $this->mapelModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subjectId = (int)$_POST['subject_id'];
            $bab = trim($_POST['bab'] ?? 'Bab 1');
            $tipe = $_POST['tipe'] === 'essay' ? 'essay' : 'pg';
            $kesulitan = $_POST['kesulitan'] ?? 'medium';
            $pertanyaan = trim($_POST['pertanyaan'] ?? '');
            $pembahasan = trim($_POST['pembahasan'] ?? '');

            // Upload gambar baru pertanyaan jika ada
            $gambarSoal = $soal['gambar'];
            if (isset($_FILES['gambar_soal']) && $_FILES['gambar_soal']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['gambar_soal']['name'], PATHINFO_EXTENSION));
                $targetDir = __DIR__ . '/../uploads/soal/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = 'soal_' . time() . '_' . rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($_FILES['gambar_soal']['tmp_name'], $targetDir . $fileName)) {
                    $gambarSoal = 'uploads/soal/' . $fileName;
                }
            }

            $opsiList = [];
            if ($tipe === 'pg') {
                $kunci = strtoupper(trim($_POST['kunci_jawaban_pg'] ?? 'A'));
                foreach (['A', 'B', 'C', 'D', 'E'] as $label) {
                    $teks = trim($_POST['opsi_teks_' . $label] ?? '');
                    
                    // Ambil gambar opsi eksisting
                    $gambarOpsi = null;
                    foreach ($soal['opsi'] as $oldOpt) {
                        if ($oldOpt['label'] === $label) {
                            $gambarOpsi = $oldOpt['gambar'];
                            break;
                        }
                    }

                    if (isset($_FILES['opsi_gambar_' . $label]) && $_FILES['opsi_gambar_' . $label]['error'] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['opsi_gambar_' . $label]['name'], PATHINFO_EXTENSION));
                        $targetDir = __DIR__ . '/../uploads/soal/';
                        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                        $fileName = 'opsi_' . $label . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                        if (move_uploaded_file($_FILES['opsi_gambar_' . $label]['tmp_name'], $targetDir . $fileName)) {
                            $gambarOpsi = 'uploads/soal/' . $fileName;
                        }
                    }
                    $opsiList[] = [
                        'label'  => $label,
                        'teks'   => $teks,
                        'gambar' => $gambarOpsi
                    ];
                }
            } else {
                $kunci = trim($_POST['kunci_jawaban_essay'] ?? '');
            }

            $qData = [
                'subject_id'    => $subjectId,
                'bab'           => $bab,
                'tipe'          => $tipe,
                'pertanyaan'    => $pertanyaan,
                'gambar'        => $gambarSoal,
                'kesulitan'     => $kesulitan,
                'kunci_jawaban' => $kunci,
                'pembahasan'    => $pembahasan
            ];

            $this->ujianModel->updateQuestion($id, $qData, $opsiList);
            Helper::flash('success', 'Butir soal berhasil diperbarui.');
            Helper::redirect('guru_bank_soal');
        }

        require __DIR__ . '/../views/guru/soal_form.php';
    }

    /**
     * Hapus Butir Soal dari Bank Soal
     */
    public function soalDelete(): void
    {
        $teacherId = Auth::user()['id'];
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->ujianModel->deleteQuestion($id, $teacherId);
            Helper::flash('success', 'Butir soal berhasil dihapus dari Bank Soal.');
        }
        Helper::redirect('guru_bank_soal');
    }

    public function bankSoal(): void
    {
        $teacherId = Auth::user()['id'];
        $subjectId = isset($_GET['subject_id']) && $_GET['subject_id'] !== '' ? (int)$_GET['subject_id'] : null;
        $perPage = 10;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $totalSoal = $this->ujianModel->countBankSoal($teacherId, false, $subjectId);
        $soalList = $this->ujianModel->getPaginatedBankSoal($teacherId, false, $subjectId, $perPage, $offset);
        $mapelList = $this->mapelModel->getAll();
        require __DIR__ . '/../views/guru/bank_soal.php';
    }

    /**
     * Buat Soal Manual (Pilihan Ganda dengan upload gambar opsi, Essay dengan Quill.js)
     */
    public function soalCreate(): void
    {
        $teacherId = Auth::user()['id'];
        $mapelList = $this->mapelModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subjectId = (int)$_POST['subject_id'];
            $bab = trim($_POST['bab'] ?? 'Bab 1');
            $tipe = $_POST['tipe'] === 'essay' ? 'essay' : 'pg';
            $kesulitan = $_POST['kesulitan'] ?? 'medium';
            $pertanyaan = trim($_POST['pertanyaan'] ?? '');
            $pembahasan = trim($_POST['pembahasan'] ?? '');

            // Upload gambar pertanyaan jika ada
            $gambarSoal = null;
            if (isset($_FILES['gambar_soal']) && $_FILES['gambar_soal']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['gambar_soal']['name'], PATHINFO_EXTENSION));
                $targetDir = __DIR__ . '/../uploads/soal/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = 'soal_' . time() . '_' . rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($_FILES['gambar_soal']['tmp_name'], $targetDir . $fileName)) {
                    $gambarSoal = 'uploads/soal/' . $fileName;
                }
            }

            $opsiList = [];
            if ($tipe === 'pg') {
                $kunci = strtoupper(trim($_POST['kunci_jawaban_pg'] ?? 'A'));
                foreach (['A', 'B', 'C', 'D', 'E'] as $label) {
                    $teks = trim($_POST['opsi_teks_' . $label] ?? '');
                    $gambarOpsi = null;
                    if (isset($_FILES['opsi_gambar_' . $label]) && $_FILES['opsi_gambar_' . $label]['error'] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['opsi_gambar_' . $label]['name'], PATHINFO_EXTENSION));
                        $targetDir = __DIR__ . '/../uploads/soal/';
                        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                        $fileName = 'opsi_' . $label . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
                        if (move_uploaded_file($_FILES['opsi_gambar_' . $label]['tmp_name'], $targetDir . $fileName)) {
                            $gambarOpsi = 'uploads/soal/' . $fileName;
                        }
                    }
                    $opsiList[] = [
                        'label'  => $label,
                        'teks'   => $teks,
                        'gambar' => $gambarOpsi
                    ];
                }
            } else {
                $kunci = trim($_POST['kunci_jawaban_essay'] ?? '');
            }

            $qData = [
                'subject_id'    => $subjectId,
                'bab'           => $bab,
                'tipe'          => $tipe,
                'pertanyaan'    => $pertanyaan,
                'gambar'        => $gambarSoal,
                'kesulitan'     => $kesulitan,
                'kunci_jawaban' => $kunci,
                'pembahasan'    => $pembahasan,
                'created_by'    => $teacherId
            ];

            $this->ujianModel->saveQuestion($qData, $opsiList);
            Helper::flash('success', 'Soal manual berhasil disimpan ke Bank Soal.');
            Helper::redirect('guru_bank_soal');
        }

        require __DIR__ . '/../views/guru/soal_form.php';
    }

    /**
     * AJAX Upload Image untuk Rich Text Editor (Quill.js)
     */
    public function uploadImage(): void
    {
        header('Content-Type: application/json');
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada file diunggah.']);
            exit;
        }

        $file = $_FILES['image'];
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Format file tidak didukung (gunakan JPG, PNG, WebP).']);
            exit;
        }

        $type = $_GET['type'] ?? 'materi';
        $folder = ($type === 'soal') ? 'uploads/soal/' : 'uploads/materi/';
        $targetDir = __DIR__ . '/../' . $folder;
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = 'img_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $targetDir . $fileName)) {
            echo json_encode(['success' => true, 'url' => $folder . $fileName]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file di server.']);
        }
        exit;
    }

    // AI Assistant Views
    public function aiGenerateSoal(): void
    {
        $mapelList = $this->mapelModel->getAll();
        require __DIR__ . '/../views/guru/ai_generate_soal.php';
    }

    public function aiGenerateRpp(): void
    {
        $mapelList = $this->mapelModel->getAll();
        require __DIR__ . '/../views/guru/ai_generate_rpp.php';
    }

    public function aiGenerateMateri(): void
    {
        $mapelList = $this->mapelModel->getAll();
        require __DIR__ . '/../views/guru/ai_generate_materi.php';
    }
}
