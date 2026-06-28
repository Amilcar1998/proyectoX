<?php
// seed_masivo.php - Genera ~1000 registros para todas las tablas
// Ejecutar: http://localhost/ProyectoX/db/seed_masivo.php
// NOTA: Asegurate de que la BD 'concentrados' existe y tienes el archivo concentrados.sql cargado.

ini_set('max_execution_time', 300);
header('Content-Type: text/plain; charset=utf-8');

$mysqli = new mysqli('localhost', 'root', '', 'concentrados');
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error . "\n");
}
$mysqli->set_charset('utf8mb4');

echo "=== Iniciando seed masivo ===\n\n";

// Desactivar FK checks temporalmente
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

// Obtener IDs máximos actuales para evitar conflictos
function getMax($mysqli, $table, $col) {
    $r = $mysqli->query("SELECT MAX($col) AS m FROM $table");
    return $r ? ($r->fetch_assoc()['m'] ?? 0) : 0;
}

$maxRol = getMax($mysqli, 'rol', 'id_Rol');
$maxPuesto = getMax($mysqli, 'puesto', 'idPuesto');
$maxProv = getMax($mysqli, 'proveedor', 'idProveedor');
$maxMP = getMax($mysqli, 'materiaprima', 'idMateriaPrima');
$maxUsuario = getMax($mysqli, 'usuarios', 'idUsuario');
$maxEmp = getMax($mysqli, 'empleado', 'idEmpleado');
$maxCli = getMax($mysqli, 'cliente', 'idCliente');
$maxReceta = getMax($mysqli, 'receta', 'idReceta');
$maxInv = getMax($mysqli, 'inventario', 'idInventario');
$maxPedido = getMax($mysqli, 'pedido', 'idPedido');
$maxDetPedido = getMax($mysqli, 'detallepedido', 'idDetallePedido');
$maxDetReceta = getMax($mysqli, 'detallereceta', 'idDetalleReceta');
$maxProd = getMax($mysqli, 'produccion', 'idProduccion');
$maxFactura = getMax($mysqli, 'factura', 'idFacturaMP');
$maxDetCompra = getMax($mysqli, 'detallecompra', 'idDetalleCompra');
$maxPedProv = getMax($mysqli, 'pedidoproveedor', 'idPedido');
$maxSalida = getMax($mysqli, 'salidas', 'idSalida');

echo "IDs máximos detectados:\n";
echo "Rol=$maxRol, Puesto=$maxPuesto, Proveedor=$maxProv, MateriaPrima=$maxMP\n";
echo "Usuario=$maxUsuario, Empleado=$maxEmp, Cliente=$maxCli, Receta=$maxReceta\n";
echo "Inventario=$maxInv, Pedido=$maxPedido, Factura=$maxFactura\n\n";

$passHash = "'" . sha1('12345') . "'";
$now = date('d/m/Y H:i');

// ---------- DATOS BASE ----------
$roles = ['Gerente','Empleado','Cliente','Admin'];
$puestos = ['Gerente General','Jefe de Produccion','Supervisor','Operario','Ventas','Almacenista','Contador','Compras','Calidad','Logistica','RRHH','Sistemas'];
$nombresM = ['Juan','Carlos','Luis','Roberto','Diego','Miguel','Jorge','Andres','Fernando','Ricardo','Oscar','Eduardo','Rafael','Manuel','Francisco','Antonio','Jose','Pedro','Daniel','Alejandro'];
$nombresF = ['Maria','Ana','Patricia','Elena','Sandra','Laura','Carmen','Rosa','Silvia','Gabriela','Adriana','Marta','Isabel','Lucia','Paola','Karen','Monica','Veronica','Natalia','Andrea'];
$apellidos = ['Perez','Lopez','Ruiz','Torres','Mendez','Rojas','Diaz','Vasquez','Morales','Gomez','Hernandez','Garcia','Martinez','Flores','Rivera','Soto','Chavez','Castillo','Ortiz','Silva'];
$materiasNombres = ['Maiz Amarillo','Maiz Blanco','Soya','Harina','Maicillo','Trigo','Avena','Sorgo','Cebada','Centeno','Arroz','Sal','Azucar','Aceite','Vitaminas','Minerales','Fosfatos','Calcio','Fibra','Melaza'];
$proveedorNombres = ['Agroindustrial del Norte','Distribuidora Maiz SA','Proveedora de Harinas Central','Soya Express','Insumos Agricolas El Campo','Trigo y Derivados SA','Distribuidora de Granos','Proveedora de Empaques Unidos','Nutricion Animal Pro','Importadora de Insumos','Agroservicios La Union','Distribuidora Oriental','Proveedora Nacional','Comercializadora del Sur','Agroinsumos Modernos'];
$clienteNombres = ['Distribuidora El Sol','Tiendas La Esquina','Supermercado Norte','Comercial Centro','Mayorista Sur','Distribuidora Oeste','Tiendas La Luz','Supermercado Central','Mayorista Nacional','Distribuidora del Pacifico','Tiendas El Eden','Comercial San Juan','Mayorista del Norte','Supermercado Sur','Distribuidora Atlacatl'];
$estados = ['Pendiente','En Proceso','Completado','Cancelado'];
$recetaNombres = ['Mezcla Pollo Inicio','Mezcla Pollo Engorde','Mezcla Cerdo Inicio','Mezcla Cerdo Engorde','Mezcla Pollo Final','Mezcla Cerdo Final','Mezcla Aves Inicio','Mezcla Aves Engorde','Mezcla Ganado','Mezcla Balanceada'];
$ciudades = ['San Salvador','Santa Ana','San Miguel','La Libertad','Sonsonate','Ahuachapan','Usulutan','San Vicente','Cabanas','Chalatenango','La Union','Morazan','Sanchez Ceren','Metapan','Zacatecoluca'];

function randName($nombres) { return $nombres[array_rand($nombres)]; }
function randApellido($apellidos) { return $apellidos[array_rand($apellidos)]; }
function randFloat($min, $max) { return round($min + mt_rand(0, ($max-$min)*100) / 100, 2); }
function randInt($min, $max) { return mt_rand($min, $max); }
function randDate($start, $end) {
    $d1 = DateTime::createFromFormat('d/m/Y', $start);
    $d2 = DateTime::createFromFormat('d/m/Y', $end);
    $ts1 = $d1->getTimestamp();
    $ts2 = $d2->getTimestamp();
    $r = mt_rand($ts1, $ts2);
    return date('d/m/Y', $r);
}

// ---------- ROLES ----------
echo "Insertando roles...\n";
foreach ($roles as $i => $r) {
    $id = $i + 1;
    if ($id > $maxRol) {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO rol (id_Rol, nombreRol) VALUES (?,?)");
        $stmt->bind_param('is', $id, $r);
        $stmt->execute();
    }
}

// ---------- PUESTOS ----------
echo "Insertando puestos...\n";
foreach ($puestos as $i => $p) {
    $id = $i + 1;
    if ($id > $maxPuesto) {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO puesto (idPuesto, nombrePuesto) VALUES (?,?)");
        $stmt->bind_param('is', $id, $p);
        $stmt->execute();
    }
}

// ---------- MATERIA PRIMA ----------
echo "Insertando materias primas...\n";
for ($i = 0; $i < 20; $i++) {
    $id = $maxMP + 1 + $i;
    $nombre = $materiasNombres[$i] ?? 'Materia ' . $id;
    $stmt = $mysqli->prepare("INSERT IGNORE INTO materiaprima (idMateriaPrima, NombreMP) VALUES (?,?)");
    $stmt->bind_param('is', $id, $nombre);
    $stmt->execute();
}

// ---------- PROVEEDORES ----------
echo "Insertando proveedores...\n";
for ($i = 0; $i < 50; $i++) {
    $id = $maxProv + 1 + $i;
    $nombre = $proveedorNombres[$i % count($proveedorNombres)] . ' ' . ($i > 14 ? $i : '');
    $contacto = randName($nombresM);
    $nit = randInt(1000000, 9999999);
    $correo = 'proveedor' . $id . '@empresa.com';
    $tel = '555-' . str_pad(randInt(0, 9999), 4, '0', STR_PAD_LEFT);
    $stmt = $mysqli->prepare("INSERT IGNORE INTO proveedor (idProveedor, nombreProveedor, contacto, NIT, correoP, telefono) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('isssss', $id, $nombre, $contacto, $nit, $correo, $tel);
    $stmt->execute();
}

// ---------- USUARIOS ----------
echo "Insertando usuarios...\n";
$usuariosInsertados = 0;
for ($i = 0; $i < 500; $i++) {
    $id = $maxUsuario + 1 + $i;
    $username = 'user' . $id;
    $rol = randInt(1, 4);
    $stmt = $mysqli->prepare("INSERT IGNORE INTO usuarios (idUsuario, username, pass, id_Rol) VALUES (?,?, $passHash, ?)");
    $stmt->bind_param('isi', $id, $username, $rol);
    $stmt->execute();
    $usuariosInsertados++;
}

// ---------- EMPLEADOS ----------
echo "Insertando empleados...\n";
$empleadosInsertados = 0;
for ($i = 0; $i < 200; $i++) {
    $idEmp = $maxEmp + 1 + $i;
    $idUsuario = $maxUsuario + 1 + $i;
    $genero = mt_rand(0, 1) ? 'M' : 'F';
    $nombre = $genero == 'M' ? randName($nombresM) : randName($nombresF);
    $apellido = randApellido($apellidos);
    $idPuesto = randInt(1, 12);
    try {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO empleado (idEmpleado, nombreEmp, apellido, genero, idPuesto, idUsuario) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('isssii', $idEmp, $nombre, $apellido, $genero, $idPuesto, $idUsuario);
        $stmt->execute();
        $empleadosInsertados++;
    } catch (Exception $e) {
        // Ignorar duplicados raros
    }
}

// ---------- CLIENTES ----------
echo "Insertando clientes...\n";
$clientesInsertados = 0;
for ($i = 0; $i < 200; $i++) {
    $idCli = $maxCli + 1 + $i;
    $idUsuario = $maxUsuario + 1 + 200 + $i; // Continuar usuarios después de empleados
    if ($idUsuario > $maxUsuario + 700) {
        $idUsuario = $maxUsuario + randInt(1, 500);
    }
    $nombreCli = randName($nombresM);
    $apellidoCli = randApellido($apellidos);
    $edad = randInt(18, 70);
    $genero = mt_rand(0, 1) ? 'M' : 'F';
    $tel = '7' . randInt(10000000, 99999999);
    try {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO cliente (idCliente, NombreCliente, apellidosCliente, telefono, edad, genero, idUsuario) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('isssisi', $idCli, $nombreCli, $apellidoCli, $tel, $edad, $genero, $idUsuario);
        $stmt->execute();
        $clientesInsertados++;
    } catch (Exception $e) {}
}

// ---------- RECETAS ----------
echo "Insertando recetas...\n";
foreach ($recetaNombres as $i => $r) {
    $id = $maxReceta + 1 + $i;
    $precio = randFloat(1.00, 3.00);
    $stmt = $mysqli->prepare("INSERT IGNORE INTO receta (idReceta, nombreReceta, PrecioUnitario) VALUES (?,?,?)");
    $stmt->bind_param('isd', $id, $r, $precio);
    $stmt->execute();
}

// ---------- INVENTARIO ----------
echo "Insertando inventario...\n";
$inventariosInsertados = 0;
$maxMP_actual = $maxMP + 20;
for ($i = 0; $i < 20; $i++) {
    $idInv = $maxInv + 1 + $i;
    $idMP = randInt(1, $maxMP_actual);
    $exist = randInt(0, 5000);
    $stmt = $mysqli->prepare("INSERT IGNORE INTO inventario (idInventario, idMateriaPrima, Existencias) VALUES (?,?,?)");
    $stmt->bind_param('iii', $idInv, $idMP, $exist);
    $stmt->execute();
    $inventariosInsertados++;
}

// ---------- ESTADO PEDIDO ----------
echo "Insertando estados de pedido...\n";
$estadosPedido = [['Pendiente'],['En Proceso'],['Completado'],['Cancelado']];
foreach ($estadosPedido as $i => $e) {
    $id = $i + 1;
    if ($id > 4) {
        $stmt = $mysqli->prepare("INSERT IGNORE INTO estadopedido (idEstadoPedido, nombreEstado) VALUES (?,?)");
        $stmt->bind_param('is', $id, $e[0]);
        $stmt->execute();
    }
}

// ---------- PEDIDOS ----------
echo "Insertando pedidos...\n";
$pedidosInsertados = 0;
for ($i = 0; $i < 100; $i++) {
    $idPed = $maxPedido + 1 + $i;
    $fecha = randDate('01/01/2025', '27/06/2026');
    $idCli = randInt($maxCli - 200, $maxCli + 200);
    if ($idCli < 1) $idCli = $maxCli;
    $idEstado = randInt(1, 4);
    $stmt = $mysqli->prepare("INSERT IGNORE INTO pedido (idPedido, fechaPedido, idCliente, idEstadoPedido) VALUES (?,?,?,?)");
    $stmt->bind_param('isii', $idPed, $fecha, $idCli, $idEstado);
    $stmt->execute();
    $pedidosInsertados++;
}

// ---------- DETALLE PEDIDO ----------
echo "Insertando detalle pedido...\n";
$detPedidosInsertados = 0;
for ($i = 0; $i < 200; $i++) {
    $idDet = $maxDetPedido + 1 + $i;
    $cant = randInt(10, 500);
    $idRec = randInt(1, $maxReceta + 10);
    $idPed = $maxPedido + 1 + randInt(0, 99);
    if ($idPed < $maxPedido + 1) $idPed = $maxPedido + 1;
    $stmt = $mysqli->prepare("INSERT IGNORE INTO detallepedido (idDetallePedido, cantidad, idReceta, IdPedido) VALUES (?,?,?,?)");
    $stmt->bind_param('iiii', $idDet, $cant, $idRec, $idPed);
    $stmt->execute();
    $detPedidosInsertados++;
}

// ---------- DETALLE RECETA ----------
echo "Insertando detalle receta...\n";
$detRecetasInsertados = 0;
$inventariosDisponibles = [];
$res = $mysqli->query("SELECT idInventario FROM inventario");
while ($row = $res->fetch_assoc()) $inventariosDisponibles[] = $row['idInventario'];
if (empty($inventariosDisponibles)) $inventariosDisponibles = [1,2,3,4,5];

for ($i = 0; $i < 50; $i++) {
    $idDetRec = $maxDetReceta + 1 + $i;
    $idMateria = randInt(1, $maxMP_actual);
    $cantidad = randInt(50, 2000);
    $fecha = randDate('01/01/2025', '27/06/2026');
    $idInventario = $inventariosDisponibles[array_rand($inventariosDisponibles)];
    $idReceta = randInt(1, $maxReceta + 10);
    $stmt = $mysqli->prepare("INSERT IGNORE INTO detallereceta (idDetalleReceta, idMateriaPrima, cantidaSa, fechaSa, idInventario, IdReceta) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('iissii', $idDetRec, $idMateria, $cantidad, $fecha, $idInventario, $idReceta);
    $stmt->execute();
    $detRecetasInsertados++;
}

// ---------- PRODUCCION ----------
echo "Insertando produccion...\n";
$produccionesInsertadas = 0;
for ($i = 0; $i < 80; $i++) {
    $idProd = $maxProd + 1 + $i;
    $fecha = randDate('01/01/2025', '27/06/2026');
    $estado = $estados[array_rand($estados)];
    $idPed = $maxPedido + 1 + randInt(0, 99);
    if ($idPed < $maxPedido + 1) $idPed = $maxPedido + 1;
    $idEmp = randInt($maxEmp - 100, $maxEmp + 100);
    if ($idEmp < 1) $idEmp = $maxEmp;
    $stmt = $mysqli->prepare("INSERT IGNORE INTO produccion (idProduccion, fechaP, estadoP, idPedido, idEmpleado) VALUES (?,?,?,?,?)");
    $stmt->bind_param('issii', $idProd, $fecha, $estado, $idPed, $idEmp);
    $stmt->execute();
    $produccionesInsertadas++;
}

// ---------- FACTURAS ----------
echo "Insertando facturas...\n";
$facturasInsertadas = 0;
for ($i = 0; $i < 100; $i++) {
    $idFac = $maxFactura + 1 + $i;
    $numero = 'FAC-' . str_pad($idFac, 5, '0', STR_PAD_LEFT);
    $monto = randFloat(100, 10000);
    $fecha = randDate('01/01/2025', '27/06/2026');
    $idProv = randInt($maxProv - 5, $maxProv + 50);
    if ($idProv < 1) $idProv = $maxProv;
    $idEmp = randInt($maxEmp - 50, $maxEmp + 50);
    if ($idEmp < 1) $idEmp = $maxEmp;
    $stmt = $mysqli->prepare("INSERT IGNORE INTO factura (idFacturaMP, numeroFac, Monto, Fecha, idProveedor, idEmpleado) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('isdssi', $idFac, $numero, $monto, $fecha, $idProv, $idEmp);
    $stmt->execute();
    $facturasInsertadas++;
}

// ---------- DETALLE COMPRA ----------
echo "Insertando detalle compra...\n";
$detComprasInsertadas = 0;
$facturasDisponibles = [];
$res = $mysqli->query("SELECT idFacturaMP FROM factura ORDER BY idFacturaMP DESC LIMIT 100");
while ($row = $res->fetch_assoc()) $facturasDisponibles[] = $row['idFacturaMP'];
if (empty($facturasDisponibles)) $facturasDisponibles = [$maxFactura + 1];

for ($i = 0; $i < 150; $i++) {
    $idDetCompra = $maxDetCompra + 1 + $i;
    $idMP = randInt(1, $maxMP_actual);
    $cantidad = randInt(10, 1000);
    $precio = randFloat(0.50, 5.00);
    $idFac = $facturasDisponibles[array_rand($facturasDisponibles)];
    $stmt = $mysqli->prepare("INSERT IGNORE INTO detallecompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) VALUES (?,?,?,?,?)");
    $stmt->bind_param('iiisi', $idDetCompra, $idMP, $cantidad, $precio, $idFac);
    $stmt->execute();
    $detComprasInsertadas++;
}

// ---------- PEDIDOS A PROVEEDOR ----------
// NOTA: La tabla pedidoproveedor no existe en la BD actual (ejecutar concentrados.sql primero)
// echo "Insertando pedidos a proveedor...\n";
// $pedidosProvInsertados = 0;
// for ($i = 0; $i < 100; $i++) {
//     ...
// }

// ---------- SALIDAS ----------
echo "Insertando salidas...\n";
$salidasInsertadas = 0;
$produccionesDisponibles = [];
$res = $mysqli->query("SELECT idProduccion FROM produccion ORDER BY idProduccion DESC LIMIT 80");
while ($row = $res->fetch_assoc()) $produccionesDisponibles[] = $row['idProduccion'];
if (empty($produccionesDisponibles)) $produccionesDisponibles = [$maxProd + 1];

for ($i = 0; $i < 80; $i++) {
    $idSalida = $maxSalida + 1 + $i;
    $idProd = $produccionesDisponibles[array_rand($produccionesDisponibles)];
    $cliente = $clienteNombres[array_rand($clienteNombres)] . ' ' . randInt(1, 100);
    $fecha = randDate('01/01/2025', '27/06/2026');
    $monto = randFloat(50, 2000);
    $estadoSalida = mt_rand(0, 1) ? 'Entregado' : 'Pendiente';
    $stmt = $mysqli->prepare("INSERT IGNORE INTO salidas (idSalida, idProduccion, NombreCliente, fecha, montoTotal, estadoSalida) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('iissds', $idSalida, $idProd, $cliente, $fecha, $monto, $estadoSalida);
    $stmt->execute();
    $salidasInsertadas++;
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "\n=== Seed masivo completado ===\n";
echo "Resumen:\n";
echo "- Usuarios nuevos: ~$usuariosInsertados\n";
echo "- Empleados nuevos: ~$empleadosInsertados\n";
echo "- Clientes nuevos: ~$clientesInsertados\n";
echo "- Inventarios nuevos: $inventariosInsertados\n";
echo "- Pedidos nuevos: $pedidosInsertados\n";
echo "- Detalle pedido nuevos: $detPedidosInsertados\n";
echo "- Detalle receta nuevos: $detRecetasInsertados\n";
echo "- Producciones nuevas: $produccionesInsertadas\n";
echo "- Facturas nuevas: $facturasInsertadas\n";
echo "- Detalle compra nuevos: $detComprasInsertadas\n";
echo "- Salidas nuevas: $salidasInsertadas\n\n";
echo "Total aproximado de registros nuevos: " . ($usuariosInsertados + $empleadosInsertados + $clientesInsertados + $inventariosInsertados + $pedidosInsertados + $detPedidosInsertados + $detRecetasInsertados + $produccionesInsertadas + $facturasInsertadas + $detComprasInsertadas + $salidasInsertadas) . "\n";
echo "Password por defecto: 12345 (SHA1)\n";

$mysqli->close();
?>