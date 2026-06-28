<?php
include '../db/conexion.php';
include '../models/Inventario.php';

class ModelInventario extends Conexion{
    public function __construct(){
        parent::__construct();
    }
    public function getTabla(): array {
        $res = $this->con->query("SELECT i.idInventario, i.idMateriaPrima, mp.NombreMP, i.Existencias FROM inventario i INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
    public function getInventario(){
        return $this->getTabla();
    }
    public function InsertarInventario($inv){
         $a="";
         $b=$inv->getIdMateriaPrima();
         $c=$inv->getExistencias();
         $d=$inv->getIdDetalleCompra();
         $res=$this->con->prepare("INSERT INTO inventario (idInventario, idMateriaPrima, Existencias, idDetalleCompra) VALUES (?,?,?,?)");
         $res->bind_param('iisi',$a,$b,$c,$d);
         return $res->execute();
     }
     public function setInventario($inv){
         $a=$inv->getIdMateriaPrima();
         $b=$inv->getExistencias();
         $c=$inv->getIdDetalleCompra();
         $d=$inv->getIdInventario();
         $res=$this->con->prepare("UPDATE inventario SET idMateriaPrima=?, Existencias=?, idDetalleCompra=? WHERE idInventario=?");
         $res->bind_param('iisi',$a,$b,$c,$d);
         return $res->execute();
     }
     public function eliminarInventario($id){
         $res=$this->con->prepare("DELETE FROM inventario WHERE idInventario=?");
         $res->bind_param('i',$id);
         return $res->execute();
     }
     public function eliminar(int $idInventario): bool {
         $res = $this->con->prepare("DELETE FROM inventario WHERE idInventario=?");
         $res->bind_param('i', $idInventario);
         return $res->execute();
     }
     public function modificar($obj): bool {
         $a = $obj->getIdMateriaPrima();
         $b = $obj->getExistencias();
         $c = $obj->getIdDetalleCompra();
         $d = $obj->getIdInventario();
         $res = $this->con->prepare("UPDATE inventario SET idMateriaPrima=?, Existencias=?, idDetalleCompra=? WHERE idInventario=?");
         $res->bind_param('iisi', $a, $b, $c, $d);
         return $res->execute();
     }
      public function getSessionEmp(string $correo): array {
          $res = $this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
          $r = [];
          while ($row = $res->fetch_assoc()) {
              $r[] = $row;
          }
          return $r;
      }

      public function getMateriasPrimas(): array {
          $res = $this->con->query("select idMateriaPrima, NombreMP from materiaprima");
          $r = [];
          while ($row = $res->fetch_assoc()) {
              $r[] = $row;
          }
          return $r;
      }
}
?>