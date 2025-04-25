<?php
include('db.php');

function obtenerDatos($tabla) {
    global $conexion; 
    return $conexion->query("SELECT * FROM $tabla");
}

function agregarDato($tabla, $datos) {
    global $conexion;
    if ($tabla == 'Empleados') {
        $stmt = $conexion->prepare("INSERT INTO Empleados (Clave_Empleado, Nombre, A_paterno, A_materno, ID_Puesto, Fecha_Ingreso, Fecha_Baja, Estatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $datos['Clave_Empleado'], $datos['Nombre'], $datos['A_paterno'], $datos['A_materno'],
            $datos['ID_Puesto'], $datos['Fecha_Ingreso'], $datos['Fecha_Baja'], $datos['Estatus']);
    } else {
        $stmt = $conexion->prepare("INSERT INTO Puestos (ID_Puesto, Puesto) VALUES (?, ?)");
        $stmt->bind_param("is", $datos['ID_Puesto'], $datos['Puesto']);
    }
    return $stmt->execute();
}

function eliminarDato($tabla, $id) {
    global $conexion;
    $col = $tabla == 'Empleados' ? 'ID_Empleado' : 'ID_Puesto';
    $stmt = $conexion->prepare("DELETE FROM $tabla WHERE $col = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function editarDato($tabla, $id, $datos) {
    global $conexion;

    if ($tabla == 'Empleados') {
        $stmt = $conexion->prepare("UPDATE Empleados SET 
            Clave_Empleado=?, Nombre=?, A_paterno=?, A_materno=?, 
            ID_Puesto=?, Fecha_Ingreso=?, Fecha_Baja=?, Estatus=? 
            WHERE ID_Empleado=?");

        $stmt->bind_param("ssssisssi",
            $datos['Clave_Empleado'],
            $datos['Nombre'],
            $datos['A_paterno'],
            $datos['A_materno'],
            $datos['ID_Puesto'],
            $datos['Fecha_Ingreso'],
            $datos['Fecha_Baja'],
            $datos['Estatus'],
            $id
        );
    } else {
        $stmt = $conexion->prepare("UPDATE Puestos SET Puesto=? WHERE ID_Puesto=?");
        $stmt->bind_param("si", $datos['Puesto'], $id);
    }

    return $stmt->execute();
}
?>