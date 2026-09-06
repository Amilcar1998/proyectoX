<?php
require_once __DIR__ . "/../db/conexion.php";
require_once __DIR__ . "/../models/Inventario.php";

class ModelInventario extends Conexion{
    public function __construct(){
        parent::__construct();
    }
    public function getTabla(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (i.idEmpresa = " . (int)$idEmpresa . " OR (i.idEmpresa IS NULL AND mp.idEmpresa = " . (int)$idEmpresa . ")) " : "";
        $res = $this->con->query("SELECT i.idInventario, i.idEmpresa, i.idMateriaPrima, mp.NombreMP, i.Existencias, COALESCE(emp.nombreEmpresa, 'Concentrados El Gordito') AS nombreEmpresa FROM inventario i INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima LEFT JOIN empresas emp ON i.idEmpresa = emp.idEmpresa $condicion ORDER BY i.idInventario ASC");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
    public function obtenerTabla(int $idEmpresa = 0): array {
        return $this->getTabla($idEmpresa);
    }
    public function listarTodos(int $idEmpresa = 0): array {
        return $this->getTabla($idEmpresa);
    }
    public function getInventario(int $idEmpresa = 0){
        return $this->getTabla($idEmpresa);
    }
    public function InsertarInventario($inv, int $idEmpresa = 1){
         $b=$inv->getIdMateriaPrima();
         $c=$inv->getExistencias();
         $d=$inv->getIdDetalleCompra();
         if ($idEmpresa <= 0) $idEmpresa = 1;
         $res=$this->con->prepare("INSERT INTO inventario (idMateriaPrima, Existencias, idDetalleCompra, idEmpresa) VALUES (?,?,?,?)");
         $res->bind_param('isii',$b,$c,$d,$idEmpresa);
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

      public function getMateriasPrimas(int $idEmpresa = 0): array {
          $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
          $res = $this->con->query("select idMateriaPrima, NombreMP from materiaprima $condicion ORDER BY NombreMP ASC");
          $r = [];
          if ($res) {
              while ($row = $res->fetch_assoc()) {
                  $r[] = $row;
              }
          }
          return $r;
      }
}
?>