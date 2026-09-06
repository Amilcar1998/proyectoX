<?php
session_start();
$_SESSION['s1'] = 'gerente@elgordito.com';
$_SESSION['id_Rol'] = 1;
$_SERVER['PHP_SELF'] = '/controllers/controllerPromociones.php';
$_SERVER['REQUEST_METHOD'] = 'GET';

ob_start();
require __DIR__ . '/../controllers/controllerPromociones.php';
$output = ob_get_clean();

echo "Ejecución de controllerPromociones.php GET:\n";
echo "Longitud HTML: " . strlen($output) . " bytes\n";
echo "Título de página presente: " . (strpos($output, 'Gestión de Precios, Nivelaciones y Promociones') !== false ? 'SI' : 'NO') . "\n";
echo "Contiene SweetAlert script: " . (strpos($output, 'Swal.fire') !== false ? 'SI' : 'NO') . "\n";
echo "Contiene variable promocionActivaActual: " . (strpos($output, 'promocionActivaActual') !== false ? 'SI' : 'NO') . "\n";
