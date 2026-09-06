<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

echo "=== TABLAS RELACIONADAS A RECETAS, PRODUCTOS Y PROMOCIONES ===\n";
$tables = $conn->query("SHOW TABLES");
while ($t = $tables->fetch_row()) {
    $tbl = $t[0];
    if (stripos($tbl, 'receta') !== false || stripos($tbl, 'prom') !== false || stripos($tbl, 'desc') !== false || stripos($tbl, 'ofer') !== false || stripos($tbl, 'prod') !== false || stripos($tbl, 'mater') !== false || stripos($tbl, 'prec') !== false) {
        echo "\nTabla: $tbl\n";
        $cols = $conn->query("DESCRIBE `$tbl`");
        while ($c = $cols->fetch_assoc()) {
            echo "  - " . $c['Field'] . " (" . $c['Type'] . ")\n";
        }
        echo "  Filas:\n";
        $rows = $conn->query("SELECT * FROM `$tbl` LIMIT 10");
        if ($rows) {
            while ($r = $rows->fetch_assoc()) {
                echo "    " . json_encode($r) . "\n";
            }
        }
    }
}
