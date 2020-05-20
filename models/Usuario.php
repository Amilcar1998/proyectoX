<?php

class Usuario{

    private $idUsuario;
    private $nombreU;
    private $pass;

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getNombreU()
    {
        return $this->nombreU;
    }

    public function setNombreU($nombreU)
    {
        $this->nombreU = $nombreU;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }
    private $rol;


    public function __construct($idUsuario, $nombreU, $pass, $rol)
    {
        $this->idUsuario = $idUsuario;
        $this->nombreU = $nombreU;
        $this->pass = $pass;
        $this->rol = $rol;
    }


}

?>