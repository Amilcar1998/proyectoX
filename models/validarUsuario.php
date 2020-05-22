<?php
include "../db/conexion.php";


class validarUsuario extends conexion
{
    public function __construct()
    {
        parent::__construct();
    }
    function validarUsuario($username, $pass){
        $para =$this->con->prepare("select * from empleado where correo=? and pass=?");
        $para->bind_praram("ss",$a,$b);
        $a=$username;
        $b=sha1($pass);
        $para->execute();
        while($para->fetch()){
            return 1;
        }
        return 0;


    }

}