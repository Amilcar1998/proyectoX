<?php

class Usuario{

    private $idUsuario;
    private $username;

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
    public function getIdRol()
    {
        return $this->id_Rol;
    }

    /**
     * @param mixed $id_Rol
     */
    public function setIdRol($id_Rol)
    {
        $this->id_Rol = $id_Rol;
    }
    private $pass;
    private $id_Rol;

    /**
     * Usuario constructor.
     * @param $idUsuario
     * @param $username
     * @param $pass
     * @param $id_Rol
     */
    public function __construct($idUsuario, $username, $pass, $id_Rol)
    {
        $this->idUsuario = $idUsuario;
        $this->username = $username;
        $this->pass = $pass;
        $this->id_Rol = $id_Rol;
    }


}

?>