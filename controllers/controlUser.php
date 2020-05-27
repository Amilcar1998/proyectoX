<?php 
include "../models/UsuarioModel.php";


$obUser = new UsuarioModel();
if(isset($_REQUEST["validar"])){
	$login = $_REQUEST["login"];
	$pass=$_REQUEST["pass"];
    $r=$obUser->validarUsuario($login,$pass);
    
    if($r==1){
        session_start();
        $_SESSION["s1"]=$_REQUEST["login"];
        header("Location:controllerEmpleado.php");
    }elseif ($r==2) {
        session_start();
        $_SESSION["c1"]=$_REQUEST["login"];
        echo("ERES CLIENTE");
    }
    elseif($r==0){
    	echo "<script>alert('Usuario o contraseña no validos');</script>";
    }
}

include "../views/login.php";




 ?>