<?php 
include '../db/conexion.php';
include 'Pedidos.php';
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
function getResProducto(){
	
    $res=$this->con->query("select idDetallePedido,cantidad,receta.idReceta,nombreReceta from detallePedido inner join receta on detallePedido.idReceta = receta.idReceta where idPedido='4' ");
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
  



}








 ?>

