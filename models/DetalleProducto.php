<?php


class DetalleProducto
{
    private $idDetalleReceta;
    private $idMateriaPrima;
    private $cantidadSa;
    private $fechaSa;
    private $idInventario;
    private $idReceta;

    /**
     * @return mixed
     */
    public function getIdDetalleReceta()
    {
        return $this->idDetalleReceta;
    }

    /**
     * @param mixed $idDetalleReceta
     */
    public function setIdDetalleReceta($idDetalleReceta)
    {
        $this->idDetalleReceta = $idDetalleReceta;
    }

    /**
     * @return mixed
     */
    public function getIdMateriaPrima()
    {
        return $this->idMateriaPrima;
    }

    /**
     * @param mixed $idMateriaPrima
     */
    public function setIdMateriaPrima($idMateriaPrima)
    {
        $this->idMateriaPrima = $idMateriaPrima;
    }

    /**
     * @return mixed
     */
    public function getCantidadSa()
    {
        return $this->cantidadSa;
    }

    /**
     * @param mixed $cantidadSa
     */
    public function setCantidadSa($cantidadSa)
    {
        $this->cantidadSa = $cantidadSa;
    }

    /**
     * @return mixed
     */
    public function getFechaSa()
    {
        return $this->fechaSa;
    }

    /**
     * @param mixed $fechaSa
     */
    public function setFechaSa($fechaSa)
    {
        $this->fechaSa = $fechaSa;
    }

    /**
     * @return mixed
     */
    public function getIdInventario()
    {
        return $this->idInventario;
    }

    /**
     * @param mixed $idInventario
     */
    public function setIdInventario($idInventario)
    {
        $this->idInventario = $idInventario;
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
     * DetalleProducto constructor.
     * @param $idDetalleReceta
     * @param $idMateriaPrima
     * @param $cantidadSa
     * @param $fechaSa
     * @param $idInventario
     * @param $idReceta
     */
    public function __construct($idDetalleReceta, $idMateriaPrima, $cantidadSa, $fechaSa, $idInventario, $idReceta)
    {
        $this->idDetalleReceta = $idDetalleReceta;
        $this->idMateriaPrima = $idMateriaPrima;
        $this->cantidadSa = $cantidadSa;
        $this->fechaSa = $fechaSa;
        $this->idInventario = $idInventario;
        $this->idReceta = $idReceta;
    }

}