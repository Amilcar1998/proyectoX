<?php
class Proveedor
{

    private $idProveedor;
    private $nombreProveedor;
    private $contacto;
    private $nit;
    private $correoP;
    private $telefono;

    /**
     * @return mixed
     */
    public function getIdProveedor()
    {
        return $this->idProveedor;
    }

    /**
     * @param mixed $idProveedor
     */
    public function setIdProveedor($idProveedor)
    {
        $this->idProveedor = $idProveedor;
    }

    /**
     * @return mixed
     */
    public function getNombreProveedor()
    {
        return $this->nombreProveedor;
    }

    /**
     * @param mixed $nombreProveedor
     */
    public function setNombreProveedor($nombreProveedor)
    {
        $this->nombreProveedor = $nombreProveedor;
    }

    /**
     * @return mixed
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * @param mixed $contacto
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit)
    {
        $this->nit = $nit;
    }

    /**
     * @return mixed
     */
    public function getCorreoP()
    {
        return $this->correoP;
    }

    /**
     * @param mixed $correoP
     */
    public function setCorreoP($correoP)
    {
        $this->correoP = $correoP;
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
     * Proveedor constructor.
     * @param $idProveedor
     * @param $nombreProveedor
     * @param $contacto
     * @param $nit
     * @param $correoP
     * @param $telefono
     */
    public function __construct($idProveedor, $nombreProveedor, $contacto, $nit, $correoP, $telefono)
    {
        $this->idProveedor = $idProveedor;
        $this->nombreProveedor = $nombreProveedor;
        $this->contacto = $contacto;
        $this->nit = $nit;
        $this->correoP = $correoP;
        $this->telefono = $telefono;
    }


}
?>