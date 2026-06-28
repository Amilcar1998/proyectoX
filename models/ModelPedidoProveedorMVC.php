<?php

include "../db/conexion.php";
include '../models/pedidoProveedor.php';

class ModelPedidoProveedor extends Conexion {

    public function __construct(){
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("select * from pedidoproveedor");
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
}
?>