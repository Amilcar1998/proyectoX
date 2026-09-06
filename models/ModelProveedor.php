<?php

require_once __DIR__ . "/../db/conexion.php";
require_once __DIR__ . "/../models/Proveedor.php";

class ModelProveedor extends Conexion {

    public function __construct(){
        parent::__construct();
    }

    public function getTabla(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
        $res = $this->con->query("SELECT * FROM proveedor $condicion ORDER BY idProveedor ASC");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function insertar($p, int $idEmpresa = 1): bool {
        $b = $p->getNombreProveedor();
        $c = $p->getContacto();
        $d = $p->getNit();
        $e = $p->getCorreoP();
        $f = $p->getTelefono();
        if ($idEmpresa <= 0) $idEmpresa = 1;
        $res = $this->con->prepare("INSERT INTO proveedor(nombreProveedor,contacto,NIT,correoP,telefono,idEmpresa) VALUES(?,?,?,?,?,?)");
        $res->bind_param('sssssi', $b, $c, $d, $e, $f, $idEmpresa);
        return $res->execute();
    }

    public function eliminar($p): bool {
        $a = $p->getIdProveedor();
        $res = $this->con->prepare("DELETE FROM proveedor WHERE idProveedor=?");
        $res->bind_param('i', $a);
        return $res->execute();
    }

    public function modificar($p): bool {
        $a = $p->getNombreProveedor();
        $b = $p->getContacto();
        $c = $p->getNit();
        $d = $p->getCorreoP();
        $e = $p->getTelefono();
        $f = $p->getIdProveedor();
        $res = $this->con->prepare("update proveedor set nombreProveedor=?,contacto=?,NIT=?,correoP=?,telefono=? where idProveedor=?");
        $res->bind_param('sssssi', $a, $b, $c, $d, $e, $f);
        return $res->execute();
    }

    public function getSessionEmp(): array {
        $correo = $_SESSION["s1"] ?? '';
        $res = $this->con->query("select nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
}