<?php
include "../models/validarUsuario.php"

$obUser = new UsuarioModel();
if(isset($_REQUEST["validar"])){
    $r=$obUser->validarUsuario($_REQUEST["login"],$_REQUEST["pass"]);
    if($r==1){
        session_start();
        $_SESSION["s1"]=$_REQUEST["login"];
        print("hola");
    }else{
        echo"<script>alert('hola te has equivocado en algo');</script>";
    }
}

require "views/vistaCliente.php";

?>