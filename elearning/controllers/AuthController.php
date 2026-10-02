<?php
class AuthController
{
    public function login(): void
    {
        if (Auth::check()) {
            Helper::redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                Helper::flash('error', 'Username dan password wajib diisi.');
                Helper::redirect('login');
            }

            if (Auth::attempt($username, $password)) {
                Helper::flash('success', 'Selamat datang kembali, ' . htmlspecialchars($_SESSION['nama']) . '!');
                Helper::redirect('dashboard');
            } else {
                Helper::flash('error', 'Username atau password salah. Pastikan data absensi Anda sesuai.');
                Helper::redirect('login');
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        Auth::logout();
        Helper::flash('success', 'Anda telah berhasil logout.');
        Helper::redirect('login');
    }
}
