<?php
require_once 'config/conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password_raw = $_POST['password'];

    if (!empty($nombre) && !empty($correo) && !empty($password_raw)) {
        // Validar que el correo no exista previamente
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "El correo electrónico ya está registrado.";
        } else {
            $stmt->close();
            // Almacenar la contraseña de forma segura usando password_hash()
            $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $correo, $password_hash);

            if ($stmt->execute()) {
                header("Location: login.php?registro=exitoso");
                exit();
            } else {
                $mensaje = "Error al registrar el usuario.";
            }
        }
        $stmt->close();
    } else {
        $mensaje = "Por favor, complete todos los campos del formulario.";
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - Agenda Personal</title>
</head>
<body>
    <h2>Registro de Nuevo Usuario</h2>
    <?php if (!empty($mensaje)) echo "<p style='color:red;'>$mensaje</p>"; ?>
    <form action="registro.php" method="POST">
        <label for="nombre">Nombre Completo:</label><br>
        <input type="text" name="nombre" required><br><br>
        
        <label for="correo">Correo Electrónico:</label><br>
        <input type="email" name="correo" required><br><br>
        
        <label for="password">Contraseña:</label><br>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" value="Registrarse">
    </form>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>
