<?php 
include "../db/conexion.php";
include "../models/Cliente.php";
include '../models/Usuario.php';

class ClienteModel extends conexion
{
	
	function __construct()
	{
		parent::__construct();
	}

function getAddUs($u){
             $a="";
             $b=$u->getUsername();
             $c=$u->getPass();
             $d=$u->getIdRol();
             $para=$this->con->prepare("insert into usuarios(idUsuario,username,pass,id_Rol) values(?,?,?,?)");
             $para->bind_param('ssss',$a,$b,$c,$d);
             $para->execute();    
       }



 	function getCliente(){
        $res=$this->con->query("SELECT c.idCliente, c.NombreCliente, c.apellidosCliente, c.telefono, c.edad, c.genero, c.idUsuario, u.username FROM cliente c INNER JOIN usuarios u ON c.idUsuario=u.idUsuario");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $e=new Cliente($row["idCliente"],$row["NombreCliente"],$row["apellidosCliente"],$row["telefono"],$row["edad"],$row["genero"],$row["idUsuario"],$row["username"]);
            $r[]=$e;
        }
        return $r;
    
 	}
  function getSessionEmp(){
         $correo=$_SESSION["s1"];
        $res=$this->con->query("select nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
      }
	    function getUser(){
        $res=$this->con->query("select * from usuarios where id_Rol ='2'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
       }
function agregarCliente($e){
       $a="";
       $b=$e->getNombreCi();
       $c=$e->getApellidos();
       $d=$e->getTelefono();
       $f=$e->getEdad();
       $g=$e->getGenero();
       $h=$e->getUsuarioC();
       $res=$this->con->prepare("insert into cliente(idCliente,NombreCliente,apellidosCliente,telefono,edad,genero,idUsuario) values(?,?,?,?,?,?,?)");
       $res->bind_param("ssssssi",$a,$b,$c,$d,$f,$g,$h);
       $res->execute();

      }
function modificarCliente($e){
       $a=$e->getNombreCi();
       $b=$e->getApellidos();
       $c=$e->getTelefono();
       $d=$e->getEdad();
       $f=$e->getGenero();
       $g=$e->getUsuarioC();
       $h=$e->getIdCliente();
       $res=$this->con->prepare("UPDATE `cliente` SET NombreCliente=?,apellidosCliente=?,telefono=?,edad=?,genero=?,idUsuario=? WHERE `cliente`.`idCliente` = ?");
       $res->bind_param("ssssssi",$a,$b,$c,$d,$f,$g,$h);
       $res->execute();

       }
       function eliminarCliente($e){
         $a=$e->getIdCliente();
         $res=$this->con->prepare("DELETE FROM cliente WHERE idCliente=?");
         $res->bind_param('i',$a);
         $res->execute();
       }
   


}



 ?>