<?php
// Iniciar sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include 'modelo/conexion.php';

// Variable para almacenar el mensaje de error
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $correo = $_POST['email'];
    $clave = $_POST['psw'];

    // Preparamos la consulta SQL
    $sql = "SELECT * FROM  `t.usuario` WHERE correo = ?";

    // Crea una declaración preparada
    $stmt = $conn->prepare($sql);

    // Vincula los parámetros
    $stmt->bind_param("s", $correo);

    // Ejecuta la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();

    // Verificar si el correo existe en la base de datos
    if ($result->num_rows > 0) {
        // Obtener la fila de resultados
        $row = $result->fetch_assoc();

        // Verifica la contraseña
        if ($clave === $row['clave']) {
            // Iniciar sesión
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $correo;

            // Redirigire al usuario a la página de inicio
            header("Location: vista/inicio.php");
            exit;
        } else {
            // La contraseña es incorrecta
            $error = "La contraseña es incorrecta.";
        }
    } else {
        // El correo no existe en la base de datos
        $error = "El correo no existe.";
    }

    // Cerramos la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App web Gestion Escolar</title>
    <link rel="stylesheet" href="EstiloMenu.css">
</head>
<body>
    <div class="login-page">
        <h1>Login</h1>
        <form method="POST" action="menuUsuario.php">
            <input type="email" name="email" placeholder="Correo electrónico" required onclick="clearError()">
            <input type="password" name="psw" placeholder="Contraseña" required>
            <button type="submit">Iniciar sesión</button>            
            <?php if ($error): ?>
                <div class="error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script src="scriptMenu.js"></script>
</body>
</html>