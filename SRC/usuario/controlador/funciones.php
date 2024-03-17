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



////////////////////////////////////////////////////////////////////////////////////////

function reporteIndividual($matricula) {
    global $conn;

    // Preparamos la consulta SQL para obtener los datos del alumno
    $sql = "SELECT * FROM t_alumnos WHERE matricula = ?";
    $stmt = mysqli_prepare($conn, $sql);
    // Vincula la matrícula a la consulta
    mysqli_stmt_bind_param($stmt, "s", $matricula);
    // Ejecuta la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si la consulta se ejecutó con éxito, recoge los resultados
        $result = mysqli_stmt_get_result($stmt);
        $alumno = mysqli_fetch_assoc($result);

        // Aquí generaramos el PDF con los datos del alumno
        require('fpdf186/fpdf.php');

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Agregamos el título al PDF
        $pdf->Cell(0, 10, 'Reporte Individual', 0, 1, 'C');

        // Agrega una separación entre el título y el contenido
        $pdf->Ln(10);

        // Agrega los datos del alumno al PDF
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Matricula: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['matricula'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Nombre: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['nombre'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Apellido Paterno: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['apaterno'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Apellido Materno: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['amaterno'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'ID Grado Grupo: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['idgradogrupo'], 0, 1);

        // Genera el PDF
        $pdf->Output();
        exit;
    } else {
        // Si hubo un error, devuelve un mensaje de error
        return "Error al obtener datos del alumno: " . mysqli_error($conn);
    }
}
// Si se recibe una solicitud GET con una matrícula, genera el reporte individual
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['matricula'])) {
    reporteIndividual($_GET['matricula']);
}
////////////////////////////////////////////////////////////////////////////////////////
function reporteGeneral() {
    global $conn;

    // Preparamos la consulta SQL para obtener los datos de todos los alumnos
    $sql = "SELECT * FROM t_alumnos";
    $result = mysqli_query($conn, $sql);

    // Aquí generamos el PDF con los datos de todos los alumnos
    require('fpdf186/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Agregamos el título al PDF
    $pdf->Cell(0, 10, 'Reporte General', 0, 1, 'C');

    // Agrega una separación entre el título y el contenido
    $pdf->Ln(10);

    // Agrega los datos de todos los alumnos al PDF
    while ($alumno = mysqli_fetch_assoc($result)) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Matricula: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['matricula'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Nombre: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['nombre'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Apellido Paterno: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['apaterno'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Apellido Materno: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['amaterno'], 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'ID Grado Grupo: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $alumno['idgradogrupo'], 0, 1);

        // Agrega una separación entre cada alumno
        $pdf->Ln(10);

        // Agrega una línea horizontal
        $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth() - 10, $pdf->GetY());
    }

    // Genera el PDF
    $pdf->Output();
    exit;
}

// Si se recibe una solicitud GET con el parámetro 'reporte_general', genera el reporte general
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reporte_general'])) {
    reporteGeneral();
}