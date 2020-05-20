<?php


class Empleado
{
private $idEmpleado;
private $nombre;
private $apellido;
private $correo;
private $genero;
private $idRol;
private $pass;
private $cargo;

    
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }
    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function getCorreo()
    {
        return $this->correo;
    }
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }
    public function getGenero()
    {
        return $this->genero;
    }
    public function setGenero($genero)
    {
        $this->genero = $genero;
    }
    public function getIdRol()
    {
        return $this->idRol;
    }
    public function setIdRol($idRol)
    {
        $this->idRol = $idRol;
    }
    public function getPass()
    {
        return $this->pass;
    }
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
    public function getCargo()
    {
        return $this->cargo;
    }
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    public function __construct($idEmpleado, $nombre, $apellido, $correo, $genero, $idRol, $pass, $cargo)
    {
        $this->idEmpleado = $idEmpleado;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->genero = $genero;
        $this->idRol = $idRol;
        $this->pass = $pass;
        $this->cargo = $cargo;
    }


}