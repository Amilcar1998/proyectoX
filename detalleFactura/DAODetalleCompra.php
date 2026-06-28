<?php

include '../db/conexion.php';
include '../detalleFactura/detalleCompra.php';

class DAODetalleCompraMVC extends Conexion {
    public function __construct(){
        parent::__construct();
    }

    public function getTabla(): array {
        $sql="SELECT dc.idDetalleCompra, dc.cantidadMP, dc.precioMP,
                     mp.NombreMP,
                     f.numeroFac
              FROM detalleCompra dc
              INNER JOIN materiaprima mp ON dc.idMateriaPrima = mp.idMateriaPrima
              INNER JOIN factura f ON dc.idFacturaMP = f.idFacturaMP
              ORDER BY dc.idDetalleCompra DESC";
        $res = $this->con->query($sql);
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }


    public function insertar($obj): bool {
        $stmt = $this->con->prepare("insert into detalleCompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) values (?,?,?,?,?)");
        $id = $obj->getIdDetalleCompra();
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $stmt->bind_param("iissi", $id, $mp, $cantidad, $precio, $factura);
        return $stmt->execute();
    }


    public function eliminar(int $idDetalleCompra): bool {
        $stmt = $this->con->prepare("delete from detalleCompra where idDetalleCompra=?");
        $stmt->bind_param("i", $idDetalleCompra);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update detalleCompra set idMateriaPrima=?, cantidadMP=?, precioMP=?, idFacturaMP=? where idDetalleCompra=?");
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $id = $obj->getIdDetalleCompra();
        $stmt->bind_param("issii", $mp, $cantidad, $precio, $factura, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql="select * from detalleCompra where $criterio like ?";
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
}




?>