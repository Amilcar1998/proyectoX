<?php 
include '../db/conexion.php';
include 'produccion.php';


class ModelProduccion extends Conexion
{
	
	function __construct()
	{
	 parent::__construct();
	}
    function alterPedido($id){
     $para=$this->con->prepare("UPDATE `pedido` SET `idEstadoPedido` = '2' WHERE `pedido`.`idPedido` = '$id' ");
     $para->execute();

        
   }
  function getProduccion(){
  	$res=$this->con->query("select  idProduccion,fechaP,estadoP,produccion.idPedido,fechaPedido,NombreCliente,empleado.nombreEmp from produccion inner join empleado on produccion.idEmpleado=empleado.idEmpleado inner join pedido on produccion.idPedido=pedido.idPedido inner join cliente on pedido.idCliente=cliente.idCliente");
      $r=array();
      while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

  }
    function getSessionEmp($correo){
        $res=$this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }
    function insertar($p){
        $para=$this->con->prepare("insert into produccion(idProduccion,fechaP,estadoP,idPedido,idEmpleado) values(?,?,?,?,?)");
        $para->bind_param('sssss',$a,$b,$c,$d,$e);
        $a='';
        $b=$p->getFechaProduccion();
        $c=$p->getEstadoProduccion();
        $d=$p->getIdPedido();
        $e=$p->getIdEmpleado();
        $para->execute();
    }
    public function eliminar($p){
       $res=$this->con->prepare("DELETE FROM `produccion` WHERE `produccion`.`idProduccion`=?");
        $res->bind_param('s',$a);
        $a=$p->getIdProduccion();
        $res->execute();
    }


}


?>


 