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
    $res=$this->con->query("select * from pedido order by fechaPedido desc");
    $r=array();
    while($row=$res->fetch_assoc()) {
        $r[]=$row;
    }
    return $r;
}

}