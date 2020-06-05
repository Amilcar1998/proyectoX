<?php


class Pedidos
{
    private $idPedido;

    /**
     * @return mixed
     */
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * @param mixed $idPedido
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    /**
     * @return mixed
     */
    public function getFechaPedido()
    {
        return $this->fechaPedido;
    }

    /**
     * @param mixed $fechaPedido
     */
    public function setFechaPedido($fechaPedido)
    {
        $this->fechaPedido = $fechaPedido;
    }

    /**
     * @return mixed
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * @param mixed $idCliente
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }

    /**
     * @return mixed
     */
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * @param mixed $idEstado
     */
    public function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;
    }
    private $fechaPedido;
    private $idCliente;
    private $idEstado;

    public function __construct($idPedido, $fechaPedido, $idCliente, $idEstado)
    {
        $this->idPedido = $idPedido;
        $this->fechaPedido = $fechaPedido;
        $this->idCliente = $idCliente;
        $this->idEstado = $idEstado;
    }

}