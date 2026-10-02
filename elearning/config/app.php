<?php
/**
 * Konfigurasi Utama Aplikasi
 */
return [
    'app_name'    => 'EduRAG SMAN 2 Surabaya',
    'app_desc'    => 'Platform Belajar Mandiri & Asisten Mengajar Berbasis AI',
    'school_name' => 'SMAN 2 Surabaya',
    'version'     => '1.0.0',
    'timezone'    => 'Asia/Jakarta',
    'base_url'    => (function() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? 'https://' : 'http://';
            $script = $_SERVER['SCRIPT_NAME'] ?? '';
            $dir = rtrim(str_replace(basename($script), '', $script), '/');
            return $proto . $_SERVER['HTTP_HOST'] . $dir;
        }
        return 'http://localhost/elearning_sman2sby';
    })()
];
