<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "Neymar18*";
$nombreBD = "bd_gestionescolar";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $contrasena, $nombreBD);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}
echo "Conexión exitosa ";
?>