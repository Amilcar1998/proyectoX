<?php
include "../db/conexion.php";
include "../models/MateriaPrima.php";

class ModelMateriaPrima extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("select * from materiaPrima");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function insertar($obj): bool {
        $stmt = $this->con->prepare("insert into materiaPrima (idMateriaPrima, NombreMP) values (?,?)");
        $id = $obj->getIdMateriaPrima();
        $nombre = $obj->getNombreMP();
        $stmt->bind_param("is", $id, $nombre);
        return $stmt->execute();
    }

    public function eliminar(int $idMateriaPrima): bool {
        $stmt = $this->con->prepare("delete from materiaPrima where idMateriaPrima=?");
        $stmt->bind_param("i", $idMateriaPrima);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update materiaPrima set NombreMP=? where idMateriaPrima=?");
        $nombre = $obj->getNombreMP();
        $id = $obj->getIdMateriaPrima();
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql = "select * from materiaPrima where $criterio like ?";
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