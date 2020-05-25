<?php
include "../db/conexion.php";
include "Empleado.php";

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

    function getUser(){
        $res=$this->con->query("select * from usuarios");
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