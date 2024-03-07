<?php
include 'modelo/conexion.php';

//funcion para mostras los datos de la tabla
function consulta($tabla){
    global $conn;
    $consulta = "SELECT * FROM ".mysqli_real_escape_string($conn, $tabla);
    $resultado = mysqli_query($conn, $consulta);
    $arreglo_resultados=array();

    if($resultado){
        while($fila = mysqli_fetch_assoc($resultado)){
            $arreglo_resultados[]=$fila;
        }
    }else{
        echo "Error: " . $consulta . "<br>" . mysqli_error($conn);
    }
    return $arreglo_resultados;
}

//funcion para modificar datos en la tabla
function actualizarAlumno($data) {
    global $conn;
    $matricula = $data['matricula'];
    $nombre = $data['nombre'];
    $apaterno = $data['apaterno'];
    $amaterno = $data['amaterno'];
    $idgradogrupo = $data['idgradogrupo'];

    $sql = "UPDATE t_alumnos SET nombre = ?, apaterno = ?, amaterno = ?, idgradogrupo = ? WHERE matricula = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apaterno, $amaterno, $idgradogrupo, $matricula);
    if (mysqli_stmt_execute($stmt)) {
        return "Record updated successfully";
    } else {
        return "Error updating record: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = actualizarAlumno($data);
    echo $result;
}

//funcion para eliminar datos en la tabla
function eliminarAlumno($matricula) {
    global $conn;

    $sql = "DELETE FROM t_alumnos WHERE matricula = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $matricula);
    if (mysqli_stmt_execute($stmt)) {
        return "Record deleted successfully";
    } else {
        return "Error deleting record: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = eliminarAlumno($data['matricula']);
    echo $result;
}

//funcion para agregar datos en la tabla
function insertarAlumno($data) {
    global $conn;
    $matricula = $data['matricula'];
    $nombre = $data['nombre'];
    $apaterno = $data['apaterno'];
    $amaterno = $data['amaterno'];
    $idgradogrupo = $data['idgradogrupo'];

    $sql = "INSERT INTO t_alumnos (matricula, nombre, apaterno, amaterno, idgradogrupo) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $matricula, $nombre, $apaterno, $amaterno, $idgradogrupo);
    if (mysqli_stmt_execute($stmt)) {
        return "Record inserted successfully";
    } else {
        return "Error inserting record: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = insertarAlumno($data);
    echo $result;
}
?>