<?php 

class Produccion
{
	private $idProduccion;
	private $fechaProduccion;
	private $estadoProduccion;

    /**
     * @return mixed
     */
    public function getIdProduccion()
    {
        return $this->idProduccion;
    }

    /**
     * @param mixed $idProduccion
     */
    public function setIdProduccion($idProduccion)
    {
        $this->idProduccion = $idProduccion;
    }

    /**
     * @return mixed
     */
    public function getFechaProduccion()
    {
        return $this->fechaProduccion;
    }

    /**
     * @param mixed $fechaProduccion
     */
    public function setFechaProduccion($fechaProduccion)
    {
        $this->fechaProduccion = $fechaProduccion;
    }

    /**
     * @return mixed
     */
    public function getEstadoProduccion()
    {
        return $this->estadoProduccion;
    }

    /**
     * @param mixed $estadoProduccion
     */
    public function setEstadoProduccion($estadoProduccion)
    {
        $this->estadoProduccion = $estadoProduccion;
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
     * @return mixed
     */
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    /**
     * @param mixed $idEmpleado
     */
    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }
	private $idPedido;
	private $idEmpleado;

    /**
     * Produccion constructor.
     * @param $idProduccion
     * @param $fechaProduccion
     * @param $estadoProduccion
     * @param $idPedido
     * @param $idEmpleado
     */
    public function __construct($idProduccion, $fechaProduccion, $estadoProduccion, $idPedido, $idEmpleado)
    {
        $this->idProduccion = $idProduccion;
        $this->fechaProduccion = $fechaProduccion;
        $this->estadoProduccion = $estadoProduccion;
        $this->idPedido = $idPedido;
        $this->idEmpleado = $idEmpleado;
    }

}


 ?>