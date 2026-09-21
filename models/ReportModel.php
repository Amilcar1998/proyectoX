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
        $condicion = ($idEmpresa > 0) ? " AND (p.idEmpresa = " . (int)$idEmpresa . ") " : "";
        $res = $this->con->query("SELECT p.idPersona AS idCliente, p.idPersona, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS NombreCliente, p.nombrePersona AS nombreCliente, p.apellidoPersona AS apellidosCliente, p.telefono, p.edad, p.genero, u.username 
                                  FROM persona p 
                                  INNER JOIN usuarios u ON p.idUsuario = u.idUsuario 
                                  INNER JOIN rol r ON u.id_Rol = r.id_Rol 
                                  WHERE (u.id_Rol = 3 OR u.id_Rol = 2) $condicion 
                                  ORDER BY p.idPersona ASC");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function dataProveedor(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE idEmpresa = " . (int)$idEmpresa . " " : "";
        $res = $this->con->query("SELECT * FROM proveedor $condicion ORDER BY idProveedor ASC");
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