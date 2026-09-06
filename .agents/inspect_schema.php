<?php
require_once __DIR__ . '/../db/conexion.php';
$con = new Conexion();
$conn = $con->getConnection();

$tablas = ['pedido', 'detallePedido', 'cliente', 'usuarios', 'receta'];
foreach ($tablas as $t) {
    echo "=== $t ===\n";
    $res = $conn->query("DESCRIBE `$t`");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            echo "  " . $r['Field'] . " - " . $r['Type'] . " " . $r['Key'] . " " . $r['Extra'] . "\n";
        }
    } else {
        echo "  ERROR: " . $conn->error . "\n";
    }
}
