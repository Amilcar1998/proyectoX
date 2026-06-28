<?php
include "../db/conexion.php";
include "../models/Factura.php";

class ModelFactura extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("select * from factura");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function insertar($obj): bool {
        $stmt = $this->con->prepare("insert into factura (idFacturaMP, numeroFac, Monto, Fecha, idProveedor, idEmpleado) values (?,?,?,?,?,?)");
        $id = $obj->getIdFacturaMP();
        $numero = $obj->getNumeroFac();
        $monto = $obj->getMonto();
        $fecha = $obj->getFecha();
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $stmt->bind_param("isssii", $id, $numero, $monto, $fecha, $proveedor, $empleado);
        return $stmt->execute();
    }

    public function eliminar(int $idFacturaMP): bool {
        $stmt = $this->con->prepare("delete from factura where idFacturaMP=?");
        $stmt->bind_param("i", $idFacturaMP);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update factura set numeroFac=?, Monto=?, Fecha=?, idProveedor=?, idEmpleado=? where idFacturaMP=?");
        $numero = $obj->getNumeroFac();
        $monto = $obj->getMonto();
        $fecha = $obj->getFecha();
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $id = $obj->getIdFacturaMP();
        $stmt->bind_param("ssiiii", $numero, $monto, $fecha, $proveedor, $empleado, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql = "select * from factura where $criterio like ?";
        $stmt = $this->con->prepare($sql);
        $valor = "%$buscar%";
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $result = $stmt->get_result();
        $r = [];
        while ($row = $result->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getSessionEmp(string $correo): array {
        $res = $this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
}
?>