<?php
class Inventario{

    private $idInventario;
    private $idMateriaPrima;
    private $Existencias;

    public function Inventario($idInventario,$idMateriaPrima,$Existencias){
        $this->idInventario=$idInventario;
        $this->idMateriaPrima=$idMateriaPrima;
        $this->Existencias=$Existencias;
    }

    //metodos set y get

    public function getIdInventario(){
        return $this->idInventario;
    }

    public function setIdInventario($idInventario){
        $this->idInventario=$idInventario;
    }

    /////

    public function getIdMateriaPrima(){
        return $this->idMateriaPrima;
    }

    public function setIdMateriaPrima($idMateriaPrima){
        $this->idMateriaPrima=$idMateriaPrima;
    }

    /////

    public function getExistencias(){
        return $this->Existencias;
    }

    public function setExistencias($Existencias){
        $this->Existencias=$Existencias;
    }

    /////

    /////

}

?>