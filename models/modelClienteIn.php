<?php 
include '../db/conexion.php';
include 'Pedidos.php';
include 'DetallePedido.php';
include 'DetalleProducto.php';
include 'Cliente.php';

class ModelClienteIn extends Conexion
{
	
	function __construct()
	{
	parent::__construct();	
	}
	function getClienteIndividual($correo){
		$res=$this->con->query("select * from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario where username='$correo'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
	}
  function getAll($idCliente){
		$res=$this->con->query("select idPedido,fechaPedido,nombreEstado,idCliente from pedido inner join estadoPedido on pedido.idEstadoPedido=estadoPedido.idEstadoPedido where idCliente=$idCliente");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
	}
  function insertarPedido($p){
    $para=$this->con->prepare("insert into pedido(idPedido,fechaPedido,idCliente,idEstadoPedido) values(?,?,?,?)");
    $para->bind_param('ssss',$a,$b,$c,$d);
    $a='';
    $b=$p->getFechaPedido();
    $c=$p->getIdCliente();
    $d=$p->getIdEstado();
    $para->execute();  
      }
     function getReceta(){
    $res=$this->con->query("select * from Receta;");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}
function getResProducto($idPedid){
    $res=$this->con->query("select idDetallePedido,cantidad,receta.idReceta,nombreReceta, (cantidad * PrecioUnitario) as total_producto from detallePedido inner join receta on detallePedido.idReceta = receta.idReceta where idPedido='$idPedid' order by idDetallePedido asc");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}
function getIdProd($idCliente){
    $res=$this->con->query("select * from pedido where idCliente=$idCliente order by idPedido desc limit 1;");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}
    function getDetalle($det){
        $res=$this->con->query("select idDetalleReceta,cantidaSa,NombreMP from detalleReceta inner join materiaPrima on detalleReceta.idMateriaPrima=materiaPrima.idMateriaPrima where idReceta='$det'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }


    function insertarDetalle($Detalle){
        $para=$this->con->prepare("insert into detalleReceta(idDetalleReceta,idMateriaPrima,cantidadSa,fechaSa,idInventario,idReceta) values(?,?,?,?,?,?)");
        $para->bind_param('ssssss',$a,$b,$c,$d,$e,$f);
        $a='';
        $b=$Detalle->getIdMateriaPrima();
        $c=$Detalle->getCantidadSa();
        $d=$Detalle->getFechaSa();
        $e=$Detalle->getInventario();
        $f=$Detalle->getIdReceta();
        $para->execute();
    }
    function AddDetalle($pedid){
        $para=$this->con->prepare("insert into detallePedido(idDetallePedido,cantidad,idReceta,idPedido) values(?,?,?,?)");
        $para->bind_param('ssss',$a,$b,$c,$d);
        $a='';
        $b=$pedid->getCantidad();
        $c=$pedid->getIdReceta();
        $d=$pedid->getIdPedido();
        $para->execute();
    }


   function alterPedido(){
     $para=$this->con->prepare("UPDATE `pedido` SET `idEstadoPedido` = '2' WHERE `pedido`.`idPedido` = 1");
     $para->execute();

        
   }
function eliminarDetalle($id){
        $res=$this->con->prepare("delete from detallePedido where idDetallePedido=?");
        $res->bind_param('s',$a);
        $a=$id->getIdDetallePedido();
        $res->execute();
      }
function eliminarPedido($id){
   $res=$this->con->prepare("delete from pedido where idPedido=?");
   $res->bind_param('s',$a);
   $a=$id->getIdPedido();
   $res->execute();
   
 }
function getObtener($cantidad){
    $res=$this->con->query("select detalleReceta.idMateriaPrima,cantidaSa,existencias,inventario.idInventario,detalleReceta.idReceta from detalleReceta inner join inventario on detalleReceta.idinventario=inventario.idinventario where idReceta='$cantidad';");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}




}








 ?>

