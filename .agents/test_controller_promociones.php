<?php
session_start();
$_SESSION['s1'] = 'gerente@elgordito.com';
$_SESSION['id_Rol'] = 1;

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['accion'] = 'nivelacion';
$_POST['idReceta'] = '1';
$_POST['nuevoPrecio'] = '2.54';
$_POST['motivo'] = 'Test nivelacion controller';

ob_start();
require __DIR__ . '/../controllers/controllerPromociones.php';
$output = ob_get_clean();

echo "Ejecución de controllerPromociones.php completada sin errores.\n";
echo "Longitud HTML generado: " . strlen($output) . " bytes\n";
echo "Contiene SweetAlert: " . (strpos($output, 'Swal.fire') !== false ? 'SI' : 'NO') . "\n";
