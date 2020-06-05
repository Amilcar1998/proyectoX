<?php 
include '../db/conexion.php';
include 'produccion.php';


class ModelProduccion extends Conexion
{
	
	function __construct()
	{
	 parent::__construct();
	}
  function getProduccion(){
  	$res=$this->con->query("select idProduccion,fechaP,estadoP,produccion.idPedido,fechaPedido,NombreCliente from produccion inner join pedido on produccion.idPedido=pedido.idPedido inner join cliente on pedido.idCliente=cliente.idCliente");
      $r=array();
      while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

  }
}





 