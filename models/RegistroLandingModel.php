<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('RegistroLandingModel')) {
    class RegistroLandingModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function buscarPorCorreo(string $correo): ?array
        {
            $stmt = $this->con->prepare(
                "SELECT idUsuario, username FROM usuarios WHERE username = ? LIMIT 1"
            );
            if (!$stmt) return null;
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $res  = $stmt->get_result();
            $row  = ($res->num_rows > 0) ? $res->fetch_assoc() : null;
            $stmt->close();
            return $row;
        }

        public function autenticarCliente(string $correo, string $clave): ?array
        {
            $hash = sha1($clave);
            $stmt = $this->con->prepare(
                "SELECT idUsuario, username FROM usuarios WHERE username = ? AND pass = ? LIMIT 1"
            );
            if (!$stmt) return null;
            $stmt->bind_param("ss", $correo, $hash);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = ($res->num_rows > 0) ? $res->fetch_assoc() : null;
            $stmt->close();

            if ($row) {
                return $this->obtenerDatosCliente((int)$row['idUsuario'], $correo);
            }
            return null;
        }

        public function registrarUsuarioCliente(array $datos): array
        {
            $existente = $this->buscarPorCorreo($datos['correo']);
            if ($existente) {
                return ['exito' => false, 'mensaje' => 'El correo ya está registrado. Usa la opción "Ya tengo cuenta".'];
            }

            $idUsuario = $this->crearUsuario($datos['correo'], $datos['clave']);
            if ($idUsuario === 0) {
                return ['exito' => false, 'mensaje' => 'No se pudo crear la cuenta. Intenta de nuevo.'];
            }

            $this->crearCliente($idUsuario, $datos);

            return [
                'exito'     => true,
                'idUsuario' => $idUsuario,
                'nombre'    => trim($datos['nombre']),
                'correo'    => $datos['correo'],
                'telefono'  => $datos['telefono'] ?? ''
            ];
        }

        private function crearUsuario(string $correo, string $clave): int
        {
            $hash = sha1($clave);
            $stmt = $this->con->prepare(
                "INSERT INTO usuarios (username, pass, id_Rol, debe_cambiar_pass) VALUES (?, ?, 2, 0)"
            );
            if (!$stmt) return 0;
            $stmt->bind_param("ss", $correo, $hash);
            $ok = $stmt->execute();
            $id = $ok ? (int)$stmt->insert_id : 0;
            $stmt->close();
            return $id;
        }

        private function crearCliente(int $idUsuario, array $datos): void
        {
            $partes    = explode(' ', trim($datos['nombre']), 2);
            $nombre    = $partes[0];
            $apellidos = $partes[1] ?? '';
            $telefono  = $datos['telefono'] ?? '';

            $stmt = $this->con->prepare(
                "INSERT INTO cliente (NombreCliente, apellidosCliente, telefono, idUsuario) VALUES (?, ?, ?, ?)"
            );
            if (!$stmt) return;
            $stmt->bind_param("sssi", $nombre, $apellidos, $telefono, $idUsuario);
            $stmt->execute();
            $stmt->close();
        }

        private function obtenerDatosCliente(int $idUsuario, string $correo): array
        {
            $stmt = $this->con->prepare(
                "SELECT NombreCliente, apellidosCliente, telefono FROM cliente WHERE idUsuario = ? LIMIT 1"
            );
            if (!$stmt) return ['idUsuario' => $idUsuario, 'correo' => $correo, 'nombre' => $correo, 'telefono' => ''];
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            $stmt->close();

            $nombre = $row ? trim($row['NombreCliente'] . ' ' . $row['apellidosCliente']) : $correo;
            return [
                'idUsuario' => $idUsuario,
                'correo'    => $correo,
                'nombre'    => $nombre ?: $correo,
                'telefono'  => $row['telefono'] ?? ''
            ];
        }
    }
}
