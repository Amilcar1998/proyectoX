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
    function getSessionEmp(){
        $correo=$_SESSION["s1"];
        $res=$this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }
    function getDetalle($id){
    $res=$this->con->query("select idDetallePedido,cantidad,nombreReceta from detallePedido inner join receta on detallePedido.idReceta=receta.idReceta where idPedido=$id");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}
function getReceta($id){
    $res=$this->con->query("select  idDetalleReceta,NombreMP,cantidaSa,fechaSa,receta.nombreReceta,detallePedido.idPedido from detalleReceta inner join materiaPrima on detalleReceta.idMateriaPrima=materiaPrima.idMateriaPrima inner join receta on detalleReceta.idReceta =receta.idReceta inner join detallePedido on detallePedido.idReceta=receta.idReceta where detallePedido.idPedido=1 order by receta.idReceta asc;");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}


}