<?php
class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getGuruList(): array
    {
        // Ambil user dengan status PEGAWAI yang merupakan guru
        $sql = "SELECT u.id, u.username AS nip, u.last_login,
                       COALESCE(p.nama, CONCAT('Guru ', u.username)) AS nama,
                       p.nomor_telepon, p.tempat_lahir, p.tanggal_lahir,
                       (SELECT COUNT(*) FROM `lms_teacher_subjects` ts WHERE ts.teacher_user_id = u.id) AS total_mengajar
                FROM `user` u
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                WHERE u.status = 'PEGAWAI' AND (j.nama IS NULL OR LOWER(j.nama) != 'siswa')
                ORDER BY u.id ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function countGuru(): int
    {
        $sql = "SELECT COUNT(*) FROM `user` u
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                WHERE u.status = 'PEGAWAI' AND (j.nama IS NULL OR LOWER(j.nama) != 'siswa')";
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function getPaginatedGuru(int $limit = 10, int $offset = 0): array
    {
        $limit = max(1, $limit);
        $offset = max(0, $offset);
        $sql = "SELECT u.id, u.username AS nip, u.last_login,
                       COALESCE(p.nama, CONCAT('Guru ', u.username)) AS nama,
                       p.nomor_telepon, p.tempat_lahir, p.tanggal_lahir,
                       (SELECT COUNT(*) FROM `lms_teacher_subjects` ts WHERE ts.teacher_user_id = u.id) AS total_mengajar
                FROM `user` u
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                WHERE u.status = 'PEGAWAI' AND (j.nama IS NULL OR LOWER(j.nama) != 'siswa')
                ORDER BY u.id ASC
                LIMIT {$limit} OFFSET {$offset}";
        return $this->db->query($sql)->fetchAll();
    }

    public function getSiswaList(?int $classId = null): array
    {
        $sql = "SELECT u.id, u.username AS nisn, u.last_login,
                       COALESCE(p.nama, CONCAT('Siswa ', u.username)) AS nama,
                       p.nomor_telepon, p.tempat_lahir, p.tanggal_lahir,
                       c.nama_kelas, cs.class_id
                FROM `user` u
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                LEFT JOIN `lms_class_students` cs ON u.id = cs.student_user_id
                LEFT JOIN `lms_classes` c ON cs.class_id = c.id
                WHERE u.status = 'PEGAWAI' AND (LOWER(j.nama) = 'siswa' OR LENGTH(u.username) <= 12)";
        
        if ($classId !== null) {
            $sql .= " AND cs.class_id = " . (int)$classId;
        }

        $sql .= " ORDER BY p.nama ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function countSiswa(?int $classId = null): int
    {
        $sql = "SELECT COUNT(*) FROM `user` u
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                LEFT JOIN `lms_class_students` cs ON u.id = cs.student_user_id
                WHERE u.status = 'PEGAWAI' AND (LOWER(j.nama) = 'siswa' OR LENGTH(u.username) <= 12)";
        if ($classId !== null) {
            $sql .= " AND cs.class_id = " . (int)$classId;
        }
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function getPaginatedSiswa(?int $classId = null, int $limit = 10, int $offset = 0): array
    {
        $limit = max(1, $limit);
        $offset = max(0, $offset);
        $sql = "SELECT u.id, u.username AS nisn, u.last_login,
                       COALESCE(p.nama, CONCAT('Siswa ', u.username)) AS nama,
                       p.nomor_telepon, p.tempat_lahir, p.tanggal_lahir,
                       c.nama_kelas, cs.class_id
                FROM `user` u
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                LEFT JOIN `lms_class_students` cs ON u.id = cs.student_user_id
                LEFT JOIN `lms_classes` c ON cs.class_id = c.id
                WHERE u.status = 'PEGAWAI' AND (LOWER(j.nama) = 'siswa' OR LENGTH(u.username) <= 12)";
        if ($classId !== null) {
            $sql .= " AND cs.class_id = " . (int)$classId;
        }
        $sql .= " ORDER BY p.nama ASC LIMIT {$limit} OFFSET {$offset}";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT u.*, p.nama, p.nomor_telepon, p.tempat_lahir, p.tanggal_lahir, p.gambar, j.nama AS nama_jabatan
                                    FROM `user` u
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    LEFT JOIN `jabatan` j ON p.id_jabatan = j.id
                                    WHERE u.id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function createGuru(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO `user` (`username`, `password`, `status`) VALUES (?, ?, 'PEGAWAI')");
        $stmt->execute([
            $data['nip'],
            $data['password'] ?: $data['nip']
        ]);
        $userId = (int)$this->db->lastInsertId();

        $stmtPeg = $this->db->prepare("INSERT INTO `pegawai` (`id_user`, `nip`, `nama`, `nomor_telepon`, `id_jabatan`) VALUES (?, ?, ?, ?, 2)");
        $stmtPeg->execute([
            $userId,
            $data['nip'],
            $data['nama'],
            $data['nomor_telepon'] ?? ''
        ]);

        return $userId;
    }

    public function updateGuru(int $userId, array $data): bool
    {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("UPDATE `user` SET `password` = ? WHERE `id` = ?");
            $stmt->execute([$data['password'], $userId]);
        }

        $stmtPeg = $this->db->prepare("UPDATE `pegawai` SET `nama` = ?, `nomor_telepon` = ? WHERE `id_user` = ?");
        return $stmtPeg->execute([
            $data['nama'],
            $data['nomor_telepon'] ?? '',
            $userId
        ]);
    }

    public function deleteGuru(int $userId): bool
    {
        $this->db->prepare("DELETE FROM `pegawai` WHERE `id_user` = ?")->execute([$userId]);
        $this->db->prepare("DELETE FROM `lms_teacher_subjects` WHERE `teacher_user_id` = ?")->execute([$userId]);
        $this->db->prepare("DELETE FROM `user` WHERE `id` = ?")->execute([$userId]);
        return true;
    }

    public function createSiswa(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO `user` (`username`, `password`, `status`) VALUES (?, ?, 'PEGAWAI')");
        $stmt->execute([
            $data['nisn'],
            $data['password'] ?: $data['nisn']
        ]);
        $userId = (int)$this->db->lastInsertId();

        $stmtPeg = $this->db->prepare("INSERT INTO `pegawai` (`id_user`, `nip`, `nama`, `nomor_telepon`, `tempat_lahir`, `id_jabatan`) VALUES (?, ?, ?, ?, ?, 1)");
        $stmtPeg->execute([
            $userId,
            $data['nisn'],
            $data['nama'],
            $data['nomor_telepon'] ?? '',
            $data['tempat_lahir'] ?? ''
        ]);

        if (!empty($data['class_id'])) {
            $stmtCls = $this->db->prepare("INSERT INTO `lms_class_students` (`class_id`, `student_user_id`) VALUES (?, ?)");
            $stmtCls->execute([(int)$data['class_id'], $userId]);
        }

        return $userId;
    }

    public function updateSiswa(int $userId, array $data): bool
    {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("UPDATE `user` SET `password` = ? WHERE `id` = ?");
            $stmt->execute([$data['password'], $userId]);
        }

        $stmtPeg = $this->db->prepare("UPDATE `pegawai` SET `nama` = ?, `nomor_telepon` = ?, `tempat_lahir` = ? WHERE `id_user` = ?");
        $stmtPeg->execute([
            $data['nama'],
            $data['nomor_telepon'] ?? '',
            $data['tempat_lahir'] ?? '',
            $userId
        ]);

        if (!empty($data['class_id'])) {
            $this->db->prepare("DELETE FROM `lms_class_students` WHERE `student_user_id` = ?")->execute([$userId]);
            $stmtCls = $this->db->prepare("INSERT INTO `lms_class_students` (`class_id`, `student_user_id`) VALUES (?, ?)");
            $stmtCls->execute([(int)$data['class_id'], $userId]);
        }

        return true;
    }

    public function deleteSiswa(int $userId): bool
    {
        $this->db->prepare("DELETE FROM `lms_class_students` WHERE `student_user_id` = ?")->execute([$userId]);
        $this->db->prepare("DELETE FROM `pegawai` WHERE `id_user` = ?")->execute([$userId]);
        $this->db->prepare("DELETE FROM `user` WHERE `id` = ?")->execute([$userId]);
        return true;
    }
}