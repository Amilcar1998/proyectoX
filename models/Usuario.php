<?php

class Usuario{

    private $idU;
    private $username;
    private $pass;

    /**
     * @return mixed
     */
    public function getIdU()
    {
        return $this->idU;
    }

    /**
     * @param mixed $idU
     */
    public function setIdU($idU)
    {
        $this->idU = $idU;
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
        return $this->rol;
    }

    /**
     * @param mixed $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }
    private $rol;

    /**
     * Usuario constructor.
     * @param $idU
     * @param $username
     * @param $pass
     * @param $rol
     */
    public function __construct($idU, $username, $pass, $rol)
    {
        $this->idU = $idU;
        $this->username = $username;
        $this->pass = $pass;
        $this->rol = $rol;
    }


}

?>