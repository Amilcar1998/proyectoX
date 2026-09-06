<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== 1. TABLA ROL ===\n";
$resRoles = $conn->query("SELECT * FROM rol ORDER BY id_Rol ASC");
while ($r = $resRoles->fetch_assoc()) {
    echo "ID: {$r['id_Rol']} | Rol: {$r['nombreRol']}\n";
}

echo "\n=== 2. USUARIOS POR ROL EN BD ===\n";
$resUsers = $conn->query("
    SELECT u.idUsuario, u.username, u.id_Rol, r.nombreRol, u.activo 
    FROM usuarios u 
    LEFT JOIN rol r ON u.id_Rol = r.id_Rol 
    ORDER BY u.id_Rol, u.idUsuario
");
while ($u = $resUsers->fetch_assoc()) {
    echo "ID: {$u['idUsuario']} | Usuario: {$u['username']} | Rol: {$u['nombreRol']} (ID: {$u['id_Rol']}) | Activo: {$u['activo']}\n";
}

echo "\n=== 3. MODULOS PERMITIDOS POR CADA ROL ===\n";
$roles = [1 => 'Gerente', 2 => 'Empleado', 3 => 'Cliente', 4 => 'Admin'];
foreach ($roles as $idRol => $nombreRol) {
    echo "\n--> ROL $idRol: $nombreRol\n";
    $stmt = $conn->prepare("
        SELECT m.idModulo, m.nombre, m.controlador, m.icono, m.orden, pr.permitido
        FROM modulos m
        JOIN permisos_rol pr ON m.idModulo = pr.idModulo
        WHERE pr.id_Rol = ? AND pr.permitido = 1 AND m.activo = 1
        ORDER BY m.orden ASC
    ");
    $stmt->bind_param("i", $idRol);
    $stmt->execute();
    $resMod = $stmt->get_result();
    $count = 0;
    while ($m = $resMod->fetch_assoc()) {
        echo "   [#{$m['orden']}] {$m['nombre']} ({$m['controlador']})\n";
        $count++;
    }
    echo "   Total módulos permitidos: $count\n";
    $stmt->close();
}
