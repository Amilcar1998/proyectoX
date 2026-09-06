<?php
/**
 * Modelo de Gestión de Empresas y Credenciales Wompi (Multi-Comercio)
 * Arquitectura 100% MVC - Métodos en Español
 * Concentrados El Gordito
 */

require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('EmpresaModel')) {
    class EmpresaModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Lista todas las empresas registradas en el sistema
         * @return array
         */
        public function listarTodas(): array
        {
            $consulta = "
                SELECT e.*, u.username AS usuarioDueno, r.nombreRol,
                       CONCAT(COALESCE(emp.nombreEmp, ''), ' ', COALESCE(emp.apellido, '')) AS nombreResponsable
                FROM empresas e
                LEFT JOIN usuarios u ON e.idUsuarioDueno = u.idUsuario
                LEFT JOIN rol r ON u.id_Rol = r.id_Rol
                LEFT JOIN empleado emp ON u.idUsuario = emp.idUsuario
                ORDER BY e.idEmpresa ASC
            ";
            $res = $this->con->query($consulta);
            if (!$res) {
                return [];
            }
            $lista = [];
            while ($fila = $res->fetch_assoc()) {
                $lista[] = $fila;
            }
            return $lista;
        }

        /**
         * Obtiene una empresa por su ID
         * @param int $idEmpresa
         * @return array|null
         */
        public function obtenerPorId(int $idEmpresa): ?array
        {
            $stmt = $this->con->prepare("
                SELECT e.*, u.username AS usuarioDueno,
                       CONCAT(COALESCE(emp.nombreEmp, ''), ' ', COALESCE(emp.apellido, '')) AS nombreResponsable
                FROM empresas e
                LEFT JOIN usuarios u ON e.idUsuarioDueno = u.idUsuario
                LEFT JOIN empleado emp ON u.idUsuario = emp.idUsuario
                WHERE e.idEmpresa = ?
                LIMIT 1
            ");
            if (!$stmt) return null;
            $stmt->bind_param("i", $idEmpresa);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $empresa = $resultado->fetch_assoc() ?: null;
            $stmt->close();
            return $empresa;
        }

        /**
         * Obtiene la empresa vinculada a un usuario
         * @param int $idUsuario
         * @return array|null
         */
        public function obtenerPorUsuario(int $idUsuario): ?array
        {
            $stmt = $this->con->prepare("SELECT * FROM empresas WHERE idUsuarioDueno = ? AND activo = 1 LIMIT 1");
            if (!$stmt) return null;
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $empresa = $resultado->fetch_assoc() ?: null;
            $stmt->close();
            return $empresa;
        }

        /**
         * Obtiene una empresa por su slug
         * @param string $slug
         * @return array|null
         */
        public function obtenerPorSlug(string $slug): ?array
        {
            $stmt = $this->con->prepare("SELECT * FROM empresas WHERE slug = ? AND activo = 1 LIMIT 1");
            if (!$stmt) return null;
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $empresa = $resultado->fetch_assoc() ?: null;
            $stmt->close();
            return $empresa;
        }

        /**
         * Extrae el dominio de correo a partir de la información de la empresa
         * @param array $empresa
         * @return string
         */
        public function extraerDominioDeEmpresa(array $empresa): string
        {
            $correo = trim((string)($empresa['correo'] ?? ''));
            if (!empty($correo) && strpos($correo, '@') !== false) {
                $partes = explode('@', $correo);
                $dom = trim($partes[1] ?? '');
                if (!empty($dom)) return $dom;
            }
            $slug = trim((string)($empresa['slug'] ?? ''));
            if (!empty($slug)) {
                $slugLimpio = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($slug));
                if (!empty($slugLimpio)) return "{$slugLimpio}.com";
            }
            $nombre = trim((string)($empresa['nombreEmpresa'] ?? ''));
            if (!empty($nombre)) {
                $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($nombre));
                if (!empty($nombreLimpio)) return "{$nombreLimpio}.com";
            }
            return 'gordito.com';
        }

        /**
         * Obtiene el dominio de una empresa por su ID
         * @param int $idEmpresa
         * @return string
         */
        public function obtenerDominioEmpresa(int $idEmpresa): string
        {
            if ($idEmpresa <= 1) {
                return 'gordito.com';
            }
            $emp = $this->obtenerPorId($idEmpresa);
            return $emp ? $this->extraerDominioDeEmpresa($emp) : 'gordito.com';
        }

        /**
         * Genera un nombre de usuario único con el dominio de la empresa
         * @param string $nombre
         * @param string $apellido
         * @param string $dominio
         * @return string
         */
        public function generarUsernameConDominio(string $nombre, string $apellido, string $dominio): string
        {
            $n = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', trim(explode(' ', $nombre)[0] ?? 'admin')));
            $a = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', trim(explode(' ', $apellido)[0] ?? 'empresa')));
            $base = ($n && $a) ? "{$n}.{$a}" : ($n ?: $a);
            $usuario = "{$base}@{$dominio}";
            
            $stmt = $this->con->prepare("SELECT idUsuario FROM usuarios WHERE username = ? LIMIT 1");
            if (!$stmt) return $usuario;
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) {
                $stmt->close();
                return $usuario;
            }
            $stmt->close();

            $i = 2;
            while (true) {
                $candidato = "{$base}{$i}@{$dominio}";
                $stmt = $this->con->prepare("SELECT idUsuario FROM usuarios WHERE username = ? LIMIT 1");
                $stmt->bind_param("s", $candidato);
                $stmt->execute();
                $res = $stmt->get_result();
                $ocupado = ($res->num_rows > 0);
                $stmt->close();
                if (!$ocupado) {
                    return $candidato;
                }
                $i++;
            }
        }

        /**
         * Crea un usuario administrador para una empresa recién registrada con su dominio propio
         * @param string $nombre
         * @param string $apellido
         * @param int $idEmpresa
         * @param string $dominio
         * @return int ID del usuario creado
         */
        public function crearUsuarioDuenoEmpresa(string $nombre, string $apellido, int $idEmpresa, string $dominio): int
        {
            $username = $this->generarUsernameConDominio($nombre, $apellido, $dominio);
            $hash = sha1('123456');
            $idRol = 1; // Gerente / Dueño
            $debeCambiar = 1;
            $activo = 1;

            $stmt = $this->con->prepare("INSERT INTO usuarios (username, pass, id_Rol, idEmpresa, debe_cambiar_pass, activo) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) return 0;
            $stmt->bind_param("ssiiii", $username, $hash, $idRol, $idEmpresa, $debeCambiar, $activo);
            $stmt->execute();
            $idUsuario = (int)$this->con->insert_id;
            $stmt->close();

            if ($idUsuario > 0) {
                // Crear registro en empleado vinculado
                $stmtEmp = $this->con->prepare("INSERT INTO empleado (nombreEmp, apellido, genero, idPuesto, idUsuario, idEmpresa, activo) VALUES (?, ?, 'M', 1, ?, ?, 1)");
                if ($stmtEmp) {
                    $stmtEmp->bind_param("ssii", $nombre, $apellido, $idUsuario, $idEmpresa);
                    $stmtEmp->execute();
                    $stmtEmp->close();
                }
            }
            return $idUsuario;
        }

        /**
         * Registra una nueva empresa en el sistema
         * @param array $datos
         * @return int ID de la nueva empresa
         */
        public function guardar(array $datos): int
        {
            $idUsuarioDueno = (int)($datos['idUsuarioDueno'] ?? 0);
            $nombreEmpresa = trim((string)($datos['nombreEmpresa'] ?? 'Mi Negocio'));
            $slug = trim((string)($datos['slug'] ?? ''));
            if (empty($slug)) {
                $slug = $this->generarSlug($nombreEmpresa);
            }
            $direccion = trim((string)($datos['direccion'] ?? ''));
            $telefono = trim((string)($datos['telefono'] ?? ''));
            $correo = trim((string)($datos['correo'] ?? ''));
            $whatsapp = trim((string)($datos['whatsapp'] ?? ''));
            $wompiAppId = trim((string)($datos['wompiAppId'] ?? ''));
            $wompiApiKey = trim((string)($datos['wompiApiKey'] ?? ''));
            $wompiActivo = !empty($datos['wompiActivo']) ? 1 : 0;
            $activo = isset($datos['activo']) ? (int)$datos['activo'] : 1;

            $stmt = $this->con->prepare(
                "INSERT INTO empresas 
                 (idUsuarioDueno, nombreEmpresa, slug, direccion, telefono, correo, whatsapp, wompiAppId, wompiApiKey, wompiActivo, activo)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            if (!$stmt) return 0;
            $stmt->bind_param("issssssssii", $idUsuarioDueno, $nombreEmpresa, $slug, $direccion, $telefono, $correo, $whatsapp, $wompiAppId, $wompiApiKey, $wompiActivo, $activo);
            $stmt->execute();
            $nuevoId = (int)$this->con->insert_id;
            $stmt->close();

            if ($nuevoId > 0) {
                // Si se solicitó crear un nuevo usuario dueño/administrador con el dominio de la empresa
                if (!empty($datos['crearNuevoDueno']) || $idUsuarioDueno <= 0) {
                    $nombreDueno = trim((string)($datos['nombreDueno'] ?? 'Gerente'));
                    $apellidoDueno = trim((string)($datos['apellidoDueno'] ?? $nombreEmpresa));
                    $dominio = $this->extraerDominioDeEmpresa([
                        'correo' => $correo,
                        'slug' => $slug,
                        'nombreEmpresa' => $nombreEmpresa
                    ]);
                    $nuevoIdUsuario = $this->crearUsuarioDuenoEmpresa($nombreDueno, $apellidoDueno, $nuevoId, $dominio);
                    if ($nuevoIdUsuario > 0) {
                        $this->con->query("UPDATE empresas SET idUsuarioDueno = $nuevoIdUsuario WHERE idEmpresa = $nuevoId");
                    }
                } else {
                    // Asociar usuario existente al tenant
                    $this->con->query("UPDATE usuarios SET idEmpresa = $nuevoId WHERE idUsuario = $idUsuarioDueno");
                }
            }

            return $nuevoId;
        }

        /**
         * Actualiza los datos completos de una empresa
         * @param int $idEmpresa
         * @param array $datos
         * @return bool
         */
        public function actualizar(int $idEmpresa, array $datos): bool
        {
            $idUsuarioDueno = (int)($datos['idUsuarioDueno'] ?? 1);
            $nombreEmpresa = trim((string)($datos['nombreEmpresa'] ?? ''));
            $slug = trim((string)($datos['slug'] ?? ''));
            if (empty($slug)) {
                $slug = $this->generarSlug($nombreEmpresa, $idEmpresa);
            }
            $direccion = trim((string)($datos['direccion'] ?? ''));
            $telefono = trim((string)($datos['telefono'] ?? ''));
            $correo = trim((string)($datos['correo'] ?? ''));
            $whatsapp = trim((string)($datos['whatsapp'] ?? ''));
            $wompiAppId = trim((string)($datos['wompiAppId'] ?? ''));
            $wompiApiKey = trim((string)($datos['wompiApiKey'] ?? ''));
            $wompiActivo = !empty($datos['wompiActivo']) ? 1 : 0;
            $activo = isset($datos['activo']) ? (int)$datos['activo'] : 1;

            $stmt = $this->con->prepare(
                "UPDATE empresas 
                 SET idUsuarioDueno = ?, nombreEmpresa = ?, slug = ?, direccion = ?, telefono = ?, correo = ?, whatsapp = ?, wompiAppId = ?, wompiApiKey = ?, wompiActivo = ?, activo = ?
                 WHERE idEmpresa = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param("issssssssiii", $idUsuarioDueno, $nombreEmpresa, $slug, $direccion, $telefono, $correo, $whatsapp, $wompiAppId, $wompiApiKey, $wompiActivo, $activo, $idEmpresa);
            $exito = $stmt->execute();
            $stmt->close();

            if ($exito && $idUsuarioDueno > 0) {
                $this->con->query("UPDATE usuarios SET idEmpresa = $idEmpresa WHERE idUsuario = $idUsuarioDueno");
            }

            return $exito;
        }

        /**
         * Actualiza los datos de perfil comercial y contacto
         * @param int $idEmpresa
         * @param array $datos
         * @return bool
         */
        public function actualizarPerfil(int $idEmpresa, array $datos): bool
        {
            $nombreEmpresa = trim((string)($datos['nombreEmpresa'] ?? ''));
            $direccion = trim((string)($datos['direccion'] ?? ''));
            $telefono = trim((string)($datos['telefono'] ?? ''));
            $correo = trim((string)($datos['correo'] ?? ''));
            $whatsapp = trim((string)($datos['whatsapp'] ?? ''));

            $stmt = $this->con->prepare(
                "UPDATE empresas 
                 SET nombreEmpresa = ?, direccion = ?, telefono = ?, correo = ?, whatsapp = ?
                 WHERE idEmpresa = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param("sssssi", $nombreEmpresa, $direccion, $telefono, $correo, $whatsapp, $idEmpresa);
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        }

        /**
         * Actualiza las credenciales de Wompi de la empresa
         * @param int $idEmpresa
         * @param string $appId
         * @param string $apiKey
         * @param bool $activo
         * @return bool
         */
        public function actualizarCredencialesWompi(int $idEmpresa, string $appId, string $apiKey, bool $activo): bool
        {
            $wompiActivo = $activo ? 1 : 0;
            $stmt = $this->con->prepare(
                "UPDATE empresas 
                 SET wompiAppId = ?, wompiApiKey = ?, wompiActivo = ?
                 WHERE idEmpresa = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param("ssii", $appId, $apiKey, $wompiActivo, $idEmpresa);
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        }

        /**
         * Cambia el estado activo/inactivo de una empresa
         * @param int $idEmpresa
         * @param int $activo
         * @return bool
         */
        public function cambiarEstado(int $idEmpresa, int $activo): bool
        {
            $stmt = $this->con->prepare("UPDATE empresas SET activo = ? WHERE idEmpresa = ?");
            if (!$stmt) return false;
            $stmt->bind_param("ii", $activo, $idEmpresa);
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        }

        /**
         * Obtiene las credenciales de Wompi activas
         * @param int $idEmpresa
         * @return array
         */
        public function obtenerCredencialesWompi(int $idEmpresa): array
        {
            $empresa = $this->obtenerPorId($idEmpresa);
            if ($empresa && !empty($empresa['wompiAppId']) && !empty($empresa['wompiApiKey']) && (int)$empresa['wompiActivo'] === 1) {
                return [
                    'configurado' => true,
                    'app_id' => $empresa['wompiAppId'],
                    'api_key' => $empresa['wompiApiKey'],
                    'nombre_empresa' => $empresa['nombreEmpresa']
                ];
            }

            return [
                'configurado' => defined('WOMPI_PUBLIC_KEY') && !empty(WOMPI_PUBLIC_KEY) && !strpos(WOMPI_PUBLIC_KEY, 'XXXX'),
                'app_id' => defined('WOMPI_PUBLIC_KEY') ? WOMPI_PUBLIC_KEY : '',
                'api_key' => defined('WOMPI_PRIVATE_KEY') ? WOMPI_PRIVATE_KEY : '',
                'nombre_empresa' => 'Concentrados El Gordito'
            ];
        }

        /**
         * Obtiene la lista de usuarios para asignar como dueño de empresa
         * @return array
         */
        public function obtenerUsuariosDisponibles(): array
        {
            $consulta = "
                SELECT u.idUsuario, u.username, r.nombreRol,
                       CONCAT(COALESCE(emp.nombreEmp, ''), ' ', COALESCE(emp.apellido, '')) AS nombreEmpleado
                FROM usuarios u
                LEFT JOIN rol r ON u.id_Rol = r.id_Rol
                LEFT JOIN empleado emp ON u.idUsuario = emp.idUsuario
                WHERE u.activo = 1
                ORDER BY u.id_Rol ASC, u.username ASC
            ";
            $res = $this->con->query($consulta);
            if (!$res) return [];
            $usuarios = [];
            while ($row = $res->fetch_assoc()) {
                $usuarios[] = $row;
            }
            return $usuarios;
        }

        /**
         * Genera un slug único para URL
         * @param string $texto
         * @param int $idExcluir
         * @return string
         */
        public function generarSlug(string $texto, int $idExcluir = 0): string
        {
            $texto = preg_replace('~[^\pL\d]+~u', '-', $texto);
            $texto = iconv('utf-8', 'us-ascii//TRANSLIT', $texto);
            $texto = preg_replace('~[^-\w]+~', '', $texto);
            $texto = trim($texto, '-');
            $texto = preg_replace('~-+~', '-', $texto);
            $slug = strtolower($texto);
            if (empty($slug)) {
                $slug = 'empresa-' . time();
            }

            // Validar unicidad
            $sql = "SELECT idEmpresa FROM empresas WHERE slug = ?";
            if ($idExcluir > 0) {
                $sql .= " AND idEmpresa != $idExcluir";
            }
            $stmt = $this->con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $slug);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    $slug .= '-' . rand(100, 999);
                }
                $stmt->close();
            }
            return $slug;
        }
    }
}
