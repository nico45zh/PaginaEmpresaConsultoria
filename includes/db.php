<?php
$configPath = __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    die(
        'Falta el archivo includes/config.php. ' .
        'Copia includes/config.example.php como includes/config.php ' .
        'y completa los datos reales de conexión (no se sube a GitHub).'
    );
}

require_once $configPath;

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
