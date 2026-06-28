<?php
class Puesto{

    private $idPuesto;
    private $nombrePuesto;

    public function Puesto(){
    }

    //metodos set y get

    public function getId(){
        return $this->idPuesto;
    }

    public function setId($idPuesto){
        $this->idPuesto=$idPuesto;
    }

    public function getNombrePuesto(){
        return $this->nombrePuesto;
    }

    public function setNombrePuesto($nombrePuesto){
        $this->nombrePuesto=$nombrePuesto;
    }  
}

?>