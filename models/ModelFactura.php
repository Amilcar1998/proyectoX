<?php
require_once __DIR__ . "/../db/conexion.php";
require_once __DIR__ . "/../models/factura.php";

class ModelFactura extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getTabla(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE f.idEmpresa = " . (int)$idEmpresa : "";
        $res = $this->con->query("SELECT f.idFacturaMP, f.numeroFac, f.Monto, f.Fecha, f.idProveedor, p.nombreProveedor, f.idEmpleado, CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado, COALESCE(emp.nombreEmpresa, 'Concentrados El Gordito') AS nombreEmpresa FROM factura f INNER JOIN proveedor p ON f.idProveedor=p.idProveedor INNER JOIN empleado e ON f.idEmpleado=e.idEmpleado LEFT JOIN empresas emp ON f.idEmpresa = emp.idEmpresa $condicion ORDER BY f.idFacturaMP DESC");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function obtenerTabla(int $idEmpresa = 0): array {
        return $this->getTabla($idEmpresa);
    }

    public function listarTodos(int $idEmpresa = 0): array {
        return $this->getTabla($idEmpresa);
    }

    public function insertar($obj, int $idEmpresa = 1): bool {
        if ($idEmpresa <= 0) $idEmpresa = 1;
        $stmt = $this->con->prepare("insert into factura (idFacturaMP, numeroFac, Monto, Fecha, idProveedor, idEmpleado, idEmpresa) values (?,?,?,?,?,?,?)");
        $id = $obj->getIdFacturaMP();
        $numero = $obj->getNumeroFac();
        $monto = $obj->getMonto();
        $fecha = $obj->getFecha();
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $stmt->bind_param("isssiii", $id, $numero, $monto, $fecha, $proveedor, $empleado, $idEmpresa);
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

    public function getProveedores(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
        $res = $this->con->query("select idProveedor, nombreProveedor from proveedor $condicion ORDER BY nombreProveedor ASC");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getEmpleados(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . ") " : "";
        $res = $this->con->query("select idEmpleado, nombreEmp, apellido from empleado $condicion ORDER BY nombreEmp ASC");
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