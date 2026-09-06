<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/PermisoModel.php';

if (!class_exists('RolModel')) {
    class RolModel extends Conexion
    {
        private PermisoModel $permisoModel;

        public function __construct()
        {
            parent::__construct();
            $this->permisoModel = new PermisoModel();
        }

        public function listarRoles(): array
        {
            $sql = "SELECT r.id_Rol, r.nombreRol, r.descripcion, r.idRolPadre, r.submodulos, 
                           r.acceso_total, r.activo, p.nombreRol AS nombreRolPadre 
                    FROM rol r 
                    LEFT JOIN rol p ON r.idRolPadre = p.id_Rol 
                    ORDER BY COALESCE(r.idRolPadre, r.id_Rol) ASC, r.idRolPadre ASC, r.id_Rol ASC";
            $res = $this->con->query($sql);
            $roles = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $row['submodulosEfectivos'] = $this->permisoModel->obtenerSubmodulosEfectivosDeRol((int)$row['id_Rol']);
                    $roles[] = $row;
                }
            }
            return $roles;
        }

        public function obtenerPorId(int $idRol): ?array
        {
            $stmt = $this->con->prepare(
                "SELECT r.id_Rol, r.nombreRol, r.descripcion, r.idRolPadre, r.submodulos, 
                        r.acceso_total, r.activo, p.nombreRol AS nombreRolPadre 
                 FROM rol r 
                 LEFT JOIN rol p ON r.idRolPadre = p.id_Rol 
                 WHERE r.id_Rol = ? LIMIT 1"
            );
            if (!$stmt) return null;
            $stmt->bind_param("i", $idRol);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            $stmt->close();
            if ($row) {
                $row['submodulosEfectivos'] = $this->permisoModel->obtenerSubmodulosEfectivosDeRol($idRol);
            }
            return $row;
        }

        public function guardar(array $datos): array
        {
            $nombre = trim($datos['nombreRol'] ?? '');
            $descripcion = trim($datos['descripcion'] ?? '');
            $idRolPadre = !empty($datos['idRolPadre']) ? (int)$datos['idRolPadre'] : null;
            $accesoTotal = !empty($datos['acceso_total']) ? 1 : 0;
            $submodulos = isset($datos['submodulos']) && is_array($datos['submodulos']) ? json_encode(array_map('intval', $datos['submodulos'])) : null;
            $activo = 1;

            if (empty($nombre)) {
                return ['exito' => false, 'mensaje' => 'El nombre del rol es obligatorio.'];
            }

            $idRol = $this->generarSiguienteId();

            $stmt = $this->con->prepare(
                "INSERT INTO rol (id_Rol, nombreRol, descripcion, idRolPadre, submodulos, acceso_total, activo) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            if (!$stmt) {
                return ['exito' => false, 'mensaje' => 'Error al preparar el registro del rol.'];
            }

            $stmt->bind_param("issisii", $idRol, $nombre, $descripcion, $idRolPadre, $submodulos, $accesoTotal, $activo);
            $exito = $stmt->execute();
            $stmt->close();

            if ($exito) {
                // Configurar módulos heredados en permisos_rol
                $this->sincronizarPermisosModuloRol($idRol, $idRolPadre);
                return ['exito' => true, 'mensaje' => "Rol '$nombre' registrado exitosamente con ID #$idRol.", 'idRol' => $idRol];
            }

            return ['exito' => false, 'mensaje' => 'Error al guardar el rol en base de datos.'];
        }

        public function actualizar(int $idRol, array $datos): array
        {
            $nombre = trim($datos['nombreRol'] ?? '');
            $descripcion = trim($datos['descripcion'] ?? '');
            $idRolPadre = !empty($datos['idRolPadre']) ? (int)$datos['idRolPadre'] : null;
            $accesoTotal = !empty($datos['acceso_total']) ? 1 : 0;
            $submodulos = isset($datos['submodulos']) && is_array($datos['submodulos']) ? json_encode(array_map('intval', $datos['submodulos'])) : null;
            $activo = isset($datos['activo']) ? (int)$datos['activo'] : 1;

            if (empty($nombre)) {
                return ['exito' => false, 'mensaje' => 'El nombre del rol es obligatorio.'];
            }

            // Evitar que un rol sea padre de sí mismo
            if ($idRolPadre === $idRol) {
                $idRolPadre = null;
            }

            // Proteger roles raíz del sistema (Gerente y Admin)
            if (in_array($idRol, [1, 4], true)) {
                $activo = 1;
            }

            $stmt = $this->con->prepare(
                "UPDATE rol 
                 SET nombreRol = ?, descripcion = ?, idRolPadre = ?, submodulos = ?, acceso_total = ?, activo = ?
                 WHERE id_Rol = ?"
            );
            if (!$stmt) {
                return ['exito' => false, 'mensaje' => 'Error al preparar la actualización del rol.'];
            }

            $stmt->bind_param("ssisiii", $nombre, $descripcion, $idRolPadre, $submodulos, $accesoTotal, $activo, $idRol);
            $exito = $stmt->execute();
            $stmt->close();

            if ($exito) {
                $this->sincronizarPermisosModuloRol($idRol, $idRolPadre);
                return ['exito' => true, 'mensaje' => "Rol '$nombre' (#$idRol) actualizado exitosamente."];
            }

            return ['exito' => false, 'mensaje' => 'Error al actualizar los datos del rol.'];
        }

        public function cambiarEstado(int $idRol, int $nuevoEstado): array
        {
            // Proteger roles raíz del sistema contra desactivación para evitar lockout
            $rolesProtegidos = [1, 4];
            if (in_array($idRol, $rolesProtegidos, true) && $nuevoEstado === 0) {
                return ['exito' => false, 'mensaje' => 'Los roles de administración raíz (Gerente y Administrador) no pueden desactivarse para prevenir el bloqueo total del sistema.'];
            }

            $stmt = $this->con->prepare("UPDATE rol SET activo = ? WHERE id_Rol = ?");
            if (!$stmt) {
                return ['exito' => false, 'mensaje' => 'Error al preparar el cambio de estado.'];
            }

            $stmt->bind_param("ii", $nuevoEstado, $idRol);
            $exito = $stmt->execute();
            $stmt->close();

            $estadoTxt = ($nuevoEstado === 1) ? 'activado' : 'desactivado';
            if ($exito) {
                return ['exito' => true, 'mensaje' => "El rol #$idRol ha sido $estadoTxt exitosamente."];
            }

            return ['exito' => false, 'mensaje' => 'No se pudo actualizar el estado del rol.'];
        }

        private function generarSiguienteId(): int
        {
            $res = $this->con->query("SELECT MAX(id_Rol) AS maxId FROM rol");
            $row = $res ? $res->fetch_assoc() : null;
            $max = (int)($row['maxId'] ?? 0);
            return max($max + 1, 8);
        }

        private function sincronizarPermisosModuloRol(int $idRol, ?int $idRolPadre): void
        {
            $padre = $idRolPadre ?? 2; // Por defecto hereda módulos de empleado
            $stmt = $this->con->prepare(
                "INSERT INTO permisos_rol (id_Rol, idModulo, permitido) 
                 SELECT ?, idModulo, permitido FROM permisos_rol WHERE id_Rol = ? 
                 ON DUPLICATE KEY UPDATE permitido = VALUES(permitido)"
            );
            if ($stmt) {
                $stmt->bind_param("ii", $idRol, $padre);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}
