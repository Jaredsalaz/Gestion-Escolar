
<?php
// Incluimos el archivo de conexión a la base de datos
include '../modelo/conexion.php';

// Inicia la sesión
session_start();

// Compruebamos si el correo electrónico del usuario está establecido en la sesión
if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Comprueba si se ha establecido una nueva foto de perfil en la sesión
    if(isset($_SESSION['foto_perfil'])) {
        $foto_perfil = $_SESSION['foto_perfil'];
    } else {
        // Obténmos la foto de perfil del usuario de la base de datos
        $sql = "SELECT foto_perfil FROM `t.usuario` WHERE correo = '$email'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $foto_perfil = $row['foto_perfil'];
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
    <link rel="stylesheet" href="../EstiloMenu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body class="body-2">
    <h1>Registro de alumnos</h1>
    <button type="button" onclick="location.href='ruta/a/tu/pagina/de/registro.php'">Registrar</button>
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
    <div class="profile">
        <img id="profilePic" src="<?php echo $foto_perfil ? $foto_perfil : 'img/perfil-del-usuario.png'; ?>" alt="Profile Picture">
        <div class="welcome-message">Bienvenido <?php echo $email; ?></div>
    </div>
    <div class="table-container">
        <table id="editableTable">
            <thead>
                <tr>
                    <th>Num</th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Paterno</th>
                    <th>Materno</th>
                    <th>Grado y Grupo</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                    
                </tr>
            </thead>
            <tbody>
                <!-- Las filas se agregarán aquí con JavaScript -->
            </tbody>
        </table>
    </div>
    <button id="addRow">Agregar fila</button>





    <script>

        // Agrega fila a la tabla
        document.getElementById('addRow').addEventListener('click', function() {
            var table = document.getElementById('editableTable');
            var row = table.insertRow(-1);
            for (var i = 0; i < 6; i++) {
                var cell = row.insertCell(i);
                cell.contentEditable = true;
                cell.setAttribute('data-placeholder', 'Haz clic para editar');
            }
            var modifyCell = row.insertCell(6);
            modifyCell.innerHTML = '<button type="button">Modificar</button>';
            var deleteCell = row.insertCell(7);
            deleteCell.innerHTML = '<button type="button">Eliminar</button>';
        });
        
        //navegacion
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