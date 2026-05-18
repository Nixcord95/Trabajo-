<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "sistema_web";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>