<?php 
require_once __DIR__ . '/../models/ModelProduccion.php';
require_once __DIR__ . '/sesiones.php';

$prod = new ModelProduccion();
$data = $prod->getProduccion();
$correo = $_SESSION['s2'] ?? '';
$session = !empty($correo) ? $prod->getSessionEmp($correo) : [];
$idEmp = 1;
if (!empty($session) && is_array($session)) {
    foreach ($session as $key) {
        if (!empty($key['idEmpleado'])) {
            $idEmp = (int)$key['idEmpleado'];
        }
    }
}
$nombres = $prod->getNombreUsuario();
$fechaActual = date('d/m/Y');

if (isset($_REQUEST['agregar'])) {
    $id = $_REQUEST['id'];
    $pedido = $prod->getPedido($id);
    $r = 0;

    foreach ($pedido as $ask) {
        $receta = $ask['idReceta'];
        $Nreceta = $ask['nombreReceta'];
        $unidades = $ask['total_Unidades'];
        $Datareceta = $prod->getObtenerReceta($receta);
        foreach ($Datareceta as $ksa) {
            $inventario = $ksa['idInventario'];
            $existencias = $ksa['Existencias'];
            $total = $unidades * ($ksa['quintal'] ?? 1);
            $r = $prod->validarProduccion($inventario, $total);
            if ($r == 1) {
                $Inv = $existencias - $total;
                $prod->actualizarInventario($Inv, $inventario);
            }
        }
    }

    if ($r == 1) {
        $p = new Produccion('', $fechaActual, 'activo', $id, $idEmp);
        $prod->insertar($p);
        $msj = "Se ha pasado a producción exitosamente";
        $icon = "success";
        $prod->alterPedido($id);
    } else {
        $msj = "Error en la operación, por falta de materia prima";
        $icon = 'warning';
    }
}

if (isset($_REQUEST['eliminar'])) {
    $p = new Produccion($_REQUEST['produccionID'], '', 'activo', "", $idEmp);
    $prod->eliminar($p);
}

include __DIR__ . '/../views/vistaProduccionIn.php';
