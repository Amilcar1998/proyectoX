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
        $sql = "SELECT pedido.idPedido, pedido.fechaPedido, pedido.idEmpresa, persona.idPersona, persona.nombrePersona, persona.apellidoPersona, persona.nombrePersona AS NombreCliente, persona.apellidoPersona AS ApellidosCliente, persona.telefono, 
                       estadopedido.idEstadoPedido, estadopedido.nombreEstado,
                       COALESCE(emp.nombreEmpresa, 'Concentrados El Gordito') AS nombreEmpresa
                FROM pedido 
                INNER JOIN persona ON pedido.idCliente = persona.idPersona 
                INNER JOIN estadopedido ON pedido.idEstadoPedido = estadopedido.idEstadoPedido 
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
        $correo = $this->con->real_escape_string($correo);
        // 1. Buscar en persona
        $res = $this->con->query("SELECT p.nombrePersona AS nombreEmp, p.apellidoPersona AS apellido FROM persona p INNER JOIN usuarios u ON p.idUsuario=u.idUsuario WHERE u.username='$correo' LIMIT 1");
        $r = [];
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }
        // 2. Fallback a empleado
        $res = $this->con->query("SELECT nombreEmp, apellido FROM empleado INNER JOIN usuarios ON empleado.idUsuario=usuarios.idUsuario WHERE username='$correo'");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getDetalle($id)
    {
        return $this->obtenerDetallePedido((int)$id);
    }

    public function obtenerDetallePedido(int $idPedido): array
    {
        $stmt = $this->con->prepare(
            "SELECT dp.iddetallePedido, dp.idReceta, dp.IdPedido, dp.cantidad, dp.subtotal,
                    r.nombreReceta, r.PrecioUnitario, r.descripcion
             FROM detallepedido dp
             INNER JOIN receta r ON dp.idReceta = r.idReceta
             WHERE dp.IdPedido = ?"
        );
        $r = [];
        if (!$stmt) return $r;
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
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
             FROM detallereceta dr 
             INNER JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima 
             INNER JOIN receta r ON dr.IdReceta = r.idReceta 
             WHERE dr.IdReceta = ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param("i", $idReceta);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        $stmt->close();
        return $r;
    }

    public function obtenerRecetaPorPedido(int $idPedido): array
    {
        $stmt = $this->con->prepare(
            "SELECT r.idReceta, r.nombreReceta, r.PrecioUnitario, r.descripcion, dp.cantidad
             FROM receta r
             INNER JOIN detallepedido dp ON r.idReceta = dp.idReceta
             WHERE dp.IdPedido = ?"
        );
        $r = [];
        if (!$stmt) return $r;
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        $stmt->close();
        return $r;
    }

    public function obtenerDetalleCompleto(int $idPedido): array
    {
        $stmt = $this->con->prepare(
            "SELECT p.idPedido, p.fechaPedido, p.idCliente, p.idEstadoPedido, ep.nombreEstado,
                    c.idPersona, c.nombrePersona, c.apellidoPersona, c.nombrePersona AS NombreCliente, c.apellidoPersona AS apellidosCliente, c.telefono, c.edad, c.genero,
                    u.username as correoCliente
             FROM pedido p
             INNER JOIN persona c ON p.idCliente = c.idPersona
             INNER JOIN estadopedido ep ON p.idEstadoPedido = ep.idEstadoPedido
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