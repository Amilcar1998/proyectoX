<?php require_once __DIR__ . '/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>💵 Pagos y Transacciones Wompi | Concentrados El Gordito</title>

    <!-- Hojas de estilo oficiales -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../vendor/sb-admin.css" rel="stylesheet" />

    <link href="../views/css/pagos.css" rel="stylesheet">
</head>
<body id="page-top">
    <?php echo "$nav"; ?>
    <div id="wrapper">
        <?php echo "$menu"; ?>
        <div id="content-wrapper">
            <div class="container-fluid py-3">
                
                <!-- Encabezado de Página -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                            <i class="fas fa-credit-card text-success mr-2"></i>Control de Pagos y Transacciones Wompi
                        </h1>
                        <p class="text-muted small mb-0">
                            Registro en tiempo real de cobros en línea, pasarela Wompi El Salvador y pedidos asociados.
                        </p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <span class="badge badge-success px-3 py-2 text-white">
                            <i class="fas fa-shield-alt mr-1"></i> Wompi SV Enlace Activo
                        </span>
                    </div>
                </div>

                <!-- Tarjetas KPI / Métricas -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-success shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monto Total Recaudado</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            USD $<?php echo number_format($estadisticas['totalRecaudado'], 2); ?>
                                        </div>
                                        <div class="text-muted small mt-1">Pagos completados con éxito</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-primary shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transacciones Aprobadas</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $estadisticas['totalCompletados']; ?>
                                        </div>
                                        <div class="text-muted small mt-1">De <?php echo $estadisticas['totalTransacciones']; ?> intentos totales</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-warning shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pagos Pendientes</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $estadisticas['totalPendientes']; ?>
                                        </div>
                                        <div class="text-muted small mt-1">Esperando confirmación</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-info shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ticket Promedio</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            USD $<?php echo number_format($estadisticas['ticketPromedio'], 2); ?>
                                        </div>
                                        <div class="text-muted small mt-1">Por compra exitosa</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-receipt fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Datos Principal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list-ul mr-2"></i>Historial de Pagos y Transacciones Wompi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable" id="tablaPagos" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Fecha y Hora</th>
                                        <th>Cliente / Contacto</th>
                                        <th>Concepto / Tipo</th>
                                        <th>Monto (USD)</th>
                                        <th>Estado</th>
                                        <th>ID Transacción Wompi</th>
                                        <th>Pedido Creado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $p): 
                                        $meta = $p['metadatos_array'] ?? [];
                                        $items = $meta['items'] ?? [];
                                        $idTxnWompi = $p['idTransaccionWompi'];
                                        if (empty($idTxnWompi)) {
                                            // Extraer del string si existe
                                            if (preg_match('/Aprobado.*?:\\s*([a-zA-Z0-9\\-]+)/', $p['descripcion'] ?? '', $matches)) {
                                                $idTxnWompi = $matches[1];
                                            }
                                        }
                                        $nombreConcepto = !empty($p['nombrePlan']) ? 'Plan: ' . $p['nombrePlan'] : ((!empty($items)) ? count($items) . ' productos (Carrito)' : 'Compra Productos');
                                    ?>
                                    <tr>
                                        <td class="font-weight-bold">
                                            #<?php echo $p['idPago']; ?>
                                            <div class="text-muted font-monospace" style="font-size: 0.72rem;"><?php echo htmlspecialchars($p['referencia'] ?? ''); ?></div>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <div><i class="far fa-calendar-alt text-muted mr-1"></i><?php echo date('d/m/Y', strtotime($p['fecha_hora'])); ?></div>
                                            <div class="text-muted small"><i class="far fa-clock text-muted mr-1"></i><?php echo date('h:i A', strtotime($p['fecha_hora'])); ?></div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($p['nombreCliente']); ?></div>
                                            <?php if (!empty($p['telefonoCliente'])): ?>
                                                <div class="small text-muted"><i class="fas fa-phone-alt mr-1"></i><?php echo htmlspecialchars($p['telefonoCliente']); ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($p['correoCliente'])): ?>
                                                <div class="small text-muted"><i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($p['correoCliente']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border text-dark">
                                                <i class="fas fa-box-open mr-1 text-primary"></i><?php echo htmlspecialchars($nombreConcepto); ?>
                                            </span>
                                        </td>
                                        <td class="font-weight-bold text-success font-monospace" style="font-size: 1.05rem;">
                                            $<?php echo number_format((float)$p['monto'], 2); ?>
                                        </td>
                                        <td>
                                            <?php if ($p['estado'] === 'completado'): ?>
                                                <span class="badge badge-completado"><i class="fas fa-check-circle mr-1"></i>Aprobado</span>
                                            <?php elseif ($p['estado'] === 'pendiente'): ?>
                                                <span class="badge badge-pendiente"><i class="fas fa-clock mr-1"></i>Pendiente</span>
                                            <?php elseif ($p['estado'] === 'reembolsado'): ?>
                                                <span class="badge badge-reembolsado"><i class="fas fa-undo mr-1"></i>Reembolsado</span>
                                            <?php else: ?>
                                                <span class="badge badge-fallido"><i class="fas fa-times-circle mr-1"></i><?php echo ucfirst($p['estado']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($idTxnWompi)): ?>
                                                <span class="badge badge-light border font-monospace text-primary" title="<?php echo htmlspecialchars($idTxnWompi); ?>" style="font-size: 0.8rem;">
                                                    <i class="fas fa-receipt mr-1"></i><?php echo htmlspecialchars(substr($idTxnWompi, 0, 16)); ?><?php echo strlen($idTxnWompi) > 16 ? '...' : ''; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small">Wompi SV</span>
                                            <?php endif; ?>
                                            <?php if (!empty($p['idEnlaceWompi'])): ?>
                                                <div class="text-muted font-monospace" style="font-size: 0.72rem;">Enlace: <?php echo htmlspecialchars($p['idEnlaceWompi']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($p['idPedidoCreado'])): ?>
                                                <a href="controllerPedidos.php" class="btn btn-sm btn-outline-primary py-1 px-2 font-weight-bold" title="Ver pedido en producción">
                                                    <i class="fas fa-box mr-1"></i>#<?php echo $p['idPedidoCreado']; ?>
                                                </a>
                                            <?php elseif (!empty($p['idPlanPago'])): ?>
                                                <span class="badge badge-info"><i class="fas fa-certificate mr-1"></i>Plan Activo</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="white-space: nowrap;">
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-ver-detalle" 
                                                    data-id="<?php echo $p['idPago']; ?>" 
                                                    title="Ver toda la información devuelta por Wompi">
                                                <i class="fas fa-eye mr-1"></i>Detalle
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 ml-1 btn-imprimir-directo-pago" 
                                                    data-id="<?php echo $p['idPago']; ?>" 
                                                    title="Imprimir Comprobante Oficial">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <?php if ($esAdmin && $p['estado'] === 'completado'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 ml-1 btn-reembolsar"
                                                        data-id="<?php echo $p['idPago']; ?>"
                                                        data-monto="<?php echo number_format((float)$p['monto'], 2); ?>"
                                                        data-cliente="<?php echo htmlspecialchars($p['nombreCliente']); ?>"
                                                        title="Procesar reembolso de este pago">
                                                    <i class="fas fa-undo mr-1"></i>Reembolsar
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($pagos)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                                Aún no se han registrado transacciones o cobros en la base de datos.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Extra Grande para Detalle Completo de Transacción Wompi -->
    <div class="modal fade" id="modalDetallePago" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 1250px; width: 95%;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-dark text-white px-4 py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 d-inline-flex align-items-center" id="modalLabel">
                            <i class="fas fa-credit-card text-success mr-2"></i>Detalle de Transacción Oficial Wompi El Salvador
                        </h5>
                        <div class="text-white-50 small mt-1" id="modalHeaderSub">Comprobante y auditoría de pago en línea</div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="font-size: 1.6rem; opacity: 0.85;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-light" id="modalContenidoDetalle" style="max-height: calc(85vh - 120px); overflow-y: auto;">
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                        <div class="mt-3 text-muted h6 font-weight-normal">Cargando toda la información devuelta por Wompi...</div>
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-primary" onclick="copiarJsonWompi()">
                            <i class="fas fa-copy mr-1"></i>Copiar Payload JSON
                        </button>
                        <button type="button" class="btn btn-outline-secondary ml-2" onclick="imprimirModalDetalle()">
                            <i class="fas fa-print mr-1"></i>Imprimir Comprobante
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="sticky-footer bg-dark mt-auto">
        <div class="container my-auto py-3">
            <div class="copyright text-center my-auto">
                <span class="text-white">Copyright &copy; Concentrados El Gordito 2026 • Módulo Oficial Wompi</span>
            </div>
        </div>
    </footer>

    <!-- Scripts Oficiales -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../views/js/sb-admin.min.js"></script>
    <script src="../views/js/translations.js"></script>
    <script src="../views/js/demo/datatables-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../views/js/pagos.js"></script>
</body>
</html>
