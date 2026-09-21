<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/DetalleCompraEntity.php';

class ModelDetalleCompra extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function obtenerTabla(int $idEmpresa = 0): array {
        $cond = ($idEmpresa > 0) ? " WHERE (f.idEmpresa = " . (int)$idEmpresa . ") " : "";
        $sql = "SELECT dc.idDetalleCompra, dc.idMateriaPrima, dc.cantidadMP, dc.precioMP, dc.idFacturaMP, mp.NombreMP, f.numeroFac 
                FROM detallecompra dc 
                INNER JOIN materiaprima mp ON dc.idMateriaPrima=mp.idMateriaPrima 
                INNER JOIN factura f ON dc.idFacturaMP=f.idFacturaMP 
                $cond
                ORDER BY dc.idDetalleCompra DESC";
        $res = $this->con->query($sql);
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getTabla(int $idEmpresa = 0): array {
        return $this->obtenerTabla($idEmpresa);
    }

    public function insertar($obj): bool {
        $stmt = $this->con->prepare("INSERT INTO detallecompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) VALUES (?,?,?,?,?)");
        $id = $obj->getIdDetalleCompra();
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $stmt->bind_param("iissi", $id, $mp, $cantidad, $precio, $factura);
        return $stmt->execute();
    }

    public function eliminar(int $idDetalleCompra): bool {
        $stmt = $this->con->prepare("DELETE FROM detallecompra WHERE idDetalleCompra=?");
        $stmt->bind_param("i", $idDetalleCompra);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("UPDATE detallecompra SET idMateriaPrima=?, cantidadMP=?, precioMP=?, idFacturaMP=? WHERE idDetalleCompra=?");
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $id = $obj->getIdDetalleCompra();
        $stmt->bind_param("issii", $mp, $cantidad, $precio, $factura, $id);
        return $stmt->execute();
    }

    public function obtenerFiltro(string $buscar, string $criterio): array {
        $sql = "SELECT dc.idDetalleCompra, dc.idMateriaPrima, dc.cantidadMP, dc.precioMP, dc.idFacturaMP, mp.NombreMP, f.numeroFac
                FROM detallecompra dc
                INNER JOIN materiaprima mp ON dc.idMateriaPrima=mp.idMateriaPrima
                INNER JOIN factura f ON dc.idFacturaMP=f.idFacturaMP
                WHERE $criterio LIKE ?";
        $stmt = $this->con->prepare($sql);
        $valor = "%$buscar%";
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $result = $stmt->get_result();
        $r = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getFiltro(string $buscar, string $criterio): array {
        return $this->obtenerFiltro($buscar, $criterio);
    }

    public function obtenerSesionEmpleado(string $correo): array {
        $correoSeguro = $this->con->real_escape_string($correo);
        $res = $this->con->query("SELECT idEmpleado, nombreEmp, apellido FROM empleado INNER JOIN usuarios ON empleado.idUsuario=usuarios.idUsuario WHERE username='$correoSeguro'");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getSessionEmp(string $correo): array {
        return $this->obtenerSesionEmpleado($correo);
    }

    public function obtenerMateriasPrimas(int $idEmpresa = 0): array {
        $cond = ($idEmpresa > 0) ? " WHERE idEmpresa = " . (int)$idEmpresa . " " : "";
        $res = $this->con->query("SELECT idMateriaPrima, NombreMP FROM materiaprima $cond ORDER BY NombreMP ASC");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getMateriasPrimas(int $idEmpresa = 0): array {
        return $this->obtenerMateriasPrimas($idEmpresa);
    }

    public function obtenerFacturas(int $idEmpresa = 0): array {
        $cond = ($idEmpresa > 0) ? " WHERE idEmpresa = " . (int)$idEmpresa . " " : "";
        $res = $this->con->query("SELECT idFacturaMP, numeroFac FROM factura $cond ORDER BY idFacturaMP DESC");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getFacturas(int $idEmpresa = 0): array {
        return $this->obtenerFacturas($idEmpresa);
    }
}
?>