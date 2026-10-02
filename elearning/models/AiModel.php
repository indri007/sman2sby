<?php
class AiModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getKeys(): array
    {
        return $this->db->query("SELECT * FROM `ai_api_keys` ORDER BY `id` ASC")->fetchAll();
    }

    public function addKey(string $keyName, string $apiKey): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `ai_api_keys` (`provider`, `key_name`, `api_key`, `status`) VALUES ('gemini', ?, ?, 'active')");
        return $stmt->execute([$keyName, $apiKey]);
    }

    public function updateKeyStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE `ai_api_keys` SET `status` = ? WHERE `id` = ?");
        return $stmt->execute([$status, $id]);
    }

    public function deleteKey(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `ai_api_keys` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    public function countLogs(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM `ai_logs`")->fetchColumn();
    }

    public function getPaginatedLogs(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare("SELECT l.*, COALESCE(p.nama, u.username) AS nama_user
                                    FROM `ai_logs` l
                                    JOIN `user` u ON l.user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    ORDER BY l.id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLogs(int $limit = 50): array
    {
        $stmt = $this->db->prepare("SELECT l.*, COALESCE(p.nama, u.username) AS nama_user
                                    FROM `ai_logs` l
                                    JOIN `user` u ON l.user_id = u.id
                                    LEFT JOIN `pegawai` p ON u.id = p.id_user
                                    ORDER BY l.id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStats(): array
    {
        $totalReq = $this->db->query("SELECT COUNT(*) FROM `ai_logs`")->fetchColumn();
        $totalSuccess = $this->db->query("SELECT COUNT(*) FROM `ai_logs` WHERE `status` = 'success'")->fetchColumn();
        $activeKeys = $this->db->query("SELECT COUNT(*) FROM `ai_api_keys` WHERE `status` = 'active'")->fetchColumn();
        
        $features = $this->db->query("SELECT feature, COUNT(*) as count FROM `ai_logs` GROUP BY feature")->fetchAll();

        return [
            'total_requests' => (int)$totalReq,
            'total_success'  => (int)$totalSuccess,
            'active_keys'    => (int)$activeKeys,
            'feature_breakdown' => $features
        ];
    }
}
