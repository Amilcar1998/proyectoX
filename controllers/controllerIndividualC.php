<?php
include "../models/modelClienteIn.php";
include 'Sesiones.php';

$cli= new ModelClienteIn();
$correo=$_SESSION['c1'];
$dataCliente=$cli->getClienteIndividual($correo);
foreach ($dataCliente as $fila) {
    $idCliente=$fila['idCliente'];
    $nombre = $fila['NombreCliente'];
    $apellido=$fila['apellidosCliente'];
}
$fechaActual = date('d/m/Y');

if (isset($_REQUEST['pedidos'])) {
    $pedido=$cli->getIdProd($idCliente);
}
if (isset($_REQUEST["agregarP"])) {
    //aqui inserto e pedido
    $p=new Pedidos('',$fechaActual,$idCliente,'1');
    $pedido=$cli->getIdProd($idCliente);
    $cli->insertarPedido($p);
    foreach($pedido as $key){
        $idPedid=$key['idPedido'];
    }
    $pedid= new DetallePedido('',$_REQUEST['txtcantidad'],$_REQUEST['producto'],$idPedid);
     $cli->AddDetalle($pedid);
     $detalleRes=$cli->getResProducto($idPedid);
    
    //$cli->alterPedido();
     $msj="producto agregado Correctamente";
     $icon='success';
}

if (isset($_REQUEST["agregar"])) {
    $pedido=$cli->getIdProd($idCliente);
    foreach($pedido as $key){
        $idPedid=$key['idPedido'];
    }
    $pedid= new DetallePedido('',$_REQUEST['txtcantidad'],$_REQUEST['producto'],$idPedid);
     $cli->AddDetalle($pedid);
     $detalleRes=$cli->getResProducto($idPedid);
     $msj="producto agregado Correctamente";
     $icon='success';
     var_dump($detalleRes);
}


$receta=$cli->getReceta();
$dataPedido=$cli->getAll($idCliente);
//$receta=$cli->getReceta();
$nombres=$nombre." ".$apellido;
include '../views/individualCliente.php';


?>