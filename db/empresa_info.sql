-- Tabla para información y configuración institucional de la empresa
CREATE TABLE IF NOT EXISTS `empresa_info` (
  `idEmpresa` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL DEFAULT 'Concentrados El Gordito',
  `eslogan` VARCHAR(255) DEFAULT 'Nutrición Animal de Alto Rendimiento para el Campo Salvadoreño',
  `resumen` TEXT DEFAULT NULL,
  `mision` TEXT DEFAULT NULL,
  `vision` TEXT DEFAULT NULL,
  `telefono` VARCHAR(50) DEFAULT '+503 2440-1234',
  `telefono_movil` VARCHAR(50) DEFAULT '+503 7890-5678',
  `whatsapp` VARCHAR(50) DEFAULT '+50378905678',
  `correo` VARCHAR(100) DEFAULT 'contacto@concentradoselgordito.com',
  `direccion` VARCHAR(255) DEFAULT 'Carretera Panamericana Km 65, El Salvador',
  `horario` VARCHAR(255) DEFAULT 'Lunes a Viernes: 7:00 AM – 5:00 PM | Sábados: 7:00 AM – 12:00 MD',
  `logo` VARCHAR(255) DEFAULT 'views/Recursos/logo.png',
  `facebook` VARCHAR(255) DEFAULT 'https://facebook.com',
  `instagram` VARCHAR(255) DEFAULT 'https://instagram.com',
  `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos iniciales por defecto
INSERT IGNORE INTO `empresa_info` (`idEmpresa`, `nombre`, `eslogan`, `resumen`, `mision`, `vision`, `telefono`, `telefono_movil`, `whatsapp`, `correo`, `direccion`, `horario`) VALUES
(1, 'Concentrados El Gordito', 'Nutrición Animal de Alto Rendimiento para el Campo Salvadoreño', 'Somos una empresa salvadoreña dedicada a la elaboración y distribución de alimentos balanceados y concentrados de primera calidad para aves, ganado bovino y porcinos. Impulsamos la productividad agropecuaria con fórmulas de precisión e ingredientes rigurosamente seleccionados.', 'Proveer soluciones nutricionales balanceadas con los más altos estándares de calidad, materias primas de primera categoría y tecnología de molienda avanzada, maximizando el rendimiento, la salud y la rentabilidad de las granjas salvadoreñas.', 'Consolidarnos como la planta de concentrados líder y más confiable de El Salvador, reconocida por la excelencia de nuestras fórmulas, trazabilidad productiva y compromiso genuino con el desarrollo agropecuario.', '+503 2440-1234', '+503 7890-5678', '+50378905678', 'contacto@concentradoselgordito.com', 'Carretera Panamericana Km 65, El Salvador', 'Lunes a Viernes: 7:00 AM – 5:00 PM | Sábados: 7:00 AM – 12:00 MD');
