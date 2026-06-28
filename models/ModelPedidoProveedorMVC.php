<?php

include "../db/conexion.php";
include '../models/pedidoProveedor.php';

class ModelPedidoProveedor extends Conexion {

    public function __construct(){
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("SELECT pp.idPedido, pp.idProveedor, p.nombreProveedor, pp.idEmpleado, CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado, pp.idMateriaPrima, mp.NombreMP, pp.fecha, pp.cantidadMP, pp.monto, pp.precioMP FROM pedidoproveedor pp INNER JOIN proveedor p ON pp.idProveedor=p.idProveedor INNER JOIN empleado e ON pp.idEmpleado=e.idEmpleado INNER JOIN materiaprima mp ON pp.idMateriaPrima=mp.idMateriaPrima");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function insertar($obj): bool {
        $stmt = $this->con->prepare("insert into pedidoproveedor (idProveedor, idEmpleado, idMateriaPrima, fecha, cantidadMP, monto, precioMP) values (?,?,?,?,?,?,?)");
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $mp = $obj->getIdMateriaPrima();
        $fecha = $obj->getFecha();
        $cantidad = $obj->getCantidadMP();
        $monto = $obj->getMonto();
        $precio = $obj->getPrecioMP();
        $stmt->bind_param("iiisddi", $proveedor, $empleado, $mp, $fecha, $cantidad, $monto, $precio);
        return $stmt->execute();
    }

    public function eliminar(int $idPedido): bool {
        $stmt = $this->con->prepare("delete from pedidoproveedor where idPedido=?");
        $stmt->bind_param("i", $idPedido);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update pedidoproveedor set idProveedor=?, idEmpleado=?, idMateriaPrima=?, fecha=?, cantidadMP=?, monto=?, precioMP=? where idPedido=?");
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $mp = $obj->getIdMateriaPrima();
        $fecha = $obj->getFecha();
        $cantidad = $obj->getCantidadMP();
        $monto = $obj->getMonto();
        $precio = $obj->getPrecioMP();
        $id = $obj->getIdPedido();
        $stmt->bind_param("iiisddii", $proveedor, $empleado, $mp, $fecha, $cantidad, $monto, $precio, $id);
        return $stmt->execute();
    }

    public function getSessionEmp($correo): array {
        $res = $this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getProveedores(): array {
        $res = $this->con->query("select idProveedor, nombreProveedor from proveedor");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getEmpleados(): array {
        $res = $this->con->query("select idEmpleado, nombreEmp, apellido from empleado");
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