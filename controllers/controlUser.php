<?php 
require_once __DIR__ . '/../models/UsuarioModel.php';


$obUser = new UsuarioModel();

if(isset($_REQUEST["validar"])){
	$login = $_REQUEST["login"];
	$pass=$_REQUEST["pass"];
    $r=$obUser->validarUsuario($login,$pass);

    
    if($r==1){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION["s1"]=$_REQUEST["login"];
        header("Location:controllerEmpleado.php");
    }elseif($r==2){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION["s2"]=$_REQUEST["login"];
       header("Location:controllerPedidosIn.php");
       
    }
    elseif ($r==3) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION["c1"]=$_REQUEST["login"];
        header("Location:controllerIndividualC.php");
    }
    elseif($r==0){
    	echo "<script>alert('Usuario o contraseña no validos');</script>";
    }
}

include "../views/login.php";




 ?>