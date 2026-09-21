<?php
require_once __DIR__ . "/../db/conexion.php";
require_once __DIR__ . "/../models/MateriaPrima.php";

class ModelMateriaPrima extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getTabla(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
        $res = $this->con->query("SELECT * FROM materiaprima $condicion ORDER BY idMateriaPrima ASC");
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
        $stmt = $this->con->prepare("INSERT INTO materiaprima (idMateriaPrima, NombreMP, idEmpresa) VALUES (?,?,?)");
        $id = $obj->getIdMateriaPrima();
        $nombre = $obj->getNombreMP();
        $stmt->bind_param("isi", $id, $nombre, $idEmpresa);
        return $stmt->execute();
    }

    public function eliminar(int $idMateriaPrima): bool {
        $stmt = $this->con->prepare("DELETE FROM materiaprima WHERE idMateriaPrima=?");
        $stmt->bind_param("i", $idMateriaPrima);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("UPDATE materiaprima SET NombreMP=? WHERE idMateriaPrima=?");
        $nombre = $obj->getNombreMP();
        $id = $obj->getIdMateriaPrima();
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql = "SELECT * FROM materiaprima WHERE $criterio LIKE ?";
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