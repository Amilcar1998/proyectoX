<?php

include "../db/conexion.php";
include 'Proveedor.php';
class ModelProveedor extends Conexion {


    public function __construct(){
        parent::__construct();

    }

    public function getTabla(){
        $res=$this->con->query("select * from proveedor");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
        return $r;

    }
       

    public function insertar($p){      
        $res=$this->con->prepare("insert into proveedor(idProveedor,nombreProveedor,contacto,NIT,correoP,telefono) values(?,?,?,?,?,?)");
        $res->bind_param('ssssss',$a,$b,$c,$d,$e,$f);
        $a="";
        $b=$p->getNombreProveedor();
        $c=$p->getContacto();
        $d=$p->getNit();
        $e=$p->getCorreoP();
        $f=$p->getTelefono();
        $res->execute();
    }


    public function eliminar($p){
       $res=$this->con->prepare("DELETE FROM `proveedor` WHERE `proveedor`.`idProveedor`=?");
        $res->bind_param('s',$a);
        $a=$p->getIdProveedor();
        $res->execute();
    }

    public function modificar($p){
        var_dump($p);
        $prueba = $p->getIdProveedor();
        var_dump($prueba);
     $res=$this->con->prepare("update proveedor set nombreProveedor=?,contacto=?,NIT=?,correoP=?,telefono=? where idProveedor=?");
        $res->bind_param('ssssss',$a,$b,$c,$d,$e,$f);
        $a=$p->getNombreProveedor();
        $b=$p->getContacto();
        $c=$p->getNit();
        $d=$p->getCorreoP();
        $e=$p->getTelefono();
        $f=$p->getIdProveedor();
        $res->execute();

       
    }
    
   
     function getSessionEmp(){
         $correo=$_SESSION["s1"];
        $res=$this->con->query("select nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $r[]=$row;
        }
          foreach ($r as $key) {
         $nombres = $key['nombreEmp'].'&nbsp;&nbsp;'.$key['apellido'];
              
           }
           return $nombres;


      }
    

}
    


?>