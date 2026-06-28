
-- Tabla para registrar logs de auditoria
CREATE TABLE IF NOT EXISTS `auditoria` (
  `idAuditoria` INT AUTO_INCREMENT PRIMARY KEY,
  `idUsuario` INT DEFAULT NULL,
  `username` VARCHAR(100) DEFAULT NULL,
  `tipo_evento` ENUM('login','logout','vista','modulo','crud','error','sistema') NOT NULL DEFAULT 'sistema',
  `modulo` VARCHAR(100) DEFAULT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `fecha_hora` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_usuario` (`idUsuario`),
  INDEX `idx_tipo` (`tipo_evento`),
  INDEX `idx_fecha` (`fecha_hora`),
  INDEX `idx_modulo` (`modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para sesiones activas en el aplicativo
CREATE TABLE IF NOT EXISTS `sesiones_activas` (
  `session_id` VARCHAR(128) PRIMARY KEY,
  `idUsuario` INT DEFAULT NULL,
  `username` VARCHAR(100) DEFAULT NULL,
  `id_Rol` INT DEFAULT NULL,
  `nombre_usuario` VARCHAR(200) DEFAULT NULL,
  `login_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_usuario` (`idUsuario`),
  INDEX `idx_activo` (`activo`),
  INDEX `idx_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de planes de pago
CREATE TABLE IF NOT EXISTS `plan_pago` (
  `idPlanPago` INT AUTO_INCREMENT PRIMARY KEY,
  `nombrePlan` VARCHAR(100) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `monto` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `duracion_dias` INT NOT NULL DEFAULT 30,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_nombre_plan` (`nombrePlan`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar planes de pago por defecto (evita duplicados por la clave unica)
INSERT IGNORE INTO `plan_pago` (`nombrePlan`, `descripcion`, `monto`, `duracion_dias`) VALUES
('Plan Basico', 'Acceso basico al sistema', 150.00, 30),
('Plan Premium', 'Acceso completo con soporte prioritario', 300.00, 30),
('Plan Enterprise', 'Acceso empresarial multi-usuario', 800.00, 30);

-- Si necesitas agregar el campo activo a usuarios, ejecuta esto manualmente:
-- ALTER TABLE `usuarios`
--   ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `id_Rol`,
--   ADD COLUMN `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `activo`,
--   ADD INDEX `idx_activo` (`activo`);

-- Tabla para vincular usuarios con planes de pago
CREATE TABLE IF NOT EXISTS `usuario_plan_pago` (
  `idUsuarioPlan` INT AUTO_INCREMENT PRIMARY KEY,
  `idUsuario` INT NOT NULL,
  `idPlanPago` INT NOT NULL,
  `fecha_inicio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin` DATETIME DEFAULT NULL,
  `estado` ENUM('activo','vencido','cancelado') NOT NULL DEFAULT 'activo',
  `monto_pagado` DECIMAL(10,2) DEFAULT 0.00,
  `observaciones` TEXT DEFAULT NULL,
  INDEX `idx_usuario` (`idUsuario`),
  INDEX `idx_plan` (`idPlanPago`),
  INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla centralizada de pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `idPago` INT AUTO_INCREMENT PRIMARY KEY,
  `idUsuario` INT NOT NULL,
  `idPlanPago` INT DEFAULT NULL,
  `monto` DECIMAL(10,2) NOT NULL,
  `moneda` VARCHAR(3) NOT NULL DEFAULT 'USD',
  `metodo_pago` VARCHAR(50) NOT NULL DEFAULT 'stripe',
  `estado` ENUM('pendiente','completado','fallido','reembolsado','cancelado') NOT NULL DEFAULT 'pendiente',
  `referencia` VARCHAR(255) DEFAULT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `fecha_hora` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `metadata` JSON DEFAULT NULL,
  INDEX `idx_usuario` (`idUsuario`),
  INDEX `idx_plan` (`idPlanPago`),
  INDEX `idx_estado` (`estado`),
  INDEX `idx_fecha` (`fecha_hora`),
  INDEX `idx_referencia` (`referencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

