<?php
/**
 * EduRAG SMAN 2 Surabaya - LMS & AI Assistant
 * Front Controller
 */

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

$appConfig = require __DIR__ . '/config/app.php';
date_default_timezone_set($appConfig['timezone'] ?? 'Asia/Jakarta');

// Load Core
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Helper.php';
require_once __DIR__ . '/core/GeminiService.php';

// Load Models
require_once __DIR__ . '/models/UserModel.php';
require_once __DIR__ . '/models/KelasModel.php';
require_once __DIR__ . '/models/MapelModel.php';
require_once __DIR__ . '/models/MateriModel.php';
require_once __DIR__ . '/models/TugasModel.php';
require_once __DIR__ . '/models/UjianModel.php';
require_once __DIR__ . '/models/AiModel.php';
require_once __DIR__ . '/models/BukuModel.php';

// Load Controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/GuruController.php';
require_once __DIR__ . '/controllers/SiswaController.php';
require_once __DIR__ . '/controllers/AiController.php';
require_once __DIR__ . '/controllers/BukuController.php';

Auth::init();

$page = $_GET['page'] ?? (Auth::check() ? 'dashboard' : 'login');

// Router
switch ($page) {
    // Auth
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    // Dashboard redirection based on role
    case 'dashboard':
        Auth::requireLogin();
        $role = Auth::role();
        if ($role === 'ADMIN') {
            (new AdminController())->dashboard();
        } elseif ($role === 'GURU') {
            (new GuruController())->dashboard();
        } else {
            (new SiswaController())->dashboard();
        }
        break;

    // Admin Routes
    case 'admin_guru':
        (new AdminController())->guru();
        break;
    case 'admin_siswa':
        (new AdminController())->siswa();
        break;
    case 'admin_kelas':
        (new AdminController())->kelas();
        break;
    case 'admin_mapel':
        (new AdminController())->mapel();
        break;
    case 'admin_ai_keys':
        (new AdminController())->aiKeys();
        break;
    case 'admin_ai_logs':
        (new AdminController())->aiLogs();
        break;

    // Guru Routes
    case 'guru_materi':
        (new GuruController())->materi();
        break;
    case 'guru_materi_create':
        (new GuruController())->materiCreate();
        break;
    case 'guru_tugas':
        (new GuruController())->tugas();
        break;
    case 'guru_tugas_create':
        (new GuruController())->tugasCreate();
        break;
    case 'guru_tugas_submissions':
        (new GuruController())->tugasSubmissions();
        break;
    case 'guru_tugas_grade':
        (new GuruController())->tugasGrade();
        break;
    case 'guru_tugas_delete':
        (new GuruController())->tugasDelete();
        break;
    case 'guru_ujian':
        (new GuruController())->ujian();
        break;
    case 'guru_ujian_create':
        (new GuruController())->ujianCreate();
        break;
    case 'guru_ujian_delete':
        (new GuruController())->ujianDelete();
        break;
    case 'guru_bank_soal':
        (new GuruController())->bankSoal();
        break;
    case 'guru_soal_create':
        (new GuruController())->soalCreate();
        break;
    case 'guru_soal_edit':
        (new GuruController())->soalEdit();
        break;
    case 'guru_soal_delete':
        (new GuruController())->soalDelete();
        break;
    case 'guru_ai_soal':
        (new GuruController())->aiGenerateSoal();
        break;
    case 'guru_ai_rpp':
        (new GuruController())->aiGenerateRpp();
        break;
    case 'guru_ai_materi':
        (new GuruController())->aiGenerateMateri();
        break;

    // Siswa Routes
    case 'siswa_materi':
        (new SiswaController())->materi();
        break;
    case 'siswa_materi_detail':
        (new SiswaController())->materiDetail();
        break;
    case 'siswa_ai_tutor':
        (new SiswaController())->aiTutor();
        break;
    case 'siswa_tugas':
        (new SiswaController())->tugas();
        break;
    case 'siswa_tugas_detail':
        (new SiswaController())->tugasDetail();
        break;
    case 'siswa_ujian':
        (new SiswaController())->ujian();
        break;
    case 'siswa_ujian_kerjakan':
        (new SiswaController())->ujianKerjakan();
        break;
    case 'siswa_ujian_hasil':
        (new SiswaController())->ujianHasil();
        break;
    case 'siswa_roadmap':
        (new SiswaController())->roadmap();
        break;

    // Buku / Perpustakaan Digital Routes
    case 'siswa_buku':
    case 'guru_buku':
    case 'admin_buku':
        (new BukuController())->index();
        break;
    case 'guru_buku_create':
    case 'admin_buku_create':
        (new BukuController())->create();
        break;
    case 'guru_buku_store':
    case 'admin_buku_store':
        (new BukuController())->store();
        break;
    case 'guru_buku_edit':
    case 'admin_buku_edit':
        (new BukuController())->edit();
        break;
    case 'guru_buku_update':
    case 'admin_buku_update':
        (new BukuController())->update();
        break;
    case 'guru_buku_delete':
    case 'admin_buku_delete':
        (new BukuController())->delete();
        break;
    case 'buku_baca':
        (new BukuController())->baca();
        break;
    case 'api_buku_stream':
        (new BukuController())->streamPdf();
        break;

    // AI API Endpoints (AJAX)
    case 'api_ai_tutor':
        (new AiController())->askTutor();
        break;
    case 'api_ai_generate_soal':
        (new AiController())->generateSoal();
        break;
    case 'api_ai_save_soal':
        (new AiController())->saveGeneratedSoal();
        break;
    case 'api_ai_generate_rpp':
        (new AiController())->generateRpp();
        break;
    case 'api_ai_generate_roadmap':
        (new AiController())->generateRoadmap();
        break;
    case 'api_upload_image':
        (new GuruController())->uploadImage();
        break;

    default:
        if (Auth::check()) {
            Helper::redirect('dashboard');
        } else {
            Helper::redirect('login');
        }
        break;
}
