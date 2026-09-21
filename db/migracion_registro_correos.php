<?php
require_once __DIR__ . '/../db/conexion.php';

try {
    $conexion = new Conexion();
    $con = $conexion->obtenerConexion();
    
    $sql = "CREATE TABLE IF NOT EXISTS `registro_correos` (
      `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del registro de envío de correo',
      `destinatario` VARCHAR(255) NOT NULL COMMENT 'Dirección de correo electrónico del destinatario',
      `asunto` VARCHAR(255) NOT NULL COMMENT 'Asunto del mensaje enviado',
      `metodo` VARCHAR(50) NOT NULL DEFAULT 'resend' COMMENT 'Método de envío utilizado (resend, smtp)',
      `estado` VARCHAR(50) NOT NULL DEFAULT 'enviado' COMMENT 'Estado del envío (enviado, fallido)',
      `fecha_envio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora exacta del envío'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de registro, auditoría y conteo mensual de correos electrónicos enviados';";
    
    if ($con->query($sql)) {
        echo "Tabla registro_correos lista y verificada en la base de datos.\n";
    } else {
        echo "Error al crear la tabla: " . $con->error . "\n";
    }
} catch (Exception $e) {
    echo "Excepción: " . $e->getMessage() . "\n";
}
