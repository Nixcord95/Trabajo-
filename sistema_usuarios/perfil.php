<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_logueado = $_SESSION['usuario'];
$mensaje = "";

// --- LÓGICA PARA ACTUALIZAR NOMBRE Y CORREO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_datos'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_correo = $_POST['correo'];

    if (!empty($nuevo_nombre) && filter_var($nuevo_correo, FILTER_VALIDATE_EMAIL)) {
        $update = "UPDATE usuarios SET nombre = '$nuevo_nombre', correo = '$nuevo_correo' WHERE correo = '$usuario_logueado' OR nombre = '$usuario_logueado'";
        
        if ($conn->query($update)) {
            $_SESSION['usuario'] = $nuevo_correo; // Actualizamos la sesión con el nuevo correo
            $usuario_logueado = $nuevo_correo;
            $mensaje = "<p style='color: green;'>Datos actualizados correctamente.</p>";
        } else {
            $mensaje = "<p style='color: red;'>Error al actualizar datos.</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>Por favor, ingresa un correo válido.</p>";
    }
}

// Consultar datos actualizados
$sql = "SELECT * FROM usuarios WHERE correo = '$usuario_logueado' OR nombre = '$usuario_logueado'";
$resultado = $conn->query($sql);
$datos = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        .btn-update { background: #28a745; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 5px; }
        .btn-pass { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Mis Datos</h2>
        <?php echo $mensaje; ?>
        <form method="POST">
            <label>Cédula (No editable):</label>
            <input type="text" value="<?php echo $datos['cedula']; ?>" disabled>
            
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
            
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" value="<?php echo $datos['correo']; ?>" required>
            
            <button type="submit" name="actualizar_datos" class="btn-update">Guardar Cambios</button>
        </form>
        
        <a href="cambiar_password.php" class="btn-pass">Seguridad: Cambiar Contraseña</a>
        <hr>
        <a href="logout.php" style="color: red; text-decoration: none;">Cerrar Sesión</a>
    </div>
</body>
</html>