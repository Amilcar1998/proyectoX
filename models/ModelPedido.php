<?php
include '../db/conexion.php';
include 'Pedidos.php';
class ModelPedido extends Conexion
{
public function __construct()
{
    parent::__construct();
}
   
function getPedido(){
    $res=$this->con->query("select idPedido,fechaPedido,NombreCliente,ApellidosCliente,estadoPedido.nombreEstado from pedido inner join cliente on pedido.idCliente=cliente.idCliente inner join estadoPedido on pedido.idEstadoPedido=estadoPedido.idEstadoPedido where pedido.idEstadoPedido=1");
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
    function getDetalle($id){
    $res=$this->con->query("select * from detallePedido inner join receta on detallePedido.idReceta=receta.idReceta where idPedido='$id';");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}
function getReceta($idReceta){
    $res=$this->con->query("select idDetalleReceta,cantidaSa,NombreMP from detalleReceta inner join materiaPrima on detalleReceta.idMateriaPrima=materiaPrima.idMateriaPrima where IdReceta='$idReceta';");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}
  

}