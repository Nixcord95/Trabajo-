<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_logueado = $_SESSION['usuario'];
    $pass_actual = $_POST['pass_actual'];
    $pass_nueva = $_POST['pass_nueva'];
    $pass_confirmar = $_POST['pass_confirmar'];

    // 1. Obtener la contraseña actual de la base de datos
    $sql = "SELECT password FROM usuarios WHERE correo = '$usuario_logueado' OR nombre = '$usuario_logueado'";
    $resultado = $conn->query($sql);
    $row = $resultado->fetch_assoc();

    // 2. Verificar si la contraseña actual coincide
    if (password_verify($pass_actual, $row['password'])) {
        
        // 3. Verificar que las nuevas coincidan
        if ($pass_nueva === $pass_confirmar) {
            // Encriptar la nueva contraseña
            $pass_encriptada = password_hash($pass_nueva, PASSWORD_DEFAULT);
            
            // 4. Actualizar en la base de datos
            $update = "UPDATE usuarios SET password = '$pass_encriptada' WHERE correo = '$usuario_logueado' OR nombre = '$usuario_logueado'";
            
            if ($conn->query($update)) {
                $mensaje = "<p style='color: green;'>¡Contraseña actualizada con éxito!</p>";
            } else {
                $mensaje = "<p style='color: red;'>Error al actualizar.</p>";
            }
        } else {
            $mensaje = "<p style='color: red;'>Las nuevas contraseñas no coinciden.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>La contraseña actual es incorrecta.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; padding-top: 50px; }
        .form-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-card">
        <h3>Cambiar Contraseña</h3>
        <?php echo $mensaje; ?>
        <form method="POST">
            <input type="password" name="pass_actual" placeholder="Contraseña Actual" required>
            <input type="password" name="pass_nueva" placeholder="Nueva Contraseña" required>
            <input type="password" name="pass_confirmar" placeholder="Confirmar Nueva Contraseña" required>
            <button type="submit">Actualizar</button>
        </form>
        <br>
        <a href="perfil.php">Volver al Perfil</a>
    </div>
</body>
</html>