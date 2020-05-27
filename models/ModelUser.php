<?php 
include '../db/conexion.php';
include "Usuario.php";

class ModelUser extends conexion
{
	
	function __construct()
	{
	parent::__construct();	
	}
	function getUsuario(){
		$res=$this->con->query("select usuarios.idUsuario,nombreEmp,apellido,username,pass,id_Rol from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

	}
	function getUsuarioCli(){
		$res=$this->con->query("select usuarios.idUsuario,nombreCliente,username,pass,id_Rol from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario where id_Rol=1");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

    } 
    function getUsuarios(){
		$res=$this->con->query("select idUsuario,username,pass,nombreRol,usuarios.id_Rol from usuarios inner join rol where usuarios.id_Rol=rol.id_Rol");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
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
    function getRol(){
        $res=$this->con->query("select * from rol");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

    }
    function modificarUsuario($u){
      $res=$this->con->prepare("UPDATE `usuarios` SET username=?,pass=?,id_Rol=? WHERE `usuarios`.`idUsuario` = ?");
      $res->bind_param("ssss",$a,$b,$c,$d);
      $a=$u->getUsername();
      $b=$u->getPass();
      $c=$u->getIdRol();-
      $d=$u->getIdUsuario();

      $res->execute();
    }

}








 ?>