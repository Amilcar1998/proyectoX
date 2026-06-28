<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../models/PlanPagoModel.php';
include '../controllers/sesiones.php';
include '../models/AuditoriaHelper.php';

$plan = new PlanPagoModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['s1'])) {
        if (isset($_REQUEST['insertar'])) {
            $plan->insertarPlan(
                $_REQUEST['nombrePlan'],
                $_REQUEST['descripcion'],
                $_REQUEST['monto'],
                $_REQUEST['duracionDias']
            );
            $msj = "Plan registrado exitosamente";
            $icon = "success";
            logModuloAcceso(0, $_SESSION['s1'], 'plan_pago');
        } elseif (isset($_REQUEST['modificar'])) {
            $plan->modificarPlan(
                $_REQUEST['idPlanPago'],
                $_REQUEST['nombrePlan'],
                $_REQUEST['descripcion'],
                $_REQUEST['monto'],
                $_REQUEST['duracionDias'],
                isset($_REQUEST['activo']) ? 1 : 0
            );
            $msj = "Plan actualizado exitosamente";
            $icon = "success";
            logModuloAcceso(0, $_SESSION['s1'], 'plan_pago');
        } elseif (isset($_REQUEST['eliminar'])) {
            $plan->eliminarPlan($_REQUEST['idPlanPago']);
            $msj = "Plan eliminado exitosamente";
            $icon = "success";
            logModuloAcceso(0, $_SESSION['s1'], 'plan_pago');
        }
    }
}

$planes = $plan->getPlanes();

$currentUsername = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));
$esAdmin = isset($_SESSION['s1']);

include '../views/vistaPlanPago.php';
