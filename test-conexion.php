<?php
/**
 * Archivo temporal para verificar la conexión a la base de datos.
 * Bórralo del servidor una vez confirmes que todo funciona —
 * no debe quedar público en producción.
 */

require "includes/db.php";

echo "<h2>✅ Conexión exitosa a la base de datos</h2>";

$resultado = $conn->query("SHOW TABLES");

if ($resultado) {
    echo "<p>Tablas encontradas (" . $resultado->num_rows . "):</p><ul>";
    while ($fila = $resultado->fetch_array()) {
        echo "<li>" . htmlspecialchars($fila[0]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Conectó, pero no se pudieron listar las tablas: " . $conn->error . "</p>";
}

$conn->close();
