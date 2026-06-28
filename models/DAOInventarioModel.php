<?php
include "../db/conexion.php";
include "../models/InventarioEntity.php";

class DAOInventarioModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("select * from inventario");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function insertar($obj): bool {
        $stmt = $this->con->prepare("insert into inventario (idInventario, idMateriaPrima, Existencias) values (?,?,?)");
        $id = $obj->getIdInventario();
        $mp = $obj->getIdMateriaPrima();
        $existencias = $obj->getExistencias();
        $stmt->bind_param("iid", $id, $mp, $existencias);
        return $stmt->execute();
    }

    public function eliminar(int $idInventario): bool {
        $stmt = $this->con->prepare("delete from inventario where idInventario=?");
        $stmt->bind_param("i", $idInventario);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update inventario set idMateriaPrima=?, Existencias=? where idInventario=?");
        $mp = $obj->getIdMateriaPrima();
        $existencias = $obj->getExistencias();
        $id = $obj->getIdInventario();
        $stmt->bind_param("idi", $mp, $existencias, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql = "select * from inventario where $criterio like ?";
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