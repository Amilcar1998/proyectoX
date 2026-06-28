<?php
include "../db/conexion.php";
include "../models/Puesto.php";

class ModelPuesto extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("select * from puesto");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function insertar($obj): bool {
        $sql = "insert into puesto value(?,?)";
        $stmt = $this->con->prepare($sql);
        $id = $obj->getId();
        $nombre = $obj->getNombrePuesto();
        $stmt->bind_param("is", $id, $nombre);
        return $stmt->execute();
    }

    public function eliminar(int $idPuesto): bool {
        $stmt = $this->con->prepare("delete from puesto where idPuesto=?");
        $stmt->bind_param("i", $idPuesto);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update puesto set nombrePuesto=? where idPuesto=?");
        $nombre = $obj->getNombrePuesto();
        $id = $obj->getId();
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql = "select * from puesto where $criterio like ?";
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