<?php
class Puesto{

    private $idPuesto;
    private $nombrePuesto;

    public function Puesto(){
    }

    //metodos set y get

    public function getIdPuesto(){
        return $this->idPuesto;
    }

    public function setIdPuesto($idPuesto){
        $this->idPuesto=$idPuesto;
    }

    /////

    public function getIdNombrePuesto(){
        return $this->idNombrePuesto;
    }

    public function setIdNombrePuesto($nombrePuesto){
        $this->nombrePuesto=$nombrePuesto;
    }  
}

?>