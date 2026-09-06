<?php
require_once __DIR__ . '/../db/conexion.php';

class ReportModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function dataEmpleados(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (empleado.idEmpresa = " . (int)$idEmpresa . ") " : "";
        $res = $this->con->query("select idEmpleado,nombreEmp,apellido,genero,nombrePuesto,username from empleado inner join puesto on empleado.idPuesto=puesto.idPuesto inner join usuarios on empleado.idUsuario=usuarios.idUsuario $condicion ORDER BY idEmpleado ASC");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function dataClientes(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (cliente.idEmpresa = " . (int)$idEmpresa . ") " : "";
        $res = $this->con->query("select idCliente,NombreCliente,apellidosCliente,telefono,edad,genero,username from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario $condicion ORDER BY idCliente ASC");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function dataProveedor(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
        $res = $this->con->query("select * from Proveedor $condicion ORDER BY idProveedor ASC");
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