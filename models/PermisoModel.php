<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('PermisoModel')) {
    class PermisoModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
            $this->inicializarEsquema();
        }

        public function inicializarEsquema(): bool
        {
            $sqlFile = __DIR__ . '/../db/permisos.sql';
            if (!file_exists($sqlFile)) return false;

            // Verificar si la tabla modulos ya existe
            $check = $this->con->query("SHOW TABLES LIKE 'submodulos'");
            if ($check && $check->num_rows > 0) return true;

            $sql = file_get_contents($sqlFile);
            return (bool)$this->con->multi_query($sql);
        }

        public function obtenerModulosPorRol(int $idRol, int $idUsuario = 0): array
        {
            $stmt = $this->con->prepare(
                "SELECT m.idModulo, m.nombre, m.controlador, m.icono, m.orden 
                 FROM modulos m 
                 INNER JOIN permisos_rol pr ON m.idModulo = pr.idModulo 
                 WHERE pr.id_Rol = ? AND pr.permitido = 1 AND m.activo = 1 
                 ORDER BY m.orden ASC"
            );
            if (!$stmt) return [];
            $stmt->bind_param("i", $idRol);
            $stmt->execute();
            $res = $stmt->get_result();
            $modulos = [];
            while ($fila = $res->fetch_assoc()) {
                $fila['submodulos'] = $this->obtenerSubmodulosPorRol($idRol, (int)$fila['idModulo'], $idUsuario);
                $modulos[] = $fila;
            }
            $stmt->close();
            return $modulos;
        }

        public function obtenerSubmodulosEfectivosDeRol(int $idRol): array
        {
            $stmt = $this->con->prepare("SELECT id_Rol, idRolPadre, submodulos, acceso_total FROM rol WHERE id_Rol = ? AND activo = 1 LIMIT 1");
            if (!$stmt) return [];
            $stmt->bind_param("i", $idRol);
            $stmt->execute();
            $rol = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$rol) return [];

            // Si tiene acceso total (Gerente o Admin), retorna todos los submódulos activos
            if ((int)($rol['acceso_total'] ?? 0) === 1) {
                $resAll = $this->con->query("SELECT idSubmodulo FROM submodulos WHERE activo = 1");
                $all = [];
                if ($resAll) {
                    while ($r = $resAll->fetch_assoc()) {
                        $all[] = (int)$r['idSubmodulo'];
                    }
                }
                return $all;
            }

            $propios = [];
            if (!empty($rol['submodulos'])) {
                $decoded = json_decode($rol['submodulos'], true);
                if (is_array($decoded)) {
                    $propios = array_map('intval', $decoded);
                }
            }

            // Herencia recursiva del rol padre (ej: Jefe hereda de Empleado)
            $heredados = [];
            if (!empty($rol['idRolPadre'])) {
                $heredados = $this->obtenerSubmodulosEfectivosDeRol((int)$rol['idRolPadre']);
            }

            return array_values(array_unique(array_merge($heredados, $propios)));
        }

        public function obtenerRolesConJerarquia(): array
        {
            $sql = "SELECT r.id_Rol, r.nombreRol, r.descripcion, r.idRolPadre, r.acceso_total,
                           p.nombreRol AS nombreRolPadre 
                    FROM rol r 
                    LEFT JOIN rol p ON r.idRolPadre = p.id_Rol 
                    WHERE r.activo = 1 
                    ORDER BY COALESCE(r.idRolPadre, r.id_Rol) ASC, r.idRolPadre ASC, r.id_Rol ASC";
            $res = $this->con->query($sql);
            $roles = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $row['submodulosEfectivos'] = $this->obtenerSubmodulosEfectivosDeRol((int)$row['id_Rol']);
                    $roles[] = $row;
                }
            }
            return $roles;
        }

        public function obtenerSubmodulosPorRol(int $idRol, int $idModulo, int $idUsuario = 0): array
        {
            // Si el usuario tiene permisos personalizados explícitos en permisos_usuario_submodulo
            if ($idUsuario > 0) {
                $stmtCheck = $this->con->prepare("SELECT COUNT(*) AS total FROM permisos_usuario_submodulo WHERE idUsuario = ?");
                if ($stmtCheck) {
                    $stmtCheck->bind_param("i", $idUsuario);
                    $stmtCheck->execute();
                    $rCheck = $stmtCheck->get_result()->fetch_assoc();
                    $stmtCheck->close();
                    if ((int)($rCheck['total'] ?? 0) > 0) {
                        $stmtUser = $this->con->prepare(
                            "SELECT s.idSubmodulo, s.idModulo, s.nombre, s.controlador_accion, s.icono, s.orden 
                             FROM submodulos s 
                             INNER JOIN permisos_usuario_submodulo pus ON s.idSubmodulo = pus.idSubmodulo 
                             WHERE pus.idUsuario = ? AND s.idModulo = ? AND pus.permitido = 1 AND s.activo = 1 
                             ORDER BY s.orden ASC"
                        );
                        if ($stmtUser) {
                            $stmtUser->bind_param("ii", $idUsuario, $idModulo);
                            $stmtUser->execute();
                            $resUser = $stmtUser->get_result();
                            $subsUser = [];
                            while ($fU = $resUser->fetch_assoc()) {
                                $subsUser[] = $fU;
                            }
                            $stmtUser->close();
                            return $subsUser;
                        }
                    }
                }
            }

            // Rol 1 (Gerente) y Rol 4 (Admin) tienen acceso a todos los submódulos activos del módulo por defecto
            if ($idRol === 1 || $idRol === 4) {
                $stmt = $this->con->prepare(
                    "SELECT idSubmodulo, idModulo, nombre, controlador_accion, icono, orden 
                     FROM submodulos 
                     WHERE idModulo = ? AND activo = 1 
                     ORDER BY orden ASC"
                );
                if (!$stmt) return [];
                $stmt->bind_param("i", $idModulo);
                $stmt->execute();
                $res = $stmt->get_result();
                $subs = [];
                while ($fila = $res->fetch_assoc()) {
                    $subs[] = $fila;
                }
                $stmt->close();
                return $subs;
            }

            // Consultar submódulos efectivos heredados desde la jerarquía de roles en la tabla rol
            $efectivos = $this->obtenerSubmodulosEfectivosDeRol($idRol);
            if (!empty($efectivos)) {
                $placeholders = implode(',', array_fill(0, count($efectivos), '?'));
                $types = str_repeat('i', count($efectivos) + 1);
                $sqlSub = "SELECT idSubmodulo, idModulo, nombre, controlador_accion, icono, orden 
                           FROM submodulos 
                           WHERE idModulo = ? AND idSubmodulo IN ($placeholders) AND activo = 1 
                           ORDER BY orden ASC";
                $stmt = $this->con->prepare($sqlSub);
                if ($stmt) {
                    $params = array_merge([$idModulo], $efectivos);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $subs = [];
                    while ($fila = $res->fetch_assoc()) {
                        $subs[] = $fila;
                    }
                    $stmt->close();
                    return $subs;
                }
            }

            return [];
        }

        public function obtenerCatalogoSubmodulosConModulo(): array
        {
            $sql = "SELECT m.idModulo, m.nombre AS nombreModulo, m.icono AS iconoModulo, 
                           s.idSubmodulo, s.nombre AS nombreSubmodulo, s.controlador_accion, s.icono AS iconoSubmodulo 
                    FROM modulos m 
                    INNER JOIN submodulos s ON m.idModulo = s.idModulo 
                    WHERE s.activo = 1 
                    ORDER BY m.orden ASC, s.orden ASC";
            $res = $this->con->query($sql);
            $agrupados = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $idMod = (int)$row['idModulo'];
                    if (!isset($agrupados[$idMod])) {
                        $agrupados[$idMod] = [
                            'idModulo' => $idMod,
                            'nombreModulo' => $row['nombreModulo'],
                            'iconoModulo' => $row['iconoModulo'],
                            'submodulos' => []
                        ];
                    }
                    $agrupados[$idMod]['submodulos'][] = [
                        'idSubmodulo' => (int)$row['idSubmodulo'],
                        'nombre' => $row['nombreSubmodulo'],
                        'controlador_accion' => $row['controlador_accion'],
                        'icono' => $row['iconoSubmodulo']
                    ];
                }
            }
            return array_values($agrupados);
        }

        public function obtenerSubmodulosDeUsuario(int $idUsuario): array
        {
            if ($idUsuario <= 0) return [];
            $stmt = $this->con->prepare(
                "SELECT idSubmodulo FROM permisos_usuario_submodulo WHERE idUsuario = ? AND permitido = 1"
            );
            if (!$stmt) return [];
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $subs = [];
            while ($row = $res->fetch_assoc()) {
                $subs[] = (int)$row['idSubmodulo'];
            }
            $stmt->close();
            return $subs;
        }

        public function guardarPermisosSubmodulosUsuario(int $idUsuario, array $submodulosPermitidos): bool
        {
            if ($idUsuario <= 0) return false;

            $stmtDel = $this->con->prepare("DELETE FROM permisos_usuario_submodulo WHERE idUsuario = ?");
            if ($stmtDel) {
                $stmtDel->bind_param("i", $idUsuario);
                $stmtDel->execute();
                $stmtDel->close();
            }

            if (empty($submodulosPermitidos)) return true;

            $stmtIns = $this->con->prepare(
                "INSERT INTO permisos_usuario_submodulo (idUsuario, idSubmodulo, permitido) VALUES (?, ?, 1)"
            );
            if (!$stmtIns) return false;

            foreach ($submodulosPermitidos as $idSub) {
                $idSubInt = (int)$idSub;
                if ($idSubInt > 0) {
                    $stmtIns->bind_param("ii", $idUsuario, $idSubInt);
                    $stmtIns->execute();
                }
            }
            $stmtIns->close();
            return true;
        }

        public function verificarAcceso(int $idRol, string $controlador, int $idUsuario = 0): bool
        {
            // Controladores públicos o no sujetos a permisos
            $publicos = ['controlUser.php', 'controllerRecuperarClave.php', 'reset_password.php', 'index.php', 'controllerCambiarClave.php'];
            if (in_array($controlador, $publicos, true)) return true;

            // Rol 4 (Admin) tiene acceso total sin restricciones
            if ($idRol === 4 && $idUsuario <= 0) return true;

            // 1. Si el usuario tiene permisos granulares personalizados explícitos en permisos_usuario_submodulo
            if ($idUsuario > 0) {
                $stmtUserCheck = $this->con->prepare("SELECT COUNT(*) AS total FROM permisos_usuario_submodulo WHERE idUsuario = ?");
                if ($stmtUserCheck) {
                    $stmtUserCheck->bind_param("i", $idUsuario);
                    $stmtUserCheck->execute();
                    $rUCheck = $stmtUserCheck->get_result()->fetch_assoc();
                    $stmtUserCheck->close();
                    if ((int)($rUCheck['total'] ?? 0) > 0) {
                        $stmtUserSub = $this->con->prepare(
                            "SELECT s.idSubmodulo, pus.permitido 
                             FROM submodulos s 
                             LEFT JOIN permisos_usuario_submodulo pus ON s.idSubmodulo = pus.idSubmodulo AND pus.idUsuario = ? 
                             WHERE s.controlador_accion LIKE ? AND s.activo = 1 
                             LIMIT 1"
                        );
                        if ($stmtUserSub) {
                            $paramC = $controlador . '%';
                            $stmtUserSub->bind_param("is", $idUsuario, $paramC);
                            $stmtUserSub->execute();
                            $resUS = $stmtUserSub->get_result();
                            if ($rowUS = $resUS->fetch_assoc()) {
                                $stmtUserSub->close();
                                return ((int)($rowUS['permitido'] ?? 0) === 1);
                            }
                            $stmtUserSub->close();
                        }
                    }
                }
            }

            if ($idRol === 4) return true;

            // Rol 1 (Gerente) tiene acceso total a todos los submódulos de sus módulos permitidos por defecto
            if ($idRol === 1) {
                $stmtParent = $this->con->prepare(
                    "SELECT pr.permitido 
                     FROM submodulos s 
                     INNER JOIN permisos_rol pr ON s.idModulo = pr.idModulo 
                     WHERE pr.id_Rol = 1 AND s.controlador_accion LIKE ? AND s.activo = 1 
                     LIMIT 1"
                );
                if ($stmtParent) {
                    $pC = $controlador . '%';
                    $stmtParent->bind_param("s", $pC);
                    $stmtParent->execute();
                    $rParent = $stmtParent->get_result()->fetch_assoc();
                    $stmtParent->close();
                    if ($rParent) {
                        return ((int)($rParent['permitido'] ?? 0) === 1);
                    }
                }
            }

            // 2. Verificar si el controlador corresponde a un submódulo específico por Rol (o herencia)
            $stmtSub = $this->con->prepare(
                "SELECT s.idSubmodulo 
                 FROM submodulos s 
                 WHERE s.controlador_accion LIKE ? AND s.activo = 1 
                 LIMIT 1"
            );
            if ($stmtSub) {
                $paramControlador = $controlador . '%';
                $stmtSub->bind_param("s", $paramControlador);
                $stmtSub->execute();
                $resSub = $stmtSub->get_result();
                if ($rowSub = $resSub->fetch_assoc()) {
                    $idSubmodulo = (int)$rowSub['idSubmodulo'];
                    $stmtSub->close();
                    
                    // Verificar si el idSubmodulo está en los submódulos efectivos del rol (propios o heredados)
                    $submodulosRol = $this->obtenerSubmodulosEfectivosDeRol($idRol);
                    return in_array($idSubmodulo, $submodulosRol, true);
                }
                $stmtSub->close();
            }

            // Sub-reportes PDF que no estén en submodulos heredan de controllerReportes.php
            $subReportes = [
                'repoClientes.php', 'repoEmpleado.php', 'repoProveedor.php',
                'reporteInventarioEscaso.php', 'reporteInventarioGeneral.php',
                'reporteMezclas.php', 'reportePedidoProveedor.php', 'reportePedidos.php'
            ];
            if (in_array($controlador, $subReportes, true)) {
                $controlador = 'controllerReportes.php';
            }

            // 3. Si no tiene restricción de submódulo, validar permiso sobre el módulo principal
            $stmt = $this->con->prepare(
                "SELECT pr.permitido 
                 FROM modulos m 
                 INNER JOIN permisos_rol pr ON m.idModulo = pr.idModulo 
                 WHERE pr.id_Rol = ? AND m.controlador = ? AND m.activo = 1 
                 LIMIT 1"
            );
            if (!$stmt) return false;
            $stmt->bind_param("is", $idRol, $controlador);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();
            $stmt->close();

            return $fila ? ((int)$fila['permitido'] === 1) : false;
        }

        public function obtenerNombreModulo(string $controlador): string
        {
            $subReportes = [
                'repoClientes.php' => 'Reporte PDF Clientes',
                'repoEmpleado.php' => 'Reporte PDF Empleados',
                'repoProveedor.php' => 'Reporte PDF Proveedores',
                'reporteInventarioEscaso.php' => 'Reporte PDF Inventario Escaso',
                'reporteInventarioGeneral.php' => 'Reporte PDF Inventario General',
                'reporteMezclas.php' => 'Reporte PDF Mezclas',
                'reportePedidoProveedor.php' => 'Reporte PDF Pedidos Proveedor',
                'reportePedidos.php' => 'Reporte PDF Pedidos'
            ];
            if (isset($subReportes[$controlador])) {
                return $subReportes[$controlador];
            }

            $stmtSub = $this->con->prepare("SELECT nombre FROM submodulos WHERE controlador_accion LIKE ? LIMIT 1");
            if ($stmtSub) {
                $p = $controlador . '%';
                $stmtSub->bind_param("s", $p);
                $stmtSub->execute();
                $resS = $stmtSub->get_result();
                if ($rS = $resS->fetch_assoc()) {
                    $stmtSub->close();
                    return (string)$rS['nombre'];
                }
                $stmtSub->close();
            }

            $stmt = $this->con->prepare("SELECT nombre FROM modulos WHERE controlador = ? LIMIT 1");
            if (!$stmt) return basename($controlador, '.php');
            $stmt->bind_param("s", $controlador);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();
            $stmt->close();
            return $fila ? (string)$fila['nombre'] : basename($controlador, '.php');
        }

        public function obtenerNombreRol(int $idRol): string
        {
            $stmt = $this->con->prepare("SELECT nombreRol FROM rol WHERE id_Rol = ? LIMIT 1");
            if (!$stmt) return 'Desconocido';
            $stmt->bind_param("i", $idRol);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();
            $stmt->close();
            return $fila ? (string)$fila['nombreRol'] : 'Desconocido';
        }

        public function obtenerRutaHome(int $idRol): string
        {
            if ($idRol >= 1 && $idRol <= 4) {
                return 'controllerDashboard.php';
            }
            return 'controlUser.php';
        }
    }
}

