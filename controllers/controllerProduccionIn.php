<?php 
include '../models/ModelProduccion.php';
$prod=new ModelProduccion();
$data=$prod->getProduccion();


include '../views/vistaProduccion.php';
 ?>
 