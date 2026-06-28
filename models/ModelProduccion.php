<?php 
include '../db/conexion.php';
include '../models/produccion.php';


class ModelProduccion extends Conexion
{
	
	function __construct()
	{
	 parent::__construct();
	}
  public function getPedido($id){
        $res=$this->con->query("select detallePedido.idReceta,nombreReceta,sum(cantidad) as total_Unidades from detallePedido 
inner join receta on detallePedido.idReceta=receta.idReceta
where idPedido='$id'
group by detallePedido.idReceta; ");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

    }
    function alterPedido($id){
     $para=$this->con->prepare("UPDATE `pedido` SET `idEstadoPedido` = '2' WHERE `pedido`.`idPedido` = '$id' ");
     $para->execute();

        
   }
    function alterProduccion($idProduccion){
     $para=$this->con->prepare("UPDATE `produccion` SET `estadoP` = 'terminado' WHERE `produccion`.`idProduccion` ='$idProduccion' ;");
     $para->execute();

        
   }
   function TerminarPedido($id){
     $para=$this->con->prepare("UPDATE `pedido` SET `idEstadoPedido` = '3' WHERE `pedido`.`idPedido` = '$id' ");
     $para->execute();

        
   }



function validarProduccion($inventario,$total){
             $a=$inventario;
             $b=$total;
             $para =$this->con->prepare("select * from inventario where idInventario=? and Existencias > ?");
             $para->bind_param("ii",$a,$b);
             $para->execute();
             while($para->fetch()) {
                 return 1;
             }
             return 0;

         }


   function getProduccion(){
  	$res=$this->con->query("select  idProduccion,fechaP,estadoP,produccion.idPedido,fechaPedido,NombreCliente,empleado.nombreEmp from produccion inner join empleado on produccion.idEmpleado=empleado.idEmpleado inner join pedido on produccion.idPedido=pedido.idPedido inner join cliente on pedido.idCliente=cliente.idCliente where estadoP='activo'");
      $r=array();
      while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

  }
    function getObtenerReceta($receta){
        $res=$this->con->query("select idDetalleReceta,cantidaSa,inventario.idInventario,Existencias,(cantidaSa/100) as quintal from detalleReceta inner join inventario on detalleReceta.idInventario=inventario.idInventario
            where idReceta='$receta'
            group by inventario.idMateriaPrima;");
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
         $a='';
         $b=$p->getFechaProduccion();
         $c=$p->getEstadoProduccion();
         $d=$p->getIdPedido();
         $e=$p->getIdEmpleado();
         $para=$this->con->prepare("insert into produccion(idProduccion,fechaP,estadoP,idPedido,idEmpleado) values(?,?,?,?,?)");
         $para->bind_param('sssss',$a,$b,$c,$d,$e);
         $para->execute();
     }
     function actualizarInventario($Inv,$inventario){
         $para=$this->con->prepare("UPDATE `inventario` SET `Existencias` = '$Inv' WHERE `inventario`.`idInventario` = '$inventario';");
         $para->execute();
     }
     public function eliminar($p){
        $a=$p->getIdProduccion();
        $res=$this->con->prepare("DELETE FROM `produccion` WHERE `produccion`.`idProduccion`=?");
         $res->bind_param('i',$a);
         $res->execute();
     }


}


?>


 