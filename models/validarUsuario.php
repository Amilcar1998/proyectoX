<?php
include "../db/conexion.php";


class validarUsuario extends conexion
{
    public function __construct()
    {
        parent::__construct();
    }
  function getUser(){
            $res=$this->con->query("select * from usuarios");
            $r=array();
            while($row=$res->fetch_assoc()) {
                $u = new Usuario($row["idUsuario"],$row["username"],$row["pass"],$row["id_Rol"]);
                $r[]=$row;
            }
            return $r;
     }
     function agregarUser(){
        $res=$this->con->prepare("insert into usuarios(idUsuario,username,pass,id_Rol) values(?,?,?,?)");
        $res->bind_param("ssss",$a,$b,$c,$d);
        $a='';
        $b=$u->getUsername();
        $c=$u->getPass();
        $d=$u->getRol();
        $res->execute();
     }
     function modificarUser(){
        $res=$this->con->prepare("UPDATE `usuarios` SET idUsuario =?,username=?,pass=?,id_Rol=? WHERE `usuarios`.`idUsuario` = ?");
        $res->bind_param("ssss",$a,$b,$c,$d);
        $a=$u->getUsername();
        $b=$u->getPass();
        $c=$u->getRol();
        $d=$u->getIdUsuario();
        $res->execute();
     }
     function eliminarUser(){
        $res=$this->con->prepare("delete from usuarios WHERE `usuarios`.`idUsuario` = ?");
        $res->bind_param("ssss",$a,$b,$c,$d);
        $d=$u->getIdUsuario();
        $res->execute();
     }












}