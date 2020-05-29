<?php
class Factura{

    private $idFacturaMP;
    private $numeroFac;
    private $monto;
    private $fecha;
    private $idProveedor;
    private $idEmpleado;

    public function Factura(){

    }

    //metodos set y get

    public function getIdFacturaMP(){
        return $this->idFacturaMP;
    }

    public function setIdFacturaMP($idFacturaMP){
        $this->idFacturaMP=$idFacturaMP;
    }

    /////

    public function getNumeroFac(){
        return $this->numeroFac;
    }

    public function setNumeroFac($numeroFac){
        $this->numeroFac=$numeroFac;
    }

    /////

    public function getMonto(){
        return $this->monto;
    }

    public function setMonto($monto){
        $this->monto=$monto;
    }

    /////
    public function getFecha(){
        return $this->fecha;
    }

    public function setFecha($fecha){
        $this->fecha=$fecha;
    }

    /////
    public function getIdProveedor(){
        return $this->idProveedor;
    }

    public function setIdProveedor($idProveedor){
        $this->idProveedor=$idProveedor;
    }

    ////
    
    public function getIdEmpleo(){
        return $this->idEmpleo;
    }

    public function setIdEmpleo($idEmpleo){
        $this->idEmpleo=$idEmpleo;
    }

    
}

?>