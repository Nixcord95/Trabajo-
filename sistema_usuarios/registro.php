<?php
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    // 1. Verificar si el correo ya existe
    $buscar = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = $conn->query($buscar);

    if ($resultado->num_rows > 0) {
        $mensaje = "<p style='color: red;'>El correo ya está registrado.</p>";
    } else {
        // 2. Encriptar contraseña
        $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);

        // 3. Insertar en la base de datos
        $sql = "INSERT INTO usuarios (nombre, cedula, correo, password) VALUES ('$nombre', '$cedula', '$correo', '$pass_encriptada')";

        if ($conn->query($sql)) {
            $mensaje = "<p style='color: green;'>Registro exitoso. <a href='login.php'>Inicia sesión aquí</a></p>";
        } else {
            $mensaje = "<p style='color: red;'>Error al registrar: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; padding-top: 50px; }
        .form-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="form-card">
        <h3>Crear Cuenta</h3>
        <?php echo $mensaje; ?>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="text" name="cedula" placeholder="Cédula" required>
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarme</button>
        </form>
        <br>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>