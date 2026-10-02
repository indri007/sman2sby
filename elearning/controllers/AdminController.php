<?php
class AdminController
{
    private UserModel $userModel;
    private KelasModel $kelasModel;
    private MapelModel $mapelModel;
    private AiModel $aiModel;

    public function __construct()
    {
        Auth::requireRole('ADMIN');
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        $this->aiModel = new AiModel();
    }

    public function dashboard(): void
    {
        $guruList = $this->userModel->getGuruList();
        $siswaList = $this->userModel->getSiswaList();
        $kelasList = $this->kelasModel->getAll();
        $mapelList = $this->mapelModel->getAll();
        $aiStats = $this->aiModel->getStats();
        $aiLogs = $this->aiModel->getLogs(5);
        $totalBuku = (new BukuModel())->countAll();

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function guru(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $nip = trim($_POST['nip'] ?? '');
                $nama = trim($_POST['nama'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $telp = trim($_POST['nomor_telepon'] ?? '');

                if ($nip && $nama) {
                    $this->userModel->createGuru([
                        'nip' => $nip,
                        'nama' => $nama,
                        'password' => $password,
                        'nomor_telepon' => $telp
                    ]);
                    Helper::flash('success', 'Data guru baru berhasil ditambahkan.');
                }
            } elseif ($action === 'edit') {
                $userId = (int)($_POST['user_id'] ?? 0);
                $nama = trim($_POST['nama'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $telp = trim($_POST['nomor_telepon'] ?? '');

                if ($userId > 0 && $nama) {
                    $this->userModel->updateGuru($userId, [
                        'nama' => $nama,
                        'password' => $password,
                        'nomor_telepon' => $telp
                    ]);
                    Helper::flash('success', 'Data guru berhasil diperbarui.');
                }
            } elseif ($action === 'delete') {
                $userId = (int)($_POST['user_id'] ?? 0);
                if ($userId > 0) {
                    $this->userModel->deleteGuru($userId);
                    Helper::flash('success', 'Data guru berhasil dihapus.');
                }
            }
            Helper::redirect('admin_guru');
        }

        $perPage = 10;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $totalGuru = $this->userModel->countGuru();
        $guruList = $this->userModel->getPaginatedGuru($perPage, $offset);
        require __DIR__ . '/../views/admin/guru.php';
    }

    public function siswa(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $nisn = trim($_POST['nisn'] ?? '');
                $nama = trim($_POST['nama'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $classId = (int)($_POST['class_id'] ?? 0);
                $telp = trim($_POST['nomor_telepon'] ?? '');
                $tempatLahir = trim($_POST['tempat_lahir'] ?? '');

                if ($nisn && $nama) {
                    $this->userModel->createSiswa([
                        'nisn' => $nisn,
                        'nama' => $nama,
                        'password' => $password,
                        'class_id' => $classId,
                        'nomor_telepon' => $telp,
                        'tempat_lahir' => $tempatLahir
                    ]);
                    Helper::flash('success', 'Data siswa baru berhasil ditambahkan ke kelas.');
                }
            } elseif ($action === 'edit') {
                $userId = (int)($_POST['user_id'] ?? 0);
                $nama = trim($_POST['nama'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $classId = (int)($_POST['class_id'] ?? 0);
                $telp = trim($_POST['nomor_telepon'] ?? '');
                $tempatLahir = trim($_POST['tempat_lahir'] ?? '');

                if ($userId > 0 && $nama) {
                    $this->userModel->updateSiswa($userId, [
                        'nama' => $nama,
                        'password' => $password,
                        'class_id' => $classId,
                        'nomor_telepon' => $telp,
                        'tempat_lahir' => $tempatLahir
                    ]);
                    Helper::flash('success', 'Data siswa berhasil diperbarui.');
                }
            } elseif ($action === 'delete') {
                $userId = (int)($_POST['user_id'] ?? 0);
                if ($userId > 0) {
                    $this->userModel->deleteSiswa($userId);
                    Helper::flash('success', 'Data siswa berhasil dihapus.');
                }
            }
            Helper::redirect('admin_siswa');
        }

        $classId = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? (int)$_GET['class_id'] : null;
        $perPage = 10;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $totalSiswa = $this->userModel->countSiswa($classId);
        $siswaList = $this->userModel->getPaginatedSiswa($classId, $perPage, $offset);
        $kelasList = $this->kelasModel->getAll();
        require __DIR__ . '/../views/admin/siswa.php';
    }

    public function kelas(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $kode = trim($_POST['kode_kelas'] ?? '');
                $nama = trim($_POST['nama_kelas'] ?? '');
                $tingkat = trim($_POST['tingkat'] ?? '10');
                $tahun = trim($_POST['tahun_ajaran'] ?? '2024/2025');
                $waliId = (int)($_POST['wali_user_id'] ?? 0);

                if ($kode && $nama) {
                    $this->kelasModel->create([
                        'kode_kelas' => $kode,
                        'nama_kelas' => $nama,
                        'tingkat' => $tingkat,
                        'tahun_ajaran' => $tahun,
                        'wali_user_id' => $waliId
                    ]);
                    Helper::flash('success', 'Kelas baru berhasil ditambahkan.');
                }
            } elseif ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $kode = trim($_POST['kode_kelas'] ?? '');
                $nama = trim($_POST['nama_kelas'] ?? '');
                $tingkat = trim($_POST['tingkat'] ?? '10');
                $tahun = trim($_POST['tahun_ajaran'] ?? '2024/2025');
                $waliId = (int)($_POST['wali_user_id'] ?? 0);

                if ($id > 0 && $kode && $nama) {
                    $this->kelasModel->update($id, [
                        'kode_kelas' => $kode,
                        'nama_kelas' => $nama,
                        'tingkat' => $tingkat,
                        'tahun_ajaran' => $tahun,
                        'wali_user_id' => $waliId
                    ]);
                    Helper::flash('success', 'Data kelas berhasil diperbarui.');
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->kelasModel->delete($id);
                    Helper::flash('success', 'Kelas berhasil dihapus.');
                }
            }
            Helper::redirect('admin_kelas');
        }

        $kelasList = $this->kelasModel->getAll();
        $guruList = $this->userModel->getGuruList();
        require __DIR__ . '/../views/admin/kelas.php';
    }

    public function mapel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $kode = trim($_POST['kode_mapel'] ?? '');
                $nama = trim($_POST['nama_mapel'] ?? '');
                $kelompok = trim($_POST['kelompok'] ?? 'Wajib');
                $deskripsi = trim($_POST['deskripsi'] ?? '');

                if ($kode && $nama) {
                    $this->mapelModel->create([
                        'kode_mapel' => $kode,
                        'nama_mapel' => $nama,
                        'kelompok' => $kelompok,
                        'deskripsi' => $deskripsi
                    ]);
                    Helper::flash('success', 'Mata pelajaran berhasil ditambahkan.');
                }
            } elseif ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $kode = trim($_POST['kode_mapel'] ?? '');
                $nama = trim($_POST['nama_mapel'] ?? '');
                $kelompok = trim($_POST['kelompok'] ?? 'Wajib');
                $deskripsi = trim($_POST['deskripsi'] ?? '');

                if ($id > 0 && $kode && $nama) {
                    $this->mapelModel->update($id, [
                        'kode_mapel' => $kode,
                        'nama_mapel' => $nama,
                        'kelompok' => $kelompok,
                        'deskripsi' => $deskripsi
                    ]);
                    Helper::flash('success', 'Mata pelajaran berhasil diperbarui.');
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->mapelModel->delete($id);
                    Helper::flash('success', 'Mata pelajaran berhasil dihapus.');
                }
            }
            Helper::redirect('admin_mapel');
        }

        $mapelList = $this->mapelModel->getAll();
        require __DIR__ . '/../views/admin/mapel.php';
    }

    public function aiKeys(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'add') {
                $name = trim($_POST['key_name'] ?? '');
                $key = trim($_POST['api_key'] ?? '');
                if ($name && $key) {
                    $this->aiModel->addKey($name, $key);
                    Helper::flash('success', 'Gemini API Key berhasil ditambahkan ke pool.');
                }
            } elseif ($action === 'toggle') {
                $id = (int)($_POST['id'] ?? 0);
                $status = $_POST['status'] === 'active' ? 'inactive' : 'active';
                $this->aiModel->updateKeyStatus($id, $status);
                Helper::flash('success', 'Status API key diperbarui.');
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                $this->aiModel->deleteKey($id);
                Helper::flash('success', 'API Key telah dihapus.');
            }
            Helper::redirect('admin_ai_keys');
        }

        $keys = $this->aiModel->getKeys();
        $stats = $this->aiModel->getStats();
        require __DIR__ . '/../views/admin/ai_keys.php';
    }

    public function aiLogs(): void
    {
        $perPage = 10;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $totalLogs = $this->aiModel->countLogs();
        $logs = $this->aiModel->getPaginatedLogs($perPage, $offset);
        require __DIR__ . '/../views/admin/ai_logs.php';
    }
}
