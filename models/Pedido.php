<?php
    Class Pedido {
        private $idPedido;
        private $fechaPedido;
        private $idCliente;
        private $estadoPedido;

        public function Pedido(){
            
        }

        public function getIdPedido(){
            return $this->idPedido;
        }
        public function setIdPediod($idPed){
            $this->idPedido = $idPed;
        }
        public function getFechaPedido(){
            return $this->fechaPedido;
        }
        public function setFechaPedido($fechaPed){
            $this->fechaPedido = $fechaPed;
        }
        public function getIdCliente(){
            return $this->idCliente;
        }
        public function setIdCliente($idClien){
            $this->IdCliente = $idClien;
        }
        public function getEstadoPedido(){
            return $this->estadoPedido;
        }
        public function setEstadoPedido($estped){
            $this->estadoPedido = $estped;
        }

    }

?>