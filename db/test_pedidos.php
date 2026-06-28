<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$sql = "SELECT p.idPedido, c.NombreCliente,
               CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado,
               r.nombreReceta, p.fechaPedido, dp.cantidad
        FROM pedido p
        INNER JOIN cliente c ON p.idCliente = c.idCliente
        INNER JOIN detallepedido dp ON p.idPedido = dp.IdPedido
        INNER JOIN receta r ON dp.idReceta = r.idReceta
        INNER JOIN produccion pr ON p.idPedido = pr.idPedido
        INNER JOIN empleado e ON pr.idEmpleado = e.idEmpleado
        ORDER BY p.fechaPedido DESC
        LIMIT 10";
$r = $m->query($sql);
echo "Rows: " . $r->num_rows . "\n";
while ($row = $r->fetch_assoc()) {
    echo "Pedido {$row['idPedido']} - {$row['NombreCliente']} - {$row['empleado']} - {$row['nombreReceta']} - {$row['fechaPedido']} - Cant: {$row['cantidad']}\n";
}
?>
