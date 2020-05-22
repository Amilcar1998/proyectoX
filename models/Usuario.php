<?php

class Usuario{

    private $idUsuario;
    private $username;
    private $pass;
    private $Rol;

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

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return mixed
     */
    public function getRol()
    {
        return $this->Rol;
    }

    /**
     * @param mixed $Rol
     */
    public function setRol($Rol)
    {
        $this->Rol = $Rol;
    }

    /**
     * Usuario constructor.
     * @param $idUsuario
     * @param $username
     * @param $pass
     * @param $Rol
     */
    public function __construct($idUsuario, $username, $pass, $Rol)
    {
        $this->idUsuario = $idUsuario;
        $this->username = $username;
        $this->pass = $pass;
        $this->Rol = $Rol;
    }
}

?>