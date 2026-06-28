-- Tabla para recuperación de contraseña
CREATE TABLE IF NOT EXISTS recuperacion_pass (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expiracion DATETIME NOT NULL,
    usado BOOLEAN DEFAULT 0,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_email (email),
    INDEX idx_expiracion (expiracion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
