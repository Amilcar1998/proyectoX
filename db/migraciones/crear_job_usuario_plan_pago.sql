-- =================================================================================
-- Script de Creación de Evento / Job Automático para Control de Planes de Pago
-- Base de Datos: concentrados
-- Tabla afectada: usuario_plan_pago
-- Cumple con la Regla #5 (Comentarios descriptivos COMMENT en tablas, columnas y objetos)
-- =================================================================================

-- 1. Habilitar el Programador de Eventos de MySQL / MariaDB
SET GLOBAL event_scheduler = ON;

-- 2. Asegurar soporte del estado 'inactivo' en el enum con su respectivo COMMENT
ALTER TABLE `usuario_plan_pago` 
MODIFY COLUMN `estado` ENUM('activo', 'vencido', 'inactivo', 'cancelado') 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo' 
COMMENT 'Estado de la suscripción: activo, vencido, inactivo o cancelado';

-- 3. Eliminar evento previo si existe
DROP EVENT IF EXISTS `job_verificar_vencimiento_planes`;

-- 4. Creación del Evento Programado (Job) en la Base de Datos
DELIMITER $$

CREATE EVENT `job_verificar_vencimiento_planes`
ON SCHEDULE EVERY 10 MINUTE
STARTS CURRENT_TIMESTAMP
ON COMPLETION PRESERVE
ENABLE
COMMENT 'Job periódico en BD para actualizar estado a inactivo si fecha_fin <= NOW() y activo si fecha_fin > NOW() (excluyendo cancelados)'
DO
BEGIN
    -- Pasar a inactivo los planes cuya fecha_fin ya expiró y no estén cancelados
    UPDATE `usuario_plan_pago`
    SET `estado` = 'inactivo'
    WHERE `fecha_fin` IS NOT NULL
      AND `fecha_fin` <= NOW()
      AND `estado` != 'cancelado'
      AND `estado` != 'inactivo';

    -- Normalizar a activo los registros con fecha_fin vigente que no tengan estado o hayan quedado vacíos
    UPDATE `usuario_plan_pago`
    SET `estado` = 'activo'
    WHERE (`estado` = '' OR `estado` IS NULL)
      AND `fecha_fin` > NOW()
      AND `estado` != 'cancelado';
END$$

DELIMITER ;
