# Reglas de Métodos y Convenciones de Código

## 1. Nombres de Métodos en Español
- Todos los métodos, funciones y procedimientos deben nombrarse íntegramente en **español**.
- Los nombres deben comenzar con un verbo en infinitivo que describa con precisión su propósito:
  - `obtener...` (en lugar de `get...`)
  - `listar...` (en lugar de `fetchAll...` o `getAll...`)
  - `guardar...` o `insertar...` (en lugar de `save...` o `insert...`)
  - `actualizar...` (en lugar de `update...`)
  - `eliminar...` (en lugar de `delete...` o `remove...`)
  - `validar...` (en lugar de `check...` o `validate...`)
  - `calcular...` (en lugar de `calculate...`)
  - `generar...` (en lugar de `generate...`)

## 2. Métodos Cortos (Responsabilidad Única)
- Cada método debe enfocarse en realizar una única acción.
- Los métodos deben mantenerse compactos, generalmente entre 10 y 25 líneas.
- Evitar métodos monolíticos o anidamientos profundos de condiciones (`if/else` múltiples).
- Si un método realiza varias tareas (por ejemplo: validar, transformar, guardar y auditar), debe descomponerse en métodos auxiliares privados.

## 3. Pocos Parámetros
- El número de parámetros por método debe ser el mínimo posible (ideal 1 o 2, máximo 3).
- Cuando una operación requiera 4 o más valores:
  - Agrupar los parámetros en una clase de entidad o modelo (ej. `$empleado`, `$factura`).
  - O utilizar un arreglo asociativo documentado (ej. `$datosUsuario`).
- Evitar firmas con muchos argumentos posicionales que aumenten el acoplamiento y la probabilidad de errores.
