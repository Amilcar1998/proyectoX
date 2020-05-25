<?php
include '../models/ModelUser.php';

$usuario = new ModelUser();



$user =$usuario->getUsuario();
include "../views/vistaUsuarios.php";












?>