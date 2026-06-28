<?php
include "../db/conexion.php";
include "../models/Empleado.php";
include "../models/Usuario.php";

if (!class_exists('empleadoModel')) {
    class empleadoModel extends Conexion
    {
        public function __construct()
        {
           parent::__construct();
    }
    function getEmpleado(){
        $res=$this->con->query("SELECT e.idEmpleado, e.nombreEmp, e.apellido, e.genero, p.nombrePuesto, u.username FROM empleado e INNER JOIN puesto p ON e.idPuesto=p.idPuesto INNER JOIN usuarios u ON e.idUsuario=u.idUsuario");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $e=new Empleado($row["idEmpleado"],$row["nombreEmp"],$row["apellido"],$row["genero"],$row["nombrePuesto"],$row["username"]);
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
function getUsuarios(){
        $res=$this->con->query("select * from usuarios");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;
}
    function getSessionEmp($correo = null){
        if ($correo === null) {
            $correo = $_SESSION["s1"] ?? '';
        }
        $correo = $this->con->real_escape_string($correo);
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
             $a="";
             $b=$u->getUsername();
             $c=$u->getPass();
             $d=$u->getIdRol();
             $para=$this->con->prepare("insert into usuarios(idUsuario,username,pass,id_Rol) values(?,?,?,?)");
             $para->bind_param('ssss',$a,$b,$c,$d);
             $para->execute();
         }    
         function eliminarUsuario($usuario){
             $a=$usuario;
             $para=$this->con->prepare("delete from usuarios where idUsuario=?");
             $para->bind_param('s',$a);
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
             $a='';
             $b=$e->getNombre();
             $c=$e->getApellido();
             $d=$e->getGenero();
             $f=$e->getCargo();
             $g=$e->getUsername();
             $para=$this->con->prepare("insert into empleado(idEmpleado,nombreEmp,apellido,genero,idPuesto,idUsuario) values(?,?,?,?,?,?)");
             $para->bind_param('ssssss',$a,$b,$c,$d,$f,$g);
             $para->execute();
         }

     function modificarEmpleado($e){
      $a=$e->getNombre();
             $b=$e->getApellido();
             $c=$e->getGenero();
             $d=$e->getCargo();
             $f=$e->getUsername();
             $g=$e->getIdEmpleado();
             $para=$this->con->prepare("UPDATE `empleado` SET nombreEmp =?,apellido=?,genero=?,idPuesto=?,idUsuario=? WHERE `empleado`.`idEmpleado` = ?");
             $para->bind_param('ssssss',$a,$b,$c,$d,$f,$g);
             $para->execute();

         }
         function eliminarEmpleado($e){
             $a=$e->getIdEmpleado();
             $para=$this->con->prepare("DELETE FROM empleado WHERE idEmpleado=?");
             $para->bind_param('s',$a);
             $para->execute();

         }
    


    }
}