<?php
// Iniciar sesión
session_start();

// Incluimos el archivo de conexión a la base de datos
include 'modelo/conexion.php';

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
            echo "<script>
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La contraseña es incorrecta.',
                })
            })
            </script>";
        }
    } else {
        // El correo no existe en la base de datos
        echo "<script>
        window.addEventListener('load', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El correo no existe.',
            })
        })
        </script>";
    }

    // Cerramos la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>

<!-- Aquí comienza tu HTML -->
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
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function clearError() {
            Swal.close();
        }
    </script>
</body>
</html>