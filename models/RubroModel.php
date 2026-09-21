<?php
/**
 * Modelo de Gestión de Rubros y Sectores Comerciales
 * Arquitectura 100% MVC - Nomenclatura en Español
 * Concentrados El Gordito
 */

require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('RubroModel')) {
    class RubroModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Lista todos los rubros registrados en el sistema
         * @return array
         */
        public function obtenerTodos(): array
        {
            $sql = "SELECT idRubro, nombreRubro, slugRubro, icono, descripcion, activo, creado_en 
                    FROM rubros 
                    ORDER BY activo DESC, nombreRubro ASC";
            $res = $this->con->query($sql);
            if (!$res) return [];
            $lista = [];
            while ($row = $res->fetch_assoc()) {
                $lista[] = $row;
            }
            return $lista;
        }

        /**
         * Obtiene la lista de rubros que se encuentran activos
         * @return array
         */
        public function obtenerActivos(): array
        {
            $sql = "SELECT idRubro, nombreRubro, slugRubro, icono, descripcion 
                    FROM rubros 
                    WHERE activo = 1 
                    ORDER BY nombreRubro ASC";
            $res = $this->con->query($sql);
            if (!$res) return [];
            $lista = [];
            while ($row = $res->fetch_assoc()) {
                $lista[] = $row;
            }
            return $lista;
        }

        /**
         * Obtiene un rubro por su ID
         * @param int $idRubro
         * @return array|null
         */
        public function obtenerPorId(int $idRubro): ?array
        {
            $stmt = $this->con->prepare("SELECT * FROM rubros WHERE idRubro = ? LIMIT 1");
            if (!$stmt) return null;
            $stmt->bind_param("i", $idRubro);
            $stmt->execute();
            $res = $stmt->get_result();
            $rubro = $res->fetch_assoc() ?: null;
            $stmt->close();
            return $rubro;
        }

        /**
         * Obtiene un rubro por su slug
         * @param string $slug
         * @return array|null
         */
        public function obtenerPorSlug(string $slug): ?array
        {
            $stmt = $this->con->prepare("SELECT * FROM rubros WHERE slugRubro = ? AND activo = 1 LIMIT 1");
            if (!$stmt) return null;
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $res = $stmt->get_result();
            $rubro = $res->fetch_assoc() ?: null;
            $stmt->close();
            return $rubro;
        }

        /**
         * Registra un nuevo rubro en la base de datos
         * @param array $datos
         * @return int
         */
        public function guardar(array $datos): int
        {
            $nombre = trim((string)($datos['nombreRubro'] ?? ''));
            $slug = trim((string)($datos['slugRubro'] ?? ''));
            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $nombre));
            }
            $icono = trim((string)($datos['icono'] ?? 'fas fa-store'));
            $descripcion = trim((string)($datos['descripcion'] ?? ''));
            $activo = isset($datos['activo']) ? (int)$datos['activo'] : 1;

            $stmt = $this->con->prepare(
                "INSERT INTO rubros (nombreRubro, slugRubro, icono, descripcion, activo) VALUES (?, ?, ?, ?, ?)"
            );
            if (!$stmt) return 0;
            $stmt->bind_param("ssssi", $nombre, $slug, $icono, $descripcion, $activo);
            $stmt->execute();
            $id = (int)$this->con->insert_id;
            $stmt->close();
            return $id;
        }

        /**
         * Actualiza la información de un rubro
         * @param int $idRubro
         * @param array $datos
         * @return bool
         */
        public function actualizar(int $idRubro, array $datos): bool
        {
            $nombre = trim((string)($datos['nombreRubro'] ?? ''));
            $icono = trim((string)($datos['icono'] ?? 'fas fa-store'));
            $descripcion = trim((string)($datos['descripcion'] ?? ''));
            $activo = isset($datos['activo']) ? (int)$datos['activo'] : 1;

            $stmt = $this->con->prepare(
                "UPDATE rubros SET nombreRubro = ?, icono = ?, descripcion = ?, activo = ? WHERE idRubro = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param("sssii", $nombre, $icono, $descripcion, $activo, $idRubro);
            $res = $stmt->execute();
            $stmt->close();
            return (bool)$res;
        }
    }
}
