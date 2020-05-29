<?php
include "../db/conexion.php";
include "Empleado.php";
include "Usuario.php";

class empleadoModel extends conexion
{
    public function __construct()
    {
       parent::__construct();
    }
    function getEmpleado(){
        $res=$this->con->query("select * from empleado");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $e=new Empleado($row["idEmpleado"],$row["nombreEmp"],$row["apellido"],$row["genero"],$row["idPuesto"],$row["idUsuario"]);
            $r[]=$e;
        }
        return $r;
    } 
function getCargo(){
        $res=$this->con->query("select * from puesto");
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

    function getUser($user){
        $res=$this->con->query("select idUsuario from usuarios where username='$user'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
}


        function insertarUsuario($u){
            $para=$this->con->prepare("insert into usuarios(idUsuario,username,pass,id_Rol) values(?,?,?,?)");
            $para->bind_param('ssss',$a,$b,$c,$d);
            $a="";
            $b=$u->getUsername();
            $c=$u->getPass();
            $d=$u->getIdRol();
            $para->execute();
        }    
        function eliminarUsuario($usuario){
         $para=$this->con->prepare("delete from usuarios where idUsuario=?");
            $para->bind_param('s',$a);
            $a=$usuario;
            $para->execute();
        }
        function obtenerID($emp){
        $res=$this->con->query("select usuarios.idUsuario from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where idEmpleado =$emp;");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

        }

        function insertarEmpleado($e){
            $para=$this->con->prepare("insert into empleado(idEmpleado,nombreEmp,apellido,genero,idPuesto,idUsuario) values(?,?,?,?,?,?)");
            $para->bind_param('ssssss',$a,$b,$c,$d,$f,$g);
            $a='';
            $b=$e->getNombre();
            $c=$e->getApellido();
            $d=$e->getGenero();
            $f=$e->getCargo();
            $g=$e->getUsername();
            $para->execute();
        }

    function modificarEmpleado($e){
     $para=$this->con->prepare("UPDATE `empleado` SET nombreEmp =?,apellido=?,genero=?,idPuesto=?,idUsuario=? WHERE `empleado`.`idEmpleado` = ?");
            $para->bind_param('ssssss',$a,$b,$c,$d,$f,$g);
            $a=$e->getNombre();
            $b=$e->getApellido();
            $c=$e->getGenero();
            $d=$e->getCargo();
            $f=$e->getUsername();
            $g=$e->getIdEmpleado();

            $para->execute();

        }
        function eliminarEmpleado($e){
            $para=$this->con->prepare("DELETE FROM empleado WHERE idEmpleado=?");
            $para->bind_param('s',$a);
            $a=$e->getIdEmpleado();
            $para->execute();

        }




}