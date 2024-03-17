
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Inicio</title>
</head>
<body class="body-2">
    <h1>Registro de alumnos</h1>
    <button type="button" id="registrar">Registrar</button>
    <button type="button" id="Reporte-general">Reporte General</button>
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
                    <th>Numero</th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Paterno</th>
                    <th>Materno</th>
                    <th>Grado y Grupo</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                    <th>Reporte</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                include ("../funciones.php");
                $num = 1;
                // Obtén los datos de la tabla t_alumnos
                $resultado = consulta("t_alumnos");

                echo "<tbody>";
                // Itera sobre los resultados y genera las filas de la tabla
                foreach ($resultado as $row) {
                    echo "<tr>";
                    echo "<td>" . $num . "</td>";
                    echo "<td>" . $row['matricula'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['apaterno'] . "</td>";
                    echo "<td>" . $row['amaterno'] . "</td>";
                    echo "<td>" . $row['idgradogrupo'] . "</td>";
                    echo "<td><button class='btn-modificar'>Modificar</button></td>";
                    echo "<td><button class='btn-eliminar'>Eliminar</button></td>";
                    echo "<td><button class='btn-reporte-individual'>Reporte</button></td>";
                    echo "</tr>";
                    $num = $num + 1;
                }
                echo "</tbody>";
                ?>
            </tbody>
        </table>
    </div>
    <button id="addRow">Agregar fila</button>





    <script>
        // Agrega un evento de doble clic a las celdas de la tabla
        document.querySelectorAll('#editableTable td').forEach(function(cell) {
            cell.addEventListener('dblclick', function() {
                // Hace que la celda sea editable
                this.contentEditable = true;

                // Selecciona el texto de la celda
                var range = document.createRange();
                range.selectNodeContents(this);
                var selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);

                // Cuando se presiona enter, hace que la celda no sea editable
                this.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        this.contentEditable = false;
                    }
                });
            });
        });
        function addClickEvents() {
            // Agrega un evento de clic a los botones de modificar
            document.querySelectorAll('.btn-modificar').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.parentElement.parentElement;
                    var data = {
                        matricula: row.children[1].textContent,
                        nombre: row.children[2].textContent,
                        apaterno: row.children[3].textContent,
                        amaterno: row.children[4].textContent,
                        idgradogrupo: row.children[5].textContent
                    };
                    fetch('../funciones.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    }).then(function(response) {
                        if (response.ok) {
                            return response.text();
                        } else {
                            throw new Error('Error: ' + response.statusText);
                        }
                    }).then(function(text) {
                        // Muestra una alerta con el resultado de la actualización
                        Swal.fire(
                        'Modificado!',
                        text,
                        'success'
                        )
                    }).catch(function(error) {
                        console.log('Request failed', error);
                    });
                });
            });

            // Agrega un evento de clic a los botones de eliminar
            document.querySelectorAll('.btn-eliminar').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.parentElement.parentElement;
                    var data = {
                        matricula: row.children[1].textContent
                    };
                    fetch('../funciones.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    }).then(function(response) {
                        if (response.ok) {
                            return response.text();
                        } else {
                            throw new Error('Error: ' + response.statusText);
                        }
                    }).then(function(text) {
                        // Muestra una alerta con el resultado de la eliminación
                        Swal.fire(
                        'Eliminado!',
                        text,
                        'success'
                        )
                        // Elimina la fila de la tabla
                        row.remove();
                    }).catch(function(error) {
                        console.log('Request failed', error);
                    });
                });
            });

            // Agrega un evento de clic a los botones de reporte
            document.querySelectorAll('.btn-reporte-individual').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.parentElement.parentElement;
                    var matricula = row.children[1].textContent;
                    window.location.href = '../funciones.php?matricula=' + matricula;
                });
            });
        }

        // Agrega los eventos de clic cuando se carga la página
        addClickEvents();

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
            modifyCell.innerHTML = '<button type="button" class="btn-modificar">Modificar</button>';
            var deleteCell = row.insertCell(7);
            deleteCell.innerHTML = '<button type="button" class="btn-eliminar">Eliminar</button>';
            var reportCell = row.insertCell(8);
            reportCell.innerHTML = '<button type="button" class="btn-reporte-individual">Reporte</button>';

            // Agrega los eventos de clic a los botones de la nueva fila
            addClickEvents();
        });
        


        // Agrega un evento de clic al botón de reporte general
        document.getElementById('Reporte-general').addEventListener('click', function() {
            window.location.href = '../funciones.php?reporte_general=true';
        });



        // Para registrar un nuevo alumno
        if (!document.getElementById('registrar').hasEventListener) {
            document.getElementById('registrar').addEventListener('click', function(event) {
                // Previene la acción predeterminada del evento de clic
                event.preventDefault();

                var table = document.getElementById('editableTable');
                var row = table.rows[table.rows.length - 1];
                var data = {
                    matricula: row.cells[1].textContent,
                    nombre: row.cells[2].textContent,
                    apaterno: row.cells[3].textContent,
                    amaterno: row.cells[4].textContent,
                    idgradogrupo: row.cells[5].textContent
                };
                fetch('../funciones.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(function(response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Error: ' + response.statusText);
                    }
                }).then(function(text) {
                    // Muestra una alerta con el resultado de la inserción
                    Swal.fire(
                    'Registrado!',
                    text,
                    'success'
                    )
                }).catch(function(error) {
                    console.log('Request failed', error);
                });
            });

            // Marca el botón como que ya tiene un evento de clic vinculado
            document.getElementById('registrar').hasEventListener = true;
        }

        
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