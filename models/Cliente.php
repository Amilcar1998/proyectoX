<?php

class Cliente
{
    private $idCliente;
    private $nombreCi;
    private $apellidos;
    Private $telefono;

    /**
     * @return mixed
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * @param mixed $idCliente
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }

    /**
     * @return mixed
     */
    public function getNombreCi()
    {
        return $this->nombreCi;
    }

    /**
     * @param mixed $nombreCi
     */
    public function setNombreCi($nombreCi)
    {
        $this->nombreCi = $nombreCi;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * @param mixed $edad
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;
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
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
    private $edad;
    private $genero;
    private $idUsuario;

    /**
     * Cliente constructor.
     * @param $idCliente
     * @param $nombreCi
     * @param $apellidos
     * @param $telefono
     * @param $edad
     * @param $genero
     * @param $idUsuario
     */
    public function __construct($idCliente, $nombreCi, $apellidos, $telefono, $edad, $genero, $idUsuario)
    {
        $this->idCliente = $idCliente;
        $this->nombreCi = $nombreCi;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->edad = $edad;
        $this->genero = $genero;
        $this->idUsuario = $idUsuario;
    }


}









 ?>