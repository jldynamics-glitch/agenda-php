<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
require_once 'config/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$id_contacto = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $ciudad = trim($_POST['ciudad']);

    // SEGURIDAD: Se valida id_contacto Y id_usuario para evitar alteración por modificación de URL
    $stmt = $conexion->prepare("UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, ciudad = ? WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ssssii", $nombre, $telefono, $correo, $ciudad, $id_contacto, $id_usuario);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "Error al actualizar el contacto.";
    }
    $stmt->close();
}

// Validar que el contacto pertenezca estricta y únicamente al usuario autenticado
$stmt = $conexion->prepare("SELECT nombre, telefono, correo, ciudad FROM contactos WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_contacto, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    // Si intenta acceder a un ID ajeno, se redirige inmediatamente
    header("Location: index.php");
    exit();
}
$contacto = $resultado->fetch_assoc();
$stmt->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto - Agenda Personal</title>
</head>
<body>
    <h2>Editar Contacto</h2>
    <?php if (!empty($mensaje)) echo "<p style='color:red;'>$mensaje</p>"; ?>
    <form action="editar.php?id=<?php echo $id_contacto; ?>" method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($contacto['nombre']); ?>" required><br><br>
        
        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($contacto['telefono']); ?>" required><br><br>
        
        <label>Correo:</label><br>
        <input type="email" name="correo" value="<?php echo htmlspecialchars($contacto['correo']); ?>" required><br><br>
        
        <label>Ciudad:</label><br>
        <input type="text" name="ciudad" value="<?php echo htmlspecialchars($contacto['ciudad']); ?>" required><br><br>
        
        <input type="submit" value="Actualizar Contacto">
    </form>
    <br>
    <a href="index.php">Cancelar y volver</a>
</body>
</html>
