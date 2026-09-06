<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== ROLES ===\n";
$resRoles = $conn->query("SELECT * FROM rol");
while ($r = $resRoles->fetch_assoc()) {
    echo json_encode($r) . "\n";
}

echo "\n=== MODULOS ===\n";
$resMod = $conn->query("SELECT * FROM modulos ORDER BY orden ASC");
while ($m = $resMod->fetch_assoc()) {
    echo json_encode($m) . "\n";
}

echo "\n=== PERMISOS DE ROLES (Gerente / Rol 1 / Rol 2) ===\n";
$resPerm = $conn->query("
    SELECT p.id_Rol, r.nombreRol, m.nombre AS modulo, p.permitido 
    FROM permisos_rol p
    JOIN rol r ON p.id_Rol = r.id_Rol
    JOIN modulos m ON p.idModulo = m.idModulo
    ORDER BY p.id_Rol, m.orden
");
while ($p = $resPerm->fetch_assoc()) {
    echo "Rol: {$p['nombreRol']} (ID: {$p['id_Rol']}) | Modulo: {$p['modulo']} | Permitido: {$p['permitido']}\n";
}
