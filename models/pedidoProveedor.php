<?php
class PedidoProveedor{

    private $idPedido = 0;
    private $idProveedor;
    private $idEmpleado;
    private $idMateriaPrima;
    private $fecha;
    private $cantidadMP;
    private $monto;
    private $precioMP;
   

    public function PedidoProveedor(){

    }

    //metodos set y get

    public function getIdPedido(){
        return $this->idPedido;
    }

    public function setIdPedido($idPedido){
        $this->idPedido=$idPedido;
    }

    /////

    public function getIdProveedor(){
        return $this->idProveedor;
    }

    public function setIdProveedor($idProveedor){
        $this->idProveedor=$idProveedor;
    }    

    /////

    public function getIdEmpleado(){
        return $this->idEmpleado;
    }

    public function setIdEmpleado($idEmpleado){
        $this->idEmpleado=$idEmpleado;
    }

    /////

    public function getIdMateriaPrima(){
        return $this->idMateriaPrima;
    }

    public function setIdMateriaPrima($idMateriaPrima){
        $this->idMateriaPrima=$idMateriaPrima;
    }

    /////

    public function getFecha(){
        return $this->fecha;
    }

    public function setFecha($fecha){
        $this->fecha=$fecha;
    }

    /////
    public function getCantidadMP(){
        return $this->cantidadMP;
    }

    public function setCantidadMP($cantidadMP){
        $this->cantidadMP=$cantidadMP;
    }

    /////

    public function getMonto(){
        return $this->monto;
    }

    public function setMonto($monto){
        $this->monto=$monto;
    }

    /////
    public function getPrecioMP(){
        return $this->precioMP;
    }

    public function setPrecioMP($precioMP){
        $this->precioMP=$precioMP;
    }

    /////
    

    

}

?>