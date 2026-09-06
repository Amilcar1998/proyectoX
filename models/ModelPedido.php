<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/Pedidos.php';
class ModelPedido extends Conexion
{
public function __construct()
{
    parent::__construct();
}
   
    public function getPedido()
    {
        return $this->obtenerPedidos();
    }

    public function obtenerPedidos(): array
    {
        $sql = "SELECT idPedido, fechaPedido, NombreCliente, ApellidosCliente, estadoPedido.nombreEstado 
                FROM pedido 
                INNER JOIN cliente ON pedido.idCliente = cliente.idCliente 
                INNER JOIN estadoPedido ON pedido.idEstadoPedido = estadoPedido.idEstadoPedido 
                WHERE pedido.idEstadoPedido = 1";
        $res = $this->con->query($sql);
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getSessionEmp($correo)
    {
        return $this->obtenerSesionEmpleado((string)$correo);
    }

    public function obtenerSesionEmpleado(string $correo): array
    {
        $stmt = $this->con->prepare(
            "SELECT idEmpleado, nombreEmp, apellido 
             FROM empleado 
             INNER JOIN usuarios ON empleado.idUsuario = usuarios.idUsuario 
             WHERE username = ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        $stmt->close();
        return $r;
    }

    public function getDetalle($id)
    {
        return $this->obtenerDetallePedido((int)$id);
    }

    public function obtenerDetallePedido(int $idPedido): array
    {
        $stmt = $this->con->prepare(
            "SELECT dp.idDetallePedido, dp.cantidad, dp.idReceta, dp.IdPedido, r.nombreReceta, r.PrecioUnitario 
             FROM detallePedido dp 
             INNER JOIN receta r ON dp.idReceta = r.idReceta 
             WHERE dp.IdPedido = ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        $stmt->close();
        return $r;
    }

    public function getReceta($idReceta)
    {
        return $this->obtenerReceta((int)$idReceta);
    }

    public function obtenerReceta(int $idReceta): array
    {
        $stmt = $this->con->prepare(
            "SELECT dr.idDetalleReceta, dr.cantidaSa, dr.fechaSa, mp.NombreMP, r.nombreReceta 
             FROM detalleReceta dr 
             INNER JOIN materiaPrima mp ON dr.idMateriaPrima = mp.idMateriaPrima 
             INNER JOIN receta r ON dr.IdReceta = r.idReceta 
             WHERE dr.IdReceta = ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param("i", $idReceta);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        $stmt->close();
        return $r;
    }

    public function obtenerRecetaPorPedido(int $idPedido): array
    {
        $stmt = $this->con->prepare(
            "SELECT dr.idDetalleReceta, dr.cantidaSa, dr.fechaSa, mp.NombreMP, r.nombreReceta, dp.IdPedido 
             FROM detallereceta dr 
             INNER JOIN materiaPrima mp ON dr.idMateriaPrima = mp.idMateriaPrima 
             INNER JOIN receta r ON dr.IdReceta = r.idReceta 
             INNER JOIN detallepedido dp ON r.idReceta = dp.idReceta 
             WHERE dp.IdPedido = ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        $stmt->close();
        return $r;
    }
}