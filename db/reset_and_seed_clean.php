<?php
// reset_and_seed_clean.php
// Limpia TODAS las tablas y regenera ~1000 registros con relaciones correctas
// Ejecutar: php db/reset_and_seed_clean.php

ini_set('max_execution_time', 300);
echo "=== Iniciando reset completo ===\n\n";

$mysqli = new mysqli('localhost', 'root', '', 'concentrados');
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error . "\n");
}
$mysqli->set_charset('utf8mb4');

// Desactivar FK checks
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

// Obtener todas las tablas
$tablas_raw = $mysqli->query("SHOW TABLES");
$tablas = [];
while ($row = $tablas_raw->fetch_array()) {
    $tablas[] = $row[0];
}
echo "Tablas encontradas: " . implode(', ', $tablas) . "\n\n";

// Orden de truncado (de hijas a padres)
$orden_truncado = [
    'salidas',
    'detallecompra', 
    'detallereceta',
    'detallepedido',
    'produccion',
    'pedido',
    'factura',
    'inventario',
    'pedidoproveedor',
    'cliente',
    'empleado',
    'proveedor',
    'materiaprima',
    'receta',
    'usuarios',
    'puesto',
    'rol',
    'estadopedido'
];

foreach ($orden_truncado as $tabla) {
    if (in_array($tabla, $tablas)) {
        $mysqli->query("TRUNCATE TABLE $tabla");
        echo "✓ $tabla limpiada\n";
    } else {
        echo "✗ $tabla no existe (omitida)\n";
    }
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "\n=== Insertando datos base ===\n\n";

// Funciones auxiliares
function rand_int($min, $max) { return mt_rand($min, $max); }
function rand_float($min, $max) { return round($min + mt_rand(0, ($max-$min)*100) / 100, 2); }
function rand_date($start, $end) {
    $d1 = DateTime::createFromFormat('d/m/Y', $start);
    $d2 = DateTime::createFromFormat('d/m/Y', $end);
    $ts1 = $d1->getTimestamp();
    $ts2 = $d2->getTimestamp();
    $r = mt_rand($ts1, $ts2);
    return date('d/m/Y', $r);
}

// Datos base
$nombresM = ['Juan','Carlos','Luis','Roberto','Diego','Miguel','Jorge','Andres','Fernando','Ricardo','Oscar','Eduardo','Rafael','Manuel','Francisco','Antonio','Jose','Pedro','Daniel','Alejandro'];
$nombresF = ['Maria','Ana','Patricia','Elena','Sandra','Laura','Carmen','Rosa','Silvia','Gabriela','Adriana','Marta','Isabel','Lucia','Paola','Karen','Monica','Veronica','Natalia','Andrea'];
$apellidos = ['Perez','Lopez','Ruiz','Torres','Mendez','Rojas','Diaz','Vasquez','Morales','Gomez','Hernandez','Garcia','Martinez','Flores','Rivera','Soto','Chavez','Castillo','Ortiz','Silva'];

// 1. ROLES (4)
$stmt = $mysqli->prepare("INSERT INTO rol (id_Rol, nombreRol) VALUES (?,?)");
$roles = ['Gerente', 'Empleado', 'Cliente', 'Admin'];
foreach ($roles as $i => $r) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $r);
    $stmt->execute();
}
echo "Roles: 4\n";

// 2. PUESTOS (12)
$stmt = $mysqli->prepare("INSERT INTO puesto (idPuesto, nombrePuesto) VALUES (?,?)");
$puestos = ['Gerente General','Jefe de Produccion','Supervisor','Operario','Ventas','Almacenista','Contador','Compras','Calidad','Logistica','RRHH','Sistemas'];
foreach ($puestos as $i => $p) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $p);
    $stmt->execute();
}
echo "Puestos: 12\n";

// 3. ESTADO PEDIDO (4)
$stmt = $mysqli->prepare("INSERT INTO estadopedido (idEstadoPedido, nombreEstado) VALUES (?,?)");
$estados = ['Pendiente','En Proceso','Completado','Cancelado'];
foreach ($estados as $i => $e) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $e);
    $stmt->execute();
}
echo "Estados pedido: 4\n";

// 4. MATERIA PRIMA (20)
$materias = ['Maiz Amarillo','Maiz Blanco','Soya','Harina','Maicillo','Trigo','Avena','Sorgo','Cebada','Centeno','Arroz','Sal','Azucar','Aceite','Vitaminas','Minerales','Fosfatos','Calcio','Fibra','Melaza'];
$stmt = $mysqli->prepare("INSERT INTO materiaprima (idMateriaPrima, NombreMP) VALUES (?,?)");
foreach ($materias as $i => $m) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $m);
    $stmt->execute();
}
echo "Materias primas: 20\n";

// 5. PROVEEDORES (15)
$prov_nombres = ['Agroindustrial del Norte','Distribuidora Maiz SA','Proveedora de Harinas Central','Soya Express','Insumos Agricolas El Campo','Trigo y Derivados SA','Distribuidora de Granos','Proveedora de Empaques Unidos','Nutricion Animal Pro','Importadora de Insumos','Agroservicios La Union','Distribuidora Oriental','Proveedora Nacional','Comercializadora del Sur','Agroinsumos Modernos'];
$stmt = $mysqli->prepare("INSERT INTO proveedor (idProveedor, nombreProveedor, contacto, NIT, correoP, telefono) VALUES (?,?,?,?,?,?)");
foreach ($prov_nombres as $i => $nombre) {
    $id = 391200 + $i + 1;
    $contacto = $nombresM[$i % 20];
    $nit = rand_int(1000000, 9999999);
    $correo = "prov$id@empresa.com";
    $tel = '555-' . str_pad(rand_int(0,9999), 4, '0', STR_PAD_LEFT);
    $stmt->bind_param('isssss', $id, $nombre, $contacto, $nit, $correo, $tel);
    $stmt->execute();
}
echo "Proveedores: 15\n";

// 6. RECETAS (10)
$recetas = ['Mezcla Pollo Inicio','Mezcla Pollo Engorde','Mezcla Cerdo Inicio','Mezcla Cerdo Engorde','Mezcla Pollo Final','Mezcla Cerdo Final','Mezcla Aves Inicio','Mezcla Aves Engorde','Mezcla Ganado','Mezcla Balanceada'];
$stmt = $mysqli->prepare("INSERT INTO receta (idReceta, nombreReceta, PrecioUnitario) VALUES (?,?,?)");
foreach ($recetas as $i => $r) {
    $id = $i + 1;
    $precio = rand_float(1.00, 3.00);
    $stmt->bind_param('isd', $id, $r, $precio);
    $stmt->execute();
}
echo "Recetas: 10\n";

echo "\n=== Insertando usuarios, empleados y clientes ===\n\n";

// 7. USUARIOS (~200)
$passHash = sha1('12345');
$stmt = $mysqli->prepare("INSERT INTO usuarios (idUsuario, username, pass, id_Rol) VALUES (?,?,?,?)");
$usuarios_ids = [];
for ($i = 0; $i < 200; $i++) {
    $id = 391000 + $i + 1;
    $username = "user$id";
    $rol = rand_int(1, 4);
    $stmt->bind_param('isis', $id, $username, $passHash, $rol);
    $stmt->execute();
    $usuarios_ids[] = $id;
}
echo "Usuarios: 200\n";

// 8. EMPLEADOS (100)
$stmt = $mysqli->prepare("INSERT INTO empleado (idEmpleado, nombreEmp, apellido, genero, idPuesto, idUsuario) VALUES (?,?,?,?,?,?)");
for ($i = 0; $i < 100; $i++) {
    $id = 391100 + $i + 1;
    $genero = rand_int(0,1) ? 'M' : 'F';
    $nombre = $genero == 'M' ? $nombresM[array_rand($nombresM)] : $nombresF[array_rand($nombresF)];
    $apellido = $apellidos[array_rand($apellidos)];
    $idPuesto = rand_int(1, 12);
    $idUsuario = $usuarios_ids[$i];
    $stmt->bind_param('isssii', $id, $nombre, $apellido, $genero, $idPuesto, $idUsuario);
    $stmt->execute();
}
echo "Empleados: 100\n";

// 9. CLIENTES (100)
$stmt = $mysqli->prepare("INSERT INTO cliente (idCliente, NombreCliente, apellidosCliente, telefono, edad, genero, idUsuario) VALUES (?,?,?,?,?,?,?)");
$cliente_nombres = ['Distribuidora','Tiendas','Supermercado','Comercial','Mayorista'];
for ($i = 0; $i < 100; $i++) {
    $id = 391200 + $i + 1;
    $nombreCli = $cliente_nombres[array_rand($cliente_nombres)] . ' ' . chr(65 + $i % 26);
    $apellidoCli = $apellidos[array_rand($apellidos)];
    $edad = rand_int(18, 70);
    $genero = rand_int(0,1) ? 'M' : 'F';
    $tel = '7' . rand_int(10000000, 99999999);
    $idUsuario = $usuarios_ids[array_rand($usuarios_ids)];
    $stmt->bind_param('isssisi', $id, $nombreCli, $apellidoCli, $tel, $edad, $genero, $idUsuario);
    $stmt->execute();
}
echo "Clientes: 100\n";

// 10. INVENTARIO (20)
$stmt = $mysqli->prepare("INSERT INTO inventario (idInventario, idMateriaPrima, Existencias) VALUES (?,?,?)");
for ($i = 0; $i < 20; $i++) {
    $id = $i + 1;
    $idMP = rand_int(1, 20);
    $exist = rand_int(0, 5000);
    $stmt->bind_param('iii', $id, $idMP, $exist);
    $stmt->execute();
}
echo "Inventario: 20\n";

echo "\n=== Insertando pedidos, produccion, facturas, compras ===\n\n";

// 11. PEDIDOS (100)
$stmt = $mysqli->prepare("INSERT INTO pedido (idPedido, fechaPedido, idCliente, idEstadoPedido) VALUES (?,?,?,?)");
$pedidos_ids = [];
for ($i = 0; $i < 100; $i++) {
    $id = 391000 + $i + 1;
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $idCli = 391200 + rand_int(0, 99);
    $idEstado = rand_int(1, 4);
    $stmt->bind_param('isii', $id, $fecha, $idCli, $idEstado);
    $stmt->execute();
    $pedidos_ids[] = $id;
}
echo "Pedidos: 100\n";

// 12. DETALLE PEDIDO (200)
$stmt = $mysqli->prepare("INSERT INTO detallepedido (idDetallePedido, cantidad, idReceta, IdPedido) VALUES (?,?,?,?)");
for ($i = 0; $i < 200; $i++) {
    $id = 391200 + $i + 1;
    $cant = rand_int(10, 500);
    $idRec = rand_int(1, 10);
    $idPed = $pedidos_ids[array_rand($pedidos_ids)];
    $stmt->bind_param('iiii', $id, $cant, $idRec, $idPed);
    $stmt->execute();
}
echo "Detalle pedido: 200\n";

// 13. DETALLE RECETA (50)
$stmt = $mysqli->prepare("INSERT INTO detallereceta (idDetalleReceta, idMateriaPrima, cantidaSa, fechaSa, idInventario, IdReceta) VALUES (?,?,?,?,?,?)");
for ($i = 0; $i < 50; $i++) {
    $id = $i + 1;
    $idMP = rand_int(1, 20);
    $cantidad = rand_int(50, 2000);
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $idInv = rand_int(1, 20);
    $idRec = rand_int(1, 10);
    $stmt->bind_param('iissii', $id, $idMP, $cantidad, $fecha, $idInv, $idRec);
    $stmt->execute();
}
echo "Detalle receta: 50\n";

// 14. PRODUCCION (80)
$stmt = $mysqli->prepare("INSERT INTO produccion (idProduccion, fechaP, estadoP, idPedido, idEmpleado) VALUES (?,?,?,?,?)");
$producciones_ids = [];
for ($i = 0; $i < 80; $i++) {
    $id = 391300 + $i + 1;
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $estado = $estados[array_rand($estados)];
    $idPed = $pedidos_ids[array_rand($pedidos_ids)];
    $idEmp = 391100 + rand_int(0, 99);
    $stmt->bind_param('issii', $id, $fecha, $estado, $idPed, $idEmp);
    $stmt->execute();
    $producciones_ids[] = $id;
}
echo "Produccion: 80\n";

// 15. FACTURAS (100)
$stmt = $mysqli->prepare("INSERT INTO factura (idFacturaMP, numeroFac, Monto, Fecha, idProveedor, idEmpleado) VALUES (?,?,?,?,?,?)");
$facturas_ids = [];
for ($i = 0; $i < 100; $i++) {
    $id = 23233 + $i;
    $numero = 'FAC-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    $monto = rand_float(100, 10000);
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $idProv = 391200 + rand_int(0, 14);
    $idEmp = 391100 + rand_int(0, 99);
    $stmt->bind_param('isdssi', $id, $numero, $monto, $fecha, $idProv, $idEmp);
    $stmt->execute();
    $facturas_ids[] = $id;
}
echo "Facturas: 100\n";

// 16. DETALLE COMPRA (150)
$stmt = $mysqli->prepare("INSERT INTO detallecompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) VALUES (?,?,?,?,?)");
for ($i = 0; $i < 150; $i++) {
    $id = $i + 1;
    $idMP = rand_int(1, 20);
    $cantidad = rand_int(10, 1000);
    $precio = rand_float(0.50, 5.00);
    $idFac = $facturas_ids[array_rand($facturas_ids)];
    $stmt->bind_param('iiisi', $id, $idMP, $cantidad, $precio, $idFac);
    $stmt->execute();
}
echo "Detalle compra: 150\n";

// 17. SALIDAS (80)
$stmt = $mysqli->prepare("INSERT INTO salidas (idSalida, idProduccion, NombreCliente, fecha, montoTotal, estadoSalida) VALUES (?,?,?,?,?,?)");
$cliente_noms = ['Distribuidora El Sol','Tiendas La Esquina','Supermercado Norte','Comercial Centro','Mayorista Sur'];
for ($i = 0; $i < 80; $i++) {
    $id = $i + 1;
    $idProd = $producciones_ids[array_rand($producciones_ids)];
    $cliente = $cliente_noms[array_rand($cliente_noms)];
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $monto = rand_float(50, 2000);
    $estadoSalida = rand_int(0,1) ? 'Entregado' : 'Pendiente';
    $stmt->bind_param('iissds', $id, $idProd, $cliente, $fecha, $monto, $estadoSalida);
    $stmt->execute();
}
echo "Salidas: 80\n";

// Verificar total
$total = $mysqli->query("SELECT 
    (SELECT COUNT(*) FROM rol) +
    (SELECT COUNT(*) FROM puesto) +
    (SELECT COUNT(*) FROM estadopedido) +
    (SELECT COUNT(*) FROM materiaprima) +
    (SELECT COUNT(*) FROM proveedor) +
    (SELECT COUNT(*) FROM receta) +
    (SELECT COUNT(*) FROM usuarios) +
    (SELECT COUNT(*) FROM empleado) +
    (SELECT COUNT(*) FROM cliente) +
    (SELECT COUNT(*) FROM inventario) +
    (SELECT COUNT(*) FROM pedido) +
    (SELECT COUNT(*) FROM detallepedido) +
    (SELECT COUNT(*) FROM detallereceta) +
    (SELECT COUNT(*) FROM produccion) +
    (SELECT COUNT(*) FROM factura) +
    (SELECT COUNT(*) FROM detallecompra) +
    (SELECT COUNT(*) FROM salidas) AS total
")->fetch_assoc()['total'];

$mysqli->close();

echo "\n=== Proceso completado ===\n";
echo "Total registros en BD: $total\n";
echo "Password por defecto: 12345 (SHA1)\n";
?>
