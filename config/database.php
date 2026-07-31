<?php
/**
 * Conexión PDO a MySQL/MariaDB.
 * Lee las credenciales de config/database.local.php (no se sube a git).
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $localConfigPath = __DIR__ . '/database.local.php';
    if (!file_exists($localConfigPath)) {
        throw new RuntimeException(
            'Falta config/database.local.php. Copia config/database.example.php, ' .
            'renómbralo y coloca tus credenciales locales de MySQL.'
        );
    }

    $config = require $localConfigPath;

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['dbname']
    );

    $pdo = new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}