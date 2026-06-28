<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$sql = "SELECT DATE_FORMAT(STR_TO_DATE(fechaPedido, '%d/%m/%Y'), '%Y-%m') AS mes, COUNT(*) AS cantidad
        FROM pedido
        WHERE fechaPedido REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
        GROUP BY mes
        ORDER BY mes DESC
        LIMIT 12";
$r = $m->query($sql);
echo "Rows: " . $r->num_rows . "\n";
while ($row = $r->fetch_assoc()) {
    echo "mes=" . var_export($row['mes'], true) . " cant=" . $row['cantidad'] . "\n";
}

$sql2 = "SELECT COUNT(*) AS total, SUM(CASE WHEN STR_TO_DATE(fechaPedido, '%d/%m/%Y') IS NULL THEN 1 ELSE 0 END) AS nulos
         FROM pedido";
$r2 = $m->query($sql2);
$row2 = $r2->fetch_assoc();
echo "\nTotal pedidos: {$row2['total']}, Nulos: {$row2['nulos']}\n";

$sql3 = "SELECT STR_TO_DATE(fechaPedido, '%d/%m/%Y') AS parsed FROM pedido LIMIT 3";
$r3 = $m->query($sql3);
echo "\nParsed samples:\n";
while ($row3 = $r3->fetch_assoc()) echo $row3['parsed'] . "\n";
?>
