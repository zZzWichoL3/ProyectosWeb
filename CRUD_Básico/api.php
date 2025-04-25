<?php
header('Content-Type: application/json'); // Respuesta en Formato JSON
include('funciones.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Si la petición es GET (obtener datos)
    $tabla = $_GET['tabla'] ?? 'Empleados'; // Toma la tabla de la URL, o usa 'Empleados' por defecto
    $result = obtenerDatos($tabla); // Llamar a la función para obtener los datos
    echo json_encode($result->fetch_all(MYSQLI_ASSOC)); // Convertir los datos a JSON y mostrarlos
    exit;
}

$input = json_decode(file_get_contents("php://input"), true); // Leer los datos enviados en formato JSON
$accion = $input['accion'] ?? ''; // Tomar la acción (agregar, eliminar, editar)
$tabla = $input['tabla'] ?? '';   // Tomar la tabla

if ($accion === 'agregar') {
    echo json_encode(['ok' => agregarDato($tabla, $input)]); // Llamar a la función y responder si fue exitoso
} elseif ($accion === 'eliminar') { 
    echo json_encode(['ok' => eliminarDato($tabla, $input['id'])]);
} elseif ($accion === 'editar') {
    echo json_encode(['ok' => editarDato($tabla, $input['id'], $input)]);
} else { 
    echo json_encode(['ok' => false, 'error' => 'Acción no válida']);
}