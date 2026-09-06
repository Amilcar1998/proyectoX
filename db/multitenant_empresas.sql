-- ==========================================================
-- Migración para Aislamiento Multi-Tenant por idEmpresa
-- Diccionario de Datos con Comentarios Técnicos Exhaustivos
-- Concentrados El Gordito (proyectoX)
-- ==========================================================

-- 1. Tabla de Empresas / Comercios Afiliados (Multi-Tenant)
CREATE TABLE IF NOT EXISTS empresas (
    idEmpresa INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único y autoincremental de la empresa o comercio',
    idUsuarioDueno INT NOT NULL DEFAULT 0 COMMENT 'ID del usuario dueño o administrador principal de la empresa (FK usuarios)',
    nombreEmpresa VARCHAR(150) NOT NULL COMMENT 'Razón social o nombre comercial visible del negocio',
    slug VARCHAR(100) UNIQUE NOT NULL COMMENT 'Identificador alfanumérico único para subdominios o URL (ej: santaelena)',
    direccion VARCHAR(255) NULL COMMENT 'Dirección física de la sede, planta o sucursal principal',
    telefono VARCHAR(50) NULL COMMENT 'Número telefónico fijo o PBX de atención al cliente',
    correo VARCHAR(150) NULL COMMENT 'Correo electrónico oficial para notificaciones y pedidos',
    whatsapp VARCHAR(50) NULL COMMENT 'Número de WhatsApp directo con código de país para atención rápida',
    logo VARCHAR(255) NULL COMMENT 'Ruta relativa de la imagen o logotipo del comercio',
    wompiAppId VARCHAR(255) NULL COMMENT 'ID de aplicación (Client ID / App ID) de la pasarela Wompi SV del comercio',
    wompiApiKey VARCHAR(255) NULL COMMENT 'Clave secreta (API Secret Key) de Wompi SV para autenticar cobros directos',
    wompiActivo TINYINT(1) DEFAULT 0 COMMENT 'Estado de la pasarela Wompi del negocio: 1 = Habilitada, 0 = Deshabilitada',
    activo TINYINT(1) DEFAULT 1 COMMENT 'Estado operativo del comercio en la plataforma: 1 = Activo, 0 = Suspendido',
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora exacta en la que se registró la empresa en el sistema',
    INDEX idx_empresa_dueno (idUsuarioDueno),
    INDEX idx_empresa_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de empresas y comercios afiliados para arquitectura SaaS Multi-Tenant';

-- 2. Asignación y documentación de idEmpresa en tablas operativas del sistema
ALTER TABLE usuarios 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa a la que pertenece el usuario (FK empresas)';

ALTER TABLE pedido 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa que vende y despacha la orden (FK empresas)';

ALTER TABLE cliente 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa a cuya cartera pertenece el cliente (FK empresas)';

ALTER TABLE factura 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa emisora del documento tributario o factura (FK empresas)';

ALTER TABLE inventario 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa dueña del lote o producto en inventario (FK empresas)';

ALTER TABLE materiaprima 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa propietaria de la materia prima (FK empresas)';

ALTER TABLE empleado 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa en cuya nómina y personal labora el empleado (FK empresas)';

ALTER TABLE proveedor 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa asociada a este proveedor de insumos (FK empresas)';

ALTER TABLE produccion 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa que ejecuta la orden o lote de producción (FK empresas)';

ALTER TABLE pagos 
    MODIFY COLUMN idEmpresa INT NOT NULL DEFAULT 1 COMMENT 'ID de la empresa que recibe y liquida la transacción de pago Wompi (FK empresas)';
