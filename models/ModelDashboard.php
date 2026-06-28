<?php
include "../db/conexion.php";

class ModelDashboard extends Conexion {

    public function __construct() {
        parent::__construct();
    }

    public function getResumen(): array {
        $data = [
            'totalPedidos' => 0,
            'montoTotal' => 0,
            'totalFacturas' => 0,
            'montoFacturado' => 0,
            'stockCritico' => 0,
            'totalEmpleados' => 0,
        ];

        $res = $this->con->query("SELECT COUNT(*) AS total FROM pedido");
        if ($res) {
            $data['totalPedidos'] = $res->fetch_assoc()['total'] ?? 0;
        }

        $res = $this->con->query("SELECT COALESCE(SUM(Monto),0) AS total FROM factura");
        if ($res) {
            $data['montoTotal'] = $res->fetch_assoc()['total'] ?? 0;
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM factura");
        if ($res) {
            $data['totalFacturas'] = $res->fetch_assoc()['total'] ?? 0;
        }

        $res = $this->con->query("SELECT COALESCE(SUM(Monto),0) AS total FROM factura");
        if ($res) {
            $data['montoFacturado'] = $res->fetch_assoc()['total'] ?? 0;
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM inventario WHERE Existencias < 500");
        if ($res) {
            $data['stockCritico'] = $res->fetch_assoc()['total'] ?? 0;
        }

        $res = $this->con->query("SELECT COUNT(*) AS total FROM empleado");
        if ($res) {
            $data['totalEmpleados'] = $res->fetch_assoc()['total'] ?? 0;
        }

        return $data;
    }

    public function getPedidosMensuales(): array {
        $sql = "SELECT DATE_FORMAT(STR_TO_DATE(fechaPedido, '%d/%m/%Y'), '%Y-%m') AS mes, COUNT(*) AS cantidad
                FROM pedido
                WHERE fechaPedido REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
                GROUP BY mes
                ORDER BY mes DESC
                LIMIT 12";
        $res = $this->con->query($sql);
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getMontoMensual(): array {
        $sql = "SELECT DATE_FORMAT(STR_TO_DATE(Fecha, '%d/%m/%Y'), '%Y-%m') AS mes, COALESCE(SUM(Monto),0) AS monto
                FROM factura
                WHERE Fecha REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
                GROUP BY mes
                ORDER BY mes DESC
                LIMIT 12";
        $res = $this->con->query($sql);
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getStockMateriasPrimas(): array {
        $sql = "SELECT mp.NombreMP, i.Existencias
                FROM inventario i
                INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima
                ORDER BY i.Existencias ASC
                LIMIT 15";
        $res = $this->con->query($sql);
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getPedidosRecientes(): array {
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
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getProduccionPorEmpleado(): array {
        $sql = "SELECT CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado,
                       COUNT(*) AS totalProduccion
                FROM produccion pr
                INNER JOIN empleado e ON pr.idEmpleado = e.idEmpleado
                GROUP BY e.idEmpleado
                ORDER BY totalProduccion DESC
                LIMIT 8";
        $res = $this->con->query($sql);
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getSessionEmp(string $correo): array {
        $res = $this->con->query("select idEmpleado,nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }
}
