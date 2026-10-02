<?php
class UjianModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByTeacher(int $teacherUserId): array
    {
        $role = class_exists('Auth') ? (Auth::role() ?? '') : '';
        if ($role === 'ADMIN') {
            $stmt = $this->db->prepare("SELECT e.*, s.nama_mapel, c.nama_kelas,
                                           (SELECT COUNT(*) FROM `lms_exam_questions` eq WHERE eq.exam_id = e.id) AS total_soal,
                                           (SELECT COUNT(*) FROM `lms_exam_attempts` att WHERE att.exam_id = e.id AND att.status = 'completed') AS total_peserta
                                    FROM `lms_exams` e
                                    JOIN `lms_subjects` s ON e.subject_id = s.id
                                    JOIN `lms_classes` c ON e.class_id = c.id
                                    ORDER BY e.id DESC");
            $stmt->execute();
        } else {
            $stmt = $this->db->prepare("SELECT e.*, s.nama_mapel, c.nama_kelas,
                                           (SELECT COUNT(*) FROM `lms_exam_questions` eq WHERE eq.exam_id = e.id) AS total_soal,
                                           (SELECT COUNT(*) FROM `lms_exam_attempts` att WHERE att.exam_id = e.id AND att.status = 'completed') AS total_peserta
                                    FROM `lms_exams` e
                                    JOIN `lms_subjects` s ON e.subject_id = s.id
                                    JOIN `lms_classes` c ON e.class_id = c.id
                                    WHERE e.teacher_user_id = ?
                                    ORDER BY e.id DESC");
            $stmt->execute([$teacherUserId]);
        }
        return $stmt->fetchAll();
    }

    public function getByClassAndStudent(int $classId, int $studentUserId): array
    {
        $stmt = $this->db->prepare("SELECT e.*, s.nama_mapel,
                                           (SELECT COUNT(*) FROM `lms_exam_questions` eq WHERE eq.exam_id = e.id) AS total_soal,
                                           att.id AS attempt_id, att.status AS status_attempt, att.nilai_akhir
                                    FROM `lms_exams` e
                                    JOIN `lms_subjects` s ON e.subject_id = s.id
                                    LEFT JOIN `lms_exam_attempts` att ON e.id = att.exam_id AND att.student_user_id = ?
                                    WHERE e.class_id = ? AND e.is_active = 1
                                    ORDER BY e.tgl_mulai DESC");
        $stmt->execute([$studentUserId, $classId]);
        return $stmt->fetchAll();
    }

    public function getDetail(int $examId): ?array
    {
        $stmt = $this->db->prepare("SELECT e.*, s.nama_mapel, c.nama_kelas, COALESCE(p.nama, u.username) AS nama_guru
                                    FROM `lms_exams` e
                                    JOIN `lms_subjects` s ON e.subject_id = s.id
                                    JOIN `lms_classes` c ON e.class_id = c.id
                                    JOIN `user` u ON e.teacher_user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    WHERE e.id = ? LIMIT 1");
        $stmt->execute([$examId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getQuestions(int $examId, bool $shuffle = false): array
    {
        $sql = "SELECT q.*, eq.bobot
                FROM `lms_exam_questions` eq
                JOIN `lms_questions` q ON eq.question_id = q.id
                WHERE eq.exam_id = ?";
        if ($shuffle) {
            $sql .= " ORDER BY RAND()";
        } else {
            $sql .= " ORDER BY eq.id ASC";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$examId]);
        $questions = $stmt->fetchAll();

        foreach ($questions as &$q) {
            $stmtOpt = $this->db->prepare("SELECT * FROM `lms_question_options` WHERE `question_id` = ? ORDER BY `label` ASC");
            $stmtOpt->execute([$q['id']]);
            $q['opsi'] = $stmtOpt->fetchAll();
        }

        return $questions;
    }

    public function getBankSoal(?int $teacherUserId = null, bool $onlyMine = false): array
    {
        $role = class_exists('Auth') ? (Auth::role() ?? '') : '';
        if ($role === 'ADMIN' || !$onlyMine) {
            $stmt = $this->db->prepare("SELECT q.*, s.nama_mapel, COALESCE(p.nama, u.username) AS nama_pembuat 
                                        FROM `lms_questions` q
                                        JOIN `lms_subjects` s ON q.subject_id = s.id
                                        LEFT JOIN `user` u ON q.created_by = u.id
                                        LEFT JOIN `pegawai` p ON u.id = p.id_user
                                        ORDER BY q.id DESC");
            $stmt->execute();
        } else {
            $stmt = $this->db->prepare("SELECT q.*, s.nama_mapel, COALESCE(p.nama, u.username) AS nama_pembuat 
                                        FROM `lms_questions` q
                                        JOIN `lms_subjects` s ON q.subject_id = s.id
                                        LEFT JOIN `user` u ON q.created_by = u.id
                                        LEFT JOIN `pegawai` p ON u.id = p.id_user
                                        WHERE q.created_by = ?
                                        ORDER BY q.id DESC");
            $stmt->execute([$teacherUserId]);
        }
        $questions = $stmt->fetchAll();

        foreach ($questions as &$q) {
            $stmtOpt = $this->db->prepare("SELECT * FROM `lms_question_options` WHERE `question_id` = ? ORDER BY `label` ASC");
            $stmtOpt->execute([$q['id']]);
            $q['opsi'] = $stmtOpt->fetchAll();
        }

        return $questions;
    }

    public function countBankSoal(?int $teacherUserId = null, bool $onlyMine = false, ?int $subjectId = null): int
    {
        $role = class_exists('Auth') ? (Auth::role() ?? '') : '';
        $sql = "SELECT COUNT(*) FROM `lms_questions` q WHERE 1=1";
        $params = [];

        if ($role !== 'ADMIN' && $onlyMine && $teacherUserId) {
            $sql .= " AND q.created_by = ?";
            $params[] = $teacherUserId;
        }

        if ($subjectId) {
            $sql .= " AND q.subject_id = ?";
            $params[] = $subjectId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getPaginatedBankSoal(?int $teacherUserId = null, bool $onlyMine = false, ?int $subjectId = null, int $limit = 10, int $offset = 0): array
    {
        $role = class_exists('Auth') ? (Auth::role() ?? '') : '';
        $sql = "SELECT q.*, s.nama_mapel, COALESCE(p.nama, u.username) AS nama_pembuat 
                FROM `lms_questions` q
                JOIN `lms_subjects` s ON q.subject_id = s.id
                LEFT JOIN `user` u ON q.created_by = u.id
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                WHERE 1=1";
        $params = [];

        if ($role !== 'ADMIN' && $onlyMine && $teacherUserId) {
            $sql .= " AND q.created_by = ?";
            $params[] = $teacherUserId;
        }

        if ($subjectId) {
            $sql .= " AND q.subject_id = ?";
            $params[] = $subjectId;
        }

        $limit = max(1, $limit);
        $offset = max(0, $offset);
        $sql .= " ORDER BY q.id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $questions = $stmt->fetchAll();

        foreach ($questions as &$q) {
            $stmtOpt = $this->db->prepare("SELECT * FROM `lms_question_options` WHERE `question_id` = ? ORDER BY `label` ASC");
            $stmtOpt->execute([$q['id']]);
            $q['opsi'] = $stmtOpt->fetchAll();
        }

        return $questions;
    }

    public function saveQuestion(array $data, array $opsiList = []): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_questions` (`bank_id`, `subject_id`, `bab`, `tipe`, `pertanyaan`, `gambar`, `kesulitan`, `kunci_jawaban`, `pembahasan`, `created_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['bank_id'] ?? null,
            $data['subject_id'],
            $data['bab'],
            $data['tipe'] ?? 'pg',
            $data['pertanyaan'],
            $data['gambar'] ?? null,
            $data['kesulitan'] ?? 'medium',
            $data['kunci_jawaban'],
            $data['pembahasan'] ?? null,
            $data['created_by']
        ]);
        $qid = (int)$this->db->lastInsertId();

        if ($data['tipe'] === 'pg' && !empty($opsiList)) {
            $stmtOpt = $this->db->prepare("INSERT INTO `lms_question_options` (`question_id`, `label`, `teks_opsi`, `gambar`, `is_benar`) VALUES (?, ?, ?, ?, ?)");
            foreach ($opsiList as $opt) {
                $isBenar = (strtoupper($opt['label']) === strtoupper($data['kunci_jawaban'])) ? 1 : 0;
                $stmtOpt->execute([$qid, $opt['label'], $opt['teks'] ?? '', $opt['gambar'] ?? null, $isBenar]);
            }
        }

        return $qid;
    }

    public function getQuestionById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT q.*, s.nama_mapel FROM `lms_questions` q
                                    JOIN `lms_subjects` s ON q.subject_id = s.id
                                    WHERE q.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $question = $stmt->fetch();
        if (!$question) return null;

        $stmtOpt = $this->db->prepare("SELECT * FROM `lms_question_options` WHERE `question_id` = ? ORDER BY `label` ASC");
        $stmtOpt->execute([$id]);
        $question['opsi'] = $stmtOpt->fetchAll();

        return $question;
    }

    public function updateQuestion(int $id, array $data, array $opsiList = []): bool
    {
        $stmt = $this->db->prepare("UPDATE `lms_questions` SET 
            `subject_id` = ?, 
            `bab` = ?, 
            `tipe` = ?, 
            `pertanyaan` = ?, 
            `gambar` = COALESCE(?, `gambar`), 
            `kesulitan` = ?, 
            `kunci_jawaban` = ?, 
            `pembahasan` = ? 
            WHERE `id` = ?");
        $stmt->execute([
            $data['subject_id'],
            $data['bab'],
            $data['tipe'] ?? 'pg',
            $data['pertanyaan'],
            $data['gambar'] ?? null,
            $data['kesulitan'] ?? 'medium',
            $data['kunci_jawaban'],
            $data['pembahasan'] ?? null,
            $id
        ]);

        if (($data['tipe'] ?? 'pg') === 'pg' && !empty($opsiList)) {
            // Hapus opsi lama dan masukkan opsi yang diperbarui
            $del = $this->db->prepare("DELETE FROM `lms_question_options` WHERE `question_id` = ?");
            $del->execute([$id]);

            $stmtOpt = $this->db->prepare("INSERT INTO `lms_question_options` (`question_id`, `label`, `teks_opsi`, `gambar`, `is_benar`) VALUES (?, ?, ?, ?, ?)");
            foreach ($opsiList as $opt) {
                $isBenar = (strtoupper($opt['label']) === strtoupper($data['kunci_jawaban'])) ? 1 : 0;
                $stmtOpt->execute([$id, $opt['label'], $opt['teks'] ?? '', $opt['gambar'] ?? null, $isBenar]);
            }
        }
        return true;
    }

    public function deleteQuestion(int $id, int $userId): bool
    {
        $delOpt = $this->db->prepare("DELETE FROM `lms_question_options` WHERE `question_id` = ?");
        $delOpt->execute([$id]);

        $delEq = $this->db->prepare("DELETE FROM `lms_exam_questions` WHERE `question_id` = ?");
        $delEq->execute([$id]);

        $delQ = $this->db->prepare("DELETE FROM `lms_questions` WHERE `id` = ?");
        $delQ->execute([$id]);

        return true;
    }

    public function createExam(array $data, array $questionIds = []): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_exams` 
            (`subject_id`, `class_id`, `teacher_user_id`, `judul`, `deskripsi`, `durasi_menit`, `tgl_mulai`, `tgl_selesai`, `kkm`, `acak_soal`, `is_active`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['subject_id'],
            $data['class_id'],
            $data['teacher_user_id'],
            $data['judul'],
            $data['deskripsi'] ?? '',
            $data['durasi_menit'] ?? 60,
            $data['tgl_mulai'],
            $data['tgl_selesai'],
            $data['kkm'] ?? 75.00,
            $data['acak_soal'] ?? 1,
            $data['is_active'] ?? 1
        ]);
        $examId = (int)$this->db->lastInsertId();

        if (!empty($questionIds)) {
            $count = count($questionIds);
            $bobot = round(100.0 / $count, 2);
            $stmtEq = $this->db->prepare("INSERT INTO `lms_exam_questions` (`exam_id`, `question_id`, `bobot`) VALUES (?, ?, ?)");
            foreach ($questionIds as $qid) {
                $stmtEq->execute([$examId, (int)$qid, $bobot]);
            }
        }

        return $examId;
    }

    public function deleteExam(int $examId, int $userId): bool
    {
        $this->db->prepare("DELETE ea FROM `lms_exam_answers` ea JOIN `lms_exam_attempts` att ON ea.attempt_id = att.id WHERE att.exam_id = ?")->execute([$examId]);
        $this->db->prepare("DELETE FROM `lms_exam_attempts` WHERE `exam_id` = ?")->execute([$examId]);
        $this->db->prepare("DELETE FROM `lms_exam_questions` WHERE `exam_id` = ?")->execute([$examId]);
        $this->db->prepare("DELETE FROM `lms_exams` WHERE `id` = ?")->execute([$examId]);
        return true;
    }
}
