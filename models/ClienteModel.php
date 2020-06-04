<?php 
include "../db/conexion.php";

include "Cliente.php";
include 'Usuario.php';

class ClienteModel extends conexion
{
	
	function __construct()
	{
		parent::__construct();
	}

  function getAddUs($u){
            $para=$this->con->prepare("insert into usuarios(idUsuario,username,pass,id_Rol) values(?,?,?,?)");
            $para->bind_param('ssss',$a,$b,$c,$d);
            $a="";
            $b=$u->getUsername();
            $c=$u->getPass();
            $d=$u->getIdRol();
            $para->execute();    
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
      $res=$this->con->prepare("insert into cliente(idCliente,NombreCliente,apellidosCliente,telefono,edad,genero,idUsuario) values(?,?,?,?,?,?,?)");
      $res->bind_param("sssssss",$a,$b,$c,$d,$f,$g,$h);
      $a="";
      $b=$e->getNombreCi();
      $c=$e->getApellidos();
      $d=$e->getTelefono();
      $f=$e->getEdad();
      $g=$e->getGenero();
      $h=$e->getUsuarioC();
      $res->execute();

     }
     function modificarCliente($e){
      $res=$this->con->prepare("UPDATE `cliente` SET NombreCliente=?,apellidosCliente=?,telefono=?,edad=?,genero=?,idUsuario=? WHERE `cliente`.`idCliente` = ?");
      $res->bind_param("sssssss",$a,$b,$c,$d,$f,$g,$h);
      $a=$e->getNombreCi();
      $b=$e->getApellidos();
      $c=$e->getTelefono();
      $d=$e->getEdad();
      $f=$e->getGenero();
      $g=$e->getUsuarioC();
      $h=$e->getIdCliente();
      $res->execute();

      }
      function eliminarCliente($e){
        $res=$this->con->prepare("DELETE FROM cliente WHERE idCliente=?");
        $res->bind_param('s',$a);
        $a=$e->getIdCliente();
        $res->execute();
      }
   


}



 ?>