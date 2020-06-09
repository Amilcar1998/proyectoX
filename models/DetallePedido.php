<?php


class DetallePedido
{
private $idDetallePedido;
private $cantidad;
private $idReceta;
private $idPedido;

    /**
     * @return mixed
     */
    public function getIdDetallePedido()
    {
        return $this->idDetallePedido;
    }

    /**
     * @param mixed $idDetallePedido
     */
    public function setIdDetallePedido($idDetallePedido)
    {
        $this->idDetallePedido = $idDetallePedido;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getIdReceta()
    {
        return $this->idReceta;
    }

    /**
     * @param mixed $idReceta
     */
    public function setIdReceta($idReceta)
    {
        $this->idReceta = $idReceta;
    }

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
     * DetallePedido constructor.
     * @param $idDetallePedido
     * @param $cantidad
     * @param $idReceta
     * @param $idPedido
     */
    public function __construct($idDetallePedido, $cantidad, $idReceta, $idPedido)
    {
        $this->idDetallePedido = $idDetallePedido;
        $this->cantidad = $cantidad;
        $this->idReceta = $idReceta;
        $this->idPedido = $idPedido;
    }
}