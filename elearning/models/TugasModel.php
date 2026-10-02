<?php
class TugasModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByTeacher(int $teacherUserId): array
    {
        $role = Auth::role();
        if ($role === 'ADMIN') {
            $stmt = $this->db->prepare("SELECT a.*, s.nama_mapel, c.nama_kelas,
                                           (SELECT COUNT(*) FROM `lms_assignment_submissions` sub WHERE sub.assignment_id = a.id) AS total_mengumpulkan,
                                           (SELECT COUNT(*) FROM `lms_class_students` cs WHERE cs.class_id = a.class_id) AS total_siswa_kelas
                                    FROM `lms_assignments` a
                                    JOIN `lms_subjects` s ON a.subject_id = s.id
                                    JOIN `lms_classes` c ON a.class_id = c.id
                                    ORDER BY a.id DESC");
            $stmt->execute();
        } else {
            $stmt = $this->db->prepare("SELECT a.*, s.nama_mapel, c.nama_kelas,
                                           (SELECT COUNT(*) FROM `lms_assignment_submissions` sub WHERE sub.assignment_id = a.id) AS total_mengumpulkan,
                                           (SELECT COUNT(*) FROM `lms_class_students` cs WHERE cs.class_id = a.class_id) AS total_siswa_kelas
                                    FROM `lms_assignments` a
                                    JOIN `lms_subjects` s ON a.subject_id = s.id
                                    JOIN `lms_classes` c ON a.class_id = c.id
                                    WHERE a.teacher_user_id = ?
                                    ORDER BY a.id DESC");
            $stmt->execute([$teacherUserId]);
        }
        return $stmt->fetchAll();
    }

    public function getByClassAndStudent(int $classId, int $studentUserId): array
    {
        $stmt = $this->db->prepare("SELECT a.*, s.nama_mapel, COALESCE(p.nama, u.username) AS nama_guru,
                                           sub.id AS submission_id, sub.nilai, sub.status AS status_tugas, sub.submitted_at
                                    FROM `lms_assignments` a
                                    JOIN `lms_subjects` s ON a.subject_id = s.id
                                    JOIN `user` u ON a.teacher_user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    LEFT JOIN `lms_assignment_submissions` sub ON a.id = sub.assignment_id AND sub.student_user_id = ?
                                    WHERE a.class_id = ?
                                    ORDER BY a.deadline ASC");
        $stmt->execute([$studentUserId, $classId]);
        return $stmt->fetchAll();
    }

    public function getDetail(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT a.*, s.nama_mapel, c.nama_kelas, COALESCE(p.nama, u.username) AS nama_guru,
                                           (SELECT COUNT(*) FROM `lms_class_students` cs WHERE cs.class_id = a.class_id) AS total_siswa_kelas,
                                           (SELECT COUNT(*) FROM `lms_assignment_submissions` sub WHERE sub.assignment_id = a.id) AS total_mengumpulkan
                                    FROM `lms_assignments` a
                                    JOIN `lms_subjects` s ON a.subject_id = s.id
                                    JOIN `lms_classes` c ON a.class_id = c.id
                                    JOIN `user` u ON a.teacher_user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    WHERE a.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getSubmissionsByAssignment(int $assignmentId): array
    {
        $assignment = $this->getDetail($assignmentId);
        if (!$assignment) return [];

        $classId = (int)$assignment['class_id'];
        $sql = "SELECT u.id AS student_user_id, u.username AS nisn, u.username AS nama_siswa,
                       sub.id AS submission_id, sub.file_jawaban, sub.catatan_siswa, sub.nilai, 
                       sub.catatan_guru, sub.status, sub.submitted_at, sub.graded_at
                FROM `lms_class_students` cs
                JOIN `user` u ON cs.student_user_id = u.id
                LEFT JOIN `lms_assignment_submissions` sub ON sub.assignment_id = ? AND sub.student_user_id = u.id
                WHERE cs.class_id = ?
                ORDER BY (sub.id IS NOT NULL) DESC, sub.submitted_at DESC, u.username ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$assignmentId, $classId]);
        return $stmt->fetchAll();
    }

    public function getSubmission(int $assignmentId, int $studentUserId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `lms_assignment_submissions` WHERE `assignment_id` = ? AND `student_user_id` = ? LIMIT 1");
        $stmt->execute([$assignmentId, $studentUserId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function submit(int $assignmentId, int $studentUserId, ?string $fileJawaban, ?string $catatan): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_assignment_submissions` (`assignment_id`, `student_user_id`, `file_jawaban`, `catatan_siswa`, `status`, `submitted_at`)
                                    VALUES (?, ?, ?, ?, 'submitted', NOW())
                                    ON DUPLICATE KEY UPDATE `file_jawaban` = VALUES(`file_jawaban`), `catatan_siswa` = VALUES(`catatan_siswa`), `submitted_at` = NOW()");
        return $stmt->execute([$assignmentId, $studentUserId, $fileJawaban, $catatan]);
    }

    public function gradeSubmission(int $assignmentId, int $studentUserId, float $nilai, ?string $catatanGuru): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_assignment_submissions` 
            (`assignment_id`, `student_user_id`, `nilai`, `catatan_guru`, `status`, `submitted_at`, `graded_at`)
            VALUES (?, ?, ?, ?, 'graded', NOW(), NOW())
            ON DUPLICATE KEY UPDATE `nilai` = VALUES(`nilai`), `catatan_guru` = VALUES(`catatan_guru`), `status` = 'graded', `graded_at` = NOW()");
        return $stmt->execute([$assignmentId, $studentUserId, $nilai, $catatanGuru]);
    }

    public function createAssignment(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_assignments` 
            (`subject_id`, `class_id`, `teacher_user_id`, `judul`, `deskripsi`, `deadline`, `file_lampiran`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['subject_id'],
            $data['class_id'],
            $data['teacher_user_id'],
            $data['judul'],
            $data['deskripsi'] ?? '',
            $data['deadline'],
            $data['file_lampiran'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function deleteAssignment(int $id, int $teacherUserId): bool
    {
        $this->db->prepare("DELETE FROM `lms_assignment_submissions` WHERE `assignment_id` = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM `lms_assignments` WHERE `id` = ?")->execute([$id]);
        return true;
    }
}
