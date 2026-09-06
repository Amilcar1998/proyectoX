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

    public function obtenerPedidos(int $idEmpresa = 0): array
    {
        $condicion = "";
        if ($idEmpresa > 0) {
            $condicion = " WHERE pedido.idEmpresa = " . (int)$idEmpresa . " ";
        }
        $sql = "SELECT pedido.idPedido, pedido.fechaPedido, pedido.idEmpresa, cliente.NombreCliente, cliente.ApellidosCliente, cliente.telefono, 
                       estadoPedido.idEstadoPedido, estadoPedido.nombreEstado,
                       COALESCE(emp.nombreEmpresa, 'Concentrados El Gordito') AS nombreEmpresa
                FROM pedido 
                INNER JOIN cliente ON pedido.idCliente = cliente.idCliente 
                INNER JOIN estadoPedido ON pedido.idEstadoPedido = estadoPedido.idEstadoPedido 
                LEFT JOIN empresas emp ON pedido.idEmpresa = emp.idEmpresa
                $condicion
                ORDER BY pedido.idPedido DESC";
        $res = $this->con->query($sql);
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
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
            "SELECT dp.idDetallePedido, dp.cantidad, dp.idReceta, dp.IdPedido, r.nombreReceta, r.PrecioUnitario,
                    (dp.cantidad * COALESCE(r.PrecioUnitario, 0)) as subtotal
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
            "SELECT dr.idDetalleReceta, dr.cantidaSa, dr.fechaSa, mp.NombreMP, r.nombreReceta, dp.IdPedido, dp.cantidad as cantidadPedida
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

    public function obtenerDetalleCompleto(int $idPedido): array
    {
        $stmt = $this->con->prepare(
            "SELECT p.idPedido, p.fechaPedido, p.idCliente, p.idEstadoPedido, ep.nombreEstado,
                    c.NombreCliente, c.apellidosCliente, c.telefono, c.edad, c.genero,
                    u.username as correoCliente
             FROM pedido p
             INNER JOIN cliente c ON p.idCliente = c.idCliente
             INNER JOIN estadoPedido ep ON p.idEstadoPedido = ep.idEstadoPedido
             LEFT JOIN usuarios u ON c.idUsuario = u.idUsuario
             WHERE p.idPedido = ?
             LIMIT 1"
        );
        if (!$stmt) return [];
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $res = $stmt->get_result();
        $infoPedido = $res->fetch_assoc();
        $stmt->close();

        if (!$infoPedido) return [];

        $items = $this->obtenerDetallePedido($idPedido);
        $recetas = $this->obtenerRecetaPorPedido($idPedido);

        $total = 0.0;
        foreach ($items as $item) {
            $total += (float)($item['subtotal'] ?? 0);
        }

        return [
            'pedido' => $infoPedido,
            'items' => $items,
            'recetas' => $recetas,
            'total' => round($total, 2)
        ];
    }
}