<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/DetalleCompraEntity.php';

class ModelDetalleCompra extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function obtenerTabla(): array {
        $sql = "SELECT dc.idDetalleCompra, dc.idMateriaPrima, dc.cantidadMP, dc.precioMP, dc.idFacturaMP, mp.NombreMP, f.numeroFac 
                FROM detalleCompra dc 
                INNER JOIN materiaprima mp ON dc.idMateriaPrima=mp.idMateriaPrima 
                INNER JOIN factura f ON dc.idFacturaMP=f.idFacturaMP 
                ORDER BY dc.idDetalleCompra DESC";
        $res = $this->con->query($sql);
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getTabla(): array {
        return $this->obtenerTabla();
    }

    public function insertar($obj): bool {
        $stmt = $this->con->prepare("INSERT INTO detalleCompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) VALUES (?,?,?,?,?)");
        $id = $obj->getIdDetalleCompra();
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $stmt->bind_param("iissi", $id, $mp, $cantidad, $precio, $factura);
        return $stmt->execute();
    }

    public function eliminar(int $idDetalleCompra): bool {
        $stmt = $this->con->prepare("DELETE FROM detalleCompra WHERE idDetalleCompra=?");
        $stmt->bind_param("i", $idDetalleCompra);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("UPDATE detalleCompra SET idMateriaPrima=?, cantidadMP=?, precioMP=?, idFacturaMP=? WHERE idDetalleCompra=?");
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
                FROM detalleCompra dc
                INNER JOIN materiaprima mp ON dc.idMateriaPrima=mp.idMateriaPrima
                INNER JOIN factura f ON dc.idFacturaMP=f.idFacturaMP
                WHERE $criterio LIKE ?";
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

    public function obtenerMateriasPrimas(): array {
        $res = $this->con->query("SELECT idMateriaPrima, NombreMP FROM materiaprima ORDER BY NombreMP ASC");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getMateriasPrimas(): array {
        return $this->obtenerMateriasPrimas();
    }

    public function obtenerFacturas(): array {
        $res = $this->con->query("SELECT idFacturaMP, numeroFac FROM factura ORDER BY idFacturaMP DESC");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getFacturas(): array {
        return $this->obtenerFacturas();
    }
}
?>