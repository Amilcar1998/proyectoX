<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== plan_pago ===\n";
$res = $conn->query("DESCRIBE plan_pago");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo "  " . $r['Field'] . " - " . $r['Type'] . " " . $r['Key'] . "\n";
    }
} else {
    echo "  ERROR tabla no existe: " . $conn->error . "\n";
}

echo "\n=== Datos plan_pago ===\n";
$res2 = $conn->query("SELECT * FROM plan_pago LIMIT 10");
if ($res2) {
    while ($r = $res2->fetch_assoc()) {
        echo json_encode($r) . "\n";
    }
} else {
    echo "  ERROR: " . $conn->error . "\n";
}

echo "\n=== Query LandingModel ===\n";
$res3 = $conn->query("SELECT MIN(idPlanPago) AS idPlanPago, nombrePlan, descripcion, monto, duracion_dias 
                     FROM plan_pago 
                     WHERE activo = 1 
                     GROUP BY nombrePlan, monto, duracion_dias 
                     ORDER BY monto ASC");
if ($res3) {
    $count = 0;
    while ($r = $res3->fetch_assoc()) {
        echo json_encode($r) . "\n";
        $count++;
    }
    echo "Total: $count registros\n";
} else {
    echo "  ERROR: " . $conn->error . "\n";
}
