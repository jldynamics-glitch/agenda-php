<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
require_once 'config/conexion.php';

if (isset($_GET['id'])) {
    $id_contacto = intval($_GET['id']);
    $id_usuario = $_SESSION['id_usuario'];

    // SEGURIDAD: Solo elimina el registro si coincide el id del contacto y el id_usuario del dueño de la sesión
    $stmt = $conexion->prepare("DELETE FROM contactos WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_contacto, $id_usuario);
    $stmt->execute();
    $stmt->close();
}

$conexion->close();
header("Location: index.php");
exit();
?>
