<?php
include "../db/conexion.php";

if (!class_exists('AuditoriaModel')) {
    class AuditoriaModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function log($idUsuario, $username, $tipoEvento, $modulo = null, $descripcion = null)
        {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

            $stmt = $this->con->prepare(
                "INSERT INTO auditoria (idUsuario, username, tipo_evento, modulo, descripcion, ip_address, user_agent, fecha_hora)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
            );
            $stmt->bind_param("issssss", $idUsuario, $username, $tipoEvento, $modulo, $descripcion, $ipAddress, $userAgent);
            $stmt->execute();
            $stmt->close();
        }

        public function getReporteSesiones($fechaInicio = null, $fechaFin = null)
        {
            $where = "";
            $params = [];
            $types = "";

            if ($fechaInicio && $fechaFin) {
                $where = "WHERE fecha_hora BETWEEN ? AND ?";
                $params = [$fechaInicio, $fechaFin . ' 23:59:59'];
                $types = "ss";
            }

            $sql = "SELECT a.*, u.username as nombre_completo
                    FROM auditoria a
                    LEFT JOIN usuarios u ON a.idUsuario = u.idUsuario
                    $where
                    ORDER BY a.fecha_hora DESC";

            if (!empty($params)) {
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->con->query($sql);
            }

            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        public function getUsuariosActivos()
        {
            $sql = "SELECT s.*, u.username, r.nombreRol
                    FROM sesiones_activas s
                    LEFT JOIN usuarios u ON s.idUsuario = u.idUsuario
                    LEFT JOIN rol r ON s.id_Rol = r.id_Rol
                    WHERE s.activo = 1
                    ORDER BY s.last_activity DESC";

            $result = $this->con->query($sql);
            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        public function getActividadPorModulo()
        {
            $sql = "SELECT modulo, COUNT(*) as total, COUNT(DISTINCT idUsuario) as usuarios_unicos,
                           MIN(fecha_hora) as primer_acceso, MAX(fecha_hora) as ultimo_acceso
                    FROM auditoria
                    WHERE tipo_evento = 'vista'
                    GROUP BY modulo
                    ORDER BY total DESC";

            $result = $this->con->query($sql);
            $r = [];
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        public function registrarSesionActiva($sessionId, $idUsuario, $username, $idRol, $nombreUsuario, $ipAddress, $userAgent)
        {
            $stmt = $this->con->prepare(
                "INSERT INTO sesiones_activas (session_id, idUsuario, username, id_Rol, nombre_usuario, login_time, last_activity, ip_address, user_agent, activo)
                 VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, 1)
                 ON DUPLICATE KEY UPDATE last_activity = NOW(), ip_address = VALUES(ip_address), user_agent = VALUES(user_agent)"
            );
            if (!$stmt) return false;
            $ok = $stmt->bind_param("siiisss", $sessionId, $idUsuario, $username, $idRol, $nombreUsuario, $ipAddress, $userAgent);
            if (!$ok) return false;
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }

        public function actualizarActividadSesion($sessionId)
        {
            $stmt = $this->con->prepare(
                "UPDATE sesiones_activas SET last_activity = NOW() WHERE session_id = ? AND activo = 1"
            );
            if (!$stmt) return false;
            $ok = $stmt->bind_param("s", $sessionId);
            if (!$ok) return false;
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }

        public function cerrarSesionActiva($sessionId)
        {
            $stmt = $this->con->prepare(
                "UPDATE sesiones_activas SET activo = 0 WHERE session_id = ?"
            );
            if (!$stmt) return false;
            $ok = $stmt->bind_param("s", $sessionId);
            if (!$ok) return false;
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
    }
}
