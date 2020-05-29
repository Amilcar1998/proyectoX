<?php 
include '../db/conexion.php';
class ReportModel extends Conexion
{
	
	function __construct()
	{
		parent::__construct();
	}
	function dataEmpleados(){
		$res=$this->con->query("select idEmpleado,nombreEmp,apellido,genero,nombrePuesto,username from empleado inner join puesto on empleado.idPuesto=puesto.idPuesto inner join usuarios on empleado.idUsuario=usuarios.idUsuario");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }
    function dataClientes(){
		$res=$this->con->query("select idCliente,NombreCliente,apellidosCliente,telefono,edad,genero,username from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }
        function dataProveedor(){
		$res=$this->con->query("select * from Proveedor");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
    }




}

 ?>