<?php
include "../db/conexion.php";
include "Usuario.php";

class UsuarioModel extends conexion
{
    public function __construct()
    {
        parent::__construct();
    }
    function validarUsuario($login,$pass){
            $para =$this->con->prepare("select * from usuarios where username=? and pass=? and id_Rol='1'");
            $para->bind_param("ss",$a,$b);
            $a=$login;
            $b=sha1($pass);
            $para->execute();
            if($para->fetch()) {
                return 1;
            }elseif (!$para->fetch()) {
                    $para =$this->con->prepare("select * from usuarios where username=? and pass=? and id_Rol='2'");
                    $para->bind_param("ss",$a,$b);
                    $a=$login;
                    $b=sha1($pass);
                    $para->execute();
                        return 2;
            }else{
                return 0;
            }

        

        }
}
