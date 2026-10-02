<?php
/**
 * Auth Guard & Session Handler
 * Integrates directly with `user` and `pegawai` tables from absen1 database
 */
class Auth
{
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        self::init();
        if (!self::check()) {
            return null;
        }

        return [
            'id'       => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'nama'     => $_SESSION['nama'] ?? $_SESSION['username'],
            'role'     => $_SESSION['role'], // 'ADMIN', 'GURU', 'SISWA'
            'nip'      => $_SESSION['nip'] ?? '',
            'foto'     => $_SESSION['foto'] ?? '',
            'pegawai_id' => $_SESSION['pegawai_id'] ?? null
        ];
    }

    public static function id(): ?int
    {
        self::init();
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }

    public static function role(): string
    {
        self::init();
        return $_SESSION['role'] ?? 'GUEST';
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu.';
            header('Location: index.php?page=login');
            exit;
        }
    }

    public static function requireRole(string|array $roles): void
    {
        self::requireLogin();
        $userRole = self::role();
        $allowed = is_array($roles) ? $roles : [$roles];

        if (!in_array($userRole, $allowed)) {
            $_SESSION['flash_error'] = 'Akses ditolak. Anda tidak memiliki izin ke halaman ini.';
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    public static function attempt(string $username, string $password): bool
    {
        self::init();
        $db = Database::getConnection();

        // 1. Cari user berdasarkan username
        $stmt = $db->prepare("SELECT * FROM `user` WHERE `username` = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        // 2. Verifikasi password (plaintext atau MD5 jika dimodifikasi)
        $passwordMatch = ($password === $user['password']) || (md5($password) === $user['password']);
        if (!$passwordMatch) {
            return false;
        }

        // 3. Tentukan Role dan ambil data profil dari pegawai/jabatan
        $role = 'SISWA';
        $nama = $user['username'];
        $nip = $user['username'];
        $foto = '';
        $pegawaiId = null;

        if (strtoupper($user['status']) === 'ADMIN') {
            $role = 'ADMIN';
            $nama = 'Administrator SMAN 2';
        } else {
            // Cek di tabel pegawai
            $stmtPeg = $db->prepare("SELECT p.*, j.nama AS nama_jabatan FROM `pegawai` p LEFT JOIN `jabatan` j ON p.id_jabatan = j.id WHERE p.id_user = ? LIMIT 1");
            $stmtPeg->execute([$user['id']]);
            $pegawai = $stmtPeg->fetch();

            if ($pegawai) {
                $pegawaiId = $pegawai['id'];
                $nama = $pegawai['nama'];
                $nip = $pegawai['nip'];
                $foto = $pegawai['gambar'] ?? '';

                if (strtolower($pegawai['nama_jabatan']) === 'siswa') {
                    $role = 'SISWA';
                } else {
                    $role = 'GURU';
                }
            } else {
                // User dengan status PEGAWAI tapi belum masuk tabel pegawai (e.g. Guru dengan NIP 18 digit)
                if (strlen($user['username']) >= 18) {
                    $role = 'GURU';
                    $nama = 'Guru SMAN 2 (' . $user['username'] . ')';
                } else {
                    $role = 'SISWA';
                    $nama = 'Siswa (' . $user['username'] . ')';
                }
            }
        }

        // 4. Set session
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['username']   = $user['username'];
        $_SESSION['nama']       = $nama;
        $_SESSION['role']       = $role;
        $_SESSION['nip']        = $nip;
        $_SESSION['foto']       = $foto;
        $_SESSION['pegawai_id'] = $pegawaiId;

        // 5. Update last login
        $updateStmt = $db->prepare("UPDATE `user` SET `last_login` = NOW(), `ip_address` = ? WHERE `id` = ?");
        $updateStmt->execute([$_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', $user['id']]);

        return true;
    }

    public static function logout(): void
    {
        self::init();
        $_SESSION = [];
        session_destroy();
    }
}
