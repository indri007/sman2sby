<?php
class MapelModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT s.*, 
                       (SELECT COUNT(*) FROM `lms_materials` m WHERE m.subject_id = s.id) AS total_materi,
                       (SELECT COUNT(*) FROM `lms_exams` e WHERE e.subject_id = s.id) AS total_ujian
                FROM `lms_subjects` s ORDER BY s.nama_mapel ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `lms_subjects` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getByTeacher(int $teacherUserId): array
    {
        $stmt = $this->db->prepare("SELECT DISTINCT s.* FROM `lms_subjects` s
                                    INNER JOIN `lms_teacher_subjects` ts ON s.id = ts.subject_id
                                    WHERE ts.teacher_user_id = ?
                                    ORDER BY s.nama_mapel ASC");
        $stmt->execute([$teacherUserId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_subjects` (`kode_mapel`, `nama_mapel`, `kelompok`, `deskripsi`, `icon`) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['kode_mapel'],
            $data['nama_mapel'],
            $data['kelompok'] ?? 'Wajib',
            $data['deskripsi'] ?? '',
            $data['icon'] ?? 'book-open'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE `lms_subjects` SET `kode_mapel` = ?, `nama_mapel` = ?, `kelompok` = ?, `deskripsi` = ? WHERE `id` = ?");
        return $stmt->execute([
            $data['kode_mapel'],
            $data['nama_mapel'],
            $data['kelompok'] ?? 'Wajib',
            $data['deskripsi'] ?? '',
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $this->db->prepare("DELETE FROM `lms_teacher_subjects` WHERE `subject_id` = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM `lms_subjects` WHERE `id` = ?")->execute([$id]);
        return true;
    }
}