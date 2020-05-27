<?php
class Proveedor{

    private $idProveedor;
    private $nombreProveedor;
    private $contacto;
    private $nit;
    private $correoP;
    private $telefono;

    public function Proveedor(){

    }

    //metodos set y get

    public function getIdProveedor(){
        return $this->idProveedor;
    }

    public function setIdProveedor($idProveedor){
        $this->idProveedor=$idProveedor;
    }

    /////

    public function getNombreProveedor(){
        return $this->nombreProveedor;
    }

    public function setNombreProveedor($nombreProveedor){
        $this->nombreProveedor=$nombreProveedor;
    }

    /////

    public function getContacto(){
        return $this->contacto;
    }

    public function setContacto($contacto){
        $this->contacto=$contacto;
    }

    /////
    public function getNit(){
        return $this->nit;
    }

    public function setNit($nit){
        $this->nit=$nit;
    }

    /////
    public function getCorreoP(){
        return $this->correoP;
    }

    public function setCorreoP($correoP){
        $this->correoP=$correoP;
    }

    ////
    
    public function getTelefono(){
        return $this->telefono;
    }

    public function setTelefono($telefono){
        $this->telefono=$telefono;
    }




}

?>