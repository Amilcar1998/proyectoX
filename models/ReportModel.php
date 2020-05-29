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




}

 ?>