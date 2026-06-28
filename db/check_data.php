<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$tables = ['pedidoproveedor', 'factura', 'inventario', 'produccion', 'empleado', 'pedido', 'detallepedido', 'salidas'];
foreach ($tables as $t) {
    $r = $m->query("SELECT COUNT(*) c FROM $t");
    $count = $r ? $r->fetch_assoc()['c'] : 'ERR: ' . $m->error;
    echo "$t: $count\n";
}
echo "\nSample factura rows:\n";
$r = $m->query("SELECT * FROM factura LIMIT 3");
if ($r) { while ($row = $r->fetch_assoc()) { print_r($row); } }
echo "\nSample pedidoproveedor check:\n";
$r2 = $m->query("SHOW TABLES LIKE 'pedidoproveedor'");
echo "Exists: " . ($r2->num_rows > 0 ? 'YES' : 'NO') . "\n";
?>
