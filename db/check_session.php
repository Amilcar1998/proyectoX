<?php
session_start();
$m = new mysqli('localhost', 'root', '', 'concentrados');

echo "SESSION s1: " . ($_SESSION['s1'] ?? 'NOT SET') . "\n\n";

if (isset($_SESSION['s1'])) {
    $correo = $_SESSION['s1'];
    $sql = "SELECT idEmpleado, nombreEmp, apellido FROM empleado e INNER JOIN usuarios u ON e.idUsuario = u.idUsuario WHERE u.username = '$correo'";
    $r = $m->query($sql);
    echo "Query: $sql\n";
    echo "Rows: " . $r->num_rows . "\n";
    if ($r->num_rows == 0) {
        echo "\nNo results. Checking username '$correo' in usuarios:\n";
        $r2 = $m->query("SELECT idUsuario, username, id_Rol FROM usuarios WHERE username = '$correo'");
        while ($row = $r2->fetch_assoc()) print_r($row);
    } else {
        while ($row = $r->fetch_assoc()) print_r($row);
    }
}
$m->close();
?>
