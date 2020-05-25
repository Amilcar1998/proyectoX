<?php 
include '../db/conexion.php';
include "Usuario.php";

/**
 * 
 */
class ModelUser extends conexion
{
	
	function __construct()
	{
	parent::__construct();	
	}
	function getUsuario(){
		$res=$this->con->query("select * from usuarios");
        $r=array();
        while($row=$res->fetch_assoc()) {
        	$e=new Usuario($row["idUsuario"],$row["username"],$row["pass"],$row["id_Rol"]);
            $r[]=$row;
        }
        return $r;

	}






}










 ?>