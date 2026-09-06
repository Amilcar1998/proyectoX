<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

// Verificar si el módulo ya existe
$res = $conn->query("SELECT * FROM modulos WHERE controlador = 'controllerPromociones.php'");
if ($res && $res->num_rows === 0) {
    $conn->query("INSERT INTO modulos (nombre, controlador, icono, orden, activo) VALUES ('Precios y Promociones', 'controllerPromociones.php', 'fa-tags', 15, 1)");
    $nuevoId = $conn->insert_id;
    echo "Módulo creado con ID: $nuevoId\n";

    // Asignar permiso al Gerente (Rol 1) y Admin (Rol 4)
    $conn->query("INSERT INTO permisos_rol (id_Rol, idModulo, permitido) VALUES (1, $nuevoId, 1)");
    $conn->query("INSERT INTO permisos_rol (id_Rol, idModulo, permitido) VALUES (4, $nuevoId, 1)");
    echo "Permisos asignados a Gerente y Admin.\n";
} else {
    echo "El módulo ya está registrado en la base de datos.\n";
}
