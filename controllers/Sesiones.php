<?php 
session_start();
if(isset($_REQUEST["c"])) {
	session_destroy();
	header("Location:controlUser.php");
}
elseif(isset($_SESSION["s1"])){
	$correo=$_SESSION["s1"];



}elseif(isset($_SESSION["c1"])){
   $cliente = $_SESSION["c1"];

}
else{
	header("Location:controlUser.php");
}

 ?>