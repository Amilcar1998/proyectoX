<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$r = $m->query("SELECT fechaPedido FROM pedido LIMIT 5");
echo "=== fechaPedido samples ===\n";
while ($row = $r->fetch_assoc()) echo $row['fechaPedido'] . "\n";

$r2 = $m->query("SELECT Fecha FROM factura LIMIT 5");
echo "\n=== Fecha samples ===\n";
while ($row = $r2->fetch_assoc()) echo $row['Fecha'] . "\n";

$r3 = $m->query("SELECT STR_TO_DATE(fechaPedido, '%d/%m/%Y') AS parsed FROM pedido LIMIT 5");
echo "\n=== STR_TO_DATE parsed ===\n";
while ($row = $r3->fetch_assoc()) echo $row['parsed'] . "\n";
?>
