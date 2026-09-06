# Reglas del Proyecto - Concentrados El Gordito (proyectoX)

Este repositorio sigue directrices estrictas de arquitectura, calidad de código y diseño de software. Todo asistente de inteligencia artificial y desarrollador debe cumplir obligatoriamente con las siguientes reglas:

---

## 1. Arquitectura 100% MVC Obligatoria
El proyecto es y debe ser **100% MVC (Modelo - Vista - Controlador)**.
> **Regla de oro:** Si alguna parte del sistema no está estructurada bajo MVC, **debes refactorizarla obligatoriamente a MVC**. No se permite agregar ni mantener código espagueti ni scripts monolíticos.

### Separación de Responsabilidades

1. **Controladores (`controllers/`)**:
   - Orquestan el flujo de la aplicación.
   - Reciben y limpian las solicitudes (`$_GET`, `$_POST`, `$_REQUEST`, JSON).
   - Invocan a los modelos correspondientes para procesar la lógica de negocio y datos.
   - Deciden qué vista cargar (`include '../views/vistaXYZ.php'`) o qué respuesta JSON retornar.
   - **Prohibido:** Ejecutar consultas SQL directas (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) en el controlador o generar cadenas extensas de HTML.

2. **Modelos (`models/`)**:
   - Gestionan las entidades, la lógica de negocio y la persistencia de datos contra la base de datos (usando la clase `Conexion`).
   - Retornan estructuras de datos limpias (arreglos asociativos, objetos de entidad, booleanos o enteros de filas afectadas).
   - **Prohibido:** Hacer `echo` de código HTML, imprimir tablas formateadas con etiquetas `<table>`, o manejar sesiones / redirecciones `header('Location: ...')`.

3. **Vistas (`views/`)**:
   - Responsables exclusivas de la presentación visual y la interacción del usuario.
   - Consumen las variables y datos expuestos previamente por el controlador.
   - **Prohibido:** Instanciar conexiones directas a la base de datos (`new mysqli(...)`) o ejecutar consultas SQL dentro de los archivos de vista. Todo dato debe provenir del controlador.

4. **Archivos fuera de las capas MVC**:
   - Scripts en raíz o carpetas heredadas con lógica mezclada (HTML + SQL) deben migrarse a sus respectivos `controllers/`, `models/` y `views/`.

---

## 2. Nomenclatura 100% en Español
Todos los métodos, funciones y nombres de procedimientos en el código deben estar escritos en **idioma español**.

- **Correcto:**
  - `obtenerPorId($id)`
  - `guardar($datos)`
  - `actualizar($id, $datos)`
  - `eliminar($id)`
  - `listarTodos()`
  - `validarUsuario($usuario, $clave)`
  - `calcularTotal($detalles)`
  - `generarReporte($parametros)`
- **Incorrecto:**
  - `getById($id)`
  - `save($data)`
  - `update($id, $data)`
  - `delete($id)`
  - `getAll()`
  - `validateUser($user, $pass)`

---

## 3. Métodos Cortos y con Pocos Parámetros

1. **Métodos Cortos (Principio de Responsabilidad Única)**:
   - Cada método debe realizar una única tarea bien definida.
   - Mantener los métodos concisos (generalmente entre 10 y 25 líneas).
   - Si un método se vuelve extenso o complejo, debe dividirse en submétodos privados auxiliares bien nombrados.

2. **Pocos Parámetros (Máximo 2 a 3 parámetros)**:
   - Los métodos deben recibir un número reducido de argumentos (idealmente 1 o 2, máximo 3).
   - Si una operación requiere múltiples valores (por ejemplo, registrar un pedido o una persona), se debe encapsular la información en:
     - Una clase de entidad (ej. objeto `Cliente`, `Empleado`, `Factura`).
     - Un arreglo asociativo estructurado (ej. `$datosCliente`).
   - Evitar firmas de métodos con listas largas de argumentos como `crearUsuario($nombre, $apellido, $correo, $telefono, $direccion, $rol, $estado, ...)`.

---

## 4. Guía Rápida de Refactorización
Al intervenir cualquier archivo del proyecto:
1. ¿El archivo mezcla HTML con consultas SQL o lógica de sesión? $\rightarrow$ Extraer la consulta a un método de un Modelo en `models/`, el flujo a un Controlador en `controllers/`, y la interfaz a una Vista en `views/`.
2. ¿El método está en inglés? $\rightarrow$ Renombrarlo al español respetando compatibilidad si es llamado por otras partes.
3. ¿El método tiene más de 3 parámetros o supera las 30 líneas? $\rightarrow$ Refactorizar agrupando parámetros en un objeto/arreglo y extrayendo submétodos.
