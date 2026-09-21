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

    public function getNombrePersona()
    {
        return $this->nombreCi;
    }

    public function setNombreCi($nombreCi)
    {
        $this->nombreCi = $nombreCi;
    }

    public function setNombrePersona($nombrePersona)
    {
        $this->nombreCi = $nombrePersona;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getApellidoPersona()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function setApellidoPersona($apellidoPersona)
    {
        $this->apellidos = $apellidoPersona;
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
    public function getUsuarioC()
    {
        return $this->usuarioC;
    }

    /**
     * @param mixed $usuarioC
     */
    public function setUsuarioC($usuarioC)
    {
        $this->usuarioC = $usuarioC;
    }
    private $edad;
    private $genero;
    private $usuarioC;
    private $username;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function __construct($idCliente, $nombreCi, $apellidos, $telefono, $edad, $genero, $usuarioC, $username = null)
    {
        $this->idCliente = $idCliente;
        $this->nombreCi = $nombreCi;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->edad = $edad;
        $this->genero = $genero;
        $this->usuarioC = $usuarioC;
        $this->username = $username;
    }


}









 ?>