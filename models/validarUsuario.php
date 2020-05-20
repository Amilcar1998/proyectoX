<?php
include "../db/conexion.php";

class validarUsuario extends conexion
{
    public function __construct()
    {
        parent::__construct();
    }
    function validarUsuario($login, $pass){
        $para =$this->con->prepare("select * from where login=? and pass=?");
        $para->bind_praram("ss",$a,$b);
        $a=$login;
        $b=sha1($pass);
        $para->execute();
        while($para->fetch()){
            return 1;
        }
        return 0;


    }

}