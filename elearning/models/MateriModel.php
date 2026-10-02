<?php
class MateriModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByTeacher(int $teacherUserId): array
    {
        $stmt = $this->db->prepare("SELECT m.*, s.nama_mapel,
                                           (SELECT GROUP_CONCAT(c.nama_kelas SEPARATOR ', ') 
                                            FROM `lms_material_classes` mc 
                                            JOIN `lms_classes` c ON mc.class_id = c.id 
                                            WHERE mc.material_id = m.id) AS kelas_terbagi
                                    FROM `lms_materials` m
                                    JOIN `lms_subjects` s ON m.subject_id = s.id
                                    WHERE m.teacher_user_id = ?
                                    ORDER BY m.id DESC");
        $stmt->execute([$teacherUserId]);
        return $stmt->fetchAll();
    }

    public function getByClass(int $classId, ?int $subjectId = null): array
    {
        $sql = "SELECT m.*, s.nama_mapel, COALESCE(p.nama, u.username) AS nama_guru
                FROM `lms_materials` m
                JOIN `lms_subjects` s ON m.subject_id = s.id
                JOIN `lms_material_classes` mc ON m.id = mc.material_id
                JOIN `user` u ON m.teacher_user_id = u.id
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                WHERE mc.class_id = ? AND m.is_published = 1";
        
        $params = [$classId];
        if ($subjectId !== null) {
            $sql .= " AND m.subject_id = ?";
            $params[] = $subjectId;
        }

        $sql .= " ORDER BY m.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDetail(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT m.*, s.nama_mapel, COALESCE(p.nama, u.username) AS nama_guru
                                    FROM `lms_materials` m
                                    JOIN `lms_subjects` s ON m.subject_id = s.id
                                    JOIN `user` u ON m.teacher_user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    WHERE m.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data, array $classIds): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_materials` (`subject_id`, `teacher_user_id`, `judul`, `bab`, `konten`, `file_lampiran`, `youtube_url`, `is_published`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['subject_id'],
            $data['teacher_user_id'],
            $data['judul'],
            $data['bab'],
            $data['konten'],
            $data['file_lampiran'] ?? null,
            $data['youtube_url'] ?? null,
            $data['is_published'] ?? 1
        ]);
        $matId = (int)$this->db->lastInsertId();

        if (!empty($classIds)) {
            $stmtCls = $this->db->prepare("INSERT IGNORE INTO `lms_material_classes` (`material_id`, `class_id`) VALUES (?, ?)");
            foreach ($classIds as $cid) {
                $stmtCls->execute([$matId, (int)$cid]);
            }
        }

        return $matId;
    }
}
