<?php

include "../db/conexion.php";
include 'Proveedor.php';

class ModelProveedor extends Conexion {

    public function __construct(){
        parent::__construct();
    }

    public function getTabla(): array {
        $res = $this->con->query("select * from proveedor");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function insertar($p): bool {
        $a = "";
        $b = $p->getNombreProveedor();
        $c = $p->getContacto();
        $d = $p->getNit();
        $e = $p->getCorreoP();
        $f = $p->getTelefono();
        $res = $this->con->prepare("insert into proveedor(idProveedor,nombreProveedor,contacto,NIT,correoP,telefono) values(?,?,?,?,?,?)");
        $res->bind_param('ssssss', $a, $b, $c, $d, $e, $f);
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