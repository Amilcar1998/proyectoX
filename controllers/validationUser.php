<?php
include "../models/validarUsuario.php";

$obUser = new validarUsuario();
if(isset($_REQUEST["validar"])){
	$login = $_REQUEST["login"];
	$pass=$_REQUEST["pass"];
    $r=$obUser->validaUsuario($login,$pass);
    
    if($r==1){
        session_start();
        $_SESSION["s1"]=$_REQUEST["login"];
        header("Location:controllerEmpleado.php");
    }elseif ($r==2) {
        //aqui pondre el dato del cliente
        
    }
    elseif($r==0){
    	echo "<script>alert('Usuario o contraseña no validos');</script>";
    }
}


require "../views/login.php";

?>