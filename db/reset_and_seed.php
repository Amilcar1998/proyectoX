<?php
// reset_and_seed.php
// Limpia todas las tablas y genera ~1000 registros en orden correcto
// Ejecutar: php db/reset_and_seed.php

ini_set('max_execution_time', 300);
header('Content-Type: text/plain; charset=utf-8');

$mysqli = new mysqli('localhost', 'root', '', 'concentrados');
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error . "\n");
}
$mysqli->set_charset('utf8mb4');

echo "=== Limpiando base de datos ===\n\n";

// Desactivar FK checks
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

// Orden de eliminación inverso a las FKs
$tablas = [
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

foreach ($tablas as $tabla) {
    $r = $mysqli->query("TRUNCATE TABLE $tabla");
    if ($r) {
        echo "Tabla $tabla limpiada.\n";
    } else {
        echo "Tabla $tabla no existe o error: " . $mysqli->error . "\n";
    }
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "\n=== Insertando datos base (lookup tables) ===\n\n";

// ---------- ROLES ----------
$stmt = $mysqli->prepare("INSERT INTO rol (id_Rol, nombreRol) VALUES (?,?)");
$roles = ['Gerente', 'Empleado', 'Cliente', 'Admin'];
foreach ($roles as $i => $r) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $r);
    $stmt->execute();
}
echo "Roles insertados: " . count($roles) . "\n";

// ---------- PUESTOS ----------
$stmt = $mysqli->prepare("INSERT INTO puesto (idPuesto, nombrePuesto) VALUES (?,?)");
$puestos = [
    'Gerente General', 'Jefe de Produccion', 'Supervisor', 'Operario',
    'Ventas', 'Almacenista', 'Contador', 'Compras', 'Calidad',
    'Logistica', 'RRHH', 'Sistemas'
];
foreach ($puestos as $i => $p) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $p);
    $stmt->execute();
}
echo "Puestos insertados: " . count($puestos) . "\n";

// ---------- ESTADO PEDIDO ----------
$stmt = $mysqli->prepare("INSERT INTO estadopedido (idEstadoPedido, nombreEstado) VALUES (?,?)");
$estadosPedido = ['Pendiente', 'En Proceso', 'Completado', 'Cancelado'];
foreach ($estadosPedido as $i => $e) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $e);
    $stmt->execute();
}
echo "Estados de pedido insertados: " . count($estadosPedido) . "\n";

// ---------- MATERIA PRIMA ----------
$materiasNombres = [
    'Maiz Amarillo', 'Maiz Blanco', 'Soya', 'Harina', 'Maicillo',
    'Trigo', 'Avena', 'Sorgo', 'Cebada', 'Centeno',
    'Arroz', 'Sal', 'Azucar', 'Aceite', 'Vitaminas',
    'Minerales', 'Fosfatos', 'Calcio', 'Fibra', 'Melaza'
];
$stmt = $mysqli->prepare("INSERT INTO materiaprima (idMateriaPrima, NombreMP) VALUES (?,?)");
foreach ($materiasNombres as $i => $m) {
    $id = $i + 1;
    $stmt->bind_param('is', $id, $m);
    $stmt->execute();
}
echo "Materias primas insertadas: " . count($materiasNombres) . "\n";

// ---------- PROVEEDORES ----------
$proveedorNombres = [
    'Agroindustrial del Norte', 'Distribuidora Maiz SA', 'Proveedora de Harinas Central',
    'Soya Express', 'Insumos Agricolas El Campo', 'Trigo y Derivados SA',
    'Distribuidora de Granos', 'Proveedora de Empaques Unidos', 'Nutricion Animal Pro',
    'Importadora de Insumos', 'Agroservicios La Union', 'Distribuidora Oriental',
    'Proveedora Nacional', 'Comercializadora del Sur', 'Agroinsumos Modernos'
];
$stmt = $mysqli->prepare("INSERT INTO proveedor (idProveedor, nombreProveedor, contacto, NIT, correoP, telefono) VALUES (?,?,?,?,?,?)");
$contactos = ['Juan Perez', 'Maria Lopez', 'Carlos Ruiz', 'Ana Torres', 'Luis Mendez', 'Patricia Rojas', 'Roberto Diaz', 'Elena Vasquez', 'Diego Morales', 'Sandra Gomez', 'Jorge Hernandez', 'Laura Garcia', 'Miguel Martinez', 'Carmen Flores', 'Ricardo Rivera'];
foreach ($proveedorNombres as $i => $nombre) {
    $id = 391200 + $i + 1;
    $contacto = $contactos[$i % count($contactos)];
    $nit = rand_int(1000000, 9999999);
    $correo = 'proveedor' . $id . '@empresa.com';
    $tel = '555-' . str_pad(rand_int(0, 9999), 4, '0', STR_PAD_LEFT);
    $stmt->bind_param('isssss', $id, $nombre, $contacto, $nit, $correo, $tel);
    $stmt->execute();
}
echo "Proveedores insertados: " . count($proveedorNombres) . "\n";

// ---------- RECETAS ----------
$recetaNombres = [
    'Mezcla Pollo Inicio', 'Mezcla Pollo Engorde', 'Mezcla Cerdo Inicio',
    'Mezcla Cerdo Engorde', 'Mezcla Pollo Final', 'Mezcla Cerdo Final',
    'Mezcla Aves Inicio', 'Mezcla Aves Engorde', 'Mezcla Ganado', 'Mezcla Balanceada'
];
$stmt = $mysqli->prepare("INSERT INTO receta (idReceta, nombreReceta, PrecioUnitario) VALUES (?,?,?)");
foreach ($recetaNombres as $i => $r) {
    $id = $i + 1;
    $precio = rand_float(1.00, 3.00);
    $stmt->bind_param('isd', $id, $r, $precio);
    $stmt->execute();
}
echo "Recetas insertadas: " . count($recetaNombres) . "\n";

echo "\n=== Insertando usuarios, empleados y clientes ===\n\n";

$passHash = sha1('12345');

// ---------- USUARIOS ----------
$stmt = $mysqli->prepare("INSERT INTO usuarios (idUsuario, username, pass, id_Rol) VALUES (?,?,?,?)");
$usuarios = [];
for ($i = 0; $i < 200; $i++) {
    $id = 391000 + $i + 1;
    $username = 'user' . $id;
    $rol = rand_int(1, 4);
    $stmt->bind_param('isis', $id, $username, $passHash, $rol);
    $stmt->execute();
    $usuarios[] = $id;
}
echo "Usuarios insertados: 200\n";

// ---------- EMPLEADOS ----------
$nombresM = ['Juan', 'Carlos', 'Luis', 'Roberto', 'Diego', 'Miguel', 'Jorge', 'Andres', 'Fernando', 'Ricardo', 'Oscar', 'Eduardo', 'Rafael', 'Manuel', 'Francisco', 'Antonio', 'Jose', 'Pedro', 'Daniel', 'Alejandro'];
$nombresF = ['Maria', 'Ana', 'Patricia', 'Elena', 'Sandra', 'Laura', 'Carmen', 'Rosa', 'Silvia', 'Gabriela', 'Adriana', 'Marta', 'Isabel', 'Lucia', 'Paola', 'Karen', 'Monica', 'Veronica', 'Natalia', 'Andrea'];
$apellidos = ['Perez', 'Lopez', 'Ruiz', 'Torres', 'Mendez', 'Rojas', 'Diaz', 'Vasquez', 'Morales', 'Gomez', 'Hernandez', 'Garcia', 'Martinez', 'Flores', 'Rivera', 'Soto', 'Chavez', 'Castillo', 'Ortiz', 'Silva'];
$stmt = $mysqli->prepare("INSERT INTO empleado (idEmpleado, nombreEmp, apellido, genero, idPuesto, idUsuario) VALUES (?,?,?,?,?,?)");
for ($i = 0; $i < 100; $i++) {
    $id = 391100 + $i + 1;
    $genero = rand_int(0, 1) ? 'M' : 'F';
    $nombre = $genero == 'M' ? $nombresM[array_rand($nombresM)] : $nombresF[array_rand($nombresF)];
    $apellido = $apellidos[array_rand($apellidos)];
    $idPuesto = rand_int(1, 12);
    $idUsuario = $usuarios[$i];
    $stmt->bind_param('isssii', $id, $nombre, $apellido, $genero, $idPuesto, $idUsuario);
    $stmt->execute();
}
echo "Empleados insertados: 100\n";

// ---------- CLIENTES ----------
$stmt = $mysqli->prepare("INSERT INTO cliente (idCliente, NombreCliente, apellidosCliente, telefono, edad, genero, idUsuario) VALUES (?,?,?,?,?,?,?)");
$clienteNombres = ['Distribuidora', 'Tiendas', 'Supermercado', 'Comercial', 'Mayorista'];
for ($i = 0; $i < 100; $i++) {
    $id = 391200 + $i + 1;
    $nombreCli = $clienteNombres[array_rand($clienteNombres)] . ' ' . chr(65 + $i % 26);
    $apellidoCli = $apellidos[array_rand($apellidos)];
    $edad = rand_int(18, 70);
    $genero = rand_int(0, 1) ? 'M' : 'F';
    $tel = '7' . rand_int(10000000, 99999999);
    $idUsuario = $usuarios[array_rand($usuarios)];
    $stmt->bind_param('isssisi', $id, $nombreCli, $apellidoCli, $tel, $edad, $genero, $idUsuario);
    $stmt->execute();
}
echo "Clientes insertados: 100\n";

// ---------- INVENTARIO ----------
$stmt = $mysqli->prepare("INSERT INTO inventario (idInventario, idMateriaPrima, Existencias) VALUES (?,?,?)");
for ($i = 0; $i < 20; $i++) {
    $id = $i + 1;
    $idMP = rand_int(1, 20);
    $exist = rand_int(0, 5000);
    $stmt->bind_param('iii', $id, $idMP, $exist);
    $stmt->execute();
}
echo "Inventarios insertados: 20\n";

echo "\n=== Insertando pedidos y detalles ===\n\n";

// ---------- PEDIDOS ----------
$stmt = $mysqli->prepare("INSERT INTO pedido (idPedido, fechaPedido, idCliente, idEstadoPedido) VALUES (?,?,?,?)");
$pedidos = [];
for ($i = 0; $i < 100; $i++) {
    $id = 391000 + $i + 1;
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $idCli = 391200 + rand_int(0, 99);
    $idEstado = rand_int(1, 4);
    $stmt->bind_param('isii', $id, $fecha, $idCli, $idEstado);
    $stmt->execute();
    $pedidos[] = $id;
}
echo "Pedidos insertados: 100\n";

// ---------- DETALLE PEDIDO ----------
$stmt = $mysqli->prepare("INSERT INTO detallepedido (idDetallePedido, cantidad, idReceta, IdPedido) VALUES (?,?,?,?)");
for ($i = 0; $i < 200; $i++) {
    $id = 391200 + $i + 1;
    $cant = rand_int(10, 500);
    $idRec = rand_int(1, 10);
    $idPed = $pedidos[array_rand($pedidos)];
    $stmt->bind_param('iiii', $id, $cant, $idRec, $idPed);
    $stmt->execute();
}
echo "Detalle pedidos insertados: 200\n";

// ---------- DETALLE RECETA ----------
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
echo "Detalle recetas insertados: 50\n";

// ---------- PRODUCCION ----------
$stmt = $mysqli->prepare("INSERT INTO produccion (idProduccion, fechaP, estadoP, idPedido, idEmpleado) VALUES (?,?,?,?,?)");
$producciones = [];
for ($i = 0; $i < 80; $i++) {
    $id = 391300 + $i + 1;
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $estado = ['Pendiente', 'En Proceso', 'Completado', 'Cancelado'][array_rand(['Pendiente', 'En Proceso', 'Completado', 'Cancelado'])];
    $idPed = $pedidos[array_rand($pedidos)];
    $idEmp = 391100 + rand_int(0, 99);
    $stmt->bind_param('issii', $id, $fecha, $estado, $idPed, $idEmp);
    $stmt->execute();
    $producciones[] = $id;
}
echo "Producciones insertadas: 80\n";

// ---------- FACTURAS ----------
$stmt = $mysqli->prepare("INSERT INTO factura (idFacturaMP, numeroFac, Monto, Fecha, idProveedor, idEmpleado) VALUES (?,?,?,?,?,?)");
$facturas = [];
for ($i = 0; $i < 100; $i++) {
    $id = 23233 + $i;
    $numero = 'FAC-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    $monto = rand_float(100, 10000);
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $idProv = 391200 + rand_int(0, 14);
    $idEmp = 391100 + rand_int(0, 99);
    $stmt->bind_param('isdssi', $id, $numero, $monto, $fecha, $idProv, $idEmp);
    $stmt->execute();
    $facturas[] = $id;
}
echo "Facturas insertadas: 100\n";

// ---------- DETALLE COMPRA ----------
$stmt = $mysqli->prepare("INSERT INTO detallecompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) VALUES (?,?,?,?,?)");
for ($i = 0; $i < 150; $i++) {
    $id = $i + 1;
    $idMP = rand_int(1, 20);
    $cantidad = rand_int(10, 1000);
    $precio = rand_float(0.50, 5.00);
    $idFac = $facturas[array_rand($facturas)];
    $stmt->bind_param('iiisi', $id, $idMP, $cantidad, $precio, $idFac);
    $stmt->execute();
}
echo "Detalle compras insertados: 150\n";

// ---------- SALIDAS ----------
$stmt = $mysqli->prepare("INSERT INTO salidas (idSalida, idProduccion, NombreCliente, fecha, montoTotal, estadoSalida) VALUES (?,?,?,?,?,?)");
$clienteNoms = ['Distribuidora El Sol', 'Tiendas La Esquina', 'Supermercado Norte', 'Comercial Centro', 'Mayorista Sur'];
for ($i = 0; $i < 80; $i++) {
    $id = $i + 1;
    $idProd = $producciones[array_rand($producciones)];
    $cliente = $clienteNoms[array_rand($clienteNoms)];
    $fecha = rand_date('01/01/2025', '27/06/2026');
    $monto = rand_float(50, 2000);
    $estadoSalida = rand_int(0, 1) ? 'Entregado' : 'Pendiente';
    $stmt->bind_param('iissds', $id, $idProd, $cliente, $fecha, $monto, $estadoSalida);
    $stmt->execute();
}
echo "Salidas insertadas: 80\n";

$mysqli->close();

echo "\n=== Proceso completado ===\n";
echo "Total registros insertados: ~" . (200 + 100 + 100 + 20 + 100 + 200 + 50 + 80 + 100 + 150 + 80) . "\n";
echo "Password por defecto: 12345 (SHA1)\n";

function rand_int($min, $max) { return mt_rand($min, $max); }
function rand_float($min, $max) { return round($min + mt_rand(0, ($max - $min) * 100) / 100, 2); }
function rand_date($start, $end) {
    $d1 = DateTime::createFromFormat('d/m/Y', $start);
    $d2 = DateTime::createFromFormat('d/m/Y', $end);
    $ts1 = $d1->getTimestamp();
    $ts2 = $d2->getTimestamp();
    $r = mt_rand($ts1, $ts2);
    return date('d/m/Y', $r);
}
?>