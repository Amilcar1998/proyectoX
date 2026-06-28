<?php
$m = new mysqli('localhost', 'root', '', 'concentrados');
$r = $m->query("SELECT u.username, e.nombreEmp, e.apellido FROM usuarios u INNER JOIN empleado e ON u.idUsuario=e.idUsuario LIMIT 5");
echo "=== Usuarios-Empleados ===\n";
while ($row = $r->fetch_assoc()) {
    echo $row['username'] . ' -> ' . $row['nombreEmp'] . ' ' . $row['apellido'] . "\n";
}
$m->close();
?>