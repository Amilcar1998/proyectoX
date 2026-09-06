<?php
require_once __DIR__ . "/../db/conexion.php";
require_once __DIR__ . "/../models/Empleado.php";
require_once __DIR__ . "/../models/Usuario.php";

if (!class_exists('EmpleadoModel')) {
    class EmpleadoModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerEmpleados(int $idEmpresa = 0): array
        {
            $condicion = "";
            if ($idEmpresa > 0) {
                $condicion = " WHERE (e.idEmpresa = " . (int)$idEmpresa . " OR (e.idEmpresa IS NULL AND u.idEmpresa = " . (int)$idEmpresa . ")) ";
            }
            $res = $this->con->query(
                "SELECT e.idEmpleado, e.nombreEmp, e.apellido, e.genero, e.idPuesto, e.idUsuario, 
                        p.nombrePuesto, u.username, u.id_Rol, r.nombreRol,
                        COALESCE(e.activo, 1) AS activo
                 FROM empleado e
                 LEFT JOIN puesto p ON e.idPuesto = p.idPuesto
                 LEFT JOIN usuarios u ON e.idUsuario = u.idUsuario
                 LEFT JOIN rol r ON u.id_Rol = r.id_Rol
                 $condicion
                 ORDER BY e.idEmpleado ASC"
            );

            $r = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $emp = new Empleado(
                        (int)$row['idEmpleado'],
                        (string)$row['nombreEmp'],
                        (string)$row['apellido'],
                        (string)$row['genero'],
                        (string)($row['nombrePuesto'] ?? 'Sin Asignar'),
                        (string)($row['username'] ?? 'Sin Usuario'),
                        (int)$row['idPuesto'],
                        (int)($row['idUsuario'] ?? 0),
                        (int)($row['id_Rol'] ?? 2),
                        (string)($row['nombreRol'] ?? 'Empleado'),
                        (int)$row['activo']
                    );
                    $r[] = $emp;
                }
            }
            return $r;
        }

        public function obtenerEmpleadoPorId(int $idEmpleado): ?Empleado
        {
            $stmt = $this->con->prepare(
                "SELECT e.idEmpleado, e.nombreEmp, e.apellido, e.genero, e.idPuesto, e.idUsuario, 
                        p.nombrePuesto, u.username, u.id_Rol, r.nombreRol,
                        COALESCE(e.activo, 1) AS activo
                 FROM empleado e
                 LEFT JOIN puesto p ON e.idPuesto = p.idPuesto
                 LEFT JOIN usuarios u ON e.idUsuario = u.idUsuario
                 LEFT JOIN rol r ON u.id_Rol = r.id_Rol
                 WHERE e.idEmpleado = ? LIMIT 1"
            );
            if (!$stmt) return null;
            $stmt->bind_param("i", $idEmpleado);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $stmt->close();
                return new Empleado(
                    (int)$row['idEmpleado'],
                    (string)$row['nombreEmp'],
                    (string)$row['apellido'],
                    (string)$row['genero'],
                    (string)($row['nombrePuesto'] ?? 'Sin Asignar'),
                    (string)($row['username'] ?? 'Sin Usuario'),
                    (int)$row['idPuesto'],
                    (int)($row['idUsuario'] ?? 0),
                    (int)($row['id_Rol'] ?? 2),
                    (string)($row['nombreRol'] ?? 'Empleado'),
                    (int)$row['activo']
                );
            }
            $stmt->close();
            return null;
        }

        public function getEmpleado(): array
        {
            return $this->obtenerEmpleados();
        }

        public function obtenerRolesSistema(): array
        {
            $sql = "SELECT r.id_Rol, r.nombreRol, r.descripcion, r.idRolPadre, r.acceso_total, 
                           p.nombreRol AS nombreRolPadre 
                    FROM rol r 
                    LEFT JOIN rol p ON r.idRolPadre = p.id_Rol 
                    WHERE r.id_Rol != 3 AND r.activo = 1 
                    ORDER BY COALESCE(r.idRolPadre, r.id_Rol) ASC, r.idRolPadre ASC, r.id_Rol ASC";
            $res = $this->con->query($sql);
            $r = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $r[] = $row;
                }
            }
            return $r;
        }

        public function obtenerCargos(): array
        {
            $res = $this->con->query("SELECT * FROM puesto ORDER BY nombrePuesto ASC");
            $r = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $r[] = $row;
                }
            }
            return $r;
        }

        public function getCargo(): array
        {
            return $this->obtenerCargos();
        }

        public function obtenerUsuarios(): array
        {
            $res = $this->con->query("SELECT idUsuario, username, id_Rol FROM usuarios ORDER BY idUsuario ASC");
            $r = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $r[] = $row;
                }
            }
            return $r;
        }

        public function getUsuarios(): array
        {
            return $this->obtenerUsuarios();
        }

        /**
         * Obtiene el dominio de correo correspondiente a una empresa
         * @param int $idEmpresa
         * @return string
         */
        public function obtenerDominioPorEmpresa(int $idEmpresa = 1): string
        {
            if ($idEmpresa <= 1) {
                return 'gordito.com';
            }
            $stmt = $this->con->prepare("SELECT slug, correo, nombreEmpresa FROM empresas WHERE idEmpresa = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("i", $idEmpresa);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $stmt->close();
                    $correo = trim((string)($row['correo'] ?? ''));
                    if (!empty($correo) && strpos($correo, '@') !== false) {
                        $partes = explode('@', $correo);
                        $dom = trim($partes[1] ?? '');
                        if (!empty($dom)) return $dom;
                    }
                    $slug = trim((string)($row['slug'] ?? ''));
                    if (!empty($slug)) {
                        $slugLimpio = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($slug));
                        if (!empty($slugLimpio)) return "{$slugLimpio}.com";
                    }
                    $nombre = trim((string)($row['nombreEmpresa'] ?? ''));
                    if (!empty($nombre)) {
                        $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($nombre));
                        if (!empty($nombreLimpio)) return "{$nombreLimpio}.com";
                    }
                } else {
                    $stmt->close();
                }
            }
            return 'gordito.com';
        }

        public function generarUsernameUnico(string $nombre, string $apellido, int $idEmpresa = 1): string
        {
            $nombres = array_values(array_filter(preg_split('/\s+/', trim($nombre))));
            $apellidos = array_values(array_filter(preg_split('/\s+/', trim($apellido))));

            $pNombre = strtolower($this->limpiarTexto($nombres[0] ?? 'empleado'));
            $sNombre = isset($nombres[1]) ? strtolower($this->limpiarTexto($nombres[1])) : '';
            
            $pApellido = strtolower($this->limpiarTexto($apellidos[0] ?? 'usuario'));
            $sApellido = isset($apellidos[1]) ? strtolower($this->limpiarTexto($apellidos[1])) : '';

            $dominioTexto = $this->obtenerDominioPorEmpresa($idEmpresa);
            $dominio = "@{$dominioTexto}";
            $candidatos = [];

            // 1. Primer nombre . Primer apellido (ej: juan.perez@dominio.com)
            $candidatos[] = "{$pNombre}.{$pApellido}{$dominio}";

            // 2. Si tiene segundo nombre, agregar letras del segundo nombre (ej: juanc.perez, juanca.perez, juancarlos.perez)
            if (!empty($sNombre)) {
                $candidatos[] = "{$pNombre}" . substr($sNombre, 0, 1) . ".{$pApellido}{$dominio}";
                $candidatos[] = "{$pNombre}" . substr($sNombre, 0, 2) . ".{$pApellido}{$dominio}";
                $candidatos[] = "{$pNombre}{$sNombre}.{$pApellido}{$dominio}";
            }

            // 3. Si tiene segundo apellido, agregar letras del segundo apellido (ej: juan.perezl)
            if (!empty($sApellido)) {
                $candidatos[] = "{$pNombre}.{$pApellido}" . substr($sApellido, 0, 1) . "{$dominio}";
            }

            // 4. Si tiene segundo nombre y segundo apellido (ej: juanc.perezl)
            if (!empty($sNombre) && !empty($sApellido)) {
                $candidatos[] = "{$pNombre}" . substr($sNombre, 0, 1) . ".{$pApellido}" . substr($sApellido, 0, 1) . "{$dominio}";
            }

            // 5. Variaciones progresivas usando letras del primer nombre (ej: j.perez, ju.perez, jua.perez, jperez, etc.)
            $lenNombre = strlen($pNombre);
            for ($i = 1; $i <= $lenNombre; $i++) {
                $sub = substr($pNombre, 0, $i);
                $candidatos[] = "{$sub}.{$pApellido}{$dominio}";
                $candidatos[] = "{$sub}{$pApellido}{$dominio}";
            }

            // Probar candidatos en orden
            $candidatosUnicos = array_values(array_unique($candidatos));
            foreach ($candidatosUnicos as $candidato) {
                if (!$this->existeUsername($candidato)) {
                    return $candidato;
                }
            }

            // Si todas las combinaciones con letras del nombre existen, agregar sufijo numérico
            $base = "{$pNombre}.{$pApellido}";
            $contador = 2;
            while (true) {
                $username = "{$base}{$contador}{$dominio}";
                if (!$this->existeUsername($username)) {
                    return $username;
                }
                $contador++;
            }
        }

        private function limpiarTexto(string $str): string
        {
            $unwanted = [
                'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
                'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U',
                'ñ'=>'n','Ñ'=>'N','ü'=>'u','Ü'=>'U'
            ];
            $str = strtr($str, $unwanted);
            return preg_replace('/[^a-zA-Z0-9]/', '', $str);
        }

        public function existeUsername(string $username): bool
        {
            $stmt = $this->con->prepare("SELECT idUsuario FROM usuarios WHERE username = ? LIMIT 1");
            if (!$stmt) return false;
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $res = $stmt->get_result();
            $existe = ($res->num_rows > 0);
            $stmt->close();
            return $existe;
        }

        public function crearEmpleadoConUsuario(array $datos): array
        {
            $nombre = trim($datos['nombre'] ?? '');
            $apellido = trim($datos['apellido'] ?? '');
            $genero = $datos['genero'] ?? '';
            $idPuesto = (int)($datos['idPuesto'] ?? 2);
            $idEmpresa = (int)($datos['idEmpresa'] ?? 1);
            if ($idEmpresa <= 0) {
                $idEmpresa = 1;
            }

            if (empty($nombre) || empty($apellido)) {
                return ['exito' => false, 'mensaje' => 'Nombre y apellidos son obligatorios.'];
            }

            // Determinar rol del usuario según parámetro o según el puesto
            $idRol = (int)($datos['idRol'] ?? 0);
            if ($idRol <= 0) {
                $idRol = 2; // Empleado por defecto
                if ($idPuesto === 1) {
                    $idRol = 1; // Gerente General
                } elseif ($idPuesto === 12) {
                    $idRol = 4; // Sistemas / Admin
                }
            }

            $username = $this->generarUsernameUnico($nombre, $apellido, $idEmpresa);
            $passHash = sha1('123456');
            $debeCambiar = 1;

            // 1. Insertar Usuario
            $stmtUser = $this->con->prepare("INSERT INTO usuarios (username, pass, id_Rol, idEmpresa, debe_cambiar_pass, activo) VALUES (?, ?, ?, ?, ?, 1)");
            if (!$stmtUser) {
                return ['exito' => false, 'mensaje' => 'Error al preparar la creación de usuario.'];
            }
            $stmtUser->bind_param("ssiii", $username, $passHash, $idRol, $idEmpresa, $debeCambiar);
            $stmtUser->execute();
            $idUsuario = $this->con->insert_id;
            $stmtUser->close();

            if (!$idUsuario) {
                return ['exito' => false, 'mensaje' => 'No se pudo generar el usuario institucional.'];
            }

            // 2. Insertar Empleado
            $stmtEmp = $this->con->prepare("INSERT INTO empleado (nombreEmp, apellido, genero, idPuesto, idUsuario, idEmpresa, activo) VALUES (?, ?, ?, ?, ?, ?, 1)");
            if (!$stmtEmp) {
                return ['exito' => false, 'mensaje' => 'Error al preparar el registro del empleado.'];
            }
            $stmtEmp->bind_param("sssiii", $nombre, $apellido, $genero, $idPuesto, $idUsuario, $idEmpresa);
            $exitoEmp = $stmtEmp->execute();
            $idEmpleado = $this->con->insert_id;
            $stmtEmp->close();

            if (!$exitoEmp) {
                // Revertir usuario si falló el empleado
                $this->con->query("DELETE FROM usuarios WHERE idUsuario = $idUsuario");
                return ['exito' => false, 'mensaje' => 'Error al guardar el empleado en base de datos.'];
            }

            return [
                'exito' => true,
                'mensaje' => "Empleado registrado exitosamente. Se creó automáticamente el usuario: $username",
                'username' => $username,
                'idEmpleado' => $idEmpleado,
                'idUsuario' => $idUsuario,
                'idRol' => $idRol
            ];
        }

        public function modificarEmpleado(Empleado $e): bool
        {
            $nombre = $e->getNombre();
            $apellido = $e->getApellido();
            $genero = $e->getGenero();
            $idPuesto = (int)$e->getIdPuesto();
            $idEmpleado = (int)$e->getIdEmpleado();
            $idUsuario = (int)$e->getIdUsuario();
            $idRol = (int)$e->getIdRol();

            if ($idRol <= 0) {
                $idRol = 2;
                if ($idPuesto === 1) {
                    $idRol = 1;
                } elseif ($idPuesto === 12) {
                    $idRol = 4;
                }
            }

            $stmt = $this->con->prepare("UPDATE empleado SET nombreEmp = ?, apellido = ?, genero = ?, idPuesto = ? WHERE idEmpleado = ?");
            if (!$stmt) return false;
            $stmt->bind_param("sssii", $nombre, $apellido, $genero, $idPuesto, $idEmpleado);
            $exito = $stmt->execute();
            $stmt->close();

            // Actualizar rol del usuario asociado
            if ($idUsuario > 0) {
                $stmtRol = $this->con->prepare("UPDATE usuarios SET id_Rol = ? WHERE idUsuario = ?");
                if ($stmtRol) {
                    $stmtRol->bind_param("ii", $idRol, $idUsuario);
                    $stmtRol->execute();
                    $stmtRol->close();
                }
            }

            return (bool)$exito;
        }

        public function cambiarEstadoEmpleado(int $idEmpleado, int $nuevoEstado): bool
        {
            // Obtener idUsuario antes de cambiar estado
            $stmtGet = $this->con->prepare("SELECT idUsuario FROM empleado WHERE idEmpleado = ? LIMIT 1");
            if (!$stmtGet) return false;
            $stmtGet->bind_param("i", $idEmpleado);
            $stmtGet->execute();
            $res = $stmtGet->get_result();
            $idUsuario = 0;
            if ($row = $res->fetch_assoc()) {
                $idUsuario = (int)$row['idUsuario'];
            }
            $stmtGet->close();

            // Cambiar estado en tabla empleado
            $stmt = $this->con->prepare("UPDATE empleado SET activo = ? WHERE idEmpleado = ?");
            if (!$stmt) return false;
            $stmt->bind_param("ii", $nuevoEstado, $idEmpleado);
            $exito = $stmt->execute();
            $stmt->close();

            // Cambiar estado en tabla usuarios asociada
            if ($idUsuario > 0) {
                $stmtU = $this->con->prepare("UPDATE usuarios SET activo = ? WHERE idUsuario = ?");
                if ($stmtU) {
                    $stmtU->bind_param("ii", $nuevoEstado, $idUsuario);
                    $stmtU->execute();
                    $stmtU->close();
                }
            }

            return (bool)$exito;
        }

        public function eliminarEmpleadoPorId(int $idEmpleado): bool
        {
            return $this->cambiarEstadoEmpleado($idEmpleado, 0);
        }

        public function getSessionEmp($correo = null): array
        {
            if ($correo === null) {
                $correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? '');
            }
            $correo = $this->con->real_escape_string($correo);
            $res = $this->con->query("SELECT e.nombreEmp, e.apellido FROM empleado e INNER JOIN usuarios u ON e.idUsuario = u.idUsuario WHERE u.username = '$correo' LIMIT 1");
            $r = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $r[] = $row;
                }
            }
            return $r;
        }

        // Compatibilidad con firmas heredadas
        public function insertarUsuario($u) {}
        public function insertarEmpleado($e) {}
        public function eliminarUsuario($usuario) {}
        public function eliminarEmpleado($e) {
            $id = is_object($e) ? (int)$e->getIdEmpleado() : (int)$e;
            return $this->eliminarEmpleadoPorId($id);
        }
        public function getUser($user) { return []; }
        public function obtenerID($emp) { return []; }
    }
}