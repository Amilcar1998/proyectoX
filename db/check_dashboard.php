<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$queries = [
    "SELECT COUNT(*) AS total FROM pedido" => 'totalPedidos',
    "SELECT COALESCE(SUM(Monto),0) AS total FROM factura" => 'montoFacturado',
    "SELECT COUNT(*) AS total FROM factura" => 'totalFacturas',
    "SELECT COUNT(*) AS total FROM inventario WHERE Existencias < 100" => 'stockCritico',
    "SELECT COUNT(*) AS total FROM empleado" => 'totalEmpleados',
    "SELECT DATE_FORMAT(fechaPedido, '%Y-%m') AS mes, COUNT(*) AS cantidad FROM pedido GROUP BY mes ORDER BY mes DESC LIMIT 3" => 'pedidosMensuales',
    "SELECT DATE_FORMAT(Fecha, '%Y-%m') AS mes, COALESCE(SUM(Monto),0) AS monto FROM factura GROUP BY mes ORDER BY mes DESC LIMIT 3" => 'montoMensual',
];
foreach ($queries as $sql => $label) {
    $r = $m->query($sql);
    echo "$label: " . ($r ? "OK (" . ($r->num_rows ?? 1) . " rows)" : "ERR: " . $m->error) . "\n";
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) echo "  " . print_r($row, true) . "\n";
    }
}
?>
