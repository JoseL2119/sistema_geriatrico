<?php
// Script de línea de comandos para crear o resetear la contraseña de un usuario.
// Uso: php scripts/crear_usuario.php
// (pide el usuario, nombre completo y contraseña de forma interactiva)

require_once __DIR__ . '/../config/ConnectionFerro.php';

function leer($etiqueta) {
    echo $etiqueta;
    $valor = trim(fgets(STDIN));
    // Quita un BOM UTF-8 si la terminal lo agrega al primer valor leído
    return preg_replace('/^\xEF\xBB\xBF/', '', $valor);
}

echo "=== Crear / resetear usuario del sistema ===\n";

$nombreUsuario = leer("Nombre de usuario: ");
$nombreCompleto = leer("Nombre completo: ");
$password = leer("Contraseña: ");

if ($nombreUsuario === '' || $nombreCompleto === '' || $password === '') {
    fwrite(STDERR, "Todos los campos son obligatorios.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$conn = ConnectionFerro::getConnection();

$sql = "
    INSERT INTO usuarios (nombre_usuario, password_hash, nombre_completo)
    VALUES (:nombre_usuario, :password_hash, :nombre_completo)
    ON CONFLICT (nombre_usuario)
    DO UPDATE SET password_hash = :password_hash, nombre_completo = :nombre_completo
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':nombre_usuario' => $nombreUsuario,
    ':password_hash' => $hash,
    ':nombre_completo' => $nombreCompleto,
]);

echo "Listo. El usuario '$nombreUsuario' quedó creado/actualizado.\n";
