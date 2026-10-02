<?php
$currentUser = Auth::user();
$role = Auth::role();
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <img src="assets/images/logo.webp" alt="Logo SMAN 2 Surabaya" class="sidebar-logo">
        <div class="sidebar-brand-title">
            APLIKASI E-LEARNING
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-title">
            <span>Menu Utama</span>
        </div>
        <a href="index.php?page=dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <i data-lucide="layout-dashboard" class="icon"></i>
            <span>Dashboard</span>
        </a>

        <?php if ($role === 'ADMIN'): ?>
            <div class="nav-section-title">
                <span>Manajemen Sekolah</span>
            </div>
            <a href="index.php?page=admin_guru" class="nav-link <?= $currentPage === 'admin_guru' ? 'active' : '' ?>">
                <i data-lucide="users" class="icon"></i>
                <span>Data Guru</span>
            </a>
            <a href="index.php?page=admin_siswa" class="nav-link <?= $currentPage === 'admin_siswa' ? 'active' : '' ?>">
                <i data-lucide="graduation-cap" class="icon"></i>
                <span>Data Siswa</span>
            </a>
            <a href="index.php?page=admin_kelas" class="nav-link <?= $currentPage === 'admin_kelas' ? 'active' : '' ?>">
                <i data-lucide="school" class="icon"></i>
                <span>Data Kelas</span>
            </a>
            <a href="index.php?page=admin_mapel" class="nav-link <?= $currentPage === 'admin_mapel' ? 'active' : '' ?>">
                <i data-lucide="book-open" class="icon"></i>
                <span>Mata Pelajaran</span>
            </a>
            <a href="index.php?page=admin_buku" class="nav-link <?= in_array($currentPage, ['admin_buku', 'admin_buku_create', 'admin_buku_edit', 'buku_baca']) ? 'active' : '' ?>">
                <i data-lucide="book-copy" class="icon"></i>
                <span>Perpus Digital (PDF)</span>
            </a>

            <div class="nav-section-title">
                <span>AI Management</span>
            </div>
            <a href="index.php?page=admin_ai_keys" class="nav-link <?= $currentPage === 'admin_ai_keys' ? 'active' : '' ?>">
                <i data-lucide="key-round" class="icon"></i>
                <span>Gemini Key Pool</span>
            </a>
            <a href="index.php?page=admin_ai_logs" class="nav-link <?= $currentPage === 'admin_ai_logs' ? 'active' : '' ?>">
                <i data-lucide="activity" class="icon"></i>
                <span>Usage & Logs AI</span>
            </a>
        <?php endif; ?>

        <?php if ($role === 'GURU'): ?>
            <div class="nav-section-title">
                <span>Pembelajaran</span>
            </div>
            <a href="index.php?page=guru_materi" class="nav-link <?= $currentPage === 'guru_materi' ? 'active' : '' ?>">
                <i data-lucide="file-text" class="icon"></i>
                <span>Materi Belajar</span>
            </a>
            <a href="index.php?page=guru_buku" class="nav-link <?= in_array($currentPage, ['guru_buku', 'guru_buku_create', 'guru_buku_edit', 'buku_baca']) ? 'active' : '' ?>">
                <i data-lucide="book-copy" class="icon"></i>
                <span>Perpus Digital (PDF)</span>
            </a>
            <a href="index.php?page=guru_tugas" class="nav-link <?= $currentPage === 'guru_tugas' ? 'active' : '' ?>">
                <i data-lucide="clipboard-check" class="icon"></i>
                <span>Tugas Siswa</span>
            </a>
            <a href="index.php?page=guru_ujian" class="nav-link <?= $currentPage === 'guru_ujian' ? 'active' : '' ?>">
                <i data-lucide="timer" class="icon"></i>
                <span>Ujian & Kuis</span>
            </a>
            <a href="index.php?page=guru_bank_soal" class="nav-link <?= $currentPage === 'guru_bank_soal' ? 'active' : '' ?>">
                <i data-lucide="database" class="icon"></i>
                <span>Bank Soal</span>
            </a>

            <div class="nav-section-title">
                <span>AI Teacher Assistant</span>
            </div>
            <a href="index.php?page=guru_ai_soal" class="nav-link <?= $currentPage === 'guru_ai_soal' ? 'active' : '' ?>">
                <i data-lucide="bot" class="icon"></i>
                <span>AI Generate Soal</span>
            </a>
            <a href="index.php?page=guru_ai_rpp" class="nav-link <?= $currentPage === 'guru_ai_rpp' ? 'active' : '' ?>">
                <i data-lucide="file-signature" class="icon"></i>
                <span>AI Generate RPP</span>
            </a>
            <a href="index.php?page=guru_ai_materi" class="nav-link <?= $currentPage === 'guru_ai_materi' ? 'active' : '' ?>">
                <i data-lucide="sparkles" class="icon"></i>
                <span>AI Ringkas / Buat Materi</span>
            </a>
        <?php endif; ?>

        <?php if ($role === 'SISWA'): ?>
            <div class="nav-section-title">
                <span>Belajar Mandiri</span>
            </div>
            <a href="index.php?page=siswa_ai_tutor" class="nav-link <?= $currentPage === 'siswa_ai_tutor' ? 'active' : '' ?>">
                <i data-lucide="sparkles" class="icon"></i>
                <span>AI Tutor EduRAG</span>
            </a>
            <a href="index.php?page=siswa_buku" class="nav-link <?= in_array($currentPage, ['siswa_buku', 'buku_baca']) ? 'active' : '' ?>">
                <i data-lucide="book-copy" class="icon"></i>
                <span>Perpus Digital (PDF)</span>
            </a>
            <a href="index.php?page=siswa_materi" class="nav-link <?= in_array($currentPage, ['siswa_materi', 'siswa_materi_detail']) ? 'active' : '' ?>">
                <i data-lucide="book-open" class="icon"></i>
                <span>Materi Pelajaran</span>
            </a>
            <a href="index.php?page=siswa_tugas" class="nav-link <?= $currentPage === 'siswa_tugas' ? 'active' : '' ?>">
                <i data-lucide="clipboard-list" class="icon"></i>
                <span>Tugas Kelas</span>
            </a>
            <a href="index.php?page=siswa_ujian" class="nav-link <?= $currentPage === 'siswa_ujian' ? 'active' : '' ?>">
                <i data-lucide="award" class="icon"></i>
                <span>Ujian & Kuis</span>
            </a>
            <a href="index.php?page=siswa_roadmap" class="nav-link <?= $currentPage === 'siswa_roadmap' ? 'active' : '' ?>">
                <i data-lucide="map" class="icon"></i>
                <span>AI Learning Roadmap</span>
            </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <?= strtoupper(substr($currentUser['nama'] ?? 'U', 0, 2)) ?>
            </div>
            <div class="user-meta">
                <div class="user-name" title="<?= htmlspecialchars($currentUser['nama'] ?? '') ?>">
                    <?= htmlspecialchars($currentUser['nama'] ?? '') ?>
                </div>
                <span class="user-role-badge" style="background:#ffffff;color:#05682b;font-weight:800;">
                    <?= $role ?>
                </span>
            </div>
            <a href="index.php?page=logout" title="Logout" style="color:#ffffff;text-decoration:none;padding:8px;border-radius:8px;display:flex;align-items:center;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);transition:all 0.15s;">
                <i data-lucide="log-out" style="width:16px;height:16px;"></i>
            </a>
        </div>
    </div>
</aside>
