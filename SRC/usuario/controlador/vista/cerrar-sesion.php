<?php
// Inicia la sesión
session_start();

// Destruye la sesión
session_destroy();

// Redirige al usuario a menuUsuario.php
header("Location: ../menuUsuario.php");
exit();
?>