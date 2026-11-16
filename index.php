<?php
session_start();

// Verificación obligatoria de sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";

// Proceso para registrar un nuevo contacto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $ciudad = trim($_POST['ciudad']);

    if (!empty($nombre) && !empty($telefono) && !empty($correo) && !empty($ciudad)) {
        $stmt = $conexion->prepare("INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_usuario, $nombre, $telefono, $correo, $ciudad);
        if ($stmt->execute()) {
            $mensaje = "Contacto agregado exitosamente.";
        } else {
            $mensaje = "Error al agregar el contacto.";
        }
        $stmt->close();
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}

// Consultar EXCLUSIVAMENTE los contactos del usuario autenticado
$stmt = $conexion->prepare("SELECT id, nombre, telefono, correo, ciudad FROM contactos WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado_contactos = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Agenda de Contactos</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
    <p><strong>Correo registrado:</strong> <?php echo htmlspecialchars($_SESSION['correo']); ?></p>
    <a href="logout.php">Cerrar Sesión</a>
    <hr>

    <h2>Registrar Nuevo Contacto</h2>
    <?php if (!empty($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
    <form action="index.php" method="POST">
        <input type="hidden" name="accion" value="crear">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>
        <label>Teléfono:</label><br>
        <input type="text" name="telefono" required><br><br>
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>
        <label>Ciudad:</label><br>
        <input type="text" name="ciudad" required><br><br>
        <input type="submit" value="Guardar Contacto">
    </form>
    <hr>

    <h2>Mis Contactos Registrados</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Ciudad</th>
            <th>Acciones</th>
        </tr>
        <?php if ($resultado_contactos->num_rows > 0): ?>
            <?php while ($contacto = $resultado_contactos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($contacto['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($contacto['correo']); ?></td>
                    <td><?php echo htmlspecialchars($contacto['ciudad']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $contacto['id']; ?>">Editar</a> | 
                        <a href="borrar.php?id=<?php echo $contacto['id']; ?>" onclick="return confirm('¿Deseas eliminar este contacto?');">Borrar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No tienes contactos registrados.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
<?php
$stmt->close();
$conexion->close();
?>
