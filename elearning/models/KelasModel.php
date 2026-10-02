<?php
class KelasModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM `lms_class_students` cs WHERE cs.class_id = c.id) AS total_siswa,
                       COALESCE(p.nama, u.username) AS nama_wali
                FROM `lms_classes` c
                LEFT JOIN `user` u ON c.wali_user_id = u.id
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                ORDER BY c.nama_kelas ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT c.*, COALESCE(p.nama, u.username) AS nama_wali
                                    FROM `lms_classes` c
                                    LEFT JOIN `user` u ON c.wali_user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    WHERE c.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getKelasByStudent(int $studentUserId): ?array
    {
        $stmt = $this->db->prepare("SELECT c.* FROM `lms_classes` c 
                                    INNER JOIN `lms_class_students` cs ON c.id = cs.class_id 
                                    WHERE cs.student_user_id = ? LIMIT 1");
        $stmt->execute([$studentUserId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getKelasByTeacher(int $teacherUserId): array
    {
        $stmt = $this->db->prepare("SELECT DISTINCT c.* FROM `lms_classes` c
                                    INNER JOIN `lms_teacher_subjects` ts ON c.id = ts.class_id
                                    WHERE ts.teacher_user_id = ?
                                    ORDER BY c.nama_kelas ASC");
        $stmt->execute([$teacherUserId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_classes` (`kode_kelas`, `nama_kelas`, `tingkat`, `jurusan`, `wali_user_id`, `tahun_ajaran`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['kode_kelas'],
            $data['nama_kelas'],
            $data['tingkat'] ?? '10',
            $data['jurusan'] ?? 'Umum',
            !empty($data['wali_user_id']) ? $data['wali_user_id'] : null,
            $data['tahun_ajaran'] ?? '2024/2025',
            $data['is_active'] ?? 1
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE `lms_classes` SET `kode_kelas` = ?, `nama_kelas` = ?, `tingkat` = ?, `jurusan` = ?, `wali_user_id` = ?, `tahun_ajaran` = ?, `is_active` = ? WHERE `id` = ?");
        return $stmt->execute([
            $data['kode_kelas'],
            $data['nama_kelas'],
            $data['tingkat'] ?? '10',
            $data['jurusan'] ?? 'Umum',
            !empty($data['wali_user_id']) ? $data['wali_user_id'] : null,
            $data['tahun_ajaran'] ?? '2024/2025',
            $data['is_active'] ?? 1,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $this->db->prepare("DELETE FROM `lms_class_students` WHERE `class_id` = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM `lms_teacher_subjects` WHERE `class_id` = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM `lms_classes` WHERE `id` = ?")->execute([$id]);
        return true;
    }
}