<?php
include "../db/conexion.php";

if (!class_exists('PagoModel')) {
    class PagoModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function getPagosPorUsuario($idUsuario, $limite = 50, $offset = 0)
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, pp.nombrePlan, u.username
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 WHERE p.idUsuario = ?
                 ORDER BY p.fecha_hora DESC
                 LIMIT ? OFFSET ?"
            );
            if (!$stmt) return [];
            $stmt->bind_param("iii", $idUsuario, $limite, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
            $stmt->close();
            return $r;
        }

        public function getPagos($limite = 100, $offset = 0)
        {
            $result = $this->con->query(
                "SELECT p.*, pp.nombrePlan, u.username
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 ORDER BY p.fecha_hora DESC
                 LIMIT $limite OFFSET $offset"
            );
            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        public function getTotalPagosPorUsuario($idUsuario)
        {
            $stmt = $this->con->prepare("SELECT COUNT(*) as total, SUM(monto) as totalMonto FROM pagos WHERE idUsuario = ? AND estado = 'completado'");
            if (!$stmt) return ['total' => 0, 'totalMonto' => 0];
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row ?: ['total' => 0, 'totalMonto' => 0];
        }

        public function getPagosPorPlan($idPlanPago)
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, u.username
                 FROM pagos p
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 WHERE p.idPlanPago = ?
                 ORDER BY p.fecha_hora DESC"
            );
            if (!$stmt) return [];
            $stmt->bind_param("i", $idPlanPago);
            $stmt->execute();
            $result = $stmt->get_result();
            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
            $stmt->close();
            return $r;
        }
    }
}
