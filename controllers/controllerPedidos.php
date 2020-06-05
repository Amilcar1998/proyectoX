<?php
include '../models/ModelPedido.php';
$pedido = new ModelPedido();
$datos = $pedido->getPedido();


include '../views/vistaPedidos.php';
