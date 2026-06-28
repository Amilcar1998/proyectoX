<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$sql = "SELECT DATE_FORMAT(STR_TO_DATE(Fecha, '%d/%m/%Y'), '%Y-%m') AS mes, COALESCE(SUM(Monto),0) AS monto
        FROM factura
        WHERE Fecha REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
        GROUP BY mes
        ORDER BY mes DESC
        LIMIT 12";
$r = $m->query($sql);
echo "Rows: " . $r->num_rows . "\n";
while ($row = $r->fetch_assoc()) {
    echo "mes=" . var_export($row['mes'], true) . " monto=" . $row['monto'] . "\n";
}
?>
