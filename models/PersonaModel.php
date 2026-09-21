<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('PersonaModel')) {
    class PersonaModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function listarPersonas(int $idEmpresa = 0): array
        {
            $condicion = ($idEmpresa > 0) ? " WHERE (p.idEmpresa = " . (int)$idEmpresa . " OR u.idEmpresa = " . (int)$idEmpresa . ") " : "";
            $sql = "SELECT p.idPersona, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS nombres, p.apellidoPersona AS apellidos, p.telefono, p.edad, p.genero, p.idUsuario, p.idEmpresa, u.username, u.id_Rol, r.nombreRol 
                    FROM persona p 
                    INNER JOIN usuarios u ON p.idUsuario = u.idUsuario 
                    LEFT JOIN rol r ON u.id_Rol = r.id_Rol 
                    $condicion 
                    ORDER BY p.idPersona ASC";
            $res = $this->con->query($sql);
            $resultado = [];
            if ($res) {
                while ($fila = $res->fetch_assoc()) {
                    $resultado[] = $fila;
                }
            }
            return $resultado;
        }

        public function listarPersonasPorRol(int $idRol, int $idEmpresa = 0): array
        {
            $condEmpresa = ($idEmpresa > 0) ? " AND (p.idEmpresa = " . (int)$idEmpresa . " OR u.idEmpresa = " . (int)$idEmpresa . ") " : "";
            $sql = "SELECT p.idPersona, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS nombres, p.apellidoPersona AS apellidos, p.telefono, p.edad, p.genero, p.idUsuario, p.idEmpresa, u.username, u.id_Rol, r.nombreRol 
                    FROM persona p 
                    INNER JOIN usuarios u ON p.idUsuario = u.idUsuario 
                    INNER JOIN rol r ON u.id_Rol = r.id_Rol 
                    WHERE u.id_Rol = ? $condEmpresa 
                    ORDER BY p.idPersona ASC";
            $stmt = $this->con->prepare($sql);
            if (!$stmt) return [];
            $stmt->bind_param("i", $idRol);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($fila = $res->fetch_assoc()) {
                $resultado[] = $fila;
            }
            $stmt->close();
            return $resultado;
        }

        public function listarClientes(int $idEmpresa = 0): array
        {
            return $this->listarPersonasPorRol(3, $idEmpresa);
        }

        public function listarEmpleados(int $idEmpresa = 0): array
        {
            return $this->listarPersonasPorRol(2, $idEmpresa);
        }

        public function obtenerPersonaPorId(int $idPersona): ?array
        {
            $stmt = $this->con->prepare("SELECT p.idPersona, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS nombres, p.apellidoPersona AS apellidos, p.telefono, p.edad, p.genero, p.idUsuario, p.idEmpresa, u.username, u.id_Rol, r.nombreRol FROM persona p INNER JOIN usuarios u ON p.idUsuario = u.idUsuario LEFT JOIN rol r ON u.id_Rol = r.id_Rol WHERE p.idPersona = ? LIMIT 1");
            if (!$stmt) return null;
            $stmt->bind_param("i", $idPersona);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();
            $stmt->close();
            return $fila ?: null;
        }

        public function obtenerPersonaPorIdUsuario(int $idUsuario): ?array
        {
            $stmt = $this->con->prepare("SELECT p.idPersona, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS nombres, p.apellidoPersona AS apellidos, p.telefono, p.edad, p.genero, p.idUsuario, p.idEmpresa, u.username, u.id_Rol, r.nombreRol FROM persona p INNER JOIN usuarios u ON p.idUsuario = u.idUsuario LEFT JOIN rol r ON u.id_Rol = r.id_Rol WHERE p.idUsuario = ? LIMIT 1");
            if (!$stmt) return null;
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();
            $stmt->close();
            return $fila ?: null;
        }

        public function guardarPersona(array $datos): int
        {
            $nombres = trim((string)($datos['nombrePersona'] ?? ($datos['nombres'] ?? ($datos['NombreCliente'] ?? ''))));
            $apellidos = trim((string)($datos['apellidoPersona'] ?? ($datos['apellidos'] ?? ($datos['apellidosCliente'] ?? ''))));
            $telefono = trim((string)($datos['telefono'] ?? ''));
            $edad = trim((string)($datos['edad'] ?? ''));
            $genero = trim((string)($datos['genero'] ?? ''));
            $idUsuario = (int)($datos['idUsuario'] ?? 0);
            $idEmpresa = (int)($datos['idEmpresa'] ?? 1);
            if ($idEmpresa <= 0) $idEmpresa = 1;

            // 1. Validar si el usuario ya tiene registro de persona en esta empresa
            if ($idUsuario > 0) {
                $stmtCheck = $this->con->prepare("SELECT idPersona FROM persona WHERE idUsuario = ? AND idEmpresa = ? LIMIT 1");
                if ($stmtCheck) {
                    $stmtCheck->bind_param("ii", $idUsuario, $idEmpresa);
                    $stmtCheck->execute();
                    $resCheck = $stmtCheck->get_result();
                    if ($resCheck && $resCheck->num_rows > 0) {
                        $stmtCheck->close();
                        return 0; // Ya existe en la empresa
                    }
                    $stmtCheck->close();
                }
            }

            // 2. Validar si el teléfono ya existe para otra persona en la misma empresa
            if (!empty($telefono)) {
                $stmtTel = $this->con->prepare("SELECT idPersona FROM persona WHERE telefono = ? AND idEmpresa = ? LIMIT 1");
                if ($stmtTel) {
                    $stmtTel->bind_param("si", $telefono, $idEmpresa);
                    $stmtTel->execute();
                    $resTel = $stmtTel->get_result();
                    if ($resTel && $resTel->num_rows > 0) {
                        $stmtTel->close();
                        return 0; // Teléfono duplicado en la empresa
                    }
                    $stmtTel->close();
                }
            }

            $stmt = $this->con->prepare("INSERT INTO persona (nombrePersona, apellidoPersona, telefono, edad, genero, idUsuario, idEmpresa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) return 0;
            $stmt->bind_param("sssssii", $nombres, $apellidos, $telefono, $edad, $genero, $idUsuario, $idEmpresa);
            $stmt->execute();
            $idInsertado = (int)$stmt->insert_id;
            $stmt->close();
            return $idInsertado;
        }

        public function actualizarPersona(int $idPersona, array $datos): bool
        {
            $nombres = trim((string)($datos['nombrePersona'] ?? ($datos['nombres'] ?? ($datos['NombreCliente'] ?? ''))));
            $apellidos = trim((string)($datos['apellidoPersona'] ?? ($datos['apellidos'] ?? ($datos['apellidosCliente'] ?? ''))));
            $telefono = trim((string)($datos['telefono'] ?? ''));
            $edad = trim((string)($datos['edad'] ?? ''));
            $genero = trim((string)($datos['genero'] ?? ''));
            $idUsuario = (int)($datos['idUsuario'] ?? 0);

            $stmt = $this->con->prepare("UPDATE persona SET nombrePersona = ?, apellidoPersona = ?, telefono = ?, edad = ?, genero = ?, idUsuario = ? WHERE idPersona = ?");
            if (!$stmt) return false;
            $stmt->bind_param("sssssii", $nombres, $apellidos, $telefono, $edad, $genero, $idUsuario, $idPersona);
            $res = $stmt->execute();
            $stmt->close();
            return (bool)$res;
        }

        public function eliminarPersona(int $idPersona): bool
        {
            $stmt = $this->con->prepare("DELETE FROM persona WHERE idPersona = ?");
            if (!$stmt) return false;
            $stmt->bind_param("i", $idPersona);
            $res = $stmt->execute();
            $stmt->close();
            return (bool)$res;
        }
    }
}
?>
