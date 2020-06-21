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
    $p=new Pedidos('',$fechaActual,$idCliente,'1');
    $pedido=$cli->getIdProd($idCliente);
    $cli->insertarPedido($p);
    foreach($pedido as $key){
        $idPedid=$key['idPedido'];
    }
    $pedid= new DetallePedido('',$_REQUEST['txtcantidad'],$_REQUEST['producto'],$idPedid);
     $cli->AddDetalle($pedid);
     $detalleRes=$cli->getResProducto($idPedid);

     $msj="producto agregado Correctamente";
     $icon='success';
}
if (isset($_REQUEST['agregarReceta'])) {
    $pedido=$cli->getIdProd($idCliente);
    $idPedid=$_REQUEST['pedido'];
    $pedid= new DetallePedido('',$_REQUEST['txtcantidad'],$_REQUEST['producto'],$idPedid);
     $cli->AddDetalle($pedid);
     $detalleRes=$cli->getResProducto($idPedid);
     $dataPedido=$cli->getAll($idCliente);

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
}

if(isset($_REQUEST['det'])){
     $pedido=$cli->getIdProd($idCliente);
    if(isset($_REQUEST['algo'])){
        $Mpedidos=$_REQUEST['algo'];
        $idPedid=$_REQUEST['algo'];
        $detalleRes=$cli->getResProducto($idPedid);
        $det=$_REQUEST['idRes'];
        $data=$cli->getDetalle($det);
    }else{
        $pedido=$cli->getIdProd($idCliente);
        foreach($pedido as $key){
            $idPedid=$key['idPedido'];
        }
        $detalleRes=$cli->getResProducto($idPedid);
        $det=$_REQUEST['idRes'];
        $data=$cli->getDetalle($det);
      }
}
if(isset($_REQUEST['eliminar'])){
    if(isset($_REQUEST['algo'])){
        $Mpedidos=$_REQUEST['algo'];
        $idPedid=$_REQUEST['algo'];
   $id=new DetallePedido($_REQUEST['id'],'','','');
   $cli->eliminarDetalle($id);
   $msj="El registro ha sido Eliminado Correctamente";
   $icon='success';
   $pedido=$cli->getIdProd($idPedid);
    }

  else{ 
  $id=new DetallePedido($_REQUEST['id'],'','','');
   $cli->eliminarDetalle($id);
   $msj="El registro ha sido Eliminado Correctamente";
   $icon='success';
   $pedido=$cli->getIdProd($idCliente);
    foreach($pedido as $key){
        $idPedid=$key['idPedido'];
    }
   $detalleRes=$cli->getResProducto($idPedid);}



}
if(isset($_REQUEST['detalle'])){
    $Mpedidos=$_REQUEST['data'];
    $pedido=$cli->getIdProd($idCliente);
    $idPedid=$_REQUEST['data'];
    $detalleRes=$cli->getResProducto($idPedid);

}
if(isset($_REQUEST['EliminarP'])){
    $id=new Pedidos($_REQUEST['data'],'','','');
    $cli->eliminarPedido($id);
    $msj="El Pedido ha sido Eliminado Correctamente";
   $icon='success';

}

$receta=$cli->getReceta();
$dataPedido=$cli->getAll($idCliente);
//$receta=$cli->getReceta();
$nombres=$nombre." ".$apellido;
include '../views/individualCliente.php';


?>