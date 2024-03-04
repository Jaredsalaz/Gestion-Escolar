<?php
// Incluimos el archivo de conexión a la base de datos
include '../modelo/conexion.php';

// Inicia la sesión
session_start();

// Comprueba si el correo electrónico del usuario está establecido en la sesión
if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Obténemos la foto de perfil del usuario de la base de datos
    $sql = "SELECT foto_perfil FROM `t.usuario` WHERE correo = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $foto_perfil = $row['foto_perfil'];
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Comprueba si se subió un archivo
    if(isset($_FILES['fileToUpload'])) {
        $errors = array();
        $file_name = $_FILES['fileToUpload']['name'];
        $file_size = $_FILES['fileToUpload']['size'];
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];
        $file_type = $_FILES['fileToUpload']['type'];
        $file_parts = explode('.', $_FILES['fileToUpload']['name']);
        $file_ext = strtolower(end($file_parts));

        if($file_size > 2097152) {
            $errors[] = 'El tamaño del archivo debe ser menor a 2 MB';
        }

        if(empty($errors) == true) {
            move_uploaded_file($file_tmp, "img/".$file_name);
            echo "Imagen subida con éxito";

            // Actualiza la foto de perfil en la base de datos
            $foto_perfil = "img/".$file_name;
            $sql = "UPDATE `t.usuario` SET foto_perfil = '$foto_perfil' WHERE correo = '$email'";
            if (mysqli_query($conn, $sql)) {
                echo "Imagen subida con éxito";

                // Redirige al usuario a inicio.php
                header("Location: inicio.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            print_r($errors);
        }
    }
} else {
    // Redirigire al usuario a la página de inicio de sesión si el correo electrónico no está establecido
    header("Location: ../menuUsuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../EstiloMenu.css">
</head>
<body class="body-2">
    <div class="header-div">
        <header>
            <nav class="menu-nav">
                <ul>
                    <li><a href="inicio.php">Inicio</a></li>
                    <li class="nav-item hidden-item"><a href="perfil.php">Perfil</a></li>
                    <li class="nav-item hidden-item"><a href="cerrar-sesion.php">Cerrar sesión</a></li>
                    <li><button id="navButton">&#9776;</button></li>
                </ul>
            </nav>
        </header>
    </div>
    <h1>Perfil de <?php echo $email; ?></h1>
    <img src="<?php echo $foto_perfil; ?>" alt="Foto de perfil">
    <form action="perfil.php" method="post" enctype="multipart/form-data">
        Seleccione una imagen para subir:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Subir imagen" name="submit">
    </form>
    <script>
        document.getElementById('navButton').addEventListener('click', function() {
            var menuItems = document.getElementsByClassName('nav-item');
            for (var i = 0; i < menuItems.length; i++) {
                menuItems[i].classList.toggle('hidden-item');
                if (menuItems[i].style.maxHeight){
                    menuItems[i].style.maxHeight = null;
                } else {
                    menuItems[i].style.maxHeight = menuItems[i].scrollHeight + "px";
                } 
            }
        });

        document.getElementById('profilePic').addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.2)';
        });

        document.getElementById('profilePic').addEventListener('mouseout', function() {
            this.style.transform = 'scale(1)';
        });
    </script>
</body>
</html>