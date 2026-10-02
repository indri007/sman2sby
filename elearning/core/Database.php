<?php
/**
 * Database Singleton using PDO
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                // Fallback for local development if running on 127.0.0.1/localhost with root
                if (($config['host'] === '127.0.0.1' || $config['host'] === 'localhost') && $config['username'] !== 'root') {
                    try {
                        $fallbackDsn = "mysql:host=127.0.0.1;port=3306;dbname=edurag_sman2sby;charset=utf8mb4";
                        self::$instance = new PDO($fallbackDsn, 'root', '', $options);
                        return self::$instance;
                    } catch (PDOException $eFallback) {
                        // ignore and proceed to error display
                    }
                }

                // If database edurag_sman2sby not yet imported or different dbname
                // Try fallback to connect without dbname to check existence
                die('<div style="font-family:sans-serif;padding:30px;max-width:600px;margin:50px auto;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.05);">' .
                    '<h2 style="color:#e11d48;margin-top:0;">Database Connection Error</h2>' .
                    '<p>Gagal terhubung ke database <code>' . htmlspecialchars($config['dbname']) . '</code>.</p>' .
                    '<p style="color:#64748b;font-size:14px;">Detail: ' . htmlspecialchars($e->getMessage()) . '</p>' .
                    '<hr style="border:0;border-top:1px solid #e2e8f0;margin:20px 0;">' .
                    '<p><b>Solusi:</b> Silakan import file database <code>edurag_sman2sby.sql</code> ke phpMyAdmin Anda dengan nama database <b>' . htmlspecialchars($config['dbname']) . '</b>.</p>' .
                    '</div>');
            }
        }

        return self::$instance;
    }
}
