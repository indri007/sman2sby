<?php
/**
 * BukuModel - Model untuk Perpustakaan Digital / Buku PDF
 */
class BukuModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Hitung total buku untuk paginasi dengan filter mapel & pencarian
     */
    public function countAll(?int $subjectId = null, ?string $search = null): int
    {
        $sql = "SELECT COUNT(*) FROM `lms_books` b WHERE 1=1";
        $params = [];

        if ($subjectId && $subjectId > 0) {
            $sql .= " AND b.subject_id = :subject_id";
            $params[':subject_id'] = $subjectId;
        }

        if ($search && trim($search) !== '') {
            $sql .= " AND (b.judul LIKE :search1 OR b.penulis LIKE :search2 OR b.penerbit LIKE :search3)";
            $term = '%' . trim($search) . '%';
            $params[':search1'] = $term;
            $params[':search2'] = $term;
            $params[':search3'] = $term;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Ambil buku dengan paginasi, filter mapel & pencarian
     */
    public function getAll(int $limit = 10, int $offset = 0, ?int $subjectId = null, ?string $search = null): array
    {
        $sql = "SELECT b.*, s.nama_mapel, s.kode_mapel, COALESCE(p.nama, u.username) AS nama_pengunggah
                FROM `lms_books` b
                LEFT JOIN `lms_subjects` s ON b.subject_id = s.id
                LEFT JOIN `user` u ON b.uploaded_by = u.id
                LEFT JOIN `pegawai` p ON u.id = p.id_user
                WHERE 1=1";
        $params = [];

        if ($subjectId && $subjectId > 0) {
            $sql .= " AND b.subject_id = :subject_id";
            $params[':subject_id'] = $subjectId;
        }

        if ($search && trim($search) !== '') {
            $sql .= " AND (b.judul LIKE :search1 OR b.penulis LIKE :search2 OR b.penerbit LIKE :search3)";
            $term = '%' . trim($search) . '%';
            $params[':search1'] = $term;
            $params[':search2'] = $term;
            $params[':search3'] = $term;
        }

        $sql .= " ORDER BY b.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Ambil buku terbaru untuk rak buku di Dashboard
     */
    public function getRecent(int $limit = 4): array
    {
        $stmt = $this->db->prepare("SELECT b.*, s.nama_mapel, s.kode_mapel
                                    FROM `lms_books` b
                                    LEFT JOIN `lms_subjects` s ON b.subject_id = s.id
                                    ORDER BY b.id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Detail 1 buku berdasarkan ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT b.*, s.nama_mapel, s.kode_mapel, COALESCE(p.nama, u.username) AS nama_pengunggah
                                    FROM `lms_books` b
                                    LEFT JOIN `lms_subjects` s ON b.subject_id = s.id
                                    LEFT JOIN `user` u ON b.uploaded_by = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    WHERE b.id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Tambah buku baru
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO `lms_books`
            (`judul`, `penulis`, `penerbit`, `tahun_terbit`, `subject_id`, `kelas`, `deskripsi`, `file_pdf`, `cover_image`, `uploaded_by`, `total_views`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([
            $data['judul'],
            $data['penulis'] ?? null,
            $data['penerbit'] ?? null,
            $data['tahun_terbit'] ?? null,
            $data['subject_id'] ?: null,
            $data['kelas'] ?? 'Kelas X',
            $data['deskripsi'] ?? null,
            $data['file_pdf'],
            $data['cover_image'] ?? null,
            $data['uploaded_by'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update data buku
     */
    public function update(int $id, array $data): bool
    {
        $fields = [
            'judul = ?',
            'penulis = ?',
            'penerbit = ?',
            'tahun_terbit = ?',
            'subject_id = ?',
            'kelas = ?',
            'deskripsi = ?'
        ];
        $params = [
            $data['judul'],
            $data['penulis'] ?? null,
            $data['penerbit'] ?? null,
            $data['tahun_terbit'] ?? null,
            $data['subject_id'] ?: null,
            $data['kelas'] ?? 'Kelas X',
            $data['deskripsi'] ?? null
        ];

        if (!empty($data['file_pdf'])) {
            $fields[] = 'file_pdf = ?';
            $params[] = $data['file_pdf'];
        }

        if (isset($data['cover_image']) && $data['cover_image'] !== null) {
            $fields[] = 'cover_image = ?';
            $params[] = $data['cover_image'];
        }

        $params[] = $id;
        $sql = "UPDATE `lms_books` SET " . implode(', ', $fields) . " WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Hapus buku
     */
    public function delete(int $id): bool
    {
        $buku = $this->getById($id);
        if ($buku) {
            if ($buku['file_pdf'] && !str_starts_with($buku['file_pdf'], 'sample_') && file_exists(__DIR__ . '/../uploads/buku/' . $buku['file_pdf'])) {
                @unlink(__DIR__ . '/../uploads/buku/' . $buku['file_pdf']);
            }
            if ($buku['cover_image'] && file_exists(__DIR__ . '/../uploads/buku/covers/' . $buku['cover_image'])) {
                @unlink(__DIR__ . '/../uploads/buku/covers/' . $buku['cover_image']);
            }
        }
        $stmt = $this->db->prepare("DELETE FROM `lms_books` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Tambahkan view count saat buku dibuka
     */
    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE `lms_books` SET `total_views` = `total_views` + 1 WHERE `id` = ?");
        $stmt->execute([$id]);
    }
}
