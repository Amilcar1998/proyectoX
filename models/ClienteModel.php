<?php 
include "../db/conexion.php";
include "Cliente.php";

class ClienteModel extends conexion
{
	
	function __construct()
	{
		parent::__construct();
	}
	function getCliente(){
        $res=$this->con->query("select * from cliente");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $e=new Cliente($row["idCliente"],$row["NombreCliente"],$row["apellidosCliente"],$row["telefono"],$row["edad"],$row["genero"],$row["idUsuario"]);
            $r[]=$e;
        }
        return $r;
    
	}
}



 ?>