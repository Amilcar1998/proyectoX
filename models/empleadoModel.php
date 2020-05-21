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
            $e=new Empleado($row["idEmpleado"],$row["nombreEmp"],$row["apellido"],$row["correo"],$row["genero"],$row["id_Rol"],$row["pass"],$row["idPuesto"]);
            $r[]=$e;
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
function getCargo(){
        $res=$this->con->query("select * from puesto");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
}
    
        function insertarEmpleado($e){
            $para=$this->con->prepare("insert into empleado(idEmpleado,nombreEmp,apellido,genero,correo,id_Rol,pass,idPuesto) values(?,?,?,?,?,?,?,?)");
            $para->bind_param('ssssssss',$a,$b,$c,$d,$f,$g,$h,$i);
            $a='';
            $b=$e->getNombre();
            $c=$e->getApellido();
            $d=$e->getGenero();
            $f=$e->getCorreo();
            $g=$e->getIdRol();
            $h=$e->getPass(); 
            $i=$e->getCargo();
            $para->execute();
        }

    function modificarEmpleado($e){
     $para=$this->con->prepare("UPDATE `empleado` SET nombreEmp =?,apellido=?,correo=?,genero=?,id_Rol,pass,idPuesto WHERE `empleado`.`idEmpleado` = ?");
            $para->bind_param('ssssssss',$a,$b,$c,$d,$f,$g,$h,$i);
            $a=$e->getNombre();
            $b=$e->getApellido();
            $c=$e->getGenero();
            $d=$e->getCorreo();
            $f=$e->getIdRol();
            $g=$e->getPass(); 
            $h=$e->getCargo();
            $i=$e->getIdEmpleado();
            $para->execute();

        }
        function eliminarEmpleado($e){
            $para=$this->con->prepare("DELETE FROM empleado WHERE idEmpleado=?");
            $para->bind_param('s',$a);
            $a=$e->getIdEmpleado();
            $para->execute();

        }




}