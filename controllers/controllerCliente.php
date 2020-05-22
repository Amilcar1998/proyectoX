<?php
include '../models/ClienteModel.php';
include "sesiones.php";
$cliente = new ClienteModel();


$Rcliente = $cliente->getCliente();



include "../views/vistaCliente.php";

