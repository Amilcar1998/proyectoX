<?php


class Empleado
{
private $idEmpleado;

    /**
     * @return mixed
     */
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    /**
     * @param mixed $idEmpleado
     */
    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * @param mixed $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * @return mixed
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * @param mixed $genero
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    /**
     * @return mixed
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    private $nombre;
    private $apellido;
    private $genero;
    private $cargo;
    private $username;
    private $idPuesto;
    private $idUsuario;
    private $id_Rol;
    private $nombreRol;
    private $activo;

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    public function getIdPuesto()
    {
        return $this->idPuesto;
    }

    public function setIdPuesto($idPuesto)
    {
        $this->idPuesto = $idPuesto;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getIdRol()
    {
        return $this->id_Rol;
    }

    public function setIdRol($id_Rol)
    {
        $this->id_Rol = $id_Rol;
    }

    public function getNombreRol()
    {
        return $this->nombreRol;
    }

    public function setNombreRol($nombreRol)
    {
        $this->nombreRol = $nombreRol;
    }

    public function __construct($idEmpleado, $nombre, $apellido, $genero, $cargo, $username, $idPuesto = '', $idUsuario = '', $id_Rol = 2, $nombreRol = 'Empleado', $activo = 1)
    {
        $this->idEmpleado = $idEmpleado;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->genero = $genero;
        $this->cargo = $cargo;
        $this->username = $username;
        $this->idPuesto = $idPuesto;
        $this->idUsuario = $idUsuario;
        $this->id_Rol = $id_Rol;
        $this->nombreRol = $nombreRol;
        $this->activo = $activo;
    }

}