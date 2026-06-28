<?php
include '../models/ModelPedido.php';
include 'sesiones.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();
$correo = $_SESSION['s2'] ?? '';
$nombres = $pedido->getNombreUsuario();
$fechaActual = date('d/m/Y');
foreach ($session as $key) {
    $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];


}


include '../views/vistaPedidosI.php';
