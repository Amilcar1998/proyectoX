<?php 
require_once __DIR__ . '/../models/ModelProduccion.php';
require_once __DIR__ . '/sesiones.php';

$prod = new ModelProduccion();
$correo = $_SESSION['s2'] ?? ($_SESSION['s1'] ?? '');
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || (($correo ?? '') === 'amilcar199819@gmail.com');
$idEmpresaFiltro = $esSuperUsuario ? 0 : $idEmpresaSesion;

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
        $prod->insertar($p, $idEmpresaSesion);
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
    $msj = 'Orden de producción eliminada';
    $icon = 'success';
}

$data = $prod->getProduccion($idEmpresaFiltro);
include __DIR__ . '/../views/vistaProduccionIn.php';
