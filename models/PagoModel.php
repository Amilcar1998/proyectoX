<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('PagoModel')) {
    class PagoModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Lista todos los pagos registrados con información de usuario y plan
         */
        public function listarPagos(int $limite = 100, int $offset = 0): array
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, pp.nombrePlan, u.username
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 ORDER BY p.idPago DESC
                 LIMIT ? OFFSET ?"
            );
            if (!$stmt) {
                return [];
            }
            $stmt->bind_param("ii", $limite, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $pagos = [];
            while ($row = $result->fetch_assoc()) {
                $pagos[] = $this->procesarFilaPago($row);
            }
            $stmt->close();
            return $pagos;
        }

        /**
         * Obtiene un pago por su ID único
         */
        public function obtenerPagoPorId(int $idPago): ?array
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, pp.nombrePlan, u.username
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 WHERE p.idPago = ?
                 LIMIT 1"
            );
            if (!$stmt) {
                return null;
            }
            $stmt->bind_param("i", $idPago);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();
            $stmt->close();
            return $fila ? $this->procesarFilaPago($fila) : null;
        }

        /**
         * Obtiene los pagos de un usuario específico
         */
        public function obtenerPagosPorUsuario(int $idUsuario, int $limite = 50, int $offset = 0): array
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, pp.nombrePlan, u.username
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 WHERE p.idUsuario = ?
                 ORDER BY p.idPago DESC
                 LIMIT ? OFFSET ?"
            );
            if (!$stmt) {
                return [];
            }
            $stmt->bind_param("iii", $idUsuario, $limite, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $pagos = [];
            while ($row = $result->fetch_assoc()) {
                $pagos[] = $this->procesarFilaPago($row);
            }
            $stmt->close();
            return $pagos;
        }

        /**
         * Lista los pagos correspondientes a una empresa específica
         */
        public function listarPagosPorEmpresa(int $idEmpresa, int $limite = 100, int $offset = 0): array
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, pp.nombrePlan, u.username
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 WHERE p.idEmpresa = ?
                 ORDER BY p.idPago DESC
                 LIMIT ? OFFSET ?"
            );
            if (!$stmt) {
                return [];
            }
            $stmt->bind_param("iii", $idEmpresa, $limite, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $pagos = [];
            while ($row = $result->fetch_assoc()) {
                $pagos[] = $this->procesarFilaPago($row);
            }
            $stmt->close();
            return $pagos;
        }

        /**
         * Estadísticas de pagos para una empresa específica
         */
        public function obtenerEstadisticasPorEmpresa(int $idEmpresa): array
        {
            $stmt = $this->con->prepare(
                "SELECT 
                    COUNT(*) AS totalTransacciones,
                    COALESCE(SUM(CASE WHEN estado = 'completado' THEN monto ELSE 0 END), 0) AS totalRecaudado,
                    COALESCE(SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END), 0) AS totalCompletados,
                    COALESCE(SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END), 0) AS totalPendientes,
                    COALESCE(SUM(CASE WHEN estado = 'fallido' THEN 1 ELSE 0 END), 0) AS totalFallidos,
                    COALESCE(AVG(CASE WHEN estado = 'completado' THEN monto ELSE NULL END), 0) AS ticketPromedio
                FROM pagos
                WHERE idEmpresa = ?"
            );
            if (!$stmt) {
                return [
                    'totalTransacciones' => 0, 'totalRecaudado' => 0.0, 'totalCompletados' => 0,
                    'totalPendientes' => 0, 'totalFallidos' => 0, 'ticketPromedio' => 0.0
                ];
            }
            $stmt->bind_param("i", $idEmpresa);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            $stmt->close();

            if ($row) {
                return [
                    'totalTransacciones' => (int)$row['totalTransacciones'],
                    'totalRecaudado' => (float)$row['totalRecaudado'],
                    'totalCompletados' => (int)$row['totalCompletados'],
                    'totalPendientes' => (int)$row['totalPendientes'],
                    'totalFallidos' => (int)$row['totalFallidos'],
                    'ticketPromedio' => (float)$row['ticketPromedio']
                ];
            }
            return [
                'totalTransacciones' => 0, 'totalRecaudado' => 0.0, 'totalCompletados' => 0,
                'totalPendientes' => 0, 'totalFallidos' => 0, 'ticketPromedio' => 0.0
            ];
        }

        /**
         * Estadísticas globales de pagos para el dashboard y módulo
         */
        public function obtenerEstadisticasGlobales(): array
        {
            $sql = "SELECT 
                        COUNT(*) AS totalTransacciones,
                        COALESCE(SUM(CASE WHEN estado = 'completado' THEN monto ELSE 0 END), 0) AS totalRecaudado,
                        COALESCE(SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END), 0) AS totalCompletados,
                        COALESCE(SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END), 0) AS totalPendientes,
                        COALESCE(SUM(CASE WHEN estado = 'fallido' THEN 1 ELSE 0 END), 0) AS totalFallidos,
                        COALESCE(AVG(CASE WHEN estado = 'completado' THEN monto ELSE NULL END), 0) AS ticketPromedio
                    FROM pagos";
            $res = $this->con->query($sql);
            if ($res && $row = $res->fetch_assoc()) {
                return [
                    'totalTransacciones' => (int)$row['totalTransacciones'],
                    'totalRecaudado' => (float)$row['totalRecaudado'],
                    'totalCompletados' => (int)$row['totalCompletados'],
                    'totalPendientes' => (int)$row['totalPendientes'],
                    'totalFallidos' => (int)$row['totalFallidos'],
                    'ticketPromedio' => (float)$row['ticketPromedio']
                ];
            }
            return [
                'totalTransacciones' => 0,
                'totalRecaudado' => 0.0,
                'totalCompletados' => 0,
                'totalPendientes' => 0,
                'totalFallidos' => 0,
                'ticketPromedio' => 0.0
            ];
        }

        /**
         * Estadísticas de pagos para un usuario específico
         */
        public function obtenerEstadisticasPorUsuario(int $idUsuario): array
        {
            $stmt = $this->con->prepare(
                "SELECT COUNT(*) as total, COALESCE(SUM(monto), 0) as totalMonto 
                 FROM pagos 
                 WHERE idUsuario = ? AND estado = 'completado'"
            );
            if (!$stmt) {
                return ['total' => 0, 'totalMonto' => 0];
            }
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row ? ['total' => (int)$row['total'], 'totalMonto' => (float)$row['totalMonto']] : ['total' => 0, 'totalMonto' => 0];
        }

        /**
         * Procesa metadatos y enriquece la fila de pago
         */
        private function procesarFilaPago(array $fila): array
        {
            $meta = [];
            if (!empty($fila['metadata'])) {
                $meta = json_decode($fila['metadata'], true) ?: [];
            }
            $fila['metadatos_array'] = $meta;
            $fila['nombreCliente'] = $meta['nombreCliente'] ?? ($fila['username'] ?? 'Cliente Web');
            $fila['telefonoCliente'] = $meta['telefonoCliente'] ?? '';
            $fila['correoCliente'] = $meta['correoCliente'] ?? ($fila['username'] ?? '');
            $fila['idTransaccionWompi'] = $meta['wompi_retorno']['idTransaccion'] ?? ($meta['idTransaccion'] ?? '');
            $fila['idEnlaceWompi'] = $meta['wompi_retorno']['idEnlace'] ?? ($meta['idEnlace'] ?? '');
            $fila['idPedidoCreado'] = $meta['idPedidoCreado'] ?? 0;
            return $fila;
        }

        // Métodos retrocompatibles en español / inglés
        public function getPagos(int $limite = 100, int $offset = 0): array
        {
            return $this->listarPagos($limite, $offset);
        }

        public function getPagosPorUsuario(int $idUsuario, int $limite = 50, int $offset = 0): array
        {
            return $this->obtenerPagosPorUsuario($idUsuario, $limite, $offset);
        }

        public function getTotalPagosPorUsuario(int $idUsuario): array
        {
            return $this->obtenerEstadisticasPorUsuario($idUsuario);
        }

        public function getPagosPorPlan(int $idPlanPago): array
        {
            $stmt = $this->con->prepare(
                "SELECT p.*, u.username
                 FROM pagos p
                 LEFT JOIN usuarios u ON p.idUsuario = u.idUsuario
                 WHERE p.idPlanPago = ?
                 ORDER BY p.idPago DESC"
            );
            if (!$stmt) return [];
            $stmt->bind_param("i", $idPlanPago);
            $stmt->execute();
            $result = $stmt->get_result();
            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $this->procesarFilaPago($row);
            }
            $stmt->close();
            return $r;
        }
    }
}
