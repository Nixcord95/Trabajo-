<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST["correo"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario["password"])) {

            $_SESSION["usuario"] = $usuario["correo"];
            $_SESSION["nombre"] = $usuario["nombre"];

            header("Location: perfil.php");
            exit();

        } else {
            $mensaje = "Contraseña incorrecta";
        }

    } else {
        $mensaje = "Usuario no encontrado";
    }
}
?>

<h2>Login</h2>

<form method="POST">

    Correo:<br>
    <input type="email" name="correo" required><br><br>

    Contraseña:<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Ingresar</button>
</form>

<p style="color:red;"><?php echo $mensaje; ?></p>