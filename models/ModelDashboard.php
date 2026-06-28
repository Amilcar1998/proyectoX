<?php
include "../db/conexion.php";

class ModelDashboard extends Conexion {

    public function __construct() {
        parent::__construct();
    }

    public function getResumen(): array {
        $data = [];

        $res = $this->con->query("SELECT COUNT(*) AS total FROM pedidoproveedor");
        $data['totalPedidos'] = $res->fetch_assoc()['total'] ?? 0;

        $res = $this->con->query("SELECT COALESCE(SUM(monto),0) AS total FROM pedidoproveedor");
        $data['montoTotal'] = $res->fetch_assoc()['total'] ?? 0;

        $res = $this->con->query("SELECT COUNT(*) AS total FROM factura");
        $data['totalFacturas'] = $res->fetch_assoc()['total'] ?? 0;

        $res = $this->con->query("SELECT COALESCE(SUM(monto),0) AS total FROM factura");
        $data['montoFacturado'] = $res->fetch_assoc()['total'] ?? 0;

        $res = $this->con->query("SELECT COUNT(*) AS total FROM inventario WHERE Existencias < 10");
        $data['stockCritico'] = $res->fetch_assoc()['total'] ?? 0;

        $res = $this->con->query("SELECT COUNT(*) AS total FROM empleado");
        $data['totalEmpleados'] = $res->fetch_assoc()['total'] ?? 0;

        return $data;
    }

    public function getPedidosMensuales(): array {
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS cantidad, COALESCE(SUM(monto),0) AS monto
                FROM pedidoproveedor
                GROUP BY mes
                ORDER BY mes DESC
                LIMIT 12";
        $res = $this->con->query($sql);
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
                ORDER BY i.Existencias ASC";
        $res = $this->con->query($sql);
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getPedidosRecientes(): array {
        $sql = "SELECT pp.idPedido, p.nombreProveedor,
                       CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado,
                       mp.NombreMP, pp.fecha, pp.cantidadMP, pp.monto
                FROM pedidoproveedor pp
                INNER JOIN proveedor p ON pp.idProveedor = p.idProveedor
                INNER JOIN empleado e ON pp.idEmpleado = e.idEmpleado
                INNER JOIN materiaprima mp ON pp.idMateriaPrima = mp.idMateriaPrima
                ORDER BY pp.fecha DESC
                LIMIT 10";
        $res = $this->con->query($sql);
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
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
}
