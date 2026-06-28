<?php
$mysqli = new mysqli('localhost', 'root', '', 'concentrados');
$r = $mysqli->query("SHOW TABLES LIKE '%pedido%'");
echo "Tablas con 'pedido':\n";
while ($row = $r->fetch_array()) {
    echo $row[0] . "\n";
}
$r2 = $mysqli->query("SHOW TABLES LIKE '%proveedor%'");
echo "\nTablas con 'proveedor':\n";
while ($row = $r2->fetch_array()) {
    echo $row[0] . "\n";
}
$r3 = $mysqli->query("SHOW TABLES");
echo "\nTodas las tablas:\n";
while ($row = $r3->fetch_array()) {
    echo $row[0] . "\n";
}
?>
