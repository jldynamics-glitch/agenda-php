<?php
session_start();
require_once 'config/conexion.php';
$mensaje = "";

if (isset($_GET['registro']) && $_GET['registro'] == 'exitoso') {
    $mensaje = "¡Registro exitoso! Por favor, inicia sesión.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (!empty($correo) && !empty($password)) {
        $stmt = $conexion->prepare("SELECT id, nombre, correo, password FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            // Verificación segura con password_verify()
            if (password_verify($password, $fila['password'])) {
                $_SESSION['id_usuario'] = $fila['id'];
                $_SESSION['nombre'] = $fila['nombre'];
                $_SESSION['correo'] = $fila['correo'];
                
                header("Location: index.php");
                exit();
            } else {
                $mensaje = "Credenciales incorrectas.";
            }
        } else {
            $mensaje = "Credenciales incorrectas.";
        }
        $stmt->close();
    } else {
        $mensaje = "Por favor, ingrese correo y contraseña.";
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión - Agenda Personal</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if (!empty($mensaje)) echo "<p style='color:red;'>$mensaje</p>"; ?>
    <form action="login.php" method="POST">
        <label for="correo">Correo Electrónico:</label><br>
        <input type="email" name="correo" required><br><br>
        
        <label for="password">Contraseña:</label><br>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" value="Iniciar Sesión">
    </form>
    <p>¿No estás registrado? <a href="registro.php">Regístrate aquí</a></p>
</body>
</html>
