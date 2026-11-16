<?php
$servidor = "localhost";
$usuario_db = "root";
$clave_db = "";
$base_datos = "agenda_db";

$conexion = new mysqli($servidor, $usuario_db, $clave_db, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>
