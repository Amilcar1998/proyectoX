<?php 
include "../models/ModelUser.php";

$objU= new ModelUser();




$dataU=$objU->getUsuario();

include "../views/vistaUsuarios.php";

 ?>