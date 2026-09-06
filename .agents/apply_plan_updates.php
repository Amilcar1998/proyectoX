<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

$sqlFile = file_get_contents(__DIR__ . '/actualizar_planes.sql');
$conn->multi_query($sqlFile);

do {
    if ($result = $conn->store_result()) {
        $result->free();
    }
} while ($conn->more_results() && $conn->next_result());

echo "=== RESULTADO DE plan_pago DESPUÉS DE LOS UPDATES ===\n";
$res = $conn->query("SELECT idPlanPago, nombrePlan, descripcion, monto, duracion_dias, activo FROM plan_pago ORDER BY idPlanPago ASC");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo "ID: {$r['idPlanPago']} | {$r['nombrePlan']} | \${$r['monto']} | {$r['duracion_dias']} días\n";
        echo "  Desc: {$r['descripcion']}\n\n";
    }
}
