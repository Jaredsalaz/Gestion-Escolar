<?php
// Incluimos el archivo de conexión a la base de datos
include 'modelo/conexion.php';

/* -----Función para mostrar los datos de una tabla-----*/
function consulta($tabla){
    // Accede a la variable de conexión global
    global $conn;
    // Preparamos la consulta SQL
    $consulta = "SELECT * FROM ".mysqli_real_escape_string($conn, $tabla);
    // Ejecuta la consulta
    $resultado = mysqli_query($conn, $consulta);
    $arreglo_resultados=array();

    // Si la consulta se ejecutó con éxito, recoge los resultados en un array
    if($resultado){
        while($fila = mysqli_fetch_assoc($resultado)){
            $arreglo_resultados[]=$fila;
        }
    }else{
        // Si hubo un error, imprime el mensaje de error
        echo "Error: " . $consulta . "<br>" . mysqli_error($conn);
    }
    // Devuelve los resultados
    return $arreglo_resultados;
}

/* -----Función para actualizar un alumno en la base de datos----- */
function actualizarAlumno($data) {
    global $conn;
    // Extraemos los datos del array
    $matricula = $data['matricula'];
    $nombre = $data['nombre'];
    $apaterno = $data['apaterno'];
    $amaterno = $data['amaterno'];
    $idgradogrupo = $data['idgradogrupo'];

    // Preparamos la consulta SQL
    $sql = "UPDATE t_alumnos SET nombre = ?, apaterno = ?, amaterno = ?, idgradogrupo = ? WHERE matricula = ?";
    $stmt = mysqli_prepare($conn, $sql);
    // Vincula los datos a la consulta
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apaterno, $amaterno, $idgradogrupo, $matricula);
    // Ejecuta la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si la consulta se ejecutó con éxito, devuelve un mensaje de éxito
        return "Registro actualizado correctamente";
    } else {
        // Si hubo un error, devuelve un mensaje de error
        return "Error al modificar registro: " . mysqli_error($conn);
    }
}

// Si se recibe una solicitud POST, actualiza el alumno correspondiente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = actualizarAlumno($data);
    echo $result;
}

/* -----Función para eliminar un alumno de la base de datos----- */
function eliminarAlumno($matricula) {
    global $conn;

    // Preparamos la consulta SQL
    $sql = "DELETE FROM t_alumnos WHERE matricula = ?";
    $stmt = mysqli_prepare($conn, $sql);
    // Vincula la matrícula a la consulta
    mysqli_stmt_bind_param($stmt, "s", $matricula);
    // Ejecuta la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si la consulta se ejecutó con éxito, devuelve un mensaje de éxito
        return "Registro eliminado correctamente";
    } else {
        // Si hubo un error, devuelve un mensaje de error
        return "Error al eliminar registro: " . mysqli_error($conn);
    }
}

// Si se recibe una solicitud DELETE, elimina el alumno correspondiente
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = eliminarAlumno($data['matricula']);
    echo $result;
}

/* -----Función para insertar un nuevo alumno en la base de datos----- */
function insertarAlumno($data) {
    global $conn;
    // Extrae los datos del array
    $matricula = $data['matricula'];
    $nombre = $data['nombre'];
    $apaterno = $data['apaterno'];
    $amaterno = $data['amaterno'];
    $idgradogrupo = $data['idgradogrupo'];

    // Prepara la consulta SQL
    $sql = "INSERT INTO t_alumnos (matricula, nombre, apaterno, amaterno, idgradogrupo) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    // Vincula los datos a la consulta
    mysqli_stmt_bind_param($stmt, "sssss", $matricula, $nombre, $apaterno, $amaterno, $idgradogrupo);
    // Ejecuta la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si la consulta se ejecutó con éxito, devuelve un mensaje de éxito
        return "Registro insertado correctamente";
    } else {
        // Si hubo un error, devuelve un mensaje de error
        return "Error al insertar registro: " . mysqli_error($conn);
    }
}

// Si se recibe una solicitud PUT, inserta un nuevo alumno
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = insertarAlumno($data);
    echo $result;
}
?>