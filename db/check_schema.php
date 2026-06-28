<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$r = $m->query("DESCRIBE pedido");
echo "=== pedido columns ===\n";
while ($row = $r->fetch_assoc()) echo $row['Field'] . ' ' . $row['Type'] . "\n";
$r2 = $m->query("DESCRIBE detallepedido");
echo "\n=== detallepedido columns ===\n";
while ($row = $r2->fetch_assoc()) echo $row['Field'] . ' ' . $row['Type'] . "\n";
$r3 = $m->query("DESCRIBE pedidoproveedor");
echo "\n=== pedidoproveedor columns ===\n";
echo $r3 ? "Exists\n" : "Does not exist\n";
$r4 = $m->query("SELECT * FROM inventario LIMIT 3");
echo "\n=== inventario sample ===\n";
if ($r4) { while ($row = $r4->fetch_assoc()) print_r($row); }
?>
