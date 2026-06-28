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
                 $r[]=$row;
             }
             return $r;
      }
      function agregarUser($u){
         $a='';
         $b=$u->getUsername();
         $c=$u->getPass();
         $d=$u->getIdRol();
         $res=$this->con->prepare("insert into usuarios(idUsuario,username,pass,id_Rol) values(?,?,?,?)");
         $res->bind_param("ssss",$a,$b,$c,$d);
         $res->execute();
      }
      function modificarUser($u){
         $a=$u->getUsername();
         $b=$u->getPass();
         $c=$u->getIdRol();
         $d=$u->getIdUsuario();
         $res=$this->con->prepare("UPDATE `usuarios` SET idUsuario =?,username=?,pass=?,id_Rol=? WHERE `usuarios`.`idUsuario` = ?");
         $res->bind_param("ssssi",$d,$a,$b,$c,$d);
         $res->execute();
      }
      function eliminarUser($u){
         $a=$u->getIdUsuario();
         $res=$this->con->prepare("delete from usuarios WHERE `usuarios`.`idUsuario` = ?");
         $res->bind_param("i",$a);
         $res->execute();
      }












}