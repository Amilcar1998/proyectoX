<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>📊 Dashboard | Concentrados El Gordito</title>

    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>

</head>

<body id="page-top">
  <?php echo "$nav"; ?>

  <div id="wrapper">

    <?php echo "$menu"; ?>

    <div id="content-wrapper">

        <div class="container-fluid">

          <!-- Header de Bienvenida según Rol -->
          <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
              <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <?php if ($idRol === 1 || $idRol === 4): ?>
                  <i class="fas fa-chart-pie text-primary mr-2"></i>Panel Gerencial y Administrativo
                <?php elseif ($idRol === 2): ?>
                  <i class="fas fa-industry text-info mr-2"></i>Panel Operativo y de Producción
                <?php elseif ($idRol === 3): ?>
                  <i class="fas fa-store text-success mr-2"></i>Mi Portal de Cliente
                <?php endif; ?>
              </h1>
              <p class="text-muted mb-0">Bienvenido/a, <strong class="text-dark"><?php echo htmlspecialchars($nombres); ?></strong> (<?php echo htmlspecialchars($rolNombre); ?>)</p>
            </div>
            <div>
              <?php if ($idRol === 1 || $idRol === 4): ?>
                <a href="controllerPromociones.php" class="btn btn-sm btn-primary shadow-sm mr-2">
                  <i class="fas fa-tag fa-sm text-white-50 mr-1"></i> Precios y Promociones
                </a>
                <a href="controllerReportes.php" class="btn btn-sm btn-outline-secondary shadow-sm">
                  <i class="fas fa-file-pdf fa-sm text-danger mr-1"></i> Reportes PDF
                </a>
              <?php elseif ($idRol === 2): ?>
                <a href="controllerProduccion.php" class="btn btn-sm btn-info shadow-sm mr-2">
                  <i class="fas fa-industry fa-sm text-white mr-1"></i> Producción
                </a>
                <a href="controllerInventario.php" class="btn btn-sm btn-outline-info shadow-sm">
                  <i class="fas fa-boxes fa-sm mr-1"></i> Inventario
                </a>
              <?php elseif ($idRol === 3): ?>
                <a href="controllerIndividualC.php" class="btn btn-sm btn-success shadow-sm mr-2">
                  <i class="fas fa-cart-plus fa-sm text-white mr-1"></i> Nuevo Pedido
                </a>
                <a href="controllerPagos.php" class="btn btn-sm btn-outline-success shadow-sm">
                  <i class="fas fa-credit-card fa-sm mr-1"></i> Pagos Wompi
                </a>
              <?php endif; ?>
            </div>
          </div>

          <?php if (!empty($promocionesActivas) && ($idRol === 3 || $idRol === 1 || $idRol === 4)): ?>
            <!-- Banner de Promoción Activa -->
            <div class="alert alert-warning border-left-warning shadow-sm mb-4" role="alert">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h5 class="alert-heading font-weight-bold mb-1">
                    <i class="fas fa-bolt text-danger mr-2"></i>¡Promoción Especial Activa!
                  </h5>
                  <p class="mb-0 text-dark">
                    <?php foreach ($promocionesActivas as $promo): ?>
                      <strong><?php echo htmlspecialchars($promo['nombreReceta']); ?></strong> ahora a solo 
                      <span class="badge badge-danger" style="font-size: 0.9rem;">$<?php echo number_format((float)$promo['precio'], 2); ?></span>
                      <?php if (!empty($promo['precio_anterior']) && (float)$promo['precio_anterior'] > (float)$promo['precio']): ?>
                        <small class="text-muted">(Antes: <del>$<?php echo number_format((float)$promo['precio_anterior'], 2); ?></del> - <?php echo htmlspecialchars((string)($promo['porcentaje_descuento'] ?? '')); ?>% OFF)</small>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </p>
                </div>
                <div>
                  <?php if ($idRol === 3): ?>
                    <a href="controllerIndividualC.php" class="btn btn-warning btn-sm font-weight-bold">
                      <i class="fas fa-shopping-bag mr-1"></i> Aprovechar Ahora &rarr;
                    </a>
                  <?php else: ?>
                    <a href="controllerPromociones.php" class="btn btn-warning btn-sm font-weight-bold">
                      <i class="fas fa-sliders-h mr-1"></i> Administrar Promo &rarr;
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($idRol === 1 || $idRol === 4): ?>
            <!-- ========================================== -->
            <!-- VISTA DASHBOARD: GERENTE (1) Y ADMIN (4)  -->
            <!-- ========================================== -->

            <!-- Fila de Tarjetas Resumen -->
            <div class="row mb-4">
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pedidos Totales</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalPedidos'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2" style="border-left: .25rem solid #10b981 !important;">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                          <i class="fas fa-bolt text-warning mr-1"></i>Ventas Wompi SV
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                          $<?php echo number_format((float)($resumen['montoPagosWompi'] ?? 0), 2); ?>
                        </div>
                        <div class="mt-1">
                          <a href="controllerPagos.php" class="badge badge-success px-2 py-1 text-white">
                            <i class="fas fa-credit-card mr-1"></i><?php echo $resumen['totalPagosWompi'] ?? 0; ?> pagos • Ver Módulo &rarr;
                          </a>
                        </div>
                      </div>
                      <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-success"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Monto Facturado</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">$<?php echo number_format((float)($resumen['montoTotal'] ?? 0), 2); ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock Crítico</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['stockCritico'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Empleados</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalEmpleados'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-primary"><i class="fas fa-chart-line mr-2"></i>Pedidos Mensuales</div>
                  <div class="card-body">
                    <canvas id="chartPedidosMensuales"></canvas>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-success"><i class="fas fa-chart-bar mr-2"></i>Stock de Materias Primas</div>
                  <div class="card-body">
                    <canvas id="chartStock"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-info"><i class="fas fa-industry mr-2"></i>Producción por Empleado</div>
                  <div class="card-body">
                    <canvas id="chartProduccion"></canvas>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-warning"><i class="fas fa-receipt mr-2"></i>Facturación</div>
                  <div class="card-body">
                    <canvas id="chartFacturacion"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tabla de Pedidos Recientes -->
            <div class="row">
              <div class="col-12 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-dark"><i class="fas fa-table mr-2"></i>Pedidos Recientes del Sistema</div>
                  <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th># Pedido</th>
                            <th>Cliente</th>
                            <th>Empleado Asignado</th>
                            <th>Receta / Producto</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($pedidosRecientes as $fila): ?>
                          <tr>
                            <td><strong>#<?php echo htmlspecialchars((string)($fila['idPedido'] ?? '')); ?></strong></td>
                            <td><?php echo htmlspecialchars((string)($fila['NombreCliente'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars((string)($fila['empleado'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars((string)($fila['nombreReceta'] ?? ($fila['recetas'] ?? ''))); ?></td>
                            <td><?php echo htmlspecialchars((string)($fila['fechaPedido'] ?? '')); ?></td>
                            <td><span class="badge badge-primary"><?php echo htmlspecialchars((string)($fila['cantidad'] ?? 0)); ?></span></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php elseif ($idRol === 2): ?>
            <!-- ========================================== -->
            <!-- VISTA DASHBOARD: EMPLEADO (ROL 2)          -->
            <!-- ========================================== -->

            <div class="row mb-4">
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pedidos en Sistema</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['pedidosActivos'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Materias Primas</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalMateriasPrimas'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-boxes fa-2x text-gray-300"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock Crítico (< 500)</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['stockCritico'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-warning"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mis Producciones</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['misProducciones'] ?? 0; ?></div>
                      </div>
                      <div class="col-auto"><i class="fas fa-industry fa-2x text-primary"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Gráficos Operativos -->
            <div class="row">
              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-success"><i class="fas fa-chart-bar mr-2"></i>Stock de Materias Primas</div>
                  <div class="card-body">
                    <canvas id="chartStock"></canvas>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-info"><i class="fas fa-industry mr-2"></i>Producción por Empleado</div>
                  <div class="card-body">
                    <canvas id="chartProduccion"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tabla de Pedidos Recientes -->
            <div class="row">
              <div class="col-12 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-dark"><i class="fas fa-dolly mr-2"></i>Pedidos Recientes para Producción</div>
                  <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th># Pedido</th>
                            <th>Cliente</th>
                            <th>Empleado</th>
                            <th>Receta</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($pedidosRecientes as $fila): ?>
                          <tr>
                            <td><strong>#<?php echo htmlspecialchars((string)($fila['idPedido'] ?? '')); ?></strong></td>
                            <td><?php echo htmlspecialchars((string)($fila['NombreCliente'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars((string)($fila['empleado'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars((string)($fila['nombreReceta'] ?? ($fila['recetas'] ?? ''))); ?></td>
                            <td><?php echo htmlspecialchars((string)($fila['fechaPedido'] ?? '')); ?></td>
                            <td><span class="badge badge-info"><?php echo htmlspecialchars((string)($fila['cantidad'] ?? 0)); ?></span></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php elseif ($idRol === 3): ?>
            <!-- ========================================== -->
            <!-- VISTA DASHBOARD: CLIENTE (ROL 3)           -->
            <!-- ========================================== -->

            <div class="row mb-4">
              <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mis Pedidos Realizados</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalPedidos'] ?? 0; ?></div>
                        <div class="mt-2">
                          <a href="controllerIndividualC.php" class="btn btn-sm btn-outline-primary py-0">
                            <i class="fas fa-plus mr-1"></i>Realizar Pedido
                          </a>
                        </div>
                      </div>
                      <div class="col-auto"><i class="fas fa-shopping-bag fa-2x text-primary"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mis Pagos en Línea (Wompi)</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalPagosWompi'] ?? 0; ?></div>
                        <div class="mt-2">
                          <a href="controllerPagos.php" class="btn btn-sm btn-outline-success py-0">
                            <i class="fas fa-credit-card mr-1"></i>Ver Pagos
                          </a>
                        </div>
                      </div>
                      <div class="col-auto"><i class="fas fa-check-circle fa-2x text-success"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Comprado / Pagado</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">$<?php echo number_format((float)($resumen['montoPagosWompi'] ?? 0), 2); ?></div>
                        <div class="mt-2">
                          <a href="controllerPlanPago.php" class="btn btn-sm btn-outline-info py-0">
                            <i class="fas fa-award mr-1"></i>Planes Disponibles
                          </a>
                        </div>
                      </div>
                      <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-info"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tablas de Historial del Cliente -->
            <div class="row">
              <div class="col-xl-7 col-lg-7 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-primary">
                    <i class="fas fa-history mr-2"></i>Mis Pedidos Recientes
                  </div>
                  <div class="card-body">
                    <?php if (empty($misPedidosCliente)): ?>
                      <div class="text-center py-4 text-muted">
                        <i class="fas fa-shopping-basket fa-3x mb-3 text-gray-300"></i>
                        <p>No tienes pedidos registrados todavía.</p>
                        <a href="controllerIndividualC.php" class="btn btn-primary btn-sm">Hacer mi primer pedido</a>
                      </div>
                    <?php else: ?>
                      <div class="table-responsive">
                        <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                          <thead class="thead-light">
                            <tr>
                              <th># Pedido</th>
                              <th>Fecha</th>
                              <th>Detalle / Productos</th>
                              <th>Cantidad</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($misPedidosCliente as $p): ?>
                              <tr>
                                <td><strong>#<?php echo htmlspecialchars((string)$p['idPedido']); ?></strong></td>
                                <td><?php echo htmlspecialchars((string)$p['fechaPedido']); ?></td>
                                <td><?php echo htmlspecialchars((string)($p['detalle'] ?? 'Concentrado')); ?></td>
                                <td><span class="badge badge-primary px-2 py-1"><?php echo htmlspecialchars((string)$p['totalCantidad']); ?> unidades</span></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <div class="col-xl-5 col-lg-5 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3 font-weight-bold text-success">
                    <i class="fas fa-receipt mr-2"></i>Mis Pagos Realizados
                  </div>
                  <div class="card-body">
                    <?php if (empty($misPagosCliente)): ?>
                      <div class="text-center py-4 text-muted">
                        <i class="fas fa-credit-card fa-3x mb-3 text-gray-300"></i>
                        <p>No tienes comprobantes de pago aún.</p>
                        <a href="controllerPagos.php" class="btn btn-success btn-sm">Realizar un pago</a>
                      </div>
                    <?php else: ?>
                      <div class="table-responsive">
                        <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                          <thead class="thead-light">
                            <tr>
                              <th>Ref / Fecha</th>
                              <th>Monto</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($misPagosCliente as $pago): ?>
                              <tr>
                                <td>
                                  <small class="font-weight-bold text-dark"><?php echo htmlspecialchars((string)($pago['referencia'] ?? ('#' . $pago['idPago']))); ?></small><br>
                                  <small class="text-muted"><?php echo htmlspecialchars((string)$pago['fecha_hora']); ?></small>
                                </td>
                                <td class="font-weight-bold text-success">
                                  $<?php echo number_format((float)$pago['monto'], 2); ?>
                                </td>
                                <td>
                                  <?php if ($pago['estado'] === 'completado'): ?>
                                    <span class="badge badge-success">Pagado</span>
                                  <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo htmlspecialchars((string)$pago['estado']); ?></span>
                                  <?php endif; ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

          <?php endif; ?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- /#content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="js/sb-admin.min.js"></script>
    <script src="js/translations.js"></script>
    <script src="js/demo/datatables-demo.js"></script>

    <?php if ($idRol === 1 || $idRol === 4 || $idRol === 2): ?>
    <script>
      <?php if (!empty($pedidosMensuales)): ?>
      const meses = <?php echo json_encode(array_column($pedidosMensuales, 'mes')); ?>;
      const cantidades = <?php echo json_encode(array_column($pedidosMensuales, 'cantidad')); ?>;

      if (document.getElementById('chartPedidosMensuales')) {
        new Chart(document.getElementById('chartPedidosMensuales'), {
          type: 'bar',
          data: {
            labels: meses,
            datasets: [{
              label: 'Pedidos',
              data: cantidades,
              backgroundColor: '#4e73df'
            }]
          },
          options: { responsive: true, plugins: { legend: { display: false } } }
        });
      }
      <?php endif; ?>

      <?php if (!empty($stockMaterias)): ?>
      const stockLabels = <?php echo json_encode(array_column($stockMaterias, 'NombreMP')); ?>;
      const stockData = <?php echo json_encode(array_column($stockMaterias, 'Existencias')); ?>;

      if (document.getElementById('chartStock')) {
        new Chart(document.getElementById('chartStock'), {
          type: 'bar',
          data: {
            labels: stockLabels,
            datasets: [{
              label: 'Existencias',
              data: stockData,
              backgroundColor: '#1cc88a'
            }]
          },
          options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } } }
        });
      }
      <?php endif; ?>

      <?php if (!empty($produccionEmpleado)): ?>
      const empLabels = <?php echo json_encode(array_column($produccionEmpleado, 'empleado')); ?>;
      const empData = <?php echo json_encode(array_column($produccionEmpleado, 'totalProduccion')); ?>;

      if (document.getElementById('chartProduccion')) {
        new Chart(document.getElementById('chartProduccion'), {
          type: 'doughnut',
          data: {
            labels: empLabels,
            datasets: [{
              data: empData,
              backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#6610f2']
            }]
          },
          options: { responsive: true }
        });
      }
      <?php endif; ?>

      <?php if (!empty($montoMensual) && ($idRol === 1 || $idRol === 4)): ?>
      const mesesFact = <?php echo json_encode(array_column($montoMensual, 'mes')); ?>;
      const montosFact = <?php echo json_encode(array_column($montoMensual, 'monto')); ?>;

      if (document.getElementById('chartFacturacion')) {
        new Chart(document.getElementById('chartFacturacion'), {
          type: 'line',
          data: {
            labels: mesesFact,
            datasets: [{
              label: 'Monto Facturado',
              data: montosFact,
              borderColor: '#36b9cc',
              fill: true,
              tension: 0.3
            }]
          },
          options: { responsive: true, plugins: { legend: { display: false } } }
        });
      }
      <?php endif; ?>
    </script>
    <?php endif; ?>

  </body>
</html>