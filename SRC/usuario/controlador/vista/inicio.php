<?php
// Inicia la sesión
session_start();

// Comprueba si el correo electrónico del usuario está establecido en la sesión
if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    echo "Bienvenido $email";
} else {
    // Redirigire al usuario a la página de inicio de sesión si el correo electrónico no está establecido
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>
    
</body>
</html>