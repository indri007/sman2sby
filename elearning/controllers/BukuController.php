<?php
/**
 * BukuController - Controller untuk Perpustakaan Digital / Buku Pelajaran PDF
 */
class BukuController
{
    private BukuModel $bukuModel;
    private MapelModel $mapelModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->bukuModel = new BukuModel();
        $this->mapelModel = new MapelModel();
    }

    /**
     * Katalog Perpustakaan Digital (Semua Role: Siswa, Guru, Admin)
     */
    public function index(): void
    {
        $role = Auth::role();
        $perPage = 12;
        $page = max(1, (int)($_GET['p'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $subjectId = !empty($_GET['subject_id']) ? (int)$_GET['subject_id'] : null;
        $search = trim($_GET['q'] ?? '');

        $totalBuku = $this->bukuModel->countAll($subjectId, $search);
        $bukuList = $this->bukuModel->getAll($perPage, $offset, $subjectId, $search);
        $mapelList = $this->mapelModel->getAll();

        $pageTitle = "Perpustakaan Digital - SMAN 2 Surabaya";
        require __DIR__ . '/../views/buku/index.php';
    }

    /**
     * Halaman Reader Online PDF (Cuman Baca Tanpa Download)
     */
    public function baca(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $buku = $this->bukuModel->getById($id);

        if (!$buku) {
            Helper::flash('error', 'Buku perpustakaan tidak ditemukan.');
            Helper::redirect(Auth::role() === 'SISWA' ? 'siswa_buku' : 'guru_buku');
        }

        // Tambah counter view
        $this->bukuModel->incrementViews($id);

        $pageTitle = "Membaca: " . $buku['judul'] . " - Perpus Digital SMAN 2 Surabaya";
        require __DIR__ . '/../views/buku/baca.php';
    }

    /**
     * Stream protected PDF file inline (Mencegah auto-download)
     */
    public function streamPdf(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $buku = $this->bukuModel->getById($id);

        if (!$buku || empty($buku['file_pdf'])) {
            http_response_code(404);
            die('Berkas buku PDF tidak ditemukan.');
        }

        $filePath = __DIR__ . '/../uploads/buku/' . $buku['file_pdf'];
        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File PDF tidak ada di server.');
        }

        // Header proteksi inline streaming
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($buku['file_pdf']) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filePath));
        header('Accept-Ranges: bytes');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Bersihkan output buffer sebelum kirim file
        if (ob_get_level()) {
            ob_end_clean();
        }

        readfile($filePath);
        exit;
    }

    /**
     * Form Tambah Buku PDF (Guru & Admin)
     */
    public function create(): void
    {
        Auth::requireRole(['ADMIN', 'GURU']);
        $mapelList = $this->mapelModel->getAll();
        $buku = null;
        $pageTitle = "Tambah Buku PDF - Perpustakaan Digital";
        require __DIR__ . '/../views/buku/form.php';
    }

    /**
     * Form Edit Buku PDF (Guru & Admin)
     */
    public function edit(): void
    {
        Auth::requireRole(['ADMIN', 'GURU']);
        $id = (int)($_GET['id'] ?? 0);
        $buku = $this->bukuModel->getById($id);

        if (!$buku) {
            Helper::flash('error', 'Buku tidak ditemukan.');
            Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku' : 'guru_buku');
        }

        $mapelList = $this->mapelModel->getAll();
        $pageTitle = "Edit Buku PDF: " . $buku['judul'];
        require __DIR__ . '/../views/buku/form.php';
    }

    /**
     * Simpan Buku PDF Baru
     */
    public function store(): void
    {
        Auth::requireRole(['ADMIN', 'GURU']);

        $judul = trim($_POST['judul'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $penerbit = trim($_POST['penerbit'] ?? '');
        $tahun = trim($_POST['tahun_terbit'] ?? '');
        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $kelas = trim($_POST['kelas'] ?? 'Kelas X');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if (empty($judul)) {
            Helper::flash('error', 'Judul buku wajib diisi.');
            Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku_create' : 'guru_buku_create');
        }

        // Upload PDF
        if (empty($_FILES['file_pdf']['name']) || $_FILES['file_pdf']['error'] !== UPLOAD_ERR_OK) {
            Helper::flash('error', 'File PDF buku wajib diunggah.');
            Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku_create' : 'guru_buku_create');
        }

        $ext = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            Helper::flash('error', 'Format file harus berupa dokumen PDF (.pdf).');
            Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku_create' : 'guru_buku_create');
        }

        $pdfFileName = 'buku_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
        $targetPdf = __DIR__ . '/../uploads/buku/' . $pdfFileName;

        if (!move_uploaded_file($_FILES['file_pdf']['tmp_name'], $targetPdf)) {
            Helper::flash('error', 'Gagal menyimpan file PDF ke server.');
            Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku_create' : 'guru_buku_create');
        }

        // Upload Cover Image (Opsional)
        $coverFileName = '';
        if (!empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverExt = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            if (in_array($coverExt, ['jpg', 'jpeg', 'png', 'webp'])) {
                $coverFileName = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $coverExt;
                move_uploaded_file($_FILES['cover_image']['tmp_name'], __DIR__ . '/../uploads/buku/covers/' . $coverFileName);
            }
        }

        $this->bukuModel->create([
            'judul' => $judul,
            'penulis' => $penulis,
            'penerbit' => $penerbit,
            'tahun_terbit' => $tahun,
            'subject_id' => $subjectId,
            'kelas' => $kelas,
            'deskripsi' => $deskripsi,
            'file_pdf' => $pdfFileName,
            'cover_image' => $coverFileName,
            'uploaded_by' => Auth::id()
        ]);

        Helper::flash('success', 'Buku PDF berhasil diterbitkan ke Perpustakaan Digital.');
        Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku' : 'guru_buku');
    }

    /**
     * Simpan Perubahan Buku
     */
    public function update(): void
    {
        Auth::requireRole(['ADMIN', 'GURU']);
        $id = (int)($_POST['id'] ?? 0);
        $buku = $this->bukuModel->getById($id);

        if (!$buku) {
            Helper::flash('error', 'Buku tidak ditemukan.');
            Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku' : 'guru_buku');
        }

        $judul = trim($_POST['judul'] ?? '');
        $penulis = trim($_POST['penulis'] ?? '');
        $penerbit = trim($_POST['penerbit'] ?? '');
        $tahun = trim($_POST['tahun_terbit'] ?? '');
        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $kelas = trim($_POST['kelas'] ?? 'Kelas X');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        $dataUpdate = [
            'judul' => $judul,
            'penulis' => $penulis,
            'penerbit' => $penerbit,
            'tahun_terbit' => $tahun,
            'subject_id' => $subjectId,
            'kelas' => $kelas,
            'deskripsi' => $deskripsi
        ];

        // Ganti file PDF jika ada upload baru
        if (!empty($_FILES['file_pdf']['name']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $pdfFileName = 'buku_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
                if (move_uploaded_file($_FILES['file_pdf']['tmp_name'], __DIR__ . '/../uploads/buku/' . $pdfFileName)) {
                    // Hapus file lama jika bukan sample
                    if ($buku['file_pdf'] && !str_starts_with($buku['file_pdf'], 'sample_') && file_exists(__DIR__ . '/../uploads/buku/' . $buku['file_pdf'])) {
                        @unlink(__DIR__ . '/../uploads/buku/' . $buku['file_pdf']);
                    }
                    $dataUpdate['file_pdf'] = $pdfFileName;
                }
            }
        }

        // Ganti cover jika ada upload baru
        if (!empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverExt = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            if (in_array($coverExt, ['jpg', 'jpeg', 'png', 'webp'])) {
                $coverFileName = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $coverExt;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], __DIR__ . '/../uploads/buku/covers/' . $coverFileName)) {
                    if ($buku['cover_image'] && file_exists(__DIR__ . '/../uploads/buku/covers/' . $buku['cover_image'])) {
                        @unlink(__DIR__ . '/../uploads/buku/covers/' . $buku['cover_image']);
                    }
                    $dataUpdate['cover_image'] = $coverFileName;
                }
            }
        }

        $this->bukuModel->update($id, $dataUpdate);
        Helper::flash('success', 'Data buku perpustakaan berhasil diperbarui.');
        Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku' : 'guru_buku');
    }

    /**
     * Hapus Buku
     */
    public function delete(): void
    {
        Auth::requireRole(['ADMIN', 'GURU']);
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->bukuModel->delete($id);
            Helper::flash('success', 'Buku perpustakaan telah dihapus.');
        }
        Helper::redirect(Auth::role() === 'ADMIN' ? 'admin_buku' : 'guru_buku');
    }
}
