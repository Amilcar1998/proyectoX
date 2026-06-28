<?php
include '../db/conexion.php';

class ReportModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function dataEmpleados(): array {
        $res = $this->con->query("select idEmpleado,nombreEmp,apellido,genero,nombrePuesto,username from empleado inner join puesto on empleado.idPuesto=puesto.idPuesto inner join usuarios on empleado.idUsuario=usuarios.idUsuario");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function dataClientes(): array {
        $res = $this->con->query("select idCliente,NombreCliente,apellidosCliente,telefono,edad,genero,username from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function dataProveedor(): array {
        $res = $this->con->query("select * from Proveedor");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
}

?>