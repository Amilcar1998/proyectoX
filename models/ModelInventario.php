<?php
include '../db/conexion.php';
include 'Inventario.php';

class ModelInventario extends Conexion{
    public function __construct(){
        parent::__construct();
    }
    public function getInventario(){
        $res=$this->con->query("SELECT * FROM inventario");
        $r=array();
        while($row=$res->fetch_assoc()) {
              $r[]=$row;
          }
          return $r;
    }
    public function InsertarInventario($inv){
        $res=$this->con->prepare("INSERT INTO inventario (idInventario, idMateriaPrima, Existencias, idDetalleCompra) VALUES (?,?,?,?)");
        $res->bind_param('ssss',$a,$b,$c,$d);
        $a='';
        $b=$inv->getIdMateriaPrima();
        $c=$inv->getExistencias();
        $d=$inv->getIdDetalleCompra();
        $res->execute();
    }
    public function setInventario($inv){
        $res=$this->con->prepare("UPDATE inventario SET idMateriaPrima=?, Existencias, idDetalleCompra WHERE idInventario=?");
        $res->bind_prepare('s,s,s,s',$a,$b,$c,$d);
        $a=$inv->getIdMateriaPrima();
        $b=$inv->getExistencias();
        $c=$inv->getIdDetallePedido();
        $d=$inv->getIdInventario();
        $res->execute();
    }
    public function eliminarInventario($inv){
        $res=$this->con->prepare("DELETE FROM inventario WHERE idInventario=?");
        $res->bind_param('s',$a);
        $a=$inv->getIdInventario();
        $res->execute();
    }
     function getSessionEmp($correo){
        $res=$this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }

}
?>