<?php 
include '../db/conexion.php';
include 'Cliente.php';

class ModelClienteIn extends Conexion
{
	
	function __construct()
	{
	parent::__construct();	
	}
	function getClienteIndividual(){
		$res=$this->con->query("select * from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario where username='sindy@gmail.com';");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;



	}




}








 ?>

