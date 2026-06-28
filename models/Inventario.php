<?php
class Inventario{

    private $idInventario;
    private $idMateriaPrima;
    private $Existencias;
    private $idDetalleCompra;

    public function Inventario(){

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
    public function getIdDetalleCompra(){
        return $this->idDetalleCompra;
    }

    public function setIdDetalleCompra($idDetalleCompra){
        $this->idDetalleCompra=$idDetalleCompra;
    }

    /////

}

?>