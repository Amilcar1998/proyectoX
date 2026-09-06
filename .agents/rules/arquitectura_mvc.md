# Reglas de Arquitectura MVC

## Obligatoriedad del Modelo - Vista - Controlador (100% MVC)
Todo componente, módulo o función desarrollada o modificada en este proyecto debe ajustarse estrictamente al patrón MVC.

### 1. Controladores (`controllers/`)
- Actúan como intermediarios entre la solicitud del cliente y los modelos.
- Responsabilidades:
  - Validar datos de entrada (`$_POST`, `$_GET`, `$_REQUEST`).
  - Control de sesiones (`Sesiones.php`).
  - Instanciar e invocar métodos de los Modelos en `models/`.
  - Preparar los datos e incluir la vista adecuada en `views/`.
- **Restricciones estrictas**:
  - No ejecutar sentencias SQL directas.
  - No generar maquetación HTML extensa.

### 2. Modelos (`models/`)
- Representan las entidades de negocio y la capa de acceso a datos.
- Responsabilidades:
  - Interactuar con la base de datos a través de la conexión centralizada (`Conexion.php` en `models/` o `db/`).
  - Ejecutar consultas preparadas (Prepared Statements) para prevenir inyecciones SQL.
  - Retornar datos estructurados (objetos, arreglos asociativos o booleanos).
- **Restricciones estrictas**:
  - No imprimir HTML (`echo`, `print`).
  - No manipular directamente `$_GET`, `$_POST`, `$_SESSION` ni redirecciones `header()`.

### 3. Vistas (`views/`)
- Encargadas exclusivamente del diseño, presentación y renderizado para el usuario (Bootstrap 5, SB-Admin, HTML).
- Responsabilidades:
  - Mostrar la información provista por el controlador.
  - Enviar formularios y peticiones AJAX hacia los controladores correspondientes.
- **Restricciones estrictas**:
  - No abrir conexiones a bases de datos (`mysqli`).
  - No ejecutar consultas SQL (`SELECT`, `INSERT`, etc.).
  - No procesar lógica compleja de negocio.

### 4. Regla de Refactorización Activa
- Si se encuentra código legado que no cumple con MVC (por ejemplo, scripts en la raíz del proyecto, carpetas con DAOs que imprimen HTML directamente, o vistas con llamadas a base de datos):
  - **Debe refactorizarse a MVC** separando la lógica en `controllers/`, `models/` y `views/`.
