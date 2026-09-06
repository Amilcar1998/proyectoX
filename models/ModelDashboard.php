<?php
require_once __DIR__ . '/../db/conexion.php';

class ModelDashboard extends Conexion {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerResumen(): array {
        $data = [
            'totalPedidos' => 0,
            'montoTotal' => 0,
            'totalFacturas' => 0,
            'montoFacturado' => 0,
            'stockCritico' => 0,
            'totalEmpleados' => 0,
            'totalPagosWompi' => 0,
            'montoPagosWompi' => 0.0
        ];

        $res = $this->con->query("SELECT COUNT(*) AS total FROM pedido");
        if ($res) {
            $data['totalPedidos'] = (int)($res->fetch_assoc()['total'] ?? 0);
        }

        $res = $this->con->query("SELECT COALESCE(SUM(Monto),0) AS total, COUNT(*) AS countFacturas FROM factura");
        if ($res && $r = $res->fetch_assoc()) {
            $data['montoTotal'] = (float)($r['total'] ?? 0);
            $data['montoFacturado'] = (float)($r['total'] ?? 0);
            $data['totalFacturas'] = (int)($r['countFacturas'] ?? 0);
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM inventario WHERE Existencias < 500");
        if ($res) {
            $data['stockCritico'] = (int)($res->fetch_assoc()['total'] ?? 0);
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM empleado");
        if ($res) {
            $data['totalEmpleados'] = (int)($res->fetch_assoc()['total'] ?? 0);
        }

        $res = $this->con->query("SELECT COUNT(*) AS total, COALESCE(SUM(monto), 0) AS totalMonto FROM pagos WHERE estado = 'completado'");
        if ($res && $rP = $res->fetch_assoc()) {
            $data['totalPagosWompi'] = (int)($rP['total'] ?? 0);
            $data['montoPagosWompi'] = (float)($rP['totalMonto'] ?? 0);
        }

        return $data;
    }

    public function getResumen(): array {
        return $this->obtenerResumen();
    }

    public function obtenerResumenEmpleado(string $correo): array {
        $data = [
            'stockCritico' => 0,
            'totalMateriasPrimas' => 0,
            'misProducciones' => 0,
            'pedidosActivos' => 0
        ];

        $res = $this->con->query("SELECT COUNT(*) AS total FROM inventario WHERE Existencias < 500");
        if ($res) {
            $data['stockCritico'] = (int)($res->fetch_assoc()['total'] ?? 0);
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM materiaprima");
        if ($res) {
            $data['totalMateriasPrimas'] = (int)($res->fetch_assoc()['total'] ?? 0);
        }

        $stmt = $this->con->prepare("SELECT COUNT(*) AS total 
                                     FROM produccion pr 
                                     INNER JOIN empleado e ON pr.idEmpleado = e.idEmpleado 
                                     INNER JOIN usuarios u ON e.idUsuario = u.idUsuario 
                                     WHERE u.username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $data['misProducciones'] = (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
            $stmt->close();
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM pedido");
        if ($res) {
            $data['pedidosActivos'] = (int)($res->fetch_assoc()['total'] ?? 0);
        }

        return $data;
    }

    public function obtenerResumenCliente(string $correo): array {
        $data = [
            'totalPedidos' => 0,
            'totalPagosWompi' => 0,
            'montoPagosWompi' => 0.0,
            'promocionesActivas' => 0
        ];

        $stmt = $this->con->prepare("SELECT c.idCliente, u.idUsuario 
                                     FROM cliente c 
                                     INNER JOIN usuarios u ON c.idUsuario = u.idUsuario 
                                     WHERE u.username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $cli = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($cli) {
                $idCli = (int)$cli['idCliente'];
                $idUser = (int)$cli['idUsuario'];

                $resP = $this->con->query("SELECT COUNT(*) AS total FROM pedido WHERE idCliente = $idCli");
                if ($resP) {
                    $data['totalPedidos'] = (int)($resP->fetch_assoc()['total'] ?? 0);
                }

                $resW = $this->con->query("SELECT COUNT(*) AS total, COALESCE(SUM(monto), 0) AS totalMonto FROM pagos WHERE idUsuario = $idUser AND estado = 'completado'");
                if ($resW && $r = $resW->fetch_assoc()) {
                    $data['totalPagosWompi'] = (int)($r['total'] ?? 0);
                    $data['montoPagosWompi'] = (float)($r['totalMonto'] ?? 0);
                }
            }
        }

        $resPromo = $this->con->query("SELECT COUNT(*) AS total FROM receta WHERE en_promocion = 1 AND (fecha_fin_promo IS NULL OR NOW() <= fecha_fin_promo)");
        if ($resPromo) {
            $data['promocionesActivas'] = (int)($resPromo->fetch_assoc()['total'] ?? 0);
        }

        return $data;
    }

    public function obtenerPedidosCliente(string $correo): array {
        $stmt = $this->con->prepare("SELECT p.idPedido, p.fechaPedido, 
                                            GROUP_CONCAT(CONCAT(r.nombreReceta, ' (x', dp.cantidad, ')') SEPARATOR ', ') AS detalle, 
                                            SUM(dp.cantidad) AS totalCantidad
                                     FROM pedido p
                                     INNER JOIN cliente c ON p.idCliente = c.idCliente
                                     INNER JOIN usuarios u ON c.idUsuario = u.idUsuario
                                     INNER JOIN detallepedido dp ON p.idPedido = dp.IdPedido
                                     INNER JOIN receta r ON dp.idReceta = r.idReceta
                                     WHERE u.username = ?
                                     GROUP BY p.idPedido, p.fechaPedido
                                     ORDER BY p.idPedido DESC 
                                     LIMIT 10");
        if (!$stmt) return [];
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        $pedidos = [];
        while ($row = $res->fetch_assoc()) {
            $pedidos[] = $row;
        }
        $stmt->close();
        return $pedidos;
    }

    public function obtenerPagosCliente(string $correo): array {
        $stmt = $this->con->prepare("SELECT p.idPago, p.monto, p.moneda, p.metodo_pago, p.estado, p.referencia, p.descripcion, p.fecha_hora 
                                     FROM pagos p 
                                     INNER JOIN usuarios u ON p.idUsuario = u.idUsuario 
                                     WHERE u.username = ? 
                                     ORDER BY p.idPago DESC 
                                     LIMIT 10");
        if (!$stmt) return [];
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        $pagos = [];
        while ($row = $res->fetch_assoc()) {
            $pagos[] = $row;
        }
        $stmt->close();
        return $pagos;
    }

    public function obtenerPromocionesActivas(): array {
        $sql = "SELECT idReceta, nombreReceta, precio, precio_anterior, porcentaje_descuento, fecha_inicio_promo, fecha_fin_promo 
                FROM receta 
                WHERE en_promocion = 1 AND (fecha_fin_promo IS NULL OR NOW() <= fecha_fin_promo)
                ORDER BY idReceta DESC";
        $res = $this->con->query($sql);
        if (!$res) return [];
        $promos = [];
        while ($row = $res->fetch_assoc()) {
            $promos[] = $row;
        }
        return $promos;
    }

    public function obtenerPedidosMensuales(): array {
        $sql = "SELECT DATE_FORMAT(STR_TO_DATE(fechaPedido, '%d/%m/%Y'), '%Y-%m') AS mes, COUNT(*) AS cantidad
                FROM pedido
                WHERE fechaPedido REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
                GROUP BY mes
                ORDER BY mes DESC
                LIMIT 12";
        $res = $this->con->query($sql);
        if (!$res) return [];
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getPedidosMensuales(): array {
        return $this->obtenerPedidosMensuales();
    }

    public function obtenerMontoMensual(): array {
        $sql = "SELECT DATE_FORMAT(STR_TO_DATE(Fecha, '%d/%m/%Y'), '%Y-%m') AS mes, COALESCE(SUM(Monto),0) AS monto
                FROM factura
                WHERE Fecha REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
                GROUP BY mes
                ORDER BY mes DESC
                LIMIT 12";
        $res = $this->con->query($sql);
        if (!$res) return [];
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getMontoMensual(): array {
        return $this->obtenerMontoMensual();
    }

    public function obtenerStockMateriasPrimas(): array {
        $sql = "SELECT mp.NombreMP, i.Existencias
                FROM inventario i
                INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima
                ORDER BY i.Existencias ASC
                LIMIT 15";
        $res = $this->con->query($sql);
        if (!$res) return [];
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getStockMateriasPrimas(): array {
        return $this->obtenerStockMateriasPrimas();
    }

    public function obtenerPedidosRecientes(): array {
        $sql = "SELECT p.idPedido, c.NombreCliente,
                       CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado,
                       GROUP_CONCAT(DISTINCT r.nombreReceta SEPARATOR ', ') AS recetas,
                       p.fechaPedido, SUM(dp.cantidad) AS cantidad
                FROM pedido p
                INNER JOIN cliente c ON p.idCliente = c.idCliente
                INNER JOIN detallepedido dp ON p.idPedido = dp.IdPedido
                INNER JOIN receta r ON dp.idReceta = r.idReceta
                INNER JOIN produccion pr ON p.idPedido = pr.idPedido
                INNER JOIN empleado e ON pr.idEmpleado = e.idEmpleado
                GROUP BY p.idPedido, c.NombreCliente, e.nombreEmp, e.apellido, p.fechaPedido
                ORDER BY p.fechaPedido DESC
                LIMIT 10";
        $res = $this->con->query($sql);
        if (!$res) return [];
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getPedidosRecientes(): array {
        return $this->obtenerPedidosRecientes();
    }

    public function obtenerProduccionPorEmpleado(): array {
        $sql = "SELECT CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado,
                       COUNT(*) AS totalProduccion
                FROM produccion pr
                INNER JOIN empleado e ON pr.idEmpleado = e.idEmpleado
                GROUP BY e.idEmpleado
                ORDER BY totalProduccion DESC
                LIMIT 8";
        $res = $this->con->query($sql);
        if (!$res) return [];
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getProduccionPorEmpleado(): array {
        return $this->obtenerProduccionPorEmpleado();
    }

    public function obtenerDatosUsuarioPorSesion(string $correo): array {
        $stmt = $this->con->prepare("SELECT e.idEmpleado, e.nombreEmp, e.apellido 
                                     FROM empleado e 
                                     INNER JOIN usuarios u ON e.idUsuario = u.idUsuario 
                                     WHERE u.username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $stmt->close();
                return $row;
            }
            $stmt->close();
        }

        $stmtCli = $this->con->prepare("SELECT c.idCliente, c.NombreCliente, c.apellidosCliente 
                                        FROM cliente c 
                                        INNER JOIN usuarios u ON c.idUsuario = u.idUsuario 
                                        WHERE u.username = ? LIMIT 1");
        if ($stmtCli) {
            $stmtCli->bind_param("s", $correo);
            $stmtCli->execute();
            $res = $stmtCli->get_result();
            if ($row = $res->fetch_assoc()) {
                $stmtCli->close();
                return $row;
            }
            $stmtCli->close();
        }

        return [];
    }

    public function getSessionEmp(string $correo): array {
        $datos = $this->obtenerDatosUsuarioPorSesion($correo);
        return !empty($datos) ? [$datos] : [];
    }
}
