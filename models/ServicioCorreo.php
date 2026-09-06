<?php
/**
 * Modelo de Servicio de Correo Electrónico (API Resend & SMTP)
 * Arquitectura MVC - 100% Español
 * Concentrados El Gordito
 */

class ServicioCorreo
{
    private array $config;
    private string $ultimoError = '';
    private array $registroDepuracion = [];

    public function __construct(?array $configPersonalizada = null)
    {
        if ($configPersonalizada !== null) {
            $this->config = $configPersonalizada;
        } else {
            $archivoConfig = __DIR__ . '/../config/correo.php';
            if (file_exists($archivoConfig)) {
                $this->config = require $archivoConfig;
            } else {
                $this->config = [
                    'metodo' => 'resend',
                    'resend_api_key' => getenv('RESEND_API_KEY') ?: '',
                    'remitente_nombre' => 'Concentrados El Gordito',
                    'remitente_correo' => 'onboarding@resend.dev',
                    'servidor' => 'sandbox.smtp.mailtrap.io',
                    'puerto' => 2525,
                    'seguridad' => 'tls',
                    'usuario' => 'e159d11f9e693d',
                    'clave' => 'dc7970754f333b',
                    'tiempo_espera' => 15,
                    'depuracion' => false
                ];
            }
        }
    }

    public function obtenerUltimoError(): string
    {
        return $this->ultimoError;
    }

    public function obtenerRegistroDepuracion(): array
    {
        return $this->registroDepuracion;
    }

    /**
     * Envía un correo electrónico utilizando el método configurado (Resend API o SMTP)
     */
    public function enviar(string $destinatario, string $asunto, string $cuerpoHtml, string $nombreDestinatario = ''): bool
    {
        $this->ultimoError = '';
        $this->registroDepuracion = [];

        if (empty($destinatario) || !filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
            $this->ultimoError = "La dirección de correo destinatario no es válida: '$destinatario'";
            return false;
        }

        $metodo = strtolower($this->config['metodo'] ?? 'resend');

        if ($metodo === 'resend') {
            $resultado = $this->enviarConResend($destinatario, $asunto, $cuerpoHtml, $nombreDestinatario);
            if ($resultado) {
                return true;
            }
            // Si falla Resend y hay SMTP configurado, intentar respaldo SMTP
            $this->registroDepuracion[] = "Resend API falló. Intentando envío de respaldo vía SMTP...";
            return $this->enviarConSmtp($destinatario, $asunto, $cuerpoHtml, $nombreDestinatario);
        }

        return $this->enviarConSmtp($destinatario, $asunto, $cuerpoHtml, $nombreDestinatario);
    }

    /**
     * Envío de correos mediante la API REST de Resend
     */
    private function enviarConResend(string $destinatario, string $asunto, string $cuerpoHtml, string $nombreDestinatario): bool
    {
        $apiKey = $this->config['resend_api_key'] ?? '';
        if (empty($apiKey)) {
            $this->ultimoError = "No se ha configurado la clave de API de Resend (resend_api_key).";
            return false;
        }

        $remitenteCorreo = $this->config['remitente_correo'] ?? 'onboarding@resend.dev';
        $remitenteNombre = $this->config['remitente_nombre'] ?? 'Concentrados El Gordito';
        $remitenteHeader = "$remitenteNombre <$remitenteCorreo>";

        $datosPayload = [
            'from'    => $remitenteHeader,
            'to'      => [$destinatario],
            'subject' => $asunto,
            'html'    => $cuerpoHtml
        ];

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode($datosPayload),
            CURLOPT_TIMEOUT        => (int)($this->config['tiempo_espera'] ?? 15),
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $respuesta = curl_exec($ch);
        $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorCurl = curl_error($ch);
        curl_close($ch);

        $this->registroDepuracion[] = "RESEND HTTP CODE: " . $codigoHttp;
        $this->registroDepuracion[] = "RESEND RESPUESTA: " . $respuesta;

        if ($errorCurl) {
            $this->ultimoError = "Error de comunicación cURL con Resend: " . $errorCurl;
            return false;
        }

        if ($codigoHttp >= 200 && $codigoHttp < 300) {
            return true;
        }

        $json = json_decode($respuesta, true);
        $mensajeError = $json['message'] ?? ($json['error'] ?? "Error desconocido en Resend (HTTP $codigoHttp)");
        $this->ultimoError = "Resend API Error ($codigoHttp): " . $mensajeError;
        return false;
    }

    /**
     * Envío de correos mediante conexión Socket SMTP con STARTTLS
     */
    private function enviarConSmtp(string $destinatario, string $asunto, string $cuerpoHtml, string $nombreDestinatario): bool
    {
        $servidor = $this->config['servidor'] ?? 'sandbox.smtp.mailtrap.io';
        $puerto = (int)($this->config['puerto'] ?? 2525);
        $tiempoEspera = (int)($this->config['tiempo_espera'] ?? 15);
        $seguridad = strtolower($this->config['seguridad'] ?? 'tls');
        $usuario = $this->config['usuario'] ?? '';
        $clave = $this->config['clave'] ?? '';
        $remitenteCorreo = $this->config['remitente_correo'] ?? 'notificaciones@concentradoselgordito.com';
        $remitenteNombre = $this->config['remitente_nombre'] ?? 'Concentrados El Gordito';

        $prefijoHost = ($seguridad === 'ssl') ? 'ssl://' : 'tcp://';
        $conexion = @stream_socket_client($prefijoHost . $servidor . ':' . $puerto, $codigoError, $mensajeError, $tiempoEspera);

        if (!$conexion) {
            $this->ultimoError = "Error al conectar al servidor SMTP ($servidor:$puerto): $mensajeError ($codigoError)";
            return false;
        }

        stream_set_timeout($conexion, $tiempoEspera);

        if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [220])) {
            $this->cerrarConexion($conexion);
            return false;
        }

        $this->enviarComando($conexion, "EHLO " . gethostname());
        if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [250])) {
            $this->cerrarConexion($conexion);
            return false;
        }

        if ($seguridad === 'tls') {
            $this->enviarComando($conexion, "STARTTLS");
            if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [220])) {
                $this->cerrarConexion($conexion);
                return false;
            }

            $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }

            if (!@stream_socket_enable_crypto($conexion, true, $cryptoMethod)) {
                $this->ultimoError = "Error al establecer cifrado STARTTLS con el servidor SMTP.";
                $this->cerrarConexion($conexion);
                return false;
            }

            $this->enviarComando($conexion, "EHLO " . gethostname());
            if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [250])) {
                $this->cerrarConexion($conexion);
                return false;
            }
        }

        if (!empty($usuario)) {
            $this->enviarComando($conexion, "AUTH LOGIN");
            if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [334])) {
                $this->cerrarConexion($conexion);
                return false;
            }

            $this->enviarComando($conexion, base64_encode($usuario));
            if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [334])) {
                $this->cerrarConexion($conexion);
                return false;
            }

            $this->enviarComando($conexion, base64_encode($clave));
            if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [235])) {
                $this->ultimoError = "Fallo de autenticación SMTP: Usuario o clave incorrectos.";
                $this->cerrarConexion($conexion);
                return false;
            }
        }

        $this->enviarComando($conexion, "MAIL FROM:<$remitenteCorreo>");
        if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [250])) {
            $this->cerrarConexion($conexion);
            return false;
        }

        $this->enviarComando($conexion, "RCPT TO:<$destinatario>");
        if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [250, 251])) {
            $this->cerrarConexion($conexion);
            return false;
        }

        $this->enviarComando($conexion, "DATA");
        if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [354])) {
            $this->cerrarConexion($conexion);
            return false;
        }

        $mensajeMime = $this->construirMensajeMime($destinatario, $asunto, $cuerpoHtml, $nombreDestinatario, $remitenteCorreo, $remitenteNombre);
        $this->enviarComando($conexion, $mensajeMime . "\r\n.");

        if (!$this->esRespuestaValida($this->leerRespuesta($conexion), [250])) {
            $this->cerrarConexion($conexion);
            return false;
        }

        $this->enviarComando($conexion, "QUIT");
        $this->cerrarConexion($conexion);

        return true;
    }

    /**
     * Envía correo de recuperación de contraseña con diseño institucional
     */
    public function enviarRecuperacionClave(string $correo, string $enlace, string $nombreUsuario = ''): bool
    {
        $asunto = "🔐 Recuperación de Contraseña - Concentrados El Gordito";
        $nombre = !empty($nombreUsuario) ? $nombreUsuario : 'Estimado Usuario';

        $cuerpo = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
          <meta charset='utf-8'>
          <title>Recuperación de Contraseña</title>
        </head>
        <body style='margin:0;padding:0;background-color:#0f172a;font-family:Arial,Helvetica,sans-serif;'>
          <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color:#0f172a;padding:40px 10px;'>
            <tr>
              <td align='center'>
                <table width='600' border='0' cellspacing='0' cellpadding='0' style='background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.3);'>
                  <tr>
                    <td style='background:linear-gradient(135deg,#1e3a8a,#0f172a);padding:35px 30px;text-align:center;'>
                      <div style='font-size:32px;color:#22c55e;margin-bottom:8px;'>🌱</div>
                      <h1 style='margin:0;color:#ffffff;font-size:24px;font-weight:bold;'>Concentrados El Gordito</h1>
                      <p style='margin:5px 0 0;color:#94a3b8;font-size:13px;letter-spacing:1px;'>SISTEMA DE GESTIÓN AGROPECUARIA</p>
                    </td>
                  </tr>
                  <tr>
                    <td style='padding:35px 30px;color:#334155;font-size:15px;line-height:1.6;'>
                      <h2 style='color:#0f172a;font-size:20px;margin-top:0;'>Solicitud de Restablecimiento</h2>
                      <p>Hola <strong>" . htmlspecialchars($nombre) . "</strong>,</p>
                      <p>Hemos recibido una solicitud para restablecer la contraseña de acceso a tu cuenta en el sistema de Concentrados El Gordito.</p>
                      <p>Para crear una nueva contraseña segura, haz clic en el siguiente botón:</p>
                      
                      <div style='text-align:center;margin:30px 0;'>
                        <a href='$enlace' style='background:linear-gradient(135deg,#16a34a,#15803d);color:#ffffff;padding:15px 32px;text-decoration:none;border-radius:10px;font-weight:bold;font-size:16px;display:inline-block;box-shadow:0 4px 15px rgba(22,163,74,0.4);'>
                          Restablecer mi Contraseña
                        </a>
                      </div>

                      <p style='font-size:13px;color:#64748b;background:#f8fafc;padding:12px;border-radius:8px;border-left:4px solid #2563eb;'>
                        ⏱️ Este enlace de recuperación es de <strong>un solo uso</strong> y expira automáticamente en <strong>1 hora</strong> por motivos de seguridad.
                      </p>

                      <p style='font-size:13px;color:#94a3b8;'>Si no solicitaste este cambio, puedes ignorar este mensaje de manera segura. Tu contraseña actual no se modificará.</p>
                    </td>
                  </tr>
                  <tr>
                    <td style='background-color:#f1f5f9;padding:20px 30px;text-align:center;font-size:12px;color:#64748b;border-top:1px solid #e2e8f0;'>
                      <p style='margin:0 0 5px;'><strong>Concentrados El Gordito &copy; " . date('Y') . "</strong></p>
                      <p style='margin:0;'>Nutrición Animal de Alto Rendimiento para el Campo Salvadoreño</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>";

        return $this->enviar($correo, $asunto, $cuerpo, $nombre);
    }

    private function construirMensajeMime(string $destinatario, string $asunto, string $cuerpoHtml, string $nombreDestinatario, string $remitenteCorreo, string $remitenteNombre): string
    {
        $fecha = date('r');
        $messageId = sprintf("<%s.%s@%s>", uniqid(), time(), parse_url('http://' . ($this->config['servidor'] ?? 'localhost'), PHP_URL_HOST));
        $asuntoCodificado = "=?UTF-8?B?" . base64_encode($asunto) . "?=";
        $fromNombreCodificado = "=?UTF-8?B?" . base64_encode($remitenteNombre) . "?=";
        $toNombreCodificado = !empty($nombreDestinatario) ? "=?UTF-8?B?" . base64_encode($nombreDestinatario) . "?= <$destinatario>" : "<$destinatario>";

        $cabeceras = [];
        $cabeceras[] = "Date: $fecha";
        $cabeceras[] = "From: $fromNombreCodificado <$remitenteCorreo>";
        $cabeceras[] = "To: $toNombreCodificado";
        $cabeceras[] = "Subject: $asuntoCodificado";
        $cabeceras[] = "Message-ID: $messageId";
        $cabeceras[] = "X-Mailer: ConcentradosElGordito Mailer 2.0";
        $cabeceras[] = "MIME-Version: 1.0";
        $cabeceras[] = "Content-Type: text/html; charset=UTF-8";
        $cabeceras[] = "Content-Transfer-Encoding: base64";

        return implode("\r\n", $cabeceras) . "\r\n\r\n" . chunk_split(base64_encode($cuerpoHtml));
    }

    private function enviarComando($conexion, string $comando): void
    {
        $this->registroDepuracion[] = "CLIENTE: " . $comando;
        fwrite($conexion, $comando . "\r\n");
    }

    private function leerRespuesta($conexion): string
    {
        $respuestaCompleta = '';
        while (!feof($conexion)) {
            $linea = fgets($conexion, 512);
            if ($linea === false) {
                break;
            }
            $respuestaCompleta .= $linea;
            $this->registroDepuracion[] = "SERVIDOR: " . trim($linea);
            if (isset($linea[3]) && $linea[3] === ' ') {
                break;
            }
        }
        return $respuestaCompleta;
    }

    private function esRespuestaValida(string $respuesta, array $codigosEsperados): bool
    {
        $codigo = (int)substr(trim($respuesta), 0, 3);
        if (in_array($codigo, $codigosEsperados, true)) {
            return true;
        }

        $this->ultimoError = "Respuesta inesperada del servidor SMTP ($codigo). Respuesta: " . trim($respuesta);
        return false;
    }

    private function cerrarConexion($conexion): void
    {
        if (is_resource($conexion)) {
            fclose($conexion);
        }
    }
}
