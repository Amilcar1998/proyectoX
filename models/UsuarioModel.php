<?php
include "../db/conexion.php";
include "Usuario.php";

class UsuarioModel extends conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuario(){
        $res=$this->con->query("select * from usuario");
        $r=array();
        while($row=$res->fetch_assoc()) {
            $e = new Empleado($row["idUsuario"], $row["username"], $row["password"], row["id_Rol"]);
            $r[] = $e;
        }
    }



}