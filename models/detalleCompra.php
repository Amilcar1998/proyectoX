<?php
class DetalleCompra{

    private $idDetalleCompra;
    private $idMateriaPrima;
    private $cantidadMP;
    private $precioMP;
    private $idFacturaMP;
    

    public function DetalleCompra(){

    }

    //metodos set y get

    public function getIdDetalleCompra(){
        return $this->idDetalleCompra;
    }

    public function setIdDetalleCompra($idDetalleCompra){
        $this->idDetalleCompra=$idDetalleCompra;
    }

    /////

    public function getIdMateriaPrima(){
        return $this->idMAteriaPrima;
    }

    public function setIdMateriaPrima($idMateriaPrima){
        $this->idMateriaPrima=$idMateriaPrima;
    }

    /////

    public function getCantidadMP(){
        return $this->cantidadMP;
    }

    public function setCantidadMP($cantidadMP){
        $this->cantidadMP=$cantidadMP;
    }

    /////
    public function getPrecioMP(){
        return $this->precioMP;
    }

    public function setPrecioMP($precioMP){
        $this->precioMP=$precioMP;
    }

    /////
    public function getIdFacturaMP(){
        return $this->idFacturaMP;
    }

    public function setIdFacturaMP($idFacturaMP){
        $this->idFacturaMP=$idFacturaMP;
    }

    ////
    
    
}

?>