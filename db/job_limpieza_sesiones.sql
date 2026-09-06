-- ====================================================================================================
-- Script de Creación de Job / Evento Programado en MariaDB / MySQL
-- Base de Datos: proyectox
-- Propósito: Desactivar automáticamente sesiones inactivas que superen las 24 horas y purgar historial antiguo.
-- ====================================================================================================

-- 1. Activar el programador de eventos global de MySQL/MariaDB
SET GLOBAL event_scheduler = ON;

-- 2. Eliminar el evento si ya existía para permitir recreación limpia
DROP EVENT IF EXISTS evento_limpiar_sesiones_24h;

-- 3. Crear el evento recurrente cada 1 hora
DELIMITER $$

CREATE EVENT evento_limpiar_sesiones_24h
ON SCHEDULE EVERY 1 HOUR
STARTS CURRENT_TIMESTAMP
COMMENT 'Job programado que se ejecuta cada hora para desactivar sesiones con inactividad mayor a 24 horas y purgar registros mayores a 7 dias'
DO
BEGIN
    -- Desactivar sesiones activas cuya última actividad fue hace más de 24 horas
    UPDATE sesiones_activas 
    SET activo = 0 
    WHERE last_activity < NOW() - INTERVAL 24 HOUR 
      AND activo = 1;

    -- Purgar registros cerrados con más de 7 días de antigüedad para mantener la tabla optimizada
    DELETE FROM sesiones_activas 
    WHERE last_activity < NOW() - INTERVAL 7 DAY 
      AND activo = 0;
END $$

DELIMITER ;
