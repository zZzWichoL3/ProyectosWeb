<?php
$host = "localhost";
$usuario = "root";
$password = "";
$db = "empresa";

//Objeto de conexión
$conexion = new mysqli($host, $usuario, $password, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

?>