<?php
class MateriaPrima{

    private $idMateriaPrima;
    private $nombreMP;

    public function MateriaPrima(){

    }

    //metodos set y get

    public function getIdMateriaPrima(){
        return $this->idMateriaPrima;
    }

    public function setIdMateriaPrima($idMateriaPrima){
        $this->idMateriaPrima=$idMateriaPrima;
    }                 

    /*////////////////////////////////*

    public function getIdProveedor(){
        return $this->idProveedor;
    }

    public function setIdProveedor($idProveedor){
        $this->idProveedor=$idProveedor;
    }

    */////

    public function getNombreMP(){
        return $this->nombreMP;
    }

    public function setNombreMP($nombreMP){
        $this->nombreMP=$nombreMP;
    }

}

?>