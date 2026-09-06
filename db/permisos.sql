-- Tabla para catálogo de módulos del sistema
CREATE TABLE IF NOT EXISTS `modulos` (
  `idModulo` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `controlador` VARCHAR(100) NOT NULL UNIQUE,
  `icono` VARCHAR(50) NOT NULL DEFAULT 'fa-folder',
  `orden` INT NOT NULL DEFAULT 0,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_controlador` (`controlador`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para permisos de roles sobre módulos
CREATE TABLE IF NOT EXISTS `permisos_rol` (
  `idPermiso` INT AUTO_INCREMENT PRIMARY KEY,
  `id_Rol` INT NOT NULL,
  `idModulo` INT NOT NULL,
  `permitido` TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY `uk_rol_modulo` (`id_Rol`, `idModulo`),
  INDEX `idx_rol_permitido` (`id_Rol`, `permitido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar catálogo de módulos (si no existen)
INSERT IGNORE INTO `modulos` (`idModulo`, `nombre`, `controlador`, `icono`, `orden`, `activo`) VALUES
(1, 'Dashboard', 'controllerDashboard.php', 'fa-chart-line', 1, 1),
(2, 'Empleados', 'controllerEmpleado.php', 'fa-user-tie', 2, 1),
(3, 'Clientes', 'controllerCliente.php', 'fa-address-book', 3, 1),
(4, 'Usuarios', 'controllerUsuarios.php', 'fa-users-cog', 4, 1),
(5, 'Proveedores', 'controllerProveedor.php', 'fa-building', 5, 1),
(6, 'Pedidos a Proveedor', 'controllerPedidoProveedor.php', 'fa-shopping-cart', 6, 0),
(7, 'Pedidos', 'controllerPedidos.php', 'fa-box-open', 7, 1),
(8, 'Producción', 'controllerProduccion.php', 'fa-industry', 8, 1),
(9, 'Inventario', 'controllerInventario.php', 'fa-warehouse', 9, 1),
(10, 'Materia Prima', 'controllerMateriaPrima.php', 'fa-leaf', 10, 0),
(11, 'Puesto', 'controllerPuesto.php', 'fa-briefcase', 11, 1),
(12, 'Factura', 'controllerFactura.php', 'fa-file-invoice-dollar', 12, 1),
(13, 'Detalle Compra', 'controllerDetalleCompra.php', 'fa-shopping-bag', 13, 0),
(14, 'Planes de Pago', 'controllerPlanPago.php', 'fa-credit-card', 14, 1),
(15, 'Pagos', 'controllerPagos.php', 'fa-money-bill-wave', 15, 1),
(16, 'Reportes', 'controllerReportes.php', 'fa-chart-pie', 16, 1),
(17, 'Mis Pedidos', 'controllerPedidosIn.php', 'fa-tasks', 17, 0),
(18, 'Producción Operativa', 'controllerProduccionIn.php', 'fa-cogs', 18, 0),
(19, 'Portal Cliente', 'controllerIndividualC.php', 'fa-user', 19, 1),
(20, 'Auditoría', 'reporteAuditoria.php', 'fa-shield-alt', 20, 1),
(21, 'Sesiones Activas', 'reporteSesionesActivas.php', 'fa-user-clock', 21, 0),
(22, 'Actividad Módulos', 'reporteActividadModulos.php', 'fa-chart-bar', 22, 0),
(23, 'Precios y Promociones', 'controllerPromociones.php', 'fa-tags', 10, 1),
(24, 'Roles y Permisos', 'controllerRoles.php', 'fa-user-shield', 4, 1),
(25, 'Administración de Empresas', 'controllerConfiguracionNegocio.php', 'fa-building', 15, 1);

-- Permisos para Rol 1: Gerente (acceso a administración, operaciones, reportes y auditoría)
INSERT IGNORE INTO `permisos_rol` (`id_Rol`, `idModulo`, `permitido`) VALUES
(1, 1, 1), (1, 2, 1), (1, 3, 1), (1, 4, 1), (1, 5, 1), (1, 6, 1),
(1, 7, 1), (1, 8, 1), (1, 9, 1), (1, 10, 1), (1, 11, 1), (1, 12, 1),
(1, 13, 1), (1, 14, 1), (1, 15, 1), (1, 16, 1), (1, 17, 1), (1, 18, 1),
(1, 20, 1), (1, 21, 1), (1, 22, 1), (1, 23, 1), (1, 24, 1), (1, 25, 1);

-- Permisos para Rol 4: Admin (acceso total a todos los módulos de gestión)
INSERT IGNORE INTO `permisos_rol` (`id_Rol`, `idModulo`, `permitido`) VALUES
(4, 1, 1), (4, 2, 1), (4, 3, 1), (4, 4, 1), (4, 5, 1), (4, 6, 1),
(4, 7, 1), (4, 8, 1), (4, 9, 1), (4, 10, 1), (4, 11, 1), (4, 12, 1),
(4, 13, 1), (4, 14, 1), (4, 15, 1), (4, 16, 1), (4, 17, 1), (4, 18, 1),
(4, 20, 1), (4, 21, 1), (4, 22, 1), (4, 23, 1), (4, 24, 1), (4, 25, 1);

-- Permisos para Rol 2: Empleado (acceso solo a módulos operativos)
INSERT IGNORE INTO `permisos_rol` (`id_Rol`, `idModulo`, `permitido`) VALUES
(2, 6, 1), (2, 7, 1), (2, 8, 1), (2, 9, 1), (2, 10, 1),
(2, 12, 1), (2, 13, 1), (2, 17, 1), (2, 18, 1);

-- Permisos para Rol 3: Cliente (acceso únicamente a su portal y pagos)
INSERT IGNORE INTO `permisos_rol` (`id_Rol`, `idModulo`, `permitido`) VALUES
(3, 14, 1), (3, 15, 1), (3, 19, 1);

-- Tabla para catálogo de submódulos del sistema (para acceso granular / subroles)
CREATE TABLE IF NOT EXISTS `submodulos` (
  `idSubmodulo` INT AUTO_INCREMENT PRIMARY KEY,
  `idModulo` INT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `controlador_accion` VARCHAR(100) NOT NULL,
  `icono` VARCHAR(50) NOT NULL DEFAULT 'fa-circle-notch',
  `orden` INT NOT NULL DEFAULT 0,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_modulo` (`idModulo`),
  INDEX `idx_controlador_sub` (`controlador_accion`),
  INDEX `idx_activo_sub` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para permisos de roles sobre submódulos
CREATE TABLE IF NOT EXISTS `permisos_submodulo` (
  `idPermisoSub` INT AUTO_INCREMENT PRIMARY KEY,
  `id_Rol` INT NOT NULL,
  `idSubmodulo` INT NOT NULL,
  `permitido` TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY `uk_rol_submodulo` (`id_Rol`, `idSubmodulo`),
  INDEX `idx_rol_sub_permitido` (`id_Rol`, `permitido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catálogo de submódulos iniciales
INSERT IGNORE INTO `submodulos` (`idSubmodulo`, `idModulo`, `nombre`, `controlador_accion`, `icono`, `orden`, `activo`) VALUES
(1, 8, 'Producción General', 'controllerProduccion.php', 'fa-industry', 1, 1),
(2, 8, 'Producción Operativa', 'controllerProduccionIn.php', 'fa-cogs', 2, 1),
(3, 7, 'Gestión de Pedidos', 'controllerPedidos.php', 'fa-box-open', 1, 1),
(4, 7, 'Mis Pedidos', 'controllerPedidosIn.php', 'fa-tasks', 2, 1),
(5, 9, 'Control de Inventario', 'controllerInventario.php', 'fa-warehouse', 1, 1),
(6, 9, 'Materia Prima', 'controllerMateriaPrima.php', 'fa-leaf', 2, 1),
(7, 9, 'Pedidos a Proveedor', 'controllerPedidoProveedor.php', 'fa-truck-loading', 3, 1),
(8, 9, 'Detalle Compra', 'controllerDetalleCompra.php', 'fa-shopping-bag', 4, 1),
(9, 23, 'Nivelación de Precios', 'controllerPromociones.php?tab=nivelacion', 'fa-balance-scale', 1, 1),
(10, 23, 'Promociones Temporales', 'controllerPromociones.php?tab=promocion', 'fa-tags', 2, 1),
(11, 16, 'Reporte de Pedidos', 'reportePedidos.php', 'fa-file-invoice', 1, 1),
(12, 16, 'Reporte de Inventario', 'reporteInventarioGeneral.php', 'fa-boxes', 2, 1),
(13, 16, 'Reporte de Mezclas', 'reporteMezclas.php', 'fa-blender', 3, 1),
(14, 16, 'Reporte de Personal', 'repoEmpleado.php', 'fa-users', 4, 1),
(15, 20, 'Log de Auditoría', 'reporteAuditoria.php', 'fa-shield-alt', 1, 1),
(16, 20, 'Sesiones Activas', 'reporteSesionesActivas.php', 'fa-user-clock', 2, 1),
(17, 20, 'Actividad de Módulos', 'reporteActividadModulos.php', 'fa-chart-bar', 3, 1);

-- Permisos de submódulos para Rol 1 (Gerente)
INSERT IGNORE INTO `permisos_submodulo` (`id_Rol`, `idSubmodulo`, `permitido`) VALUES
(1, 1, 1), (1, 2, 1), (1, 3, 1), (1, 4, 1), (1, 5, 1), (1, 6, 1), (1, 7, 1), (1, 8, 1),
(1, 9, 1), (1, 10, 1), (1, 11, 1), (1, 12, 1), (1, 13, 1), (1, 14, 1), (1, 15, 1), (1, 16, 1), (1, 17, 1);

-- Permisos de submódulos para Rol 4 (Admin)
INSERT IGNORE INTO `permisos_submodulo` (`id_Rol`, `idSubmodulo`, `permitido`) VALUES
(4, 1, 1), (4, 2, 1), (4, 3, 1), (4, 4, 1), (4, 5, 1), (4, 6, 1), (4, 7, 1), (4, 8, 1),
(4, 9, 1), (4, 10, 1), (4, 11, 1), (4, 12, 1), (4, 13, 1), (4, 14, 1), (4, 15, 1), (4, 16, 1), (4, 17, 1);

-- Permisos de submódulos para Rol 2 (Empleado)
INSERT IGNORE INTO `permisos_submodulo` (`id_Rol`, `idSubmodulo`, `permitido`) VALUES
(2, 1, 1), (2, 2, 1), (2, 4, 1), (2, 5, 1), (2, 6, 1), (2, 7, 1), (2, 8, 1), (2, 12, 1), (2, 13, 1);

-- Tabla para asignación granular de submódulos por usuario específico (personalización por empleado)
CREATE TABLE IF NOT EXISTS `permisos_usuario_submodulo` (
  `idPermisoUserSub` INT AUTO_INCREMENT PRIMARY KEY,
  `idUsuario` INT NOT NULL,
  `idSubmodulo` INT NOT NULL,
  `permitido` TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY `uk_usuario_submodulo` (`idUsuario`, `idSubmodulo`),
  INDEX `idx_user_sub_permitido` (`idUsuario`, `permitido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catálogo de roles y subroles jerárquicos con herencia de submódulos
INSERT INTO `rol` (`id_Rol`, `nombreRol`, `descripcion`, `idRolPadre`, `submodulos`, `acceso_total`, `activo`) VALUES
(1, 'Gerente', 'Dirección General y Gestión Estratégica', NULL, NULL, 1, 1),
(2, 'Empleado', 'Personal Operativo Estándar', NULL, '[1, 2, 4, 5, 6, 7, 8, 12, 13]', 0, 1),
(3, 'Cliente', 'Clientes y Compradores Registrados', NULL, '[]', 0, 1),
(4, 'Administrador', 'Superadministrador de Sistemas y Configuración', NULL, NULL, 1, 1),
(5, 'Jefe de Producción', 'Jefe de Planta (Hereda Empleado + Reportes de Mezclas y Pedidos)', 2, '[11, 13]', 0, 1),
(6, 'Jefe de Almacén', 'Jefe de Bodega (Hereda Empleado + Reporte de Inventario)', 2, '[12]', 0, 1),
(7, 'Supervisor de Ventas', 'Supervisor de Pedidos (Hereda Empleado + Gestión y Reportes)', 2, '[3, 11]', 0, 1)
ON DUPLICATE KEY UPDATE 
  `nombreRol` = VALUES(`nombreRol`), 
  `descripcion` = VALUES(`descripcion`), 
  `idRolPadre` = VALUES(`idRolPadre`), 
  `submodulos` = VALUES(`submodulos`), 
  `acceso_total` = VALUES(`acceso_total`), 
  `activo` = VALUES(`activo`);

-- Permisos de módulos base para subroles (Jefe de Producción, Jefe de Almacén, Supervisor de Ventas)
INSERT IGNORE INTO `permisos_rol` (`id_Rol`, `idModulo`, `permitido`) VALUES
(5, 1, 1), (5, 7, 1), (5, 8, 1), (5, 9, 1), (5, 12, 1), (5, 16, 1),
(6, 1, 1), (6, 7, 1), (6, 8, 1), (6, 9, 1), (6, 12, 1), (6, 16, 1),
(7, 1, 1), (7, 7, 1), (7, 8, 1), (7, 9, 1), (7, 12, 1), (7, 16, 1);

-- Módulo de Gestión de Roles y Permisos (controllerRoles.php)
INSERT IGNORE INTO `modulos` (`idModulo`, `nombre`, `icono`, `controlador`, `orden`, `activo`) VALUES
(24, 'Roles y Permisos', 'fa-user-shield', 'controllerRoles.php', 24, 1);

-- Asignar acceso al módulo de Roles y Permisos para Gerente (Rol 1) y Administrador (Rol 4)
INSERT IGNORE INTO `permisos_rol` (`id_Rol`, `idModulo`, `permitido`) VALUES
(1, 24, 1),
(4, 24, 1);
