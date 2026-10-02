<?php
/**
 * Utility & Presentation Helpers
 */
class Helper
{
    public static function url(string $path = ''): string
    {
        $app = require __DIR__ . '/../config/app.php';
        $base = rtrim($app['base_url'], '/');
        $path = ltrim($path, '/');
        return empty($path) ? $base : "{$base}/{$path}";
    }

    public static function redirect(string $page, array $params = []): void
    {
        $query = http_build_query(array_merge(['page' => $page], $params));
        header("Location: index.php?{$query}");
        exit;
    }

    public static function flash(string $key, ?string $message = null): ?string
    {
        Auth::init();
        if ($message !== null) {
            $_SESSION["flash_{$key}"] = $message;
            return null;
        }

        if (isset($_SESSION["flash_{$key}"])) {
            $msg = $_SESSION["flash_{$key}"];
            unset($_SESSION["flash_{$key}"]);
            return $msg;
        }

        return null;
    }

    public static function formatTanggal(?string $datetime, bool $withTime = true): string
    {
        if (empty($datetime)) return '-';
        $timestamp = strtotime($datetime);
        if (!$timestamp) return '-';

        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tgl = date('j', $timestamp);
        $bln = $bulanIndo[(int)date('n', $timestamp)];
        $thn = date('Y', $timestamp);

        if ($withTime) {
            $jam = date('H:i', $timestamp);
            return "{$tgl} {$bln} {$thn}, {$jam} WIB";
        }

        return "{$tgl} {$bln} {$thn}";
    }

    public static function timeAgo(?string $datetime): string
    {
        if (empty($datetime)) return '-';
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) return 'baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 2592000) return floor($diff / 86400) . ' hari lalu';
        return self::formatTanggal($datetime, false);
    }

    public static function sanitize(mixed $data): mixed
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
    }

    public static function badgeKesulitan(string $level): string
    {
        return match (strtolower($level)) {
            'easy'   => '<span class="badge badge-success">Mudah</span>',
            'medium' => '<span class="badge badge-warning">Sedang</span>',
            'hard'   => '<span class="badge badge-danger">Sulit</span>',
            'hots'   => '<span class="badge badge-purple">HOTS</span>',
            default  => '<span class="badge badge-secondary">' . htmlspecialchars($level) . '</span>',
        };
    }

    public static function renderPagination(int $total, int $perPage, int $currentPage, string $pageName, array $extraParams = []): string
    {
        if ($total <= 0) return '';
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($totalPages <= 1) {
            return "<div class=\"pagination-wrapper\"><div class=\"pagination-info\">Menampilkan <b>{$total}</b> data</div></div>";
        }

        $currentPage = max(1, min($currentPage, $totalPages));
        $startItem = (($currentPage - 1) * $perPage) + 1;
        $endItem = min($currentPage * $perPage, $total);

        $buildUrl = function(int $p) use ($pageName, $extraParams) {
            $params = array_merge(['page' => $pageName], $extraParams, ['p' => $p]);
            return 'index.php?' . http_build_query($params);
        };

        $html = '<div class="pagination-wrapper">';
        $html .= "<div class=\"pagination-info\">Menampilkan <b>{$startItem} - {$endItem}</b> dari total <b>{$total}</b> data (Hal. {$currentPage}/{$totalPages})</div>";
        $html .= '<ul class="pagination">';

        // Tombol Sebelumnya
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl($currentPage - 1)) . '">&larr; Prev</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&larr; Prev</span></li>';
        }

        // Nomor Halaman
        $range = 2;
        $startPage = max(1, $currentPage - $range);
        $endPage = min($totalPages, $currentPage + $range);

        if ($startPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl(1)) . '">1</a></li>';
            if ($startPage > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($p = $startPage; $p <= $endPage; $p++) {
            $activeClass = ($p === $currentPage) ? ' active' : '';
            $html .= "<li class=\"page-item{$activeClass}\"><a class=\"page-link\" href=\"" . htmlspecialchars($buildUrl($p)) . "\">{$p}</a></li>";
        }

        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . htmlspecialchars($buildUrl($totalPages)) . "\">{$totalPages}</a></li>";
        }

        // Tombol Selanjutnya
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl($currentPage + 1)) . '">Next &rarr;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Next &rarr;</span></li>';
        }

        $html .= '</ul>';
        $html .= '</div>';
        return $html;
    }
}

