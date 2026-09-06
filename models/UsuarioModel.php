<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('UsuarioModel')) {
    class UsuarioModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function validarUsuario(string $usuario, string $clave): int
        {
            $claveHash = sha1($clave);
            $stmt = $this->con->prepare("SELECT id_Rol, activo FROM usuarios WHERE username = ? AND pass = ? LIMIT 1");
            $stmt->bind_param("ss", $usuario, $claveHash);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                if (isset($fila['activo']) && (int)$fila['activo'] === 0) {
                    return -1; // Cuenta desactivada
                }
                return (int)$fila['id_Rol'];
            }
            return 0; // Credenciales inválidas
        }

        public function debeCambiarClave(string $usuario): bool
        {
            $stmt = $this->con->prepare("SELECT debe_cambiar_pass FROM usuarios WHERE username = ? LIMIT 1");
            if (!$stmt) return false;
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            $stmt->close();
            return $fila ? ((int)$fila['debe_cambiar_pass'] === 1) : false;
        }

        public function cambiarClaveObligatoria(string $usuario, string $claveActual, string $nuevaClave): array
        {
            if (strlen($nuevaClave) < 6) {
                return ['exito' => false, 'mensaje' => 'La nueva contraseña debe tener al menos 6 caracteres.'];
            }
            if ($claveActual === $nuevaClave) {
                return ['exito' => false, 'mensaje' => 'La nueva contraseña debe ser diferente a la contraseña temporal actual.'];
            }

            $actualHash = sha1($claveActual);
            $stmt = $this->con->prepare("SELECT idUsuario FROM usuarios WHERE username = ? AND pass = ? LIMIT 1");
            if (!$stmt) return ['exito' => false, 'mensaje' => 'Error de conexión.'];
            $stmt->bind_param("ss", $usuario, $actualHash);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows === 0) {
                $stmt->close();
                return ['exito' => false, 'mensaje' => 'La contraseña actual ingresada es incorrecta.'];
            }
            $stmt->close();

            $nuevaHash = sha1($nuevaClave);
            $stmtUp = $this->con->prepare("UPDATE usuarios SET pass = ?, debe_cambiar_pass = 0 WHERE username = ?");
            if (!$stmtUp) return ['exito' => false, 'mensaje' => 'Error al actualizar la contraseña.'];
            $stmtUp->bind_param("ss", $nuevaHash, $usuario);
            $exito = $stmtUp->execute();
            $stmtUp->close();

            return [
                'exito' => (bool)$exito,
                'mensaje' => $exito ? 'Contraseña actualizada con éxito.' : 'Error al guardar la nueva contraseña.'
            ];
        }

        public function solicitarRecuperacion(string $correo): array
        {
            if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                return ['exito' => false, 'mensaje' => 'Por favor ingrese un correo válido.'];
            }

            $stmt = $this->con->prepare("SELECT idUsuario FROM usuarios WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 0) {
                return [
                    'exito' => true,
                    'mensaje' => 'Si el correo existe en nuestro sistema, recibirás un enlace de recuperación.'
                ];
            }

            $token = bin2hex(random_bytes(32));
            $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmtIns = $this->con->prepare("INSERT INTO recuperacion_pass (email, token, expiracion) VALUES (?, ?, ?)");
            $stmtIns->bind_param("sss", $correo, $token, $expiracion);
            $stmtIns->execute();

            $enlace = "http://localhost/ProyectoX/reset_password.php?token=" . urlencode($token);
            $enviado = $this->enviarCorreoRecuperacion($correo, $enlace);

            return [
                'exito' => true,
                'enviado' => $enviado,
                'enlace' => $enlace,
                'mensaje' => $enviado 
                    ? "Correo de recuperación enviado a $correo." 
                    : "No se pudo enviar el correo automáticamente. Usa el enlace directo:"
            ];
        }

        public function validarTokenRecuperacion(string $token): array
        {
            if (empty($token)) {
                return ['valido' => false, 'mensaje' => 'Token de recuperación no proporcionado.'];
            }

            $stmt = $this->con->prepare("SELECT id, email, expiracion, usado FROM recuperacion_pass WHERE token = ? LIMIT 1");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 0) {
                return ['valido' => false, 'mensaje' => 'Token de recuperación inválido.'];
            }

            $registro = $resultado->fetch_assoc();
            if ((int)$registro['usado'] === 1 || strtotime($registro['expiracion']) <= time()) {
                return ['valido' => false, 'mensaje' => 'El enlace de recuperación ha expirado o ya fue utilizado.'];
            }

            return [
                'valido' => true,
                'mensaje' => 'Token válido.',
                'id' => (int)$registro['id'],
                'correo' => $registro['email']
            ];
        }

        public function restablecerClave(string $token, string $nuevaClave): array
        {
            if (strlen($nuevaClave) < 6) {
                return ['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.'];
            }

            $validacion = $this->validarTokenRecuperacion($token);
            if (!$validacion['valido']) {
                return ['exito' => false, 'mensaje' => $validacion['mensaje']];
            }

            $claveHash = sha1($nuevaClave);
            $correo = $validacion['correo'];
            $idToken = $validacion['id'];

            $stmtUsuario = $this->con->prepare("UPDATE usuarios SET pass = ? WHERE username = ?");
            $stmtUsuario->bind_param("ss", $claveHash, $correo);
            $stmtUsuario->execute();

            $stmtToken = $this->con->prepare("UPDATE recuperacion_pass SET usado = 1 WHERE id = ?");
            $stmtToken->bind_param("i", $idToken);
            $stmtToken->execute();

            return [
                'exito' => true,
                'mensaje' => 'Contraseña actualizada correctamente. Redirigiendo al inicio de sesión...'
            ];
        }

        private function enviarCorreoRecuperacion(string $correo, string $enlace): bool
        {
            $asunto = "Recuperación de contraseña - Concentrados El Gordito";
            $cuerpo = "<html><body style='font-family:Arial,sans-serif;background:#f4f4f4;padding:30px;'>"
                . "<div style='max-width:600px;margin:0 auto;background:white;padding:30px;border-radius:10px;'>"
                . "<h2 style='color:#1e3a8a;'>Recuperación de contraseña</h2>"
                . "<p>Hola, haz clic en el siguiente enlace para restablecer tu contraseña:</p>"
                . "<a href='$enlace' style='display:inline-block;padding:14px 28px;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;font-weight:600;'>Restablecer contraseña</a>"
                . "<p style='margin-top:20px;color:#666;'>El enlace expira en 1 hora.</p>"
                . "</div></body></html>";

            $cabeceras = "MIME-Version: 1.0\r\n"
                . "Content-type: text/html; charset=utf-8\r\n"
                . "From: Concentrados El Gordito <admin@localhost>\r\n"
                . "Reply-To: admin@localhost\r\n";

            return (bool)@mail($correo, $asunto, $cuerpo, $cabeceras);
        }
    }
}
